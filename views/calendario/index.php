<?php
$first = new DateTime(sprintf('%04d-%02d-01',$y,$m));
$days  = (int)$first->format('t');
$startDow = (int)$first->format('N');
$prev = (clone $first)->modify('-1 month'); 
$next = (clone $first)->modify('+1 month');
$map = []; 
foreach($eventos as $ev){ 
  $d = (new DateTime($ev['inicio']))->format('Y-m-d'); 
  $map[$d][] = $ev; 
}
$monthNames = [
  1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',
  7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'
];
?>

<style>
.calendar-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:1.5rem;
  background:linear-gradient(135deg, var(--accent-blue), var(--accent-purple));
  border-radius:1rem 1rem 0 0;
}

.calendar-header h2{
  margin:0;
  color:white;
  font-size:1.75rem;
  font-weight:700;
}

.calendar-nav{
  display:flex;
  gap:0.5rem;
}

.calendar-nav-btn{
  padding:0.5rem 1rem;
  background:rgba(255,255,255,.2);
  border:1px solid rgba(255,255,255,.3);
  border-radius:0.5rem;
  color:white;
  font-weight:600;
  transition:all .2s;
  display:flex;
  align-items:center;
  gap:0.5rem;
}

.calendar-nav-btn:hover{
  background:rgba(255,255,255,.3);
  transform:translateY(-2px);
  text-decoration:none;
}

.calendar-body{
  padding:1.5rem;
}

.calendar-weekdays{
  display:grid;
  grid-template-columns:repeat(7, 1fr);
  gap:0.5rem;
  margin-bottom:0.5rem;
}

.weekday{
  text-align:center;
  padding:0.75rem;
  font-weight:600;
  color:var(--accent-blue);
  font-size:0.875rem;
}

.calendar-days{
  display:grid;
  grid-template-columns:repeat(7, 1fr);
  gap:0.5rem;
}

.calendar-day{
  background:var(--bg-secondary);
  border:1px solid var(--border-color);
  border-radius:0.75rem;
  min-height:120px;
  padding:0.75rem;
  transition:all .2s;
  position:relative;
}

.calendar-day:hover{
  border-color:var(--accent-blue);
  box-shadow:0 4px 15px rgba(33,150,243,.2);
  transform:translateY(-2px);
}

.calendar-day.today{
  border-color:var(--accent-green);
  background:rgba(0,200,83,.05);
}

.day-number{
  font-weight:700;
  color:var(--text-primary);
  margin-bottom:0.5rem;
  font-size:1.125rem;
}

.calendar-day.today .day-number{
  color:var(--accent-green);
}

.event-item{
  background:var(--accent-blue);
  color:white;
  padding:0.375rem 0.5rem;
  border-radius:0.375rem;
  font-size:0.75rem;
  margin-bottom:0.375rem;
  cursor:pointer;
  transition:all .2s;
  position:relative;
  overflow:hidden;
}

.event-item:hover{
  transform:scale(1.02);
  box-shadow:0 2px 8px rgba(0,0,0,.3);
}

.event-item.all-day{
  background:var(--accent-purple);
}

.event-title{
  font-weight:600;
  margin-bottom:0.125rem;
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
}

.event-time{
  opacity:0.9;
  font-size:0.7rem;
}

.event-actions{
  position:absolute;
  top:0;
  right:0;
  display:flex;
  gap:2px;
  opacity:0;
  transition:opacity .2s;
}

.event-item:hover .event-actions{
  opacity:1;
}

.event-edit{
  background:rgba(245,158,11,.9);
  border:none;
  color:white;
  padding:0.25rem 0.5rem;
  border-radius:0.375rem 0 0 0;
  cursor:pointer;
  font-size:0.7rem;
  transition:all .2s;
}

.event-edit:hover{
  background:#f59e0b;
}

.event-delete{
  background:rgba(244,67,54,.9);
  border:none;
  color:white;
  padding:0.25rem 0.5rem;
  border-radius:0 0.375rem 0 0;
  cursor:pointer;
  font-size:0.7rem;
  transition:all .2s;
}

.event-delete:hover{
  background:#ef4444;
}

.event-item[data-color="red"] {
  background: rgba(239, 68, 68, 0.1);
  border-color: var(--danger);
}

.event-item[data-color="green"] {
  background: rgba(16, 185, 129, 0.1);
  border-color: var(--success);
}

.event-item[data-color="yellow"] {
  background: rgba(245, 158, 11, 0.1);
  border-color: var(--warning);
}

