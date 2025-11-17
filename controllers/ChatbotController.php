<?php
require_once BASE_PATH.'/core/DB.php';
require_once BASE_PATH.'/core/Response.php';

/**
 * 🤖 NIBARRA AI ASSISTANT - IA Conversacional Conectada a Base de Datos
 * Entiende preguntas naturales y responde con datos reales del sistema
 * VERSIÓN MEJORADA: Patrones flexibles + Todas las tablas conectadas
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
        'ok'          => true,
        'respuesta'   => $respuesta['text'],
        'metadata'    => $respuesta['metadata']    ?? null,
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
    // 🎯 ATAJOS RÁPIDOS (PREGUNTAS MUY COMUNES)
    // ============================================
    
    // "todo" o "general"
    if (preg_match('/^(todo|general|overview|resumen general)$/i', $pregunta)) {
      return $this->analisisCompleto($pdo);
    }
    
    // Solo "equipos"
    if (preg_match('/^equipos?$/i', $pregunta)) {
      $equipos = $pdo->query("SELECT * FROM equipos ORDER BY created_at DESC LIMIT 5")->fetchAll();
      
      if (empty($equipos)) {
        return [
          'text' => "❌ No hay equipos registrados aún.",
          'sugerencias' => ["Agregar equipo", "Ayuda"]
        ];
      }
      
      $resp = "🔧 **Últimos 5 Equipos Registrados:**\n\n";
      foreach ($equipos as $i => $eq) {
        $estadoIcon = [
          'operativo'          => '✅',
          'fuera_de_servicio'  => '⚠️',
          'baja'               => '❌'
        ][$eq['estado']] ?? '❓';
        
        $resp .= ($i + 1) . ". {$estadoIcon} **{$eq['nombre']}** ({$eq['codigo']})\n";
      }
      $resp .= "\n💡 Pregunta \"equipos recientes\" para ver más detalles\n";
      $resp .= "💡 Pregunta \"cuántos equipos\" para ver estadísticas";
      
      return [
        'text' => $resp,
        'sugerencias' => ["Equipos recientes", "Cuántos equipos", "Analizar sistema"]
      ];
    }
    
    // Solo "mantenimientos"
    if (preg_match('/^mantenimientos?$/i', $pregunta)) {
      return [
        'text' => "📋 **Opciones sobre Mantenimientos:**\n\n" .
                 "Pregunta específicamente:\n" .
                 "• \"mantenimientos pendientes\"\n" .
                 "• \"mantenimientos recientes\"\n" .
                 "• \"mantenimientos atrasados\"\n" .
                 "• \"mantenimientos críticos\"",
        'sugerencias' => [
          "Mantenimientos pendientes",
          "Mantenimientos recientes",
          "Mantenimientos atrasados"
        ]
      ];
    }
    
    // Solo "facturas"
    if (preg_match('/^facturas?$/i', $pregunta)) {
      $facturas  = $pdo->query("SELECT COUNT(*) as total FROM facturas")->fetch();
      $pendientes = $pdo->query("SELECT COUNT(*) as total FROM facturas WHERE estado='pendiente'")->fetch();
      
      return [
        'text' => "🧾 **Resumen Rápido de Facturas:**\n\n" .
                 "• Total: {$facturas['total']} factura(s)\n" .
                 "• Pendientes: {$pendientes['total']}\n\n" .
                 "💡 Pregunta \"facturas pendientes\" o \"estado de facturas\" para más detalles",
        'sugerencias' => [
          "Facturas pendientes",
          "Estado de facturas",
          "Todas las facturas"
        ]
      ];
    }
    
    // Solo "calendario" o "eventos"
    if (preg_match('/^(calendario|eventos?)$/i', $pregunta)) {
      $eventos = $pdo->query("
        SELECT COUNT(*) as total 
        FROM calendario_eventos 
        WHERE DATE(inicio) >= CURDATE()
      ")->fetch();
      
      return [
        'text' => "📅 **Eventos en Calendario:**\n\n" .
                 "• Próximos eventos: {$eventos['total']}\n\n" .
                 "💡 Pregunta \"eventos próximos\" o \"eventos hoy\" para ver detalles",
        'sugerencias' => [
          "Eventos próximos",
          "Eventos hoy",
          "Ver calendario"
        ]
      ];
    }
    
    // Predicciones generales
    if (preg_match('/(predice|predecir|futuro|proximo|necesita|prever)/i', $pregunta)) {
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
                 "• 📅 Eventos del calendario\n" .
                 "• 🧾 Facturas\n" .
                 "• 📊 Análisis y reportes\n\n" .
                 "Pregúntame lo que necesites.",
        'sugerencias' => [
          "Equipos recientes",
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
    
    if (preg_match('/(ayuda|help|que puedes|comandos|como funciona)/i', $pregunta)) {
      return [
        'text' => "🤖 **Guía Completa del Asistente Nibarra**\n\n" .
                 "Puedo ayudarte con información en tiempo real sobre:\n\n" .
                 "### 🔧 Equipos\n" .
                 "• \"equipos recientes\" / \"qué equipos nuevos\"\n" .
                 "• \"busca equipo servidor\" / \"información del router\"\n" .
                 "• \"cuántos equipos\" / \"estado de equipos\"\n" .
                 "• \"equipos fuera de servicio\"\n\n" .
                 "### 📋 Mantenimientos\n" .
                 "• \"mantenimientos pendientes\"\n" .
                 "• \"mantenimientos recientes\"\n" .
                 "• \"mantenimientos atrasados\"\n" .
                 "• \"mantenimientos críticos\"\n\n" .
                 "### 📅 Calendario\n" .
                 "• \"eventos próximos\" / \"qué eventos hay\"\n" .
                 "• \"eventos hoy\" / \"eventos esta semana\"\n" .
                 "• \"calendario\" / \"programación\"\n\n" .
                 "### 🧾 Facturas\n" .
                 "• \"facturas pendientes\" / \"por cobrar\"\n" .
                 "• \"facturas pagadas\"\n" .
                 "• \"estado de facturas\"\n\n" .
                 "### 💰 Finanzas\n" .
                 "• \"cuánto he gastado\" / \"gastos totales\"\n" .
                 "• \"inversión total\"\n" .
                 "• \"costos de mantenimiento\"\n\n" .
                 "### 🔮 Análisis Inteligente\n" .
                 "• \"analiza el sistema\" / \"reporte general\"\n" .
                 "• \"recomienda\" / \"qué debo hacer\"\n" .
                 "• \"predice mantenimientos\"\n\n" .
                 "💡 **Tip:** Habla naturalmente, entiendo variaciones de estas preguntas. Por ejemplo:\n" .
                 "• \"equipos que agregué últimamente\"\n" .
                 "• \"hay mantenimientos sin hacer\"\n" .
                 "• \"qué eventos tengo programados\"",
        'sugerencias' => [
          "Equipos recientes",
          "Analiza el sistema",
          "Eventos próximos",
          "Facturas pendientes"
        ]
      ];
    }
    
    // ============================================
    // 📊 CONSULTAS SOBRE EQUIPOS
    // ============================================
    
    // ¿Qué equipos se agregaron recientemente? (REGEX MÁS FLEXIBLE)
    if (
      preg_match('/(equipos?|maquinas?).*(reciente|nuevo|ultimo|agregad|añadid)/i', $pregunta) || 
      preg_match('/(reciente|nuevo|ultimo|agregad|añadid).*(equipos?|maquinas?)/i', $pregunta)
    ) {
      
      $equipos = $pdo->query("
        SELECT nombre, codigo, categoria, marca, modelo, estado, 
               DATE_FORMAT(created_at, '%d/%m/%Y a las %H:%i') as fecha,
               TIMESTAMPDIFF(HOUR, created_at, NOW()) as horas_desde
        FROM equipos 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
        ORDER BY created_at DESC 
        LIMIT 10
      ")->fetchAll();
      
      if (empty($equipos)) {
        return [
          'text' => "ℹ️ **No se han agregado equipos en las últimas 24 horas.**\n\nEl último registro de equipos fue hace más de un día.",
          'sugerencias' => ["Ver todos los equipos", "Estado del sistema", "Agregar nuevo equipo"]
        ];
      }
      
      $resp = "🔧 **Equipos agregados recientemente (últimas 24 horas):**\n\n";
      
      foreach ($equipos as $i => $eq) {
        $estadoIcon = [
          'operativo'          => '✅',
          'fuera_de_servicio'  => '⚠️',
          'baja'               => '❌'
        ][$eq['estado']] ?? '❓';
        
        $tiempo = $this->tiempoRelativo($eq['horas_desde']);
        
        $resp .= "**" . ($i + 1) . ". {$eq['nombre']}**\n";
        $resp .= "   • Código: {$eq['codigo']}\n";
        if (!empty($eq['marca'])) {
          $resp .= "   • Marca: {$eq['marca']} {$eq['modelo']}\n";
        }
        $resp .= "   • Categoría: {$eq['categoria']}\n";
        $resp .= "   • Estado: {$estadoIcon} " . str_replace('_', ' ', ucfirst($eq['estado'])) . "\n";
        $resp .= "   • Agregado: {$tiempo} ({$eq['fecha']})\n\n";
      }
      
      $total = count($equipos);
      $resp .= "✅ **Total:** {$total} equipo(s) nuevo(s) en las últimas 24 horas\n";
      
      return [
        'text' => $resp,
        'sugerencias' => [
          "Ver equipos operativos",
          "Equipos fuera de servicio",
          "Estado del sistema"
        ]
      ];
    }
    
    // Buscar equipo específico por nombre (MÁS FLEXIBLE)
    if (
      preg_match('/(busca|encuentra|informacion|datos|dame|dime|muestr).*(de|del|sobre|equipo|maquina)/i', $pregunta) ||
      preg_match('/(equipo|maquina).*(busca|encuentra|informacion|datos)/i', $pregunta)
    ) {
      
      preg_match('/(de|del|sobre)\s+(.+)/i', $pregunta, $matches);
      $nombreBuscar = $matches[2] ?? '';
      
      if (!$nombreBuscar) {
        preg_match('/(busca|encuentra|informacion|datos|dame|dime|muestr)\s+(.+)/i', $pregunta, $matches2);
        $nombreBuscar = $matches2[2] ?? '';
      }
      
      if ($nombreBuscar) {
        $stmt = $pdo->prepare("
          SELECT e.*, 
                 DATE_FORMAT(e.created_at, '%d/%m/%Y %H:%i') as fecha_registro,
                 (SELECT COUNT(*) FROM mantenimientos WHERE equipo_id = e.id) as total_mantenimientos
          FROM equipos e
          WHERE LOWER(e.nombre)   LIKE ? 
             OR LOWER(e.codigo)   LIKE ?
             OR LOWER(e.categoria)LIKE ?
             OR LOWER(e.marca)    LIKE ?
          ORDER BY e.created_at DESC
          LIMIT 1
        ");
        $search = '%' . mb_strtolower($nombreBuscar) . '%';
        $stmt->execute([$search, $search, $search, $search]);
        $equipo = $stmt->fetch();
        
        if ($equipo) {
          $estadoIcon = [
            'operativo'          => '✅',
            'fuera_de_servicio'  => '⚠️',
            'baja'               => '❌'
          ][$equipo['estado']] ?? '❓';
          
          $resp  = "🔍 **Información del equipo encontrado:**\n\n";
          $resp .= "## {$equipo['nombre']}\n\n";
          $resp .= "• **Código:** {$equipo['codigo']}\n";
          $resp .= "• **Categoría:** {$equipo['categoria']}\n";
          if (!empty($equipo['marca'])) {
            $resp .= "• **Marca/Modelo:** {$equipo['marca']} {$equipo['modelo']}\n";
          }
          if (!empty($equipo['nro_serie'])) {
            $resp .= "• **Nro. Serie:** {$equipo['nro_serie']}\n";
          }
          if (!empty($equipo['ubicacion'])) {
            $resp .= "• **Ubicación:** {$equipo['ubicacion']}\n";
          }
          $resp .= "• **Estado:** {$estadoIcon} " . str_replace('_', ' ', ucfirst($equipo['estado'])) . "\n";
          if (!empty($equipo['costo'])) {
            $resp .= "• **Costo:** $" . number_format($equipo['costo'], 2) . "\n";
          }
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
        } else {
          return [
            'text' => "❌ **No encontré ningún equipo** que coincida con \"{$nombreBuscar}\"\n\nIntenta con otro nombre, código o categoría.",
            'sugerencias' => ["Equipos recientes", "Ver todos los equipos", "Estado del sistema"]
          ];
        }
      }
    }
    
    // Estado general de equipos (PATRÓN MÁS FLEXIBLE)
    if (
      preg_match('/(cuantos?|estado|resumen|total).*(equipos?|maquinas?)/i', $pregunta) ||
      preg_match('/(equipos?|maquinas?).*(cuantos?|estado|resumen|total)/i', $pregunta) ||
      preg_match('/(tengo|hay).*(equipos?|maquinas?)/i', $pregunta)
    ) {
      
      $stats = $pdo->query("
        SELECT 
          COUNT(*) as total,
          SUM(CASE WHEN estado='operativo'         THEN 1 ELSE 0 END) as operativos,
          SUM(CASE WHEN estado='fuera_de_servicio' THEN 1 ELSE 0 END) as fuera_servicio,
          SUM(CASE WHEN estado='baja'              THEN 1 ELSE 0 END) as dados_baja
        FROM equipos
      ")->fetch();
      
      $tasa = $stats['total'] > 0
        ? round(($stats['operativos'] / $stats['total']) * 100, 1)
        : 0;
      
      $resp  = "📊 **Estado actual de equipos:**\n\n";
      $resp .= "• **Total de equipos:** {$stats['total']}\n";
      $resp .= "• ✅ **Operativos:** {$stats['operitivos']} ({$tasa}%)\n";
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
    
    // Mantenimientos recientes (PATRÓN MÁS FLEXIBLE)
    if (
      preg_match('/(mantenimiento|mant).*(reciente|ultimo|nuevo)/i', $pregunta) ||
      preg_match('/(reciente|ultimo|nuevo).*(mantenimiento|mant)/i', $pregunta)
    ) {
      
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
        $prioIcon = [
          'baja'    => '🟢',
          'media'   => '🟡',
          'alta'    => '🔴',
          'critica' => '🚨'
        ][$m['prioridad']] ?? '⚪';
        
        $estadoIcon = [
          'pendiente'   => '⏳',
          'en_progreso' => '🔧',
          'completado'  => '✅'
        ][$m['estado']] ?? '❓';
        
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
    
    // Mantenimientos pendientes (PATRÓN MÁS FLEXIBLE)
    if (
      preg_match('/(mantenimiento|mant).*(pendiente|atrasado|vencido|falta|sin hacer)/i', $pregunta) ||
      preg_match('/(pendiente|atrasado|vencido|falta|sin hacer).*(mantenimiento|mant)/i', $pregunta)
    ) {
      
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
        $prioIcon = [
          'baja'    => '🟢',
          'media'   => '🟡',
          'alta'    => '🔴',
          'critica' => '🚨'
        ][$m['prioridad']] ?? '⚪';
        
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
    // 💰 CONSULTAS SOBRE COSTOS (PATRÓN MÁS FLEXIBLE)
    // ============================================
    
    if (preg_match('/(cuanto|costo|gasto|precio|dinero|inversion|pagado|gastado|total.*dinero)/i', $pregunta)) {
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
      
      $total_invertido   = $costos['total_equipos'] + $mantenimientos['gastado'];
      $promedio_equipo   = $costos['cant_equipos'] > 0
        ? $total_invertido / $costos['cant_equipos']
        : 0;
      
      $resp  = "💰 **Análisis financiero del sistema:**\n\n";
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
    // 📅 CONSULTAS SOBRE CALENDARIO
    // ============================================
    
    if (preg_match('/(evento|calendar|proxim|program|cita)/i', $pregunta)) {
      $eventos = $pdo->query("
        SELECT titulo, inicio, fin, all_day,
               DATE_FORMAT(inicio, '%d/%m/%Y %H:%i') as fecha_formateada,
               DATEDIFF(inicio, NOW()) as dias_restantes
        FROM calendario_eventos
        WHERE DATE(inicio) >= CURDATE()
        ORDER BY inicio ASC
        LIMIT 10
      ")->fetchAll();
      
      if (empty($eventos)) {
        return [
          'text' => "📅 **No hay eventos próximos programados**\n\nNo tienes eventos en el calendario para los próximos días.",
          'sugerencias' => ["Ver equipos", "Mantenimientos pendientes", "Ayuda"]
        ];
      }
      
      $resp = "📅 **Eventos Próximos en el Calendario:**\n\n";
      
      foreach ($eventos as $i => $ev) {
        $dias   = (int) $ev['dias_restantes'];
        $tiempo = $dias === 0
          ? "🔴 Hoy"
          : ($dias === 1 ? "🟡 Mañana" : "⏳ En {$dias} días");
        
        $resp .= "**" . ($i + 1) . ". {$ev['titulo']}**\n";
        $resp .= "   • Fecha: {$ev['fecha_formateada']}\n";
        $resp .= "   • {$tiempo}\n";
        if ($ev['all_day']) {
          $resp .= "   • 📅 Todo el día\n";
        }
        $resp .= "\n";
      }
      
      $total = count($eventos);
      $resp .= "✅ **Total:** {$total} evento(s) programado(s)\n";
      
      return [
        'text' => $resp,
        'sugerencias' => [
          "Eventos de hoy",
          "Ver calendario completo",
          "Crear nuevo evento"
        ]
      ];
    }
    
    // ============================================
    // 🧾 CONSULTAS SOBRE FACTURAS
    // ============================================
    
    if (preg_match('/(factura|cobr|pag|deud|pendi.*pag)/i', $pregunta)) {
      $facturas = $pdo->query("
        SELECT f.numero_factura, f.fecha_emision, f.total, f.estado,
               m.titulo as mantenimiento,
               e.nombre as equipo
        FROM facturas f
        JOIN mantenimientos m ON m.id = f.mantenimiento_id
        JOIN equipos e       ON e.id = m.equipo_id
        ORDER BY f.fecha_emision DESC
        LIMIT 10
      ")->fetchAll();
      
      if (empty($facturas)) {
        return [
          'text' => "🧾 **No hay facturas registradas**\n\nAún no se han generado facturas en el sistema.",
          'sugerencias' => ["Ver mantenimientos", "Estado del sistema"]
        ];
      }
      
      $total_facturas  = count($facturas);
      $pendientes      = 0;
      $pagadas         = 0;
      $monto_pendiente = 0;
      $monto_total     = 0;
      
      foreach ($facturas as $f) {
        $monto_total += $f['total'];
        if ($f['estado'] === 'pendiente') {
          $pendientes++;
          $monto_pendiente += $f['total'];
        } elseif ($f['estado'] === 'pagada') {
          $pagadas++;
        }
      }
      
      $resp  = "🧾 **Estado de Facturas:**\n\n";
      $resp .= "### Resumen\n";
      $resp .= "• **Total facturas:** {$total_facturas}\n";
      $resp .= "• ⏳ **Pendientes:** {$pendientes}\n";
      $resp .= "• ✅ **Pagadas:** {$pagadas}\n";
      $resp .= "• 💰 **Monto total:** $" . number_format($monto_total, 2) . "\n";
      $resp .= "• ⚠️ **Por cobrar:** $" . number_format($monto_pendiente, 2) . "\n\n";
      
      $resp .= "### Últimas Facturas\n\n";
      
      $mostrar = array_slice($facturas, 0, 5);
      foreach ($mostrar as $i => $f) {
        $estadoIcon = [
          'pendiente' => '⏳',
          'pagada'    => '✅',
          'cancelada' => '❌'
        ][$f['estado']] ?? '❓';
        
        $resp .= "**" . ($i + 1) . ". {$f['numero_factura']}**\n";
        $resp .= "   • Mantenimiento: {$f['mantenimiento']}\n";
        $resp .= "   • Equipo: {$f['equipo']}\n";
        $resp .= "   • Total: $" . number_format($f['total'], 2) . "\n";
        $resp .= "   • Estado: {$estadoIcon} " . ucfirst($f['estado']) . "\n";
        $resp .= "   • Fecha: " . date('d/m/Y', strtotime($f['fecha_emision'])) . "\n\n";
      }
      
      if ($pendientes > 0) {
        $resp .= "⚠️ **Atención:** Tienes {$pendientes} factura(s) pendiente(s) de pago por un total de $" . number_format($monto_pendiente, 2) . "\n";
      }
      
      return [
        'text' => $resp,
        'sugerencias' => [
          "Facturas pendientes",
          "Ver todas las facturas",
          "Análisis financiero"
        ]
      ];
    }
    
    // ============================================
    // 🔮 PREDICCIONES Y ANÁLISIS (EXPLÍCITO)
    // ============================================
    
    if (preg_match('/(analiza|analizar|estado|salud|reporte|resumen|diagnostico)/i', $pregunta)) {
      return $this->analisisCompleto($pdo);
    }
    
    if (preg_match('/(recomienda|sugiere|deberia|que hacer|consejos?|acciones?)/i', $pregunta)) {
      return $this->generarRecomendaciones($pdo);
    }
    
    // ============================================
    // ❓ RESPUESTA POR DEFECTO (MEJORADA)
    // ============================================
    
    // Si llegamos aquí, intentamos dar sugerencias útiles basadas en palabras clave
    $keywords = [
      'equipo'      => 'Prueba preguntar: "equipos recientes" o "cuántos equipos tengo"',
      'mantenimiento'=> 'Prueba: "mantenimientos pendientes" o "mantenimientos atrasados"',
      'costo'       => 'Pregunta: "cuánto he gastado" o "inversión total"',
      'factura'     => 'Pregunta: "facturas pendientes" o "estado de facturas"',
      'evento'      => 'Pregunta: "eventos próximos" o "qué hay programado"',
      'calendario'  => 'Pregunta: "eventos próximos" o "calendario del mes"'
    ];
    
    $sugerencia = null;
    foreach ($keywords as $key => $msg) {
      if (stripos($preguntaLower, $key) !== false) {
        $sugerencia = $msg;
        break;
      }
    }
    
    $texto = "🤔 No estoy seguro de cómo responder esa pregunta específicamente.\n\n";
    
    if ($sugerencia) {
      $texto .= "💡 {$sugerencia}\n\n";
    }
    
    $texto .= "**Ejemplos de preguntas que puedo responder:**\n" .
              "• \"equipos recientes\" o \"qué equipos nuevos hay\"\n" .
              "• \"mantenimientos pendientes\" o \"qué mantenimientos faltan\"\n" .
              "• \"eventos próximos\" o \"qué hay programado\"\n" .
              "• \"facturas\" o \"cuánto debo cobrar\"\n" .
              "• \"analiza el sistema\" o \"dame un reporte\"\n\n" .
              "Escribe **\"ayuda\"** para ver la guía completa.";
    
    return [
      'text' => $texto,
      'sugerencias' => [
        "Ayuda",
        "Equipos recientes",
        "Mantenimientos pendientes",
        "Eventos próximos"
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
        SUM(CASE WHEN estado='operativo'         THEN 1 ELSE 0 END) as operativos,
        SUM(CASE WHEN estado='fuera_de_servicio' THEN 1 ELSE 0 END) as fuera_servicio
      FROM equipos
    ")->fetch();
    
    $mant = $pdo->query("
      SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN estado='pendiente'    THEN 1 ELSE 0 END) as pendientes,
        SUM(CASE WHEN estado='en_progreso'  THEN 1 ELSE 0 END) as en_progreso,
        SUM(CASE WHEN DATEDIFF(NOW(), fecha_programada) > 0 AND estado='pendiente' THEN 1 ELSE 0 END) as atrasados
      FROM mantenimientos
    ")->fetch();
    
    $tasa = $stats['total_equipos'] > 0
      ? round(($stats['operativos'] / $stats['total_equipos']) * 100, 1)
      : 0;
    
    $salud = $tasa >= 90
      ? '🟢 Excelente'
      : ($tasa >= 70 ? '🟡 Aceptable' : '🔴 Crítico');
    
    $resp  = "📊 **Análisis Completo del Sistema**\n\n";
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
    
    // Equipos sin mantenimiento en 6 meses
    $sinMant = $pdo->query("
      SELECT e.nombre FROM equipos e
      LEFT JOIN mantenimientos m 
        ON m.equipo_id = e.id 
       AND m.created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
      WHERE e.estado='operativo' AND m.id IS NULL
      LIMIT 5
    ")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!empty($sinMant)) {
      $recomendaciones[] = "📅 **Mantenimiento Preventivo:** " .
                           count($sinMant) . " equipo(s) sin mantenimiento en 6 meses: " .
                           implode(', ', $sinMant);
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
             DATEDIFF(
               NOW(), 
               COALESCE(
                 (SELECT MAX(fecha_programada) 
                    FROM mantenimientos 
                   WHERE equipo_id = e.id),
                 e.fecha_compra
               )
             ) as dias_sin_mant
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
        $urgencia =
          $eq['dias_sin_mant'] > 180 ? '🔴 Urgente' :
          ($eq['dias_sin_mant'] > 120 ? '🟡 Pronto' : '🟢 Normal');
        
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
    if ($horas < 1)   return "Hace menos de 1 hora";
    if ($horas < 24)  return "Hace " . round($horas) . " hora(s)";
    
    $dias = round($horas / 24);
    if ($dias == 1)   return "Hace 1 día";
    if ($dias < 7)    return "Hace {$dias} días";
    
    $semanas = round($dias / 7);
    if ($semanas == 1)  return "Hace 1 semana";
    if ($semanas < 4)   return "Hace {$semanas} semanas";
    
    $meses = round($dias / 30);
    if ($meses == 1)  return "Hace 1 mes";
    return "Hace {$meses} meses";
  }
}
