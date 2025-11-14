<?php
require_once BASE_PATH.'/core/DB.php';

class Factura {
  
  /**
   * Generar número de factura único
   */
  public static function generarNumero() {
    $prefijo = 'FAC-' . date('Ym') . '-';
    $ultimo = DB::pdo()->query(
      "SELECT numero_factura FROM facturas 
       WHERE numero_factura LIKE '{$prefijo}%' 
       ORDER BY id DESC LIMIT 1"
    )->fetchColumn();
    
    if ($ultimo) {
      $numero = (int)substr($ultimo, -4) + 1;
    } else {
      $numero = 1;
    }
    
    return $prefijo . str_pad($numero, 4, '0', STR_PAD_LEFT);
  }
  
  /**
   * Crear factura automáticamente desde un mantenimiento
   */
  public static function crearDesdeMantenimiento($mantenimiento_id) {
    $pdo = DB::pdo();
    
    // Obtener datos del mantenimiento
    $mant = $pdo->prepare("
      SELECT m.*, e.nombre as equipo_nombre, e.codigo as equipo_codigo
      FROM mantenimientos m
      JOIN equipos e ON e.id = m.equipo_id
      WHERE m.id = ?
    ");
    $mant->execute([$mantenimiento_id]);
    $mantenimiento = $mant->fetch();
    
    if (!$mantenimiento) {
      throw new Exception("Mantenimiento no encontrado");
    }
    
    // Verificar si ya existe una factura
    $existe = $pdo->prepare("SELECT id FROM facturas WHERE mantenimiento_id = ?");
    $existe->execute([$mantenimiento_id]);
    if ($existe->fetchColumn()) {
      throw new Exception("Ya existe una factura para este mantenimiento");
    }
    
    $pdo->beginTransaction();
    
    try {
      // Generar número de factura
      $numero_factura = self::generarNumero();
      
      // Calcular subtotal (usar costo_real si existe, sino costo_estimado)
      $subtotal = $mantenimiento['costo_real'] 
                  ?? $mantenimiento['costo_estimado'] 
                  ?? 0.00;
      
      // Calcular impuesto (7% ITBMS en Panamá)
      $impuesto = round($subtotal * 0.07, 2);
      $total = $subtotal + $impuesto;
      
      // Crear factura
      $sql_factura = "INSERT INTO facturas (
        mantenimiento_id, numero_factura, fecha_emision, 
        subtotal, impuesto, total, estado, notas
      ) VALUES (?, ?, NOW(), ?, ?, ?, 'pendiente', ?)";
      
      $notas = "Factura generada automáticamente por completar mantenimiento #{$mantenimiento_id}";
      
      $stmt = $pdo->prepare($sql_factura);
      $stmt->execute([
        $mantenimiento_id,
        $numero_factura,
        $subtotal,
        $impuesto,
        $total,
        $notas
      ]);
      
      $factura_id = $pdo->lastInsertId();
      
      // Crear items de la factura
      $items = [
        [
          'descripcion' => "Mantenimiento {$mantenimiento['tipo']} - {$mantenimiento['titulo']}",
          'cantidad' => 1,
          'precio_unitario' => $subtotal
        ],
        [
          'descripcion' => "Equipo: {$mantenimiento['equipo_nombre']} ({$mantenimiento['equipo_codigo']})",
          'cantidad' => 1,
          'precio_unitario' => 0.00
        ]
      ];
      
      // Agregar tareas completadas como items
      $tareas = $pdo->prepare("
        SELECT titulo FROM mantenimiento_tareas 
        WHERE mantenimiento_id = ? AND hecho = 1
      ");
      $tareas->execute([$mantenimiento_id]);
      
      foreach ($tareas->fetchAll() as $tarea) {
        $items[] = [
          'descripcion' => "✓ " . $tarea['titulo'],
          'cantidad' => 1,
          'precio_unitario' => 0.00
        ];
      }
      
      // Insertar items
      $sql_item = "INSERT INTO factura_items (factura_id, descripcion, cantidad, precio_unitario, subtotal) 
                   VALUES (?, ?, ?, ?, ?)";
      $stmt_item = $pdo->prepare($sql_item);
      
      foreach ($items as $item) {
        $item_subtotal = $item['cantidad'] * $item['precio_unitario'];
        $stmt_item->execute([
          $factura_id,
          $item['descripcion'],
          $item['cantidad'],
          $item['precio_unitario'],
          $item_subtotal
        ]);
      }
      
      log_audit('facturas', $factura_id, 'insert', [
        'numero_factura' => $numero_factura,
        'mantenimiento_id' => $mantenimiento_id,
        'total' => $total
      ]);
      
      $pdo->commit();
      
      return [
        'id' => $factura_id,
        'numero_factura' => $numero_factura,
        'total' => $total
      ];
      
    } catch (Exception $e) {
      $pdo->rollBack();
      throw $e;
    }
  }
      
      /**
     * Obtener factura por ID con sus items
     */
    public static function obtenerPorId($id) {
      $pdo = DB::pdo();
      
      // ⭐ AGREGADO: m.costo_real y m.costo_estimado
      $sql = "SELECT f.*, 
              m.id as mantenimiento_id,
              m.titulo as mantenimiento_titulo,
              m.tipo as mantenimiento_tipo,
              m.costo_real as mantenimiento_costo_real,
              m.costo_estimado as mantenimiento_costo_estimado,
              e.nombre as equipo_nombre,
              e.codigo as equipo_codigo
              FROM facturas f
              JOIN mantenimientos m ON m.id = f.mantenimiento_id
              JOIN equipos e ON e.id = m.equipo_id
              WHERE f.id = ?";
      
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$id]);
      $factura = $stmt->fetch();
      
