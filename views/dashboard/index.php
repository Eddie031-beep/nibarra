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
  background:linear-gradient(135deg, #1a472a 0%, #2d5f3f 50%, #1a472a 100%);
  border-radius:1rem;
  margin-bottom:2rem;
  color:white;
  text-align:center;
}

.dashboard-header h1{
  margin:0 0 0.5rem 0;
  font-size:2.5rem;
  font-weight:800;
}

.dashboard-header p{
  margin:0;
  opacity:0.9;
  font-size:1.125rem;
}

.stats-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));
  gap:1.5rem;
  margin-bottom:2rem;
}

.stat-card{
  background:white;
  border:1px solid #e0e0e0;
  border-radius:1rem;
  padding:1.5rem;
  box-shadow:0 2px 8px rgba(0,0,0,.08);
  transition:all .3s;
}

.stat-card:hover{
  transform:translateY(-4px);
  box-shadow:0 8px 24px rgba(0,0,0,.12);
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
}

.stat-icon.green{
  background:linear-gradient(135deg, #95e1d3, #4ecdc4);
}

.stat-icon.orange{
  background:linear-gradient(135deg, #ffb84d, #ff9a3d);
}

.stat-icon.blue{
  background:linear-gradient(135deg, #64b5f6, #42a5f5);
}

.stat-icon.purple{
  background:linear-gradient(135deg, #ba68c8, #ab47bc);
}

.stat-value{
  font-size:2.5rem;
  font-weight:800;
  color:#2c3e50;
  margin:0.5rem 0;
}

.stat-label{
  color:#666;
  font-size:0.875rem;
  font-weight:500;
  text-transform:uppercase;
  letter-spacing:0.5px;
}

.stat-sublabel{
  color:#999;
  font-size:0.75rem;
  margin-top:0.25rem;
}

.quick-actions{
  background:white;
  border:1px solid #e0e0e0;
  border-radius:1rem;
  padding:1.5rem;
  margin-bottom:2rem;
}

.quick-actions h2{
  margin:0 0 1rem 0;
  color:#2c3e50;
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
  background:#f8f9fa;
  border:1px solid #e0e0e0;
  border-radius:0.75rem;
  text-decoration:none;
  color:#2c3e50;
  transition:all .2s;
}

.action-btn:hover{
  background:#4ecdc4;
  border-color:#4ecdc4;
  color:white;
  transform:translateX(4px);
}

.action-icon{
  font-size:2rem;
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
  background:white;
  border:1px solid #e0e0e0;
  border-radius:1rem;
  padding:1.5rem;
}

.recent-activity h2{
  margin:0 0 1rem 0;
  color:#2c3e50;
  font-size:1.25rem;
}

.activity-item{
  padding:1rem;
  border-left:3px solid #4ecdc4;
  background:#f8f9fa;
  margin-bottom:0.75rem;
  border-radius:0 0.5rem 0.5rem 0;
}

.activity-time{
  font-size:0.75rem;
  color:#999;
}

.activity-desc{
  color:#2c3e50;
  margin-top:0.25rem;
}
</style>

<div class="dashboard-header">
  <h1>🏠 Panel de Control</h1>
  <p>Bienvenido al Sistema de Gestión de Mantenimiento</p>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon green">🔧</div>
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