<?php
require_once BASE_PATH.'/models/Equipo.php';
$equipos = Equipo::all();
?>
<section class="card" style="padding:16px">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
    <h2 style="margin:0">Mantenimiento • Kanban</h2>
    <button class="btn-primary" onclick="document.getElementById('modalNuevo').classList.add('active')">
      ➕ Nuevo Mantenimiento
    </button>
  </div>

  <style>
    .board{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
    .col{border:1px solid #1e293b;border-radius:12px;background:#0b1220;min-height:260px;padding:10px}
    .col h3{margin:0 0 8px 0;color:#cbd5e1;font-size:14px;display:flex;align-items:center;gap:6px}
    .cardx{background:#0f172a;border:1px solid #233157;border-radius:10px;padding:10px;margin-bottom:8px;position:relative}
    .cardx .delete-btn{position:absolute;top:8px;right:8px;background:#7f1d1d;border:0;color:white;border-radius:6px;padding:4px 8px;font-size:11px;cursor:pointer}
    .meta{font-size:11px;color:#94a3b8;margin-bottom:6px}
    .progress{height:8px;background:#1f2937;border-radius:999px;overflow:hidden;margin:8px 0}
    .bar{height:8px}
    .bar-baja{background:#10b981}
    .bar-media{background:#f59e0b}
    .bar-alta{background:#ef4444}
    .bar-critica{background:#7f1d1d}
    .actions{display:flex;gap:4px;margin-top:6px;flex-wrap:wrap}
    .btn{border:1px solid #2b364b;background:#111827;color:#e5e7eb;border-radius:8px;padding:6px 8px;cursor:pointer;font-size:11px}
    .btn:hover{background:#1f2937}
    .btn-primary{background:#4f46e5;border:0;color:white;border-radius:10px;padding:10px 16px;font-weight:700;cursor:pointer}
    .tasks{margin-top:6px;font-size:12px}
    .task{display:flex;align-items:center;gap:6px;margin:4px 0}
    .task input{accent-color:#4f46e5}
    .newtask{display:flex;gap:6px;margin-top:6px}
    .newtask input{flex:1;padding:6px;border-radius:8px;border:1px solid #243044;background:#0b1220;color:#e5e7eb;font-size:11px}
    .newtask button{padding:6px 8px;font-size:11px}
    .modal{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.8);z-index:9999;align-items:center;justify-content:center}
    .modal.active{display:flex}
    .modal-content{background:#0f172a;border:1px solid #1e293b;border-radius:16px;padding:24px;max-width:600px;width:90%;max-height:90vh;overflow-y:auto}
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .form-group{margin-bottom:14px}
    .form-group label{display:block;margin-bottom:6px;color:#cbd5e1;font-size:13px;font-weight:500}
    .form-group input, .form-group select, .form-group textarea{width:100%;padding:10px;border-radius:10px;border:1px solid #243044;background:#0b1220;color:#e5e7eb}
    .form-group textarea{min-height:80px;resize:vertical}
    .modal-actions{display:flex;gap:8px;margin-top:16px}
    .btn-secondary{background:#374151;border:0;color:white;border-radius:10px;padding:10px 16px;flex:1;cursor:pointer}
    .badge{font-size:11px;padding:3px 8px;border-radius:999px;border:1px solid;display:inline-block}
    .badge-preventivo{background:#0f2a18;color:#6ee7b7;border-color:#047857}
    .badge-correctivo{background:#331f2b;color:#fca5a5;border-color:#991b1b}
    .badge-predictivo{background:#1e3a8a;color:#93c5fd;border-color:#1e40af}
    @media(max-width:1200px){.board{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:768px){.board{grid-template-columns:1fr}}
  </style>

  <?php
    $names=['pendiente'=>'⏳ Pendiente','en_progreso'=>'🔧 En Progreso','completado'=>'✅ Completado','cancelado'=>'❌ Cancelado'];
    $order=['pendiente','en_progreso','completado','cancelado'];
  ?>
  
  <div class="board">
    <?php foreach($order as $k): $title=$names[$k]; ?>
      <div class="col">
        <h3><?= $title ?> <span style="background:#1f2937;padding:2px 8px;border-radius:999px;font-size:11px"><?= count($cols[$k]) ?></span></h3>
        <?php foreach($cols[$k] as $t): ?>
          <div class="cardx" id="mant-<?= $t['id'] ?>">
            <form method="post" action="<?= ENV_APP['BASE_URL'] ?>/mantenimiento/delete/<?= $t['id'] ?>" onsubmit="return confirm('¿Eliminar este mantenimiento?')" style="display:inline">
              <button class="delete-btn" type="submit">🗑️</button>
            </form>
            
            <div class="meta">
              #<?= $t['id'] ?> • 
              <b><?= safe($t['equipo_nombre']) ?></b>
            </div>
            
            <div style="margin:6px 0">
              <b><?= safe($t['titulo']) ?></b>
            </div>
            
            <div style="display:flex;gap:4px;margin:6px 0">
              <?php
                $badgeTipo = 'badge';
                if($t['tipo'] === 'preventivo') $badgeTipo .= ' badge-preventivo';
                elseif($t['tipo'] === 'correctivo') $badgeTipo .= ' badge-correctivo';
                elseif($t['tipo'] === 'predictivo') $badgeTipo .= ' badge-predictivo';
              ?>
              <span class="<?= $badgeTipo ?>"><?= safe($t['tipo']) ?></span>
              <span class="badge" style="background:#1e293b;color:#cbd5e1;border-color:#334155">
                📅 <?= safe(date('d/m/Y', strtotime($t['fecha_programada']))) ?>
              </span>
            </div>
            
            <div class="progress">
              <div class="bar bar-<?= safe($t['prioridad']) ?>" style="width:<?= (int)$t['pct'] ?>%"></div>
            </div>
            <small style="color:#94a3b8">
              <?= (int)$t['hechas_tareas'] ?>/<?= (int)$t['total_tareas'] ?> tareas (<?= (int)$t['pct'] ?>%)
            </small>

            <div class="newtask">
              <input placeholder="Nueva tarea..." id="new-<?= $t['id'] ?>">
              <button class="btn" onclick="crearTarea(<?= $t['id'] ?>)">➕</button>
            </div>

            <div class="actions">
              <?php foreach($order as $kk): if($kk===$k) continue; ?>
                <button class="btn" onclick="mover(<?= $t['id'] ?>,'<?= $kk ?>')">
                  → <?= $names[$kk] ?>
                </button>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Modal Nuevo Mantenimiento -->
  <div class="modal" id="modalNuevo">
    <div class="modal-content">
      <h3 style="margin:0 0 16px 0;color:#cbd5e1">Nuevo Mantenimiento</h3>
      <form method="post" action="<?= ENV_APP['BASE_URL'] ?>/mantenimiento/store">
        <div class="form-group">
          <label>Título *</label>
          <input name="titulo" placeholder="Ej: Mantenimiento preventivo trimestral" required>
        </div>
        
        <div class="form-grid">
          <div class="form-group">
            <label>Equipo *</label>
            <select name="equipo_id" required>
              <option value="">Selecciona un equipo</option>
              <?php foreach($equipos as $eq): ?>
                <option value="<?= $eq['id'] ?>"><?= safe($eq['nombre']) ?> (<?= safe($eq['codigo']) ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
          
          <div class="form-group">
            <label>Fecha Programada *</label>
            <input type="datetime-local" name="fecha_programada" required>
          </div>
        </div>
        
        <div class="form-grid">
          <div class="form-group">
            <label>Tipo *</label>
            <select name="tipo" required>
              <option value="preventivo">Preventivo</option>
              <option value="correctivo">Correctivo</option>
              <option value="predictivo">Predictivo</option>
            </select>
          </div>
          
          <div class="form-group">
            <label>Prioridad *</label>
            <select name="prioridad" required>
              <option value="baja">Baja</option>
              <option value="media" selected>Media</option>
              <option value="alta">Alta</option>
              <option value="critica">Crítica</option>
            </select>
          </div>
        </div>
        
        <div class="form-group">
          <label>Descripción</label>
          <textarea name="descripcion" placeholder="Detalles del mantenimiento..."></textarea>
        </div>
        
        <div class="form-group">
          <label>Costo Estimado (USD)</label>
          <input type="number" step="0.01" name="costo_estimado" placeholder="0.00">
        </div>
        
        <div class="modal-actions">
          <button type="submit" class="btn-primary" style="flex:1">💾 Guardar</button>
          <button type="button" class="btn-secondary" onclick="cerrarModal()">Cancelar</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    async function mover(id,estado){
      const r=await fetch('<?= ENV_APP['BASE_URL'] ?>/mantenimiento/mover',
        {method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`id=${id}&estado=${encodeURIComponent(estado)}`});
      if(r.ok) location.reload();
    }
    
    async function toggleTarea(tarea_id,el){
      const r=await fetch('<?= ENV_APP['BASE_URL'] ?>/mantenimiento/tareaToggle',
        {method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`tarea_id=${tarea_id}&hecho=${el.checked?1:0}`});
      if(r.ok) location.reload();
    }
    
    async function crearTarea(mid){
      const val=document.getElementById('new-'+mid).value.trim(); 
      if(!val) return;
      const r=await fetch('<?= ENV_APP['BASE_URL'] ?>/mantenimiento/tareaNueva',
        {method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`mantenimiento_id=${mid}&titulo=${encodeURIComponent(val)}`});
      if(r.ok) location.reload();
    }
    
    function cerrarModal() {
      document.getElementById('modalNuevo').classList.remove('active');
    }
    
    // Cerrar modal al hacer clic fuera
    document.getElementById('modalNuevo').addEventListener('click', function(e) {
      if (e.target === this) {
        cerrarModal();
      }
    });
    
    // ESC para cerrar
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        cerrarModal();
      }
    });
  </script>
</section>