      if (!$factura) {
        return null;
      }
      
      // Obtener items
      $stmt_items = $pdo->prepare("SELECT * FROM factura_items WHERE factura_id = ? ORDER BY id");
      $stmt_items->execute([$id]);
      $factura['items'] = $stmt_items->fetchAll();
      
      return $factura;
    }
  
  /**
   * Obtener factura por mantenimiento_id
   */
  public static function obtenerPorMantenimiento($mantenimiento_id) {
    $pdo = DB::pdo();
    
    $stmt = $pdo->prepare("SELECT id FROM facturas WHERE mantenimiento_id = ? LIMIT 1");
    $stmt->execute([$mantenimiento_id]);
    $factura_id = $stmt->fetchColumn();
    
    if (!$factura_id) {
      return null;
    }
    
    return self::obtenerPorId($factura_id);
  }
  
  /**
   * Listar todas las facturas
   */
  public static function all() {
    $sql = "SELECT f.*, 
            m.titulo as mantenimiento_titulo,
            e.nombre as equipo_nombre
            FROM facturas f
            JOIN mantenimientos m ON m.id = f.mantenimiento_id
            JOIN equipos e ON e.id = m.equipo_id
            ORDER BY f.fecha_emision DESC";
    
    return DB::pdo()->query($sql)->fetchAll();
  }
  
  /**
   * Actualizar estado de factura
   */
  public static function actualizarEstado($id, $estado) {
    $estados_validos = ['pendiente', 'pagada', 'cancelada'];
    
    if (!in_array($estado, $estados_validos)) {
      throw new Exception("Estado inválido");
    }
    
    $stmt = DB::pdo()->prepare("UPDATE facturas SET estado = ? WHERE id = ?");
    $stmt->execute([$estado, $id]);
    
    log_audit('facturas', $id, 'update', ['estado' => $estado]);
    
    return true;
  }

      public static function actualizar($id, $items, $costo_real = null) {
    $pdo = DB::pdo();
    $pdo->beginTransaction();
    
    try {
      // 1. Eliminar items antiguos
      $pdo->prepare("DELETE FROM factura_items WHERE factura_id = ?")->execute([$id]);
      
      // 2. Recalcular totales
      $subtotal = 0;
      foreach ($items as $item) {
        $subtotal += $item['cantidad'] * $item['precio_unitario'];
      }
      
      $impuesto = round($subtotal * 0.07, 2);
      $total = $subtotal + $impuesto;
      
      // 3. Actualizar factura
      $pdo->prepare("UPDATE facturas SET subtotal=?, impuesto=?, total=?, updated_at=NOW() WHERE id=?")
          ->execute([$subtotal, $impuesto, $total, $id]);
      
      // 4. Actualizar costo_real en mantenimientos si se proporciona
      if ($costo_real !== null) {
        $stmt = $pdo->prepare("SELECT mantenimiento_id FROM facturas WHERE id = ?");
        $stmt->execute([$id]);
        $mantenimiento_id = $stmt->fetchColumn();
        
        if ($mantenimiento_id) {
          $pdo->prepare("UPDATE mantenimientos SET costo_real = ? WHERE id = ?")
              ->execute([$costo_real, $mantenimiento_id]);
        }
      }
      
      // 5. Insertar nuevos items
      $stmt = $pdo->prepare("INSERT INTO factura_items (factura_id, descripcion, cantidad, precio_unitario, subtotal) VALUES (?,?,?,?,?)");
      
      foreach ($items as $item) {
        $item_subtotal = $item['cantidad'] * $item['precio_unitario'];
        $stmt->execute([
          $id,
          $item['descripcion'],
          $item['cantidad'],
          $item['precio_unitario'],
          $item_subtotal
        ]);
      }
      
      $pdo->commit();
      log_audit('facturas', $id, 'update', ['items_count' => count($items), 'total' => $total, 'costo_real' => $costo_real]);
      
      return ['total' => $total];
    } catch (Exception $e) {
      $pdo->rollBack();
      throw $e;
    }
  }


  
  /**
   * Eliminar factura
   */
  public static function delete($id) {
    DB::pdo()->prepare("DELETE FROM facturas WHERE id = ?")->execute([$id]);
    log_audit('facturas', $id, 'delete', null);
  }
}