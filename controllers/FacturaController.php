<?php
// FacturaController.php - Gestión de Facturas
require_once BASE_PATH.'/models/Factura.php';
require_once BASE_PATH.'/core/Permisos.php';
require_once BASE_PATH.'/core/Response.php';

class FacturaController {
  
  /**
   * Listar todas las facturas
   */
  public function index() {
    Auth::requireLogin();
    $facturas = Factura::all();
    view('facturas/index', compact('facturas'));
  }
  
  /**
   * Ver detalle de factura
   */
  public function ver($id) {
    Auth::requireLogin();
    $factura = Factura::obtenerPorId($id);
    
    if (!$factura) {
      redirect('/facturas');
      return;
    }
    
    view('facturas/detalle', compact('factura'));
  }
  
  /**
   * Generar factura desde mantenimiento (AJAX)
   */
  public function generar() {
    Auth::requireLogin();
    Permisos::requireCrear();
    
    $mantenimiento_id = (int)post('mantenimiento_id');
    
    try {
      $resultado = Factura::crearDesdeMantenimiento($mantenimiento_id);
      
      Response::json([
        'ok' => true,
        'factura_id' => $resultado['id'],
        'numero_factura' => $resultado['numero_factura'],
        'total' => $resultado['total'],
        'mensaje' => "Factura {$resultado['numero_factura']} generada exitosamente"
      ]);
      
    } catch (Exception $e) {
      Response::json([
        'ok' => false,
        'error' => $e->getMessage()
      ], 400);
    }
  }
  
  /**
   * Actualizar estado de factura (AJAX)
   */
  public function actualizarEstado() {
    Auth::requireLogin();
    Permisos::requireEditar();
    
    $id = (int)post('factura_id');
    $estado = post('estado');
    
    try {
      Factura::actualizarEstado($id, $estado);
      
      Response::json([
        'ok' => true,
        'mensaje' => 'Estado actualizado correctamente'
      ]);
      
    } catch (Exception $e) {
      Response::json([
        'ok' => false,
        'error' => $e->getMessage()
      ], 400);
    }
  }

    // Línea 84 - Método faltante
  public function actualizar() {
  Auth::requireLogin();
  Permisos::requireEditar();
  
  $factura_id = (int)post('factura_id');
  $items = json_decode(post('items'), true);
  $costo_real = post('costo_real') !== '' ? (float)post('costo_real') : null;
  
  try {
    $resultado = Factura::actualizar($factura_id, $items, $costo_real);
    Response::json(['ok' => true, 'total' => $resultado['total']]);
  } catch (Exception $e) {
    Response::json(['ok' => false, 'error' => $e->getMessage()], 400);
  }
}

  
  /**
   * Descargar factura en PDF
   */
  public function descargarPDF($id) {
    Auth::requireLogin();
    
    $factura = Factura::obtenerPorId($id);
    
    if (!$factura) {
      redirect('/facturas');
      return;
    }
    
    // Generar HTML para PDF
    ob_start();
    include VIEWS_PATH . '/facturas/pdf.php';
    $html = ob_get_clean();
    
    // Headers para descarga
    header('Content-Type: text/html; charset=utf-8');
    header('Content-Disposition: inline; filename="factura-' . $factura['numero_factura'] . '.html"');
    
    echo $html;
  }
  
  /**
   * Eliminar factura
   */
  public function destroy($id) {
    Auth::requireLogin();
    Permisos::requireEliminar();
    
    Factura::delete($id);
    redirect('/facturas');
  }
}