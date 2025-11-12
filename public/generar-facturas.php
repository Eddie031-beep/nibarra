<?php
/**
 * NIBARRA - Generador de Facturas Retroactivas
 * Archivo: /var/www/nibarra/public/generar-facturas.php
 */

require_once dirname(__DIR__).'/app.php';
require_once dirname(__DIR__).'/models/Factura.php';

Auth::start();

// Solo admin
if (!Auth::check() || (Auth::user()['rol'] ?? '') !== 'admin') {
    die('
    <!DOCTYPE html>
    <html><head><meta charset="utf-8"><title>Acceso Denegado</title>
    <style>
        body{font-family:system-ui;background:#0b1220;color:#e5e7eb;padding:40px;text-align:center}
        .box{background:#0f172a;border:2px solid #ef4444;padding:40px;border-radius:20px;max-width:500px;margin:0 auto}
        h1{color:#ef4444;font-size:2rem;margin-bottom:20px}
        a{color:#60a5fa;text-decoration:none}
    </style></head>
    <body><div class="box">
        <h1>🔒 Acceso Denegado</h1>
        <p>Solo administradores pueden ejecutar este script.</p>
        <p><a href="'.ENV_APP['BASE_URL'].'/login">Iniciar sesión</a></p>
    </div></body></html>
    ');
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Facturas - Nibarra</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: system-ui, sans-serif;
            background: linear-gradient(135deg, #0b1220, #1a1f35);
            color: #e5e7eb;
            padding: 40px 20px;
            min-height: 100vh;
        }
        .container { max-width: 900px; margin: 0 auto; }
        .header {
            background: linear-gradient(135deg, #10b981, #059669);
            padding: 30px;
            border-radius: 20px 20px 0 0;
            text-align: center;
            color: white;
        }
        .header h1 { font-size: 2rem; margin-bottom: 10px; }
        .content {
            background: #0f172a;
            border: 1px solid #1e293b;
            border-radius: 0 0 20px 20px;
            padding: 30px;
        }
        .alert {
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .alert-info { background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.3); color: #93c5fd; }
        .alert-success { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); color: #6ee7b7; }
        .alert-warning { background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.3); color: #fcd34d; }
        .alert-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; }
        .result-card {
            background: #0b1220;
            border: 1px solid #1e293b;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
        }
        .result-card h3 { color: #cbd5e1; margin-bottom: 15px; font-size: 1.1rem; }
        .result-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .result-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 15px;
            background: #1f2937;
            border-radius: 8px;
            font-size: 0.9rem;
        }
        .result-label { color: #94a3b8; }
        .result-value { color: #cbd5e1; font-weight: 600; }
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }
        .btn-primary { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(79,70,229,0.5); }
        .btn-success { background: linear-gradient(135deg, #10b981, #059669); color: white; }
        .actions { display: flex; gap: 12px; margin-top: 30px; flex-wrap: wrap; }
        .icon { font-size: 2rem; }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        .success-icon { font-size: 4rem; animation: bounce 1s ease-in-out; }
        @media(max-width:768px) {
            .result-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧾 Generar Facturas Retroactivas</h1>
            <p>Sistema de Generación Automática</p>
        </div>
        
        <div class="content">
            <?php
            try {
                $pdo = DB::pdo();
                
                // Buscar mantenimientos completados sin factura
                $sql = "SELECT m.*, e.nombre as equipo_nombre, e.codigo as equipo_codigo
                        FROM mantenimientos m
                        JOIN equipos e ON e.id = m.equipo_id
                        WHERE m.estado = 'completado' 
                        AND m.id NOT IN (SELECT mantenimiento_id FROM facturas WHERE mantenimiento_id IS NOT NULL)
                        ORDER BY m.updated_at DESC";
                
                $stmt = $pdo->query($sql);
                $mantenimientos = $stmt->fetchAll();
                
                $total = count($mantenimientos);
                
                if ($total === 0) {
                    echo '<div class="alert alert-info">';
                    echo '<span class="icon">ℹ️</span>';
                    echo '<div>';
                    echo '<strong>No hay mantenimientos pendientes</strong><br>';
                    echo 'Todos los mantenimientos completados ya tienen factura.';
                    echo '</div>';
                    echo '</div>';
                    
                    echo '<div class="actions">';
                    echo '<a href="'.ENV_APP['BASE_URL'].'/facturas" class="btn btn-primary">📋 Ver Facturas</a>';
                    echo '<a href="'.ENV_APP['BASE_URL'].'/mantenimiento" class="btn btn-success">🔧 Mantenimientos</a>';
                    echo '</div>';
                    
                } else {
                    echo '<div class="alert alert-warning">';
                    echo '<span class="icon">⚠️</span>';
                    echo '<div>';
                    echo "<strong>Encontrados: {$total} mantenimiento(s) sin factura</strong><br>";
                    echo 'Generando facturas automáticamente...';
                    echo '</div>';
                    echo '</div>';
                    
                    $generadas = 0;
                    $errores = 0;
                    $detalles = [];
                    
                    // Generar facturas
                    foreach ($mantenimientos as $mant) {
                        try {
                            $resultado = Factura::crearDesdeMantenimiento($mant['id']);
                            
                            $detalles[] = [
                                'success' => true,
                                'mantenimiento' => $mant['titulo'],
                                'equipo' => $mant['equipo_nombre'],
                                'factura' => $resultado['numero_factura'],
                                'total' => $resultado['total']
                            ];
                            
                            $generadas++;
                            
                        } catch (Exception $e) {
                            $detalles[] = [
                                'success' => false,
                                'mantenimiento' => $mant['titulo'],
                                'equipo' => $mant['equipo_nombre'],
                                'error' => $e->getMessage()
                            ];
                            
                            $errores++;
                        }
                    }
                    
                    // Resumen
                    if ($generadas > 0) {
                        echo '<div class="alert alert-success">';
                        echo '<div class="success-icon">✅</div>';
                        echo '<div>';
                        echo "<strong>¡Proceso completado!</strong><br>";
                        echo "Facturas generadas: <strong>{$generadas}</strong>";
                        if ($errores > 0) {
                            echo "<br>⚠️ Con errores: <strong>{$errores}</strong>";
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                    
                    // Detalles
                    foreach ($detalles as $det) {
                        echo '<div class="result-card">';
                        
                        if ($det['success']) {
                            echo '<h3>✅ '.htmlspecialchars($det['mantenimiento']).'</h3>';
                            echo '<div class="result-grid">';
                            echo '<div class="result-item">';
                            echo '<span class="result-label">Equipo:</span>';
                            echo '<span class="result-value">'.htmlspecialchars($det['equipo']).'</span>';
                            echo '</div>';
                            echo '<div class="result-item">';
                            echo '<span class="result-label">Factura:</span>';
                            echo '<span class="result-value">'.htmlspecialchars($det['factura']).'</span>';
                            echo '</div>';
                            echo '<div class="result-item">';
                            echo '<span class="result-label">Total:</span>';
                            echo '<span class="result-value">$'.number_format($det['total'], 2).'</span>';
                            echo '</div>';
                            echo '<div class="result-item">';
                            echo '<span class="result-label">Estado:</span>';
                            echo '<span class="result-value" style="color:#6ee7b7">✓ Generada</span>';
                            echo '</div>';
                            echo '</div>';
                        } else {
                            echo '<h3>❌ '.htmlspecialchars($det['mantenimiento']).'</h3>';
                            echo '<div class="result-grid">';
                            echo '<div class="result-item">';
                            echo '<span class="result-label">Equipo:</span>';
                            echo '<span class="result-value">'.htmlspecialchars($det['equipo']).'</span>';
                            echo '</div>';
                            echo '<div class="result-item" style="grid-column:1/-1">';
                            echo '<span class="result-label">Error:</span>';
                            echo '<span class="result-value" style="color:#fca5a5">'.htmlspecialchars($det['error']).'</span>';
                            echo '</div>';
                            echo '</div>';
                        }
                        
                        echo '</div>';
                    }
                    
                    echo '<div class="actions">';
                    echo '<a href="'.ENV_APP['BASE_URL'].'/facturas" class="btn btn-primary">📋 Ver Facturas Generadas</a>';
                    echo '<a href="'.ENV_APP['BASE_URL'].'/mantenimiento" class="btn btn-success">🔧 Ver Mantenimientos</a>';
                    echo '</div>';
                    
                    echo '<div class="alert alert-warning" style="margin-top:30px">';
                    echo '<span class="icon">🔒</span>';
                    echo '<div>';
                    echo '<strong>SEGURIDAD IMPORTANTE</strong><br>';
                    echo 'Elimina este archivo ahora: <code>rm /var/www/nibarra/public/generar-facturas.php</code>';
                    echo '</div>';
                    echo '</div>';
                }
                
            } catch (Exception $e) {
                echo '<div class="alert alert-error">';
                echo '<span class="icon">❌</span>';
                echo '<div>';
                echo '<strong>Error Fatal</strong><br>';
                echo htmlspecialchars($e->getMessage());
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>