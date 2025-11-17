<?php
require_once BASE_PATH.'/core/DB.php';
require_once BASE_PATH.'/core/Response.php';

/**
 * 🤖 NIBARRA AI ASSISTANT - IA Conversacional Conectada a Base de Datos
 * Entiende preguntas naturales y responde con datos reales del sistema
 */
class ChatbotController {
  
  public function query() {
    Auth::requireLogin();
    
    $pregunta = post('pregunta', '');
    if (empty($pregunta)) {
      return Response::json(['error' => 'Pregunta vacía'], 400);
    }
    
    try {
      $respuesta = $this->procesarPreguntaInteligente($pregunta);
      
      return Response::json([
        'ok' => true,
        'respuesta' => $respuesta['text'],
        'metadata' => $respuesta['metadata'] ?? null,
        'sugerencias' => $respuesta['sugerencias'] ?? []
      ]);
      
    } catch (Throwable $e) {
      error_log("Error ChatBot: " . $e->getMessage());
      return Response::json([
        'error' => 'Error al procesar: ' . $e->getMessage()
      ], 500);
    }
  }
  
  /**
   * 🧠 Procesamiento inteligente de preguntas naturales
   */
  private function procesarPreguntaInteligente($pregunta) {
    $preguntaLower = mb_strtolower($pregunta);
    $pdo = DB::pdo();
    
    // ============================================
    // 📊 CONSULTAS SOBRE EQUIPOS
    // ============================================
    
    // ¿Qué equipos se agregaron recientemente?
    if (preg_match('/(que|cuales|cuantos).+(equipos?|maquinas?).+(agregado|añadido|nuevo|reciente|ultimo)/i', $pregunta)) {
      $equipos = $pdo->query("
        SELECT nombre, codigo, categoria, marca, modelo, estado, 
               DATE_FORMAT(created_at, '%d/%m/%Y a las %H:%i') as fecha,
               TIMESTAMPDIFF(HOUR, created_at, NOW()) as horas_desde
        FROM equipos 
        ORDER BY created_at DESC 
        LIMIT 10
      ")->fetchAll();
      
      if (empty($equipos)) {
        return [
          'text' => "❌ No hay equipos registrados aún en el sistema.",
          'sugerencias' => ["Agregar nuevo equipo"]
        ];
      }
      
      $resp = "🔧 **Equipos agregados recientemente:**\n\n";
      
      foreach ($equipos as $i => $eq) {
        $estadoIcon = ['operativo' => '✅', 'fuera_de_servicio' => '⚠️', 'baja' => '❌'][$eq['estado']] ?? '❓';
        
        // Calcular tiempo relativo
        $tiempo = $this->tiempoRelativo($eq['horas_desde']);
        
        $resp .= "**" . ($i + 1) . ". {$eq['nombre']}**\n";
        $resp .= "   • Código: {$eq['codigo']}\n";
        if ($eq['marca']) $resp .= "   • Marca: {$eq['marca']} {$eq['modelo']}\n";
        $resp .= "   • Categoría: {$eq['categoria']}\n";
        $resp .= "   • Estado: {$estadoIcon} " . str_replace('_', ' ', ucfirst($eq['estado'])) . "\n";
        $resp .= "   • Agregado: {$tiempo} ({$eq['fecha']})\n\n";
      }
      
      return [
        'text' => $resp,
        'sugerencias' => [
          "Ver equipos operativos",
          "Equipos fuera de servicio",
          "Agregar nuevo equipo"
        ]
      ];
    }
    
    // Buscar equipo específico por nombre
    if (preg_match('/(busca|encuentra|informacion|datos|dame).+(de|del|sobre).+/i', $pregunta)) {
      // Extraer palabras clave después de "de/del/sobre"
      preg_match('/(de|del|sobre)\s+(.+)/i', $pregunta, $matches);
      $nombreBuscar = $matches[2] ?? '';
      
      if ($nombreBuscar) {
        $stmt = $pdo->prepare("
          SELECT e.*, 
                 DATE_FORMAT(e.created_at, '%d/%m/%Y %H:%i') as fecha_registro,
                 (SELECT COUNT(*) FROM mantenimientos WHERE equipo_id = e.id) as total_mantenimientos
          FROM equipos e
          WHERE LOWER(e.nombre) LIKE ? 
             OR LOWER(e.codigo) LIKE ?
             OR LOWER(e.categoria) LIKE ?
             OR LOWER(e.marca) LIKE ?
          ORDER BY e.created_at DESC
          LIMIT 1
        ");
        $search = "%{$nombreBuscar}%";
        $stmt->execute([$search, $search, $search, $search]);
        $equipo = $stmt->fetch();
        
        if ($equipo) {
          $estadoIcon = ['operativo' => '✅', 'fuera_de_servicio' => '⚠️', 'baja' => '❌'][$equipo['estado']] ?? '❓';
          
          $resp = "🔍 **Información del equipo encontrado:**\n\n";
          $resp .= "## {$equipo['nombre']}\n\n";
          $resp .= "• **Código:** {$equipo['codigo']}\n";
          $resp .= "• **Categoría:** {$equipo['categoria']}\n";
          if ($equipo['marca']) $resp .= "• **Marca/Modelo:** {$equipo['marca']} {$equipo['modelo']}\n";
          if ($equipo['nro_serie']) $resp .= "• **Nro. Serie:** {$equipo['nro_serie']}\n";
          $resp .= "• **Ubicación:** {$equipo['ubicacion']}\n";
          $resp .= "• **Estado:** {$estadoIcon} " . str_replace('_', ' ', ucfirst($equipo['estado'])) . "\n";
          if ($equipo['costo']) $resp .= "• **Costo:** $" . number_format($equipo['costo'], 2) . "\n";
          $resp .= "• **Mantenimientos realizados:** {$equipo['total_mantenimientos']}\n";
          $resp .= "• **Registrado:** {$equipo['fecha_registro']}\n\n";
          
          return [
            'text' => $resp,
            'sugerencias' => [
              "Ver mantenimientos de este equipo",
              "Crear orden de mantenimiento",
              "Ver historial completo"
            ]
          ];
        }
      }
    }
    
    // Estado general de equipos
    if (preg_match('/(cuantos?|estado|resumen).+(equipos?|maquinas?)/i', $pregunta)) {
      $stats = $pdo->query("
        SELECT 
          COUNT(*) as total,
          SUM(CASE WHEN estado='operativo' THEN 1 ELSE 0 END) as operativos,
          SUM(CASE WHEN estado='fuera_de_servicio' THEN 1 ELSE 0 END) as fuera_servicio,
          SUM(CASE WHEN estado='baja' THEN 1 ELSE 0 END) as dados_baja
        FROM equipos
      ")->fetch();
      
      $tasa = $stats['total'] > 0 ? round(($stats['operativos'] / $stats['total']) * 100, 1) : 0;
      
      $resp = "📊 **Estado actual de equipos:**\n\n";
      $resp .= "• **Total de equipos:** {$stats['total']}\n";
      $resp .= "• ✅ **Operativos:** {$stats['operativos']} ({$tasa}%)\n";
      $resp .= "• ⚠️ **Fuera de servicio:** {$stats['fuera_servicio']}\n";
      $resp .= "• ❌ **Dados de baja:** {$stats['dados_baja']}\n\n";
      
      if ($tasa >= 90) {
        $resp .= "✨ **Excelente:** El sistema tiene una alta tasa de operatividad.\n";
      } elseif ($tasa >= 70) {
        $resp .= "⚠️ **Aceptable:** Considera dar mantenimiento a los equipos fuera de servicio.\n";
      } else {
        $resp .= "🚨 **Crítico:** Necesitas atender urgentemente los equipos fuera de servicio.\n";
      }
      
      return [
        'text' => $resp,
        'sugerencias' => [
          "Ver equipos fuera de servicio",
          "Equipos agregados recientemente",
          "Analizar sistema completo"
        ]
      ];
    }
    
    // ============================================
    // 📋 CONSULTAS SOBRE MANTENIMIENTOS
    // ============================================
    
    // Mantenimientos recientes
    if (preg_match('/(que|cuales).+(mantenimiento).+(reciente|ultimo|nuevo)/i', $pregunta)) {
      $mantenimientos = $pdo->query("
        SELECT m.id, m.titulo, m.tipo, m.prioridad, m.estado,
               e.nombre as equipo_nombre,
               DATE_FORMAT(m.fecha_programada, '%d/%m/%Y %H:%i') as fecha,
               DATE_FORMAT(m.created_at, '%d/%m/%Y %H:%i') as fecha_creacion,
               TIMESTAMPDIFF(DAY, m.created_at, NOW()) as dias_desde
        FROM mantenimientos m
        JOIN equipos e ON e.id = m.equipo_id
        ORDER BY m.created_at DESC
        LIMIT 5
      ")->fetchAll();
      
      if (empty($mantenimientos)) {
        return [
          'text' => "❌ No hay mantenimientos registrados aún.",
          'sugerencias' => ["Crear nuevo mantenimiento"]
        ];
      }
      
      $resp = "📋 **Mantenimientos recientes:**\n\n";
      
      foreach ($mantenimientos as $i => $m) {
        $prioIcon = ['baja' => '🟢', 'media' => '🟡', 'alta' => '🔴', 'critica' => '🚨'][$m['prioridad']] ?? '⚪';
        $estadoIcon = ['pendiente' => '⏳', 'en_progreso' => '🔧', 'completado' => '✅'][$m['estado']] ?? '❓';
        
        $resp .= "**" . ($i + 1) . ". {$m['titulo']}**\n";
        $resp .= "   • Equipo: {$m['equipo_nombre']}\n";
        $resp .= "   • Tipo: " . ucfirst($m['tipo']) . "\n";
        $resp .= "   • Prioridad: {$prioIcon} " . ucfirst($m['prioridad']) . "\n";
        $resp .= "   • Estado: {$estadoIcon} " . str_replace('_', ' ', ucfirst($m['estado'])) . "\n";
        $resp .= "   • Programado: {$m['fecha']}\n";
        $resp .= "   • Creado hace: {$m['dias_desde']} día(s)\n\n";
      }
      
      return [
        'text' => $resp,
        'sugerencias' => [
          "Mantenimientos pendientes",
          "Mantenimientos críticos",
          "Crear nuevo mantenimiento"
        ]
      ];
    }
    
    // Mantenimientos pendientes
    if (preg_match('/(mantenimiento).+(pendiente|atrasado|vencido)/i', $pregunta)) {
      $pendientes = $pdo->query("
        SELECT m.titulo, e.nombre as equipo, m.prioridad,
               DATE_FORMAT(m.fecha_programada, '%d/%m/%Y %H:%i') as fecha,
               DATEDIFF(NOW(), m.fecha_programada) as dias_atrasado
        FROM mantenimientos m
        JOIN equipos e ON e.id = m.equipo_id
        WHERE m.estado = 'pendiente'
        ORDER BY m.fecha_programada ASC
      ")->fetchAll();
      
      if (empty($pendientes)) {
        return [
          'text' => "✅ **¡Excelente!** No hay mantenimientos pendientes.\n\nTodo está al día.",
          'sugerencias' => ["Ver mantenimientos completados", "Crear nuevo mantenimiento"]
        ];
      }
      
      $resp = "⏳ **Mantenimientos pendientes:** " . count($pendientes) . "\n\n";
      
      $atrasados = 0;
      foreach ($pendientes as $i => $m) {
        $prioIcon = ['baja' => '🟢', 'media' => '🟡', 'alta' => '🔴', 'critica' => '🚨'][$m['prioridad']] ?? '⚪';
        
        if ($m['dias_atrasado'] > 0) {
          $atrasados++;
          $resp .= "🚨 **ATRASADO** ({$m['dias_atrasado']} días)\n";
        }
        
        $resp .= "**" . ($i + 1) . ". {$m['titulo']}**\n";
        $resp .= "   • Equipo: {$m['equipo']}\n";
        $resp .= "   • Prioridad: {$prioIcon} " . ucfirst($m['prioridad']) . "\n";
        $resp .= "   • Fecha: {$m['fecha']}\n\n";
      }
      
      if ($atrasados > 0) {
        $resp .= "⚠️ **Atención:** {$atrasados} mantenimiento(s) están atrasados.\n";
      }
      
      return [
        'text' => $resp,
        'sugerencias' => [
          "Mantenimientos críticos",
          "Programar mantenimientos",
          "Analizar sistema"
        ]
      ];
    }
    
    // ============================================
    // 💰 CONSULTAS SOBRE COSTOS
    // ============================================
    
    if (preg_match('/(cuanto|costo|gasto|precio|dinero|inversion)/i', $pregunta)) {
      $costos = $pdo->query("
        SELECT 
          COALESCE(SUM(costo), 0) as total_equipos,
          COUNT(*) as cant_equipos
        FROM equipos
      ")->fetch();
      
      $mantenimientos = $pdo->query("
        SELECT 
          COALESCE(SUM(costo_real), 0) as gastado,
          COALESCE(SUM(costo_estimado), 0) as estimado_pendiente,
          COUNT(CASE WHEN costo_real IS NOT NULL THEN 1 END) as completados,
          COUNT(CASE WHEN estado='pendiente' THEN 1 END) as pendientes
        FROM mantenimientos
      ")->fetch();
      
      $total_invertido = $costos['total_equipos'] + $mantenimientos['gastado'];
      $promedio_equipo = $costos['cant_equipos'] > 0 ? $total_invertido / $costos['cant_equipos'] : 0;
      
      $resp = "💰 **Análisis financiero del sistema:**\n\n";
      $resp .= "### Inversión en Equipos\n";
      $resp .= "• **Total equipos:** $" . number_format($costos['total_equipos'], 2) . "\n";
      $resp .= "• **Cantidad:** {$costos['cant_equipos']} equipos\n";
      $resp .= "• **Promedio por equipo:** $" . number_format($promedio_equipo, 2) . "\n\n";
      
      $resp .= "### Gastos en Mantenimiento\n";
      $resp .= "• **Gastado (realizados):** $" . number_format($mantenimientos['gastado'], 2) . "\n";
      $resp .= "• **Estimado pendiente:** $" . number_format($mantenimientos['estimado_pendiente'], 2) . "\n";
      $resp .= "• **Completados:** {$mantenimientos['completados']}\n";
      $resp .= "• **Pendientes:** {$mantenimientos['pendientes']}\n\n";
      
      $resp .= "### Total General\n";
      $resp .= "• **Total invertido:** $" . number_format($total_invertido, 2) . "\n";
      $resp .= "• **Proyección total:** $" . number_format($total_invertido + $mantenimientos['estimado_pendiente'], 2) . "\n";
      
      return [
        'text' => $resp,
        'sugerencias' => [
          "Ver equipos más costosos",
          "Analizar ROI",
          "Optimizar costos"
        ]
      ];
    }
    
    // ============================================
    // 🔮 PREDICCIONES Y ANÁLISIS
    // ============================================
    
    if (preg_match('/(analiza|analizar|estado|salud|reporte)/i', $pregunta)) {
      return $this->analisisCompleto($pdo);
    }
    
    if (preg_match('/(recomienda|sugiere|deberia|que hacer)/i', $pregunta)) {
      return $this->generarRecomendaciones($pdo);
    }
    
    if (preg_match('/(predice|predecir|futuro|proximo|necesita)/i', $pregunta)) {
      return $this->predecirMantenimientos($pdo);
    }
    
    // ============================================
    // 💬 RESPUESTAS CONVERSACIONALES
    // ============================================
    
    if (preg_match('/^(hola|hi|hey|buenos dias|buenas tardes)/i', $pregunta)) {
      return [
        'text' => "👋 ¡Hola! Soy el asistente inteligente de Nibarra.\n\n" .
                 "Puedo ayudarte con información en tiempo real sobre:\n" .
                 "• 🔧 Tus equipos\n" .
                 "• 📋 Mantenimientos\n" .
                 "• 💰 Costos e inversiones\n" .
                 "• 📊 Análisis y reportes\n\n" .
                 "Pregúntame lo que necesites.",
        'sugerencias' => [
          "¿Qué equipos se agregaron recientemente?",
          "Analiza el sistema",
          "Mantenimientos pendientes",
          "¿Cuánto he gastado?"
        ]
      ];
    }
    
    if (preg_match('/(gracias|thanks|genial|perfecto|excelente)/i', $pregunta)) {
      return [
        'text' => "😊 ¡De nada! ¿Hay algo más en lo que pueda ayudarte?",
        'sugerencias' => [
          "Ver estadísticas",
          "Mantenimientos pendientes",
          "Equipos recientes"
        ]
      ];
    }
    
    if (preg_match('/(ayuda|help|que puedes|comandos)/i', $pregunta)) {
      return [
        'text' => "🤖 **Puedo ayudarte con:**\n\n" .
                 "### 🔍 Búsquedas Específicas\n" .
                 "• \"Busca información del servidor\"\n" .
                 "• \"Equipos agregados recientemente\"\n" .
                 "• \"Dame datos de [nombre equipo]\"\n\n" .
                 "### 📊 Estadísticas\n" .
                 "• \"Cuántos equipos tengo\"\n" .
                 "• \"Estado de los equipos\"\n" .
                 "• \"Equipos fuera de servicio\"\n\n" .
                 "### 📋 Mantenimientos\n" .
                 "• \"Mantenimientos pendientes\"\n" .
                 "• \"Mantenimientos recientes\"\n" .
                 "• \"Qué mantenimientos están atrasados\"\n\n" .
                 "### 💰 Costos\n" .
                 "• \"Cuánto he gastado\"\n" .
                 "• \"Cuál es la inversión total\"\n\n" .
                 "### 🔮 Análisis Inteligente\n" .
                 "• \"Analiza el sistema\"\n" .
                 "• \"Recomienda acciones\"\n" .
                 "• \"Predice mantenimientos\"\n\n" .
                 "**Habla naturalmente, ¡te entenderé!** 😊",
        'sugerencias' => [
          "¿Qué equipos se agregaron recientemente?",
          "Analiza el sistema",
          "Cuánto he gastado",
          "Mantenimientos pendientes"
        ]
      ];
    }
    
    // ============================================
    // ❓ RESPUESTA POR DEFECTO
    // ============================================
    
    return [
      'text' => "🤔 No estoy seguro de entender tu pregunta.\n\n" .
               "Intenta preguntar:\n" .
               "• \"¿Qué equipos se agregaron recientemente?\"\n" .
               "• \"Busca información del [nombre]\"\n" .
               "• \"Mantenimientos pendientes\"\n" .
               "• \"Cuánto he gastado\"\n" .
               "• \"Analiza el sistema\"\n\n" .
               "O escribe **ayuda** para ver más ejemplos.",
      'sugerencias' => [
        "Ayuda",
        "Equipos recientes",
        "Analizar sistema",
        "Mantenimientos pendientes"
      ]
    ];
  }
  
  /**
   * 📊 Análisis completo del sistema
   */
  private function analisisCompleto($pdo) {
    $stats = $pdo->query("
      SELECT 
        COUNT(*) as total_equipos,
        SUM(CASE WHEN estado='operativo' THEN 1 ELSE 0 END) as operativos,
        SUM(CASE WHEN estado='fuera_de_servicio' THEN 1 ELSE 0 END) as fuera_servicio
      FROM equipos
    ")->fetch();
    
    $mant = $pdo->query("
      SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN estado='pendiente' THEN 1 ELSE 0 END) as pendientes,
        SUM(CASE WHEN estado='en_progreso' THEN 1 ELSE 0 END) as en_progreso,
        SUM(CASE WHEN DATEDIFF(NOW(), fecha_programada) > 0 AND estado='pendiente' THEN 1 ELSE 0 END) as atrasados
      FROM mantenimientos
    ")->fetch();
    
    $tasa = $stats['total_equipos'] > 0 ? round(($stats['operativos'] / $stats['total_equipos']) * 100, 1) : 0;
    
    $salud = $tasa >= 90 ? '🟢 Excelente' : ($tasa >= 70 ? '🟡 Aceptable' : '🔴 Crítico');
    
    $resp = "📊 **Análisis Completo del Sistema**\n\n";
    $resp .= "### Estado de Salud: {$salud}\n\n";
    $resp .= "### Equipos\n";
    $resp .= "• Total: {$stats['total_equipos']}\n";
    $resp .= "• ✅ Operativos: {$stats['operativos']} ({$tasa}%)\n";
    $resp .= "• ⚠️ Fuera de servicio: {$stats['fuera_servicio']}\n\n";
    
    $resp .= "### Mantenimientos\n";
    $resp .= "• Total: {$mant['total']}\n";
    $resp .= "• ⏳ Pendientes: {$mant['pendientes']}\n";
    $resp .= "• 🔧 En progreso: {$mant['en_progreso']}\n";
    if ($mant['atrasados'] > 0) {
      $resp .= "• 🚨 Atrasados: {$mant['atrasados']}\n";
    }
    $resp .= "\n";
    
    // Insights
    $resp .= "### 💡 Insights\n";
    if ($stats['fuera_servicio'] > 0) {
      $resp .= "⚠️ Tienes {$stats['fuera_servicio']} equipo(s) que requieren atención.\n";
    }
    if ($mant['atrasados'] > 0) {
      $resp .= "🚨 Hay {$mant['atrasados']} mantenimiento(s) atrasados.\n";
    }
    if ($tasa >= 95) {
      $resp .= "✨ Sistema en óptimas condiciones.\n";
    }
    
    return [
      'text' => $resp,
      'sugerencias' => [
        "Ver equipos fuera de servicio",
        "Mantenimientos atrasados",
        "Generar recomendaciones"
      ]
    ];
  }
  
  /**
   * 💡 Generar recomendaciones
   */
  private function generarRecomendaciones($pdo) {
    $recomendaciones = [];
    
    // Equipos fuera de servicio
    $fueraServicio = $pdo->query("SELECT COUNT(*) FROM equipos WHERE estado='fuera_de_servicio'")->fetchColumn();
    if ($fueraServicio > 0) {
      $recomendaciones[] = "🔴 **Prioridad Alta:** Tienes {$fueraServicio} equipo(s) fuera de servicio que requieren mantenimiento correctivo inmediato.";
    }
    
    // Mantenimientos atrasados
    $atrasados = $pdo->query("
      SELECT COUNT(*) FROM mantenimientos 
      WHERE estado='pendiente' AND fecha_programada < NOW()
    ")->fetchColumn();
    if ($atrasados > 0) {
      $recomendaciones[] = "🚨 **Urgente:** {$atrasados} mantenimiento(s) están atrasados. Reprograma o ejecuta lo antes posible.";
    }
    
    // Equipos sin mantenimiento
    $sinMant = $pdo->query("
      SELECT e.nombre FROM equipos e
      LEFT JOIN mantenimientos m ON m.equipo_id = e.id AND m.created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
      WHERE e.estado='operativo' AND m.id IS NULL
      LIMIT 5
    ")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!empty($sinMant)) {
      $recomendaciones[] = "📅 **Mantenimiento Preventivo:** " . count($sinMant) . " equipo(s) sin mantenimiento en 6 meses: " . implode(', ', $sinMant);
    }
    
    if (empty($recomendaciones)) {
      return [
        'text' => "✅ **¡Felicitaciones!**\n\nTu sistema está funcionando óptimamente.\n\n" .
                 "Continúa con:\n" .
                 "• Mantenimiento preventivo regular\n" .
                 "• Monitoreo constante\n" .
                 "• Documentación actualizada",
        'sugerencias' => ["Ver estadísticas", "Próximos mantenimientos"]
      ];
    }
    
    $resp = "💡 **Recomendaciones Personalizadas:**\n\n";
    foreach ($recomendaciones as $i => $rec) {
      $resp .= ($i + 1) . ". {$rec}\n\n";
    }
    
    return [
      'text' => $resp,
      'sugerencias' => [
        "Ver equipos fuera de servicio",
        "Mantenimientos atrasados",
        "Crear nuevo mantenimiento"
      ]
    ];
  }
  
  /**
   * 🔮 Predecir necesidades de mantenimiento
   */
  private function predecirMantenimientos($pdo) {
    $criticos = $pdo->query("
      SELECT e.nombre, e.codigo,
             DATEDIFF(NOW(), COALESCE(
               (SELECT MAX(fecha_programada) FROM mantenimientos WHERE equipo_id = e.id),
               e.fecha_compra
             )) as dias_sin_mant
      FROM equipos e
      WHERE e.estado = 'operativo'
      HAVING dias_sin_mant > 90
      ORDER BY dias_sin_mant DESC
      LIMIT 5
    ")->fetchAll();
    
    $resp = "🔮 **Predicción de Mantenimientos:**\n\n";
    
    if (empty($criticos)) {
      $resp .= "✅ Todos los equipos están al día con sus mantenimientos.\n\n";
      $resp .= "No hay equipos que requieran atención inmediata.";
    } else {
      $resp .= "⚠️ **Equipos que necesitarán mantenimiento pronto:**\n\n";
      
      foreach ($criticos as $i => $eq) {
        $urgencia = $eq['dias_sin_mant'] > 180 ? '🔴 Urgente' : ($eq['dias_sin_mant'] > 120 ? '🟡 Pronto' : '🟢 Normal');
        
        $resp .= ($i + 1) . ". **{$eq['nombre']}** ({$eq['codigo']})\n";
        $resp .= "   • Días sin mantenimiento: {$eq['dias_sin_mant']}\n";
        $resp .= "   • Urgencia: {$urgencia}\n\n";
      }
    }
    
    return [
      'text' => $resp,
      'sugerencias' => [
        "Crear orden de mantenimiento",
        "Ver calendario",
        "Analizar sistema"
      ]
    ];
  }
  
  /**
   * ⏰ Calcular tiempo relativo
   */
  private function tiempoRelativo($horas) {
    if ($horas < 1) return "Hace menos de 1 hora";
    if ($horas < 24) return "Hace " . round($horas) . " hora(s)";
    
    $dias = round($horas / 24);
    if ($dias == 1) return "Hace 1 día";
    if ($dias < 7) return "Hace {$dias} días";
    
    $semanas = round($dias / 7);
    if ($semanas == 1) return "Hace 1 semana";
    if ($semanas < 4) return "Hace {$semanas} semanas";
    
    $meses = round($dias / 30);
    if ($meses == 1) return "Hace 1 mes";
    return "Hace {$meses} meses";
  }
}