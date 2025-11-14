<?php
// Obtener estadísticas generales
$totalEquipos = DB::pdo()->query("SELECT COUNT(*) FROM equipos")->fetchColumn();
$equiposOperativos = DB::pdo()->query("SELECT COUNT(*) FROM equipos WHERE estado='operativo'")->fetchColumn();
$equiposFuera = DB::pdo()->query("SELECT COUNT(*) FROM equipos WHERE estado='fuera_de_servicio'")->fetchColumn();

$totalMantenimientos = DB::pdo()->query("SELECT COUNT(*) FROM mantenimientos")->fetchColumn();
$mantPendientes = DB::pdo()->query("SELECT COUNT(*) FROM mantenimientos WHERE estado='pendiente'")->fetchColumn();
$mantEnProgreso = DB::pdo()->query("SELECT COUNT(*) FROM mantenimientos WHERE estado='en_progreso'")->fetchColumn();

$totalEventos = DB::pdo()->query("SELECT COUNT(*) FROM calendario_eventos")->fetchColumn();
$proximosEventos = DB::pdo()->query("SELECT COUNT(*) FROM calendario_eventos WHERE inicio >= NOW()")->fetchColumn();

$costoTotal = DB::pdo()->query("SELECT COALESCE(SUM(costo), 0) FROM equipos")->fetchColumn();
?>

<style>
.dashboard-header{
  padding:2rem;
  background:linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
  border-radius:1rem;
  margin-bottom:2rem;
  color:white;
  text-align:center;
  box-shadow:0 10px 30px rgba(16,185,129,0.3);
}

.dashboard-header h1{
  margin:0 0 0.5rem 0;
  font-size:2.5rem;
  font-weight:800;
  text-shadow:0 2px 10px rgba(0,0,0,0.2);
}

.dashboard-header p{
  margin:0;
  opacity:0.95;
  font-size:1.125rem;
}

.stats-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));
  gap:1.5rem;
  margin-bottom:2rem;
}

.stat-card{
  background:linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  border:1px solid #334155;
  border-radius:1rem;
  padding:1.5rem;
  box-shadow:0 4px 12px rgba(0,0,0,0.2);
  transition:all .3s;
  position:relative;
  overflow:hidden;
}

.stat-card::before{
  content:'';
  position:absolute;
  top:0;
  left:0;
  right:0;
  height:4px;
  background:linear-gradient(90deg, #10b981, #3b82f6, #8b5cf6);
}

.stat-card:hover{
  transform:translateY(-8px);
  box-shadow:0 12px 30px rgba(16,185,129,0.3);
  border-color:#10b981;
}

.stat-icon{
  width:60px;
  height:60px;
  border-radius:1rem;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:2rem;
  margin-bottom:1rem;
  background:linear-gradient(135deg, #10b981, #059669);
  box-shadow:0 4px 12px rgba(16,185,129,0.3);
}

.stat-icon.orange{
  background:linear-gradient(135deg, #f59e0b, #d97706);
  box-shadow:0 4px 12px rgba(245,158,11,0.3);
}

.stat-icon.blue{
  background:linear-gradient(135deg, #3b82f6, #2563eb);
  box-shadow:0 4px 12px rgba(59,130,246,0.3);
}

.stat-icon.purple{
  background:linear-gradient(135deg, #8b5cf6, #7c3aed);
  box-shadow:0 4px 12px rgba(139,92,246,0.3);
}

.stat-value{
  font-size:2.5rem;
  font-weight:800;
  color:#e5e7eb;
  margin:0.5rem 0;
  text-shadow:0 2px 4px rgba(0,0,0,0.2);
}

.stat-label{
  color:#94a3b8;
  font-size:0.875rem;
  font-weight:500;
  text-transform:uppercase;
  letter-spacing:0.5px;
}

.stat-sublabel{
  color:#64748b;
  font-size:0.75rem;
  margin-top:0.25rem;
}

.quick-actions{
  background:linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  border:1px solid #334155;
  border-radius:1rem;
  padding:1.5rem;
  margin-bottom:2rem;
  box-shadow:0 4px 12px rgba(0,0,0,0.2);
}

.quick-actions h2{
  margin:0 0 1rem 0;
  color:#e5e7eb;
  font-size:1.25rem;
}

.action-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));
  gap:1rem;
}

.action-btn{
  display:flex;
  align-items:center;
  gap:1rem;
  padding:1rem;
  background:linear-gradient(135deg, #111827 0%, #1f2937 100%);
  border:1px solid #374151;
  border-radius:0.75rem;
  text-decoration:none;
  color:#e5e7eb;
  transition:all .3s;
  position:relative;
  overflow:hidden;
}

.action-btn::before{
  content:'';
  position:absolute;
  top:0;
  left:-100%;
  width:100%;
  height:100%;
  background:linear-gradient(90deg, transparent, rgba(16,185,129,0.2), transparent);
  transition:left 0.5s;
}

.action-btn:hover::before{
  left:100%;
}

.action-btn:hover{
  background:linear-gradient(135deg, #10b981 0%, #059669 100%);
  border-color:#10b981;
  color:white;
  transform:translateX(4px) translateY(-2px);
  box-shadow:0 8px 20px rgba(16,185,129,0.3);
}

.action-icon{
  font-size:2rem;
  filter:drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

.action-text{
  flex:1;
}

.action-text strong{
  display:block;
  font-size:1rem;
  margin-bottom:0.25rem;
}

.action-text span{
  font-size:0.75rem;
  opacity:0.8;
}

.recent-activity{
  background:linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  border:1px solid #334155;
  border-radius:1rem;
  padding:1.5rem;
  box-shadow:0 4px 12px rgba(0,0,0,0.2);
}

.recent-activity h2{
  margin:0 0 1rem 0;
  color:#e5e7eb;
  font-size:1.25rem;
}

.activity-item{
  padding:1rem;
  border-left:3px solid #10b981;
  background:linear-gradient(135deg, #111827 0%, #1f2937 100%);
  margin-bottom:0.75rem;
  border-radius:0 0.5rem 0.5rem 0;
  transition:all .2s;
}

.activity-item:hover{
  transform:translateX(4px);
  box-shadow:0 4px 12px rgba(16,185,129,0.2);
}

.activity-time{
  font-size:0.75rem;
  color:#64748b;
}

.activity-desc{
  color:#cbd5e1;
  margin-top:0.25rem;
}

@media(max-width:768px){
  .dashboard-header h1{
    font-size:2rem;
  }
  
  .stats-grid{
    grid-template-columns:repeat(2, 1fr);
  }
  
  .action-grid{
    grid-template-columns:1fr;
  }
}
</style>

<div class="dashboard-header">
  <h1>🏠 Panel de Control</h1>
  <p>Bienvenido al Sistema de Gestión de Mantenimiento</p>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon">🔧</div>
    <div class="stat-label">Total de Equipos</div>
    <div class="stat-value"><?= $totalEquipos ?></div>
    <div class="stat-sublabel">
      <?= $equiposOperativos ?> operativos • <?= $equiposFuera ?> fuera de servicio
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-icon orange">📋</div>
    <div class="stat-label">Mantenimientos</div>
    <div class="stat-value"><?= $totalMantenimientos ?></div>
    <div class="stat-sublabel">
      <?= $mantPendientes ?> pendientes • <?= $mantEnProgreso ?> en progreso
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-icon blue">📅</div>
    <div class="stat-label">Eventos</div>
    <div class="stat-value"><?= $totalEventos ?></div>
    <div class="stat-sublabel">
      <?= $proximosEventos ?> próximos eventos programados
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-icon purple">💰</div>
    <div class="stat-label">Inversión Total</div>
    <div class="stat-value">$<?= number_format($costoTotal, 0) ?></div>
    <div class="stat-sublabel">
      En equipos registrados
    </div>
  </div>
</div>

<div class="quick-actions">
  <h2>⚡ Acciones Rápidas</h2>
  <div class="action-grid">
    <a href="<?= ENV_APP['BASE_URL'] ?>/equipos" class="action-btn">
      <div class="action-icon">➕</div>
      <div class="action-text">
        <strong>Agregar Equipo</strong>
        <span>Registrar nuevo activo</span>
      </div>
    </a>
    
    <a href="<?= ENV_APP['BASE_URL'] ?>/mantenimiento" class="action-btn">
      <div class="action-icon">📝</div>
      <div class="action-text">
        <strong>Crear Orden</strong>
        <span>Nueva orden de trabajo</span>
      </div>
    </a>
    
    <a href="<?= ENV_APP['BASE_URL'] ?>/calendario" class="action-btn">
      <div class="action-icon">📆</div>
      <div class="action-text">
        <strong>Ver Calendario</strong>
        <span>Programar eventos</span>
      </div>
    </a>
    
    <a href="#" onclick="document.getElementById('chatbot-trigger').click(); return false;" class="action-btn">
      <div class="action-icon">💬</div>
      <div class="action-text">
        <strong>Asistente Virtual</strong>
        <span>Consultar información</span>
      </div>
    </a>
  </div>
</div>

<div class="recent-activity">
  <h2>📊 Resumen del Sistema</h2>
  
  <?php
  $porcentajeOperativos = $totalEquipos > 0 ? round(($equiposOperativos / $totalEquipos) * 100) : 0;
  $alertas = [];
  
  if($mantPendientes > 5){
    $alertas[] = "⚠️ Tienes {$mantPendientes} mantenimientos pendientes que requieren atención";
  }
  
  if($equiposFuera > 0){
    $alertas[] = "🔴 Hay {$equiposFuera} equipo(s) fuera de servicio";
  }
  
  if($porcentajeOperativos >= 90){
    $alertas[] = "✅ Excelente: {$porcentajeOperativos}% de equipos operativos";
  } elseif($porcentajeOperativos >= 70){
    $alertas[] = "⚠️ Aceptable: {$porcentajeOperativos}% de equipos operativos";
  } else {
    $alertas[] = "🔴 Crítico: Solo {$porcentajeOperativos}% de equipos operativos";
  }
  
  if(empty($alertas)){
    $alertas[] = "✅ Todo el sistema funcionando correctamente";
  }
  ?>
  
  <?php foreach($alertas as $alerta): ?>
    <div class="activity-item">
      <div class="activity-time"><?= date('d/m/Y H:i') ?></div>
      <div class="activity-desc"><?= $alerta ?></div>
    </div>
  <?php endforeach; ?>
</div>