.event-item[data-color="blue"] {
  background: rgba(59, 130, 246, 0.1);
  border-color: var(--info);
}

.calendar-more-events {
  font-size: 0.6875rem;
  color: var(--text-muted);
  text-align: center;
  padding: 0.25rem;
  cursor: pointer;
  margin-top: 0.25rem;
}

.calendar-more-events:hover {
  color: var(--primary);
  text-decoration: underline;
}

.add-event-section{
  margin-top:2rem;
  padding:1.5rem;
  background:var(--bg-secondary);
  border:1px solid var(--border-color);
  border-radius:1rem;
}

.add-event-section h3{
  margin:0 0 1rem 0;
  color:var(--text-primary);
  display:flex;
  align-items:center;
  gap:0.5rem;
}

.event-form{
  display:grid;
  grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));
  gap:1rem;
}

.form-group{
  display:flex;
  flex-direction:column;
  gap:0.5rem;
}

.form-group label{
  font-size:0.875rem;
  color:var(--text-secondary);
  font-weight:500;
}

.form-group input,
.form-group select,
.form-group textarea{
  width:100%;
  padding:0.625rem 0.875rem;
  border:1px solid var(--border-light);
  border-radius:var(--radius-sm);
  background:var(--bg-primary);
  color:var(--text-primary);
  font-size:0.875rem;
}

.form-group input[type="color"]{
  height:45px;
  padding:0.25rem;
  cursor:pointer;
}

.form-group input[type="checkbox"]{
  width:auto;
  accent-color:var(--primary);
  cursor:pointer;
}

.checkbox-group{
  display:flex;
  align-items:center;
  gap:0.5rem;
  cursor:pointer;
}

.checkbox-group input[type="checkbox"]{
  width:20px;
  height:20px;
  cursor:pointer;
  accent-color:var(--accent-blue);
}

.submit-btn{
  padding:0.75rem 2rem;
  background:linear-gradient(135deg, var(--accent-blue), var(--accent-purple));
  border:none;
  border-radius:0.5rem;
  color:white;
  font-weight:700;
  cursor:pointer;
  transition:all .3s;
  box-shadow:0 4px 15px rgba(33,150,243,.4);
}

.submit-btn:hover{
  transform:translateY(-2px);
  box-shadow:0 6px 20px rgba(33,150,243,.5);
}

.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.85);
  z-index: 10000;
  align-items: center;
  justify-content: center;
  animation: fadeIn 0.3s ease;
}

.modal.active {
  display: flex;
}

.modal-content {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: 1rem;
  padding: 0;
  max-width: 600px;
  width: 90%;
  max-height: 90vh;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
  animation: slideUp 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from { 
    opacity: 0;
    transform: translateY(30px);
  }
  to { 
    opacity: 1;
    transform: translateY(0);
  }
}

.modal-header {
  padding: 20px 24px;
  background: linear-gradient(135deg, #4f46e5, #7c3aed);
  color: white;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.modal-header h3 {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 700;
}

.modal-close {
  background: rgba(255, 255, 255, 0.2);
  border: 0;
  color: white;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 1.25rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.modal-close:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: rotate(90deg);
}

.modal-body {
  padding: 24px;
  max-height: calc(90vh - 180px);
  overflow-y: auto;
}

.modal-body::-webkit-scrollbar {
  width: 8px;
}

.modal-body::-webkit-scrollbar-track {
  background: var(--bg-primary);
  border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb {
  background: #334155;
  border-radius: 4px;
}

.modal-footer {
  padding: 16px 24px;
  background: var(--bg-secondary);
  border-top: 1px solid var(--border-color);
  display: flex;
  gap: 8px;
}

.info-item {
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 10px;
  padding: 12px;
  margin-bottom: 12px;
}

.info-label {
  font-size: 11px;
  color: var(--text-muted);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 6px;
}

.info-value {
  font-size: 14px;
  color: var(--text-primary);
  font-weight: 600;
}

.action-btn {
  padding: 0.5rem 1rem;
  background: var(--accent-red);
  border: none;
  border-radius: 0.5rem;
  color: white;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 0.875rem;
}

.action-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(244,67,54,.4);
}

.action-btn.edit {
  background: var(--accent-orange);
}

.action-btn.edit:hover {
  box-shadow: 0 4px 12px rgba(255,152,0,.4);
}

@media(max-width:768px){
  .calendar-grid{
    gap:0.25rem;
  }
  
  .calendar-day{
    min-height:80px;
    padding:0.375rem;
  }
  
  .day-number{
    font-size:0.75rem;
  }
  
  .event-item{
    font-size:0.6875rem;
    padding:0.25rem 0.375rem;
  }
  
  .event-title{
    font-size:0.6875rem;
  }
  
  .event-time{
    font-size:0.625rem;
  }
  
  .event-form{
    grid-template-columns:1fr;
    padding:1rem;
  }
  
  .calendar-toolbar{
    flex-direction:column;
    gap:0.75rem;
  }
  
  .calendar-toolbar .month-display{
    order:-1;
  }
}

@media(max-width:480px){
  .calendar-header{
    font-size:0.75rem;
    padding:0.5rem 0.25rem;
  }
  
  .calendar-day{
    min-height:60px;
  }
  
  .calendar-events{
    max-height:40px;
  }
}
</style>

<section class="card">
  <div class="calendar-header">
    <h2>📅 <?= $monthNames[(int)$m] ?> <?= $y ?></h2>
    <div class="calendar-nav">
      <a href="<?= ENV_APP['BASE_URL'].'/calendario?y='.$prev->format('Y').'&m='.$prev->format('n') ?>" class="calendar-nav-btn">
        ◀ Anterior
      </a>
      <a href="<?= ENV_APP['BASE_URL'].'/calendario' ?>" class="calendar-nav-btn" style="background:rgba(0,200,83,.2);border-color:rgba(0,200,83,.3)">
        • Hoy
      </a>
      <a href="<?= ENV_APP['BASE_URL'].'/calendario?y='.$next->format('Y').'&m='.$next->format('n') ?>" class="calendar-nav-btn">
        Siguiente ▶
      </a>
    </div>
  </div>
  
  <div class="calendar-body">
    <?php
      $totalEventos = count($eventos);
      $eventosDelMes = array_filter($eventos, function($ev) use($y, $m) {
        $evDate = new DateTime($ev['inicio']);
        return (int)$evDate->format('Y') === $y && (int)$evDate->format('n') === $m;
      });
      $countMes = count($eventosDelMes);
    ?>
    
    <?php if($countMes > 0): ?>
      <div style="padding:1rem;background:rgba(0,200,83,.1);border:1px solid rgba(0,200,83,.3);border-radius:0.75rem;margin-bottom:1rem">
        📊 <strong><?= $countMes ?></strong> evento(s) en <?= $monthNames[(int)$m] ?> <?= $y ?>
      </div>
    <?php endif; ?>
    
    <div class="calendar-weekdays">
      <div class="weekday">Lun</div>
      <div class="weekday">Mar</div>
      <div class="weekday">Mié</div>
      <div class="weekday">Jue</div>
      <div class="weekday">Vie</div>
      <div class="weekday">Sáb</div>
      <div class="weekday">Dom</div>
    </div>
    
    <div class="calendar-days">
      <?php
        // Días vacíos antes del primero
        for($i=1; $i<$startDow; $i++) {
          echo "<div></div>";
        }
        
        // Días del mes
        $today = date('Y-m-d');
        for($d=1; $d<=$days; $d++):
          $date = sprintf('%04d-%02d-%02d', $y, $m, $d);
          $isToday = $date === $today;
      ?>
        <div class="calendar-day <?= $isToday ? 'today' : '' ?>">
          <div class="day-number"><?= $d ?></div>
          
          <?php if(!empty($map[$date])): ?>
            <?php foreach($map[$date] as $ev): ?>
              <div class="event-item <?= (int)$ev['all_day'] === 1 ? 'all-day' : '' ?>" 
                   style="<?= $ev['color'] ? 'background:'.safe($ev['color']) : '' ?>"
                   title="<?= safe($ev['titulo']) ?>"
                   onclick="verDetalleEvento(<?= (int)$ev['id'] ?>, <?= htmlspecialchars(json_encode($ev), ENT_QUOTES) ?>)">
                <div class="event-title"><?= safe($ev['titulo']) ?></div>
                <div class="event-time">
                  <?php if((int)$ev['all_day'] === 1): ?>
                    Todo el día
                  <?php else: ?>
                    <?= date('H:i', strtotime($ev['inicio'])) ?>
                    <?php if($ev['fin']): ?>
                      - <?= date('H:i', strtotime($ev['fin'])) ?>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
                <div class="event-actions" onclick="event.stopPropagation()">
                  <button class="event-edit" onclick="editarEvento(<?= (int)$ev['id'] ?>, <?= htmlspecialchars(json_encode($ev), ENT_QUOTES) ?>)">✏️</button>
                  <form method="post" action="<?= ENV_APP['BASE_URL'].'/calendario/delete/'.$ev['id'] ?>" 
                        onsubmit="return confirm('¿Eliminar evento?')" style="display:inline">
                    <button type="submit" class="event-delete">✕</button>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      <?php endfor; ?>
    </div>
  </div>
  
  <div class="add-event-section">
    <h3>➕ Agregar nuevo evento</h3>
    <div style="background:rgba(33,150,243,.1);border:1px solid rgba(33,150,243,.3);padding:1rem;border-radius:0.5rem;margin-bottom:1rem;color:var(--text-secondary)">
      ℹ️ <strong>Nota:</strong> Los eventos se muestran en el mes y año correspondiente a su fecha de inicio. 
      Actualmente estás viendo: <strong><?= $monthNames[(int)$m] ?> <?= $y ?></strong>
    </div>
    <form class="event-form" method="post" action="<?= ENV_APP['BASE_URL'] ?>/calendario/store">
      <input type="hidden" name="y" value="<?= $y ?>">
      <input type="hidden" name="m" value="<?= $m ?>">
      
      <div class="form-group">
        <label>Título *</label>
        <input name="titulo" placeholder="Ej: Mantenimiento preventivo" required>
      </div>
      
      <div class="form-group">
        <label>Fecha y hora inicio *</label>
        <input type="datetime-local" name="inicio" required>
      </div>
      
      <div class="form-group">
        <label>Fecha y hora fin</label>
        <input type="datetime-local" name="fin" placeholder="Opcional">
      </div>
      
      <div class="form-group">
        <label>Color</label>
        <input type="color" name="color" value="#2196f3">
      </div>
      
      <div class="form-group">
        <label>ID Mantenimiento</label>
        <input name="mantenimiento_id" type="number" placeholder="Opcional">
      </div>
      
      <div class="form-group">
        <label class="checkbox-group">
          <input type="checkbox" name="all_day" value="1">
          <span>Evento de todo el día</span>
        </label>
      </div>
      
      <div class="form-group">
        <button type="submit" class="submit-btn">✓ Crear evento</button>
      </div>
    </form>
  </div>
</section>

<!-- Modal de Detalle de Evento -->
<div class="modal" id="modalDetalleEvento">
  <div class="modal-content">
    <div class="modal-header">
      <h3 id="detalleEventoTitulo">Detalle del Evento</h3>
      <button class="modal-close" onclick="cerrarDetalleEvento()">✕</button>
    </div>
    <div class="modal-body" id="detalleEventoBody">
      <!-- Se llenará dinámicamente -->
    </div>
    <div class="modal-footer">
      <button class="action-btn edit" onclick="editarDesdeDetalle()">✏️ Editar</button>
      <button class="submit-btn" onclick="cerrarDetalleEvento()">Cerrar</button>
    </div>
  </div>
</div>

<!-- Modal de Edición de Evento -->
<div class="modal" id="modalEditarEvento">
  <div class="modal-content">
    <div class="modal-header">
      <h3>✏️ Editar Evento</h3>
      <button class="modal-close" onclick="cerrarEditarEvento()">✕</button>
    </div>
    <div class="modal-body">
      <form method="post" id="formEditarEvento">
        <div class="form-group">
          <label>Título *</label>
          <input name="titulo" id="edit_titulo" required>
        </div>
        
        <div class="form-group">
          <label>Fecha y hora inicio *</label>
          <input type="datetime-local" name="inicio" id="edit_inicio" required>
        </div>
        
        <div class="form-group">
          <label>Fecha y hora fin</label>
          <input type="datetime-local" name="fin" id="edit_fin">
        </div>
        
        <div class="form-group">
          <label>Color</label>
          <input type="color" name="color" id="edit_color" value="#2196f3">
        </div>
        
        <div class="form-group">
          <label>ID Mantenimiento</label>
          <input name="mantenimiento_id" id="edit_mantenimiento_id" type="number" placeholder="Opcional">
        </div>
        
        <div class="form-group">
          <label class="checkbox-group">
            <input type="checkbox" name="all_day" id="edit_all_day" value="1">
            <span>Evento de todo el día</span>
          </label>
        </div>
        
        <input type="hidden" name="y" id="edit_y">
        <input type="hidden" name="m" id="edit_m">
      </form>
    </div>
    <div class="modal-footer">
      <button type="submit" form="formEditarEvento" class="submit-btn">💾 Guardar cambios</button>
      <button type="button" class="action-btn" onclick="cerrarEditarEvento()">Cancelar</button>
    </div>
  </div>
</div>

<script>
let currentEventoData = null;

function verDetalleEvento(id, evento) {
  currentEventoData = evento;
  const modal = document.getElementById('modalDetalleEvento');
  const body = document.getElementById('detalleEventoBody');
  
  document.getElementById('detalleEventoTitulo').textContent = evento.titulo;
  
  const inicio = new Date(evento.inicio);
  const fin = evento.fin ? new Date(evento.fin) : null;
  
  body.innerHTML = `
    <div class="info-item">
      <div class="info-label">📅 Fecha de Inicio</div>
      <div class="info-value">${inicio.toLocaleDateString('es-PA', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })}</div>
    </div>
    
    ${fin ? `
      <div class="info-item">
        <div class="info-label">⏰ Fecha de Fin</div>
        <div class="info-value">${fin.toLocaleDateString('es-PA', {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        })}</div>
      </div>
    ` : ''}
    
    <div class="info-item">
      <div class="info-label">🎨 Color</div>
      <div class="info-value">
        <span style="display:inline-block;width:30px;height:30px;background:${evento.color || '#2196f3'};border-radius:6px;vertical-align:middle"></span>
        ${evento.color || '#2196f3'}
      </div>
    </div>
    
    <div class="info-item">
      <div class="info-label">⏲️ Tipo</div>
      <div class="info-value">${evento.all_day == 1 ? '📅 Todo el día' : '⏰ Hora específica'}</div>
    </div>
    
    ${evento.mantenimiento_id ? `
      <div class="info-item">
        <div class="info-label">🔧 Mantenimiento Relacionado</div>
        <div class="info-value">ID: ${evento.mantenimiento_id}</div>
      </div>
    ` : ''}
  `;
  
  modal.classList.add('active');
}

function cerrarDetalleEvento() {
  document.getElementById('modalDetalleEvento').classList.remove('active');
  currentEventoData = null;
}

function editarDesdeDetalle() {
  if (currentEventoData) {
    editarEvento(currentEventoData.id, currentEventoData);
    cerrarDetalleEvento();
  }
}

function editarEvento(id, evento) {
  // Llenar el formulario
  document.getElementById('edit_titulo').value = evento.titulo || '';
  
  // Convertir fechas al formato datetime-local
  const inicio = new Date(evento.inicio);
  document.getElementById('edit_inicio').value = formatDateTimeLocal(inicio);
  
  if (evento.fin) {
    const fin = new Date(evento.fin);
    document.getElementById('edit_fin').value = formatDateTimeLocal(fin);
  } else {
    document.getElementById('edit_fin').value = '';
  }
  
  document.getElementById('edit_color').value = evento.color || '#2196f3';
  document.getElementById('edit_mantenimiento_id').value = evento.mantenimiento_id || '';
  document.getElementById('edit_all_day').checked = evento.all_day == 1;
  
  // Mantener el mes y año actual para la redirección
  const urlParams = new URLSearchParams(window.location.search);
  document.getElementById('edit_y').value = urlParams.get('y') || <?= $y ?>;
  document.getElementById('edit_m').value = urlParams.get('m') || <?= $m ?>;
  
  // Configurar action del formulario
  const form = document.getElementById('formEditarEvento');
  form.action = '<?= ENV_APP['BASE_URL'] ?>/calendario/update/' + id;
  
  // Mostrar modal
  document.getElementById('modalEditarEvento').classList.add('active');
}

function cerrarEditarEvento() {
  document.getElementById('modalEditarEvento').classList.remove('active');
}

function formatDateTimeLocal(date) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  
  return `${year}-${month}-${day}T${hours}:${minutes}`;
}

// Cerrar modales con ESC
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    cerrarDetalleEvento();
    cerrarEditarEvento();
  }
});

// Cerrar modales al hacer clic fuera
document.getElementById('modalDetalleEvento').addEventListener('click', function(e) {
  if (e.target === this) {
    cerrarDetalleEvento();
  }
});

document.getElementById('modalEditarEvento').addEventListener('click', function(e) {
  if (e.target === this) {
    cerrarEditarEvento();
  }
});
</script>