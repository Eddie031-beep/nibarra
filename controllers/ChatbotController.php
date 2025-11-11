<?php
require_once BASE_PATH.'/core/DB.php';
require_once BASE_PATH.'/core/Response.php';

class ChatbotController {
  
  public function query() {
    Auth::requireLogin();
    
    $pregunta = post('pregunta', '');
    if (empty($pregunta)) {
      return Response::json(['error' => 'Pregunta vacía'], 400);
    }
    
    try {
      // Analizar la pregunta y generar respuesta con datos reales
      $respuesta = $this->procesarPregunta($pregunta);
      
      return Response::json([
        'ok' => true,
        'respuesta' => $respuesta
      ]);
      
    } catch (Throwable $e) {
      return Response::json([
        'error' => 'Error al procesar: ' . $e->getMessage()
      ], 500);
    }
  }
  
  private function procesarPregunta($pregunta) {
    $preguntaLower = mb_strtolower($pregunta);
    
    // ==========================================
    // CONSULTAS SOBRE EQUIPOS
    // ==========================================
    
    if (preg_match('/cuantos?\s+(equipos?|maquinas?)/i', $preguntaLower)) {
      $total = DB::pdo()->query("SELECT COUNT(*) FROM equipos")->fetchColumn();
      $operativos = DB::pdo()->query("SELECT COUNT(*) FROM equipos WHERE estado='operativo'")->fetchColumn();
      $fuera = DB::pdo()->query("SELECT COUNT(*) FROM equipos WHERE estado='fuera_de_servicio'")->fetchColumn();
      $baja = DB::pdo()->query("SELECT COUNT(*) FROM equipos WHERE estado='baja'")->fetchColumn();
      
      return "📊 **Estado de equipos:**\n\n" .
             "• Total de equipos: **{$total}**\n" .
             "• Operativos: **{$operativos}**\n" .
             "• Fuera de servicio: **{$fuera}**\n" .
             "• Dados de baja: **{$baja}**";
    }
    
    if (preg_match('/equipos?\s+(reciente|nuevo|ultimo|agregado)/i', $preguntaLower)) {
      $equipos = DB::pdo()->query(
        "SELECT nombre, codigo, categoria, estado, DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') as fecha 
         FROM equipos 
         ORDER BY created_at DESC 
         LIMIT 5"
      )->fetchAll();
      
      if (empty($equipos)) {
        return "No hay equipos registrados aún.";
      }
      
      $resp = "🔧 **Últimos equipos agregados:**\n\n";
      foreach ($equipos as $eq) {
        $resp .= "• **{$eq['nombre']}** ({$eq['codigo']})\n";
        $resp .= "  Categoría: {$eq['categoria']} | Estado: {$eq['estado']}\n";
        $resp .= "  Agregado: {$eq['fecha']}\n\n";
      }
      return $resp;
    }
    
    if (preg_match('/equipos?\s+operativo/i', $preguntaLower)) {
      $equipos = DB::pdo()->query(
        "SELECT nombre, codigo, ubicacion 
         FROM equipos 
         WHERE estado='operativo' 
         ORDER BY nombre"
      )->fetchAll();
      
      if (empty($equipos)) {
        return "No hay equipos operativos en este momento.";
      }
      
      $resp = "✅ **Equipos operativos ({$count}):**\n\n";
      foreach ($equipos as $eq) {
        $resp .= "• **{$eq['nombre']}** ({$eq['codigo']}) - {$eq['ubicacion']}\n";
      }
      return $resp;
    }
    
    // ==========================================
    // CONSULTAS SOBRE MANTENIMIENTOS
    // ==========================================
    
    if (preg_match('/cuantos?\s+(mantenimiento|mantenciones)/i', $preguntaLower)) {
      $total = DB::pdo()->query("SELECT COUNT(*) FROM mantenimientos")->fetchColumn();
      $pendientes = DB::pdo()->query("SELECT COUNT(*) FROM mantenimientos WHERE estado='pendiente'")->fetchColumn();
      $enProgreso = DB::pdo()->query("SELECT COUNT(*) FROM mantenimientos WHERE estado='en_progreso'")->fetchColumn();
      $completados = DB::pdo()->query("SELECT COUNT(*) FROM mantenimientos WHERE estado='completado'")->fetchColumn();
      
      return "📋 **Estado de mantenimientos:**\n\n" .
             "• Total: **{$total}**\n" .
             "• Pendientes: **{$pendientes}**\n" .
             "• En progreso: **{$enProgreso}**\n" .
             "• Completados: **{$completados}**";
    }
    
    if (preg_match('/mantenimiento.+(pendiente|proximo|programado)/i', $preguntaLower)) {
      $mants = DB::pdo()->query(
        "SELECT m.titulo, m.tipo, m.prioridad, e.nombre as equipo, 
                DATE_FORMAT(m.fecha_programada, '%d/%m/%Y %H:%i') as fecha
         FROM mantenimientos m
         JOIN equipos e ON e.id = m.equipo_id
         WHERE m.estado = 'pendiente'
         ORDER BY m.fecha_programada ASC
         LIMIT 5"
      )->fetchAll();
      
      if (empty($mants)) {
        return "No hay mantenimientos pendientes.";
      }
      
      $resp = "⏰ **Próximos mantenimientos:**\n\n";
      foreach ($mants as $m) {
        $resp .= "• **{$m['titulo']}**\n";
        $resp .= "  Equipo: {$m['equipo']}\n";
        $resp .= "  Tipo: {$m['tipo']} | Prioridad: {$m['prioridad']}\n";
        $resp .= "  Fecha: {$m['fecha']}\n\n";
      }
      return $resp;
    }
    
    if (preg_match('/mantenimiento.+(critico|urgente|alta)/i', $preguntaLower)) {
      $mants = DB::pdo()->query(
        "SELECT m.titulo, e.nombre as equipo, m.estado
         FROM mantenimientos m
         JOIN equipos e ON e.id = m.equipo_id
         WHERE m.prioridad IN ('alta', 'critica')
         ORDER BY FIELD(m.prioridad, 'critica', 'alta'), m.fecha_programada ASC"
      )->fetchAll();
      
      if (empty($mants)) {
        return "No hay mantenimientos de prioridad alta o crítica.";
      }
      
      $resp = "🚨 **Mantenimientos prioritarios:**\n\n";
      foreach ($mants as $m) {
        $resp .= "• **{$m['titulo']}**\n";
        $resp .= "  Equipo: {$m['equipo']} | Estado: {$m['estado']}\n\n";
      }
      return $resp;
    }
    
    // ==========================================
    // CONSULTAS SOBRE CALENDARIO
    // ==========================================
    
    if (preg_match('/cuantos?\s+(eventos?|calendario)/i', $preguntaLower)) {
      $total = DB::pdo()->query("SELECT COUNT(*) FROM calendario_eventos")->fetchColumn();
      $hoy = date('Y-m-d');
      $proximos = DB::pdo()->query(
        "SELECT COUNT(*) FROM calendario_eventos WHERE DATE(inicio) >= ?"
      , [$hoy])->fetchColumn();
      
      return "📅 **Calendario:**\n\n" .
             "• Total de eventos: **{$total}**\n" .
             "• Próximos eventos: **{$proximos}**";
    }
    
    if (preg_match('/(eventos?|calendario).+(reciente|nuevo|ultimo|agregado|hoy|proximos?)/i', $preguntaLower)) {
      $eventos = DB::pdo()->query(
        "SELECT titulo, DATE_FORMAT(inicio, '%d/%m/%Y %H:%i') as fecha_inicio,
                DATE_FORMAT(fin, '%H:%i') as hora_fin, all_day
         FROM calendario_eventos
         WHERE inicio >= NOW()
         ORDER BY inicio ASC
         LIMIT 5"
      )->fetchAll();
      
      if (empty($eventos)) {
        // Si no hay próximos, mostrar los más recientes
        $eventos = DB::pdo()->query(
          "SELECT titulo, DATE_FORMAT(inicio, '%d/%m/%Y %H:%i') as fecha_inicio,
                  DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') as creado
           FROM calendario_eventos
           ORDER BY created_at DESC
           LIMIT 5"
        )->fetchAll();
        
        if (empty($eventos)) {
          return "No hay eventos en el calendario.";
        }
        
        $resp = "📅 **Últimos eventos agregados:**\n\n";
        foreach ($eventos as $ev) {
          $resp .= "• **{$ev['titulo']}**\n";
          $resp .= "  Fecha evento: {$ev['fecha_inicio']}\n";
          $resp .= "  Creado: {$ev['creado']}\n\n";
        }
        return $resp;
      }
      
      $resp = "📅 **Próximos eventos:**\n\n";
      foreach ($eventos as $ev) {
        $resp .= "• **{$ev['titulo']}**\n";
        if ($ev['all_day']) {
          $resp .= "  Todo el día: {$ev['fecha_inicio']}\n\n";
        } else {
          $resp .= "  Desde: {$ev['fecha_inicio']}";
          if ($ev['hora_fin']) {
            $resp .= " hasta {$ev['hora_fin']}";
          }
          $resp .= "\n\n";
        }
      }
      return $resp;
    }
    
    // ==========================================
    // CONSULTAS SOBRE AUDITORÍA
    // ==========================================
    
    if (preg_match('/(actividad|auditoria|cambios?|registro).+(reciente|ultimo)/i', $preguntaLower)) {
      $logs = DB::pdo()->query(
        "SELECT a.tabla, a.accion, a.registro_id, 
                u.nombre as usuario, 
                DATE_FORMAT(a.created_at, '%d/%m/%Y %H:%i:%s') as fecha
         FROM audit_logs a
         LEFT JOIN users u ON u.id = a.usuario_id
         ORDER BY a.created_at DESC
         LIMIT 10"
      )->fetchAll();
      
      if (empty($logs)) {
        return "No hay registros de auditoría.";
      }
      
      $resp = "📝 **Actividad reciente:**\n\n";
      foreach ($logs as $log) {
        $accion = [
          'insert' => 'Creó',
          'update' => 'Actualizó',
          'delete' => 'Eliminó'
        ][$log['accion']] ?? $log['accion'];
        
        $resp .= "• **{$log['usuario']}** {$accion} en {$log['tabla']} (ID: {$log['registro_id']})\n";
        $resp .= "  {$log['fecha']}\n\n";
      }
      return $resp;
    }
    
    // ==========================================
    // CONSULTAS SOBRE COSTOS
    // ==========================================
    
    if (preg_match('/costo|gasto|inversion/i', $preguntaLower)) {
      $totalEquipos = DB::pdo()->query(
        "SELECT COALESCE(SUM(costo), 0) FROM equipos WHERE costo IS NOT NULL"
      )->fetchColumn();
      
      $totalMant = DB::pdo()->query(
        "SELECT COALESCE(SUM(costo_real), 0) FROM mantenimientos WHERE costo_real IS NOT NULL"
      )->fetchColumn();
      
      $estimadoMant = DB::pdo()->query(
        "SELECT COALESCE(SUM(costo_estimado), 0) FROM mantenimientos WHERE estado='pendiente'"
      )->fetchColumn();
      
      return "💰 **Resumen de costos:**\n\n" .
             "• Inversión en equipos: **$" . number_format($totalEquipos, 2) . "**\n" .
             "• Gasto en mantenimientos: **$" . number_format($totalMant, 2) . "**\n" .
             "• Estimado pendiente: **$" . number_format($estimadoMant, 2) . "**\n" .
             "• **Total invertido: $" . number_format($totalEquipos + $totalMant, 2) . "**";
    }
    
    // ==========================================
    // BÚSQUEDA POR NOMBRE DE EQUIPO
    // ==========================================
    
    if (preg_match('/buscar|encontrar|informacion|datos de/i', $preguntaLower)) {
      // Extraer posible nombre de equipo
      $palabras = explode(' ', $preguntaLower);
      $posibleNombre = '';
      foreach ($palabras as $i => $palabra) {
        if (in_array($palabra, ['buscar', 'encontrar', 'informacion', 'datos', 'de', 'del', 'sobre', 'equipo'])) {
          if (isset($palabras[$i + 1])) {
            $posibleNombre = $palabras[$i + 1];
            break;
          }
        }
      }
      
      if ($posibleNombre) {
        $equipo = DB::pdo()->prepare(
          "SELECT * FROM equipos WHERE LOWER(nombre) LIKE ? OR LOWER(codigo) LIKE ? LIMIT 1"
        );
        $equipo->execute(["%$posibleNombre%", "%$posibleNombre%"]);
        $eq = $equipo->fetch();
        
        if ($eq) {
          return "🔧 **{$eq['nombre']}** ({$eq['codigo']})\n\n" .
                 "• Categoría: {$eq['categoria']}\n" .
                 "• Marca: {$eq['marca']} {$eq['modelo']}\n" .
                 "• Serie: {$eq['nro_serie']}\n" .
                 "• Ubicación: {$eq['ubicacion']}\n" .
                 "• Estado: **{$eq['estado']}**\n" .
                 "• Costo: $" . number_format($eq['costo'] ?? 0, 2);
        }
      }
    }
    
    // ==========================================
    // AYUDA Y COMANDOS
    // ==========================================
    
    if (preg_match('/ayuda|help|comandos|que puedes/i', $preguntaLower)) {
      return "🤖 **Comandos disponibles:**\n\n" .
             "📊 **Equipos:**\n" .
             "• ¿Cuántos equipos hay?\n" .
             "• Equipos recientes\n" .
             "• Equipos operativos\n\n" .
             "📋 **Mantenimientos:**\n" .
             "• ¿Cuántos mantenimientos hay?\n" .
             "• Mantenimientos pendientes\n" .
             "• Mantenimientos críticos\n\n" .
             "📅 **Calendario:**\n" .
             "• ¿Cuántos eventos hay?\n" .
             "• Eventos recientes\n" .
             "• Próximos eventos\n\n" .
             "💰 **Costos:**\n" .
             "• Resumen de costos\n\n" .
             "📝 **Auditoría:**\n" .
             "• Actividad reciente";
    }
    
    // ==========================================
    // RESPUESTA POR DEFECTO
    // ==========================================
    
    return "🤔 No entendí tu pregunta. Intenta con:\n\n" .
           "• ¿Cuántos equipos hay?\n" .
           "• Mantenimientos pendientes\n" .
           "• Eventos recientes\n" .
           "• Resumen de costos\n\n" .
           "Escribe **ayuda** para ver todos los comandos.";
  }
}