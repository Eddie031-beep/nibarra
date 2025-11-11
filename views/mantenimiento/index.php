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
    .cardx{background:#0f172a;border:1px solid #233157;border-radius:10px;padding:10px;margin-bottom:8px;position:relative;cursor:pointer;transition:all .2s}
    .cardx:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(79,70,229,.3);border-color:#4f46e5}
    .cardx .delete-btn{position:absolute;top:8px;right:8px;background:#7f1d1d;border:0;color:white;border-radius:6px;padding:4px 8px;font-size:11px;cursor:pointer;z-index:10}
    .cardx .delete-btn:hover{background:#991b1b}
    .meta{font-size:11px;color:#94a3b8;margin-bottom:6px}
    .progress{height:8px;background:#1f2937;border-radius:999px;overflow:hidden;margin:8px 0}
    .bar{height:8px}
    .bar-baja{background:#10b981}
    .bar-media{background:#f59e0b}
    .bar-alta{background:#ef4444}
    .bar-critica{background:#7f1d1d}
    .actions{display:flex;gap:4px;margin-top:6px;flex-wrap:wrap}
    .btn{border:1px solid #2b364b;background:#111827;color:#e5e7eb;border-radius:8px;padding:6px 8px;cursor:pointer;font-size:11px;transition:all .2s}
    .btn:hover{background:#1f2937;border-color:#4f46e5}
    .btn-primary{background:#4f46e5;border:0;color:white;border-radius:10px;padding:10px 16px;font-weight:700;cursor:pointer;transition:all .3s}
    .btn-primary:hover{background:#4338ca;transform:translateY(-2px)}
    .tasks{margin-top:6px;font-size:12px}
    .task{display:flex;align-items:center;gap:6px;margin:4px 0}
    .task input{accent-color:#4f46e5}
    .newtask{display:flex;gap:6px;margin-top:6px}
    .newtask input{flex:1;padding:6px;border-radius:8px;border:1px solid #243044;background:#0b1220;color:#e5e7eb;font-size:11px}
    .newtask button{padding:6px 8px;font-size:11px}
    
    .modal{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.85);z-index:9999;align-items:center;justify-content:center;animation:fadeIn .3s ease}
    .modal.active{display:flex}
    @keyframes fadeIn{from{opacity:0}to{opacity:1}}
    
    .modal-content{background:#0f172a;border:1px solid #1e293b;border-radius:16px;padding:0;max-width:700px;width:90%;max-height:90vh;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.5);animation:slideUp .3s ease}
    @keyframes slideUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
    
    .modal-header{padding:20px 24px;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;display:flex;align-items:center;justify-content:space-between;border-radius:16px 16px 0 0}
    .modal-header h3{margin:0;font-size:1.25rem;font-weight:700}
    .modal-close{background:rgba(255,255,255,.2);border:0;color:white;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:1.25rem;display:flex;align-items:center;justify-content:center;transition:all .2s}
    .modal-close:hover{background:rgba(255,255,255,.3);transform:rotate(90deg)}
    
    .modal-body{padding:24px;max-height:calc(90vh - 180px);overflow-y:auto}
    .modal-body::-webkit-scrollbar{width:8px}
    .modal-body::-webkit-scrollbar-track{background:#0b1220;border-radius:4px}
    .modal-body::-webkit-scrollbar-thumb{background:#334155;border-radius:4px}
    .modal-body::-webkit-scrollbar-thumb:hover{background:#475569}
    
    .modal-footer{padding:16px 24px;background:#0b1220;border-top:1px solid #1e293b;display:flex;gap:8px;border-radius:0 0 16px 16px}
    
    .info-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:20px}
    .info-item{background:#0b1220;border:1px solid #1e293b;border-radius:10px;padding:12px}
    .info-label{font-size:11px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px}
    .info-value{font-size:14px;color:#cbd5e1;font-weight:600}
    
    .preview-section{margin-bottom:20px}
    .preview-section h4{margin:0 0 12px 0;color:#cbd5e1;font-size:14px;font-weight:700;display:flex;align-items:center;gap:8px}
    .preview-section p{margin:0;color:#94a3b8;font-size:13px;line-height:1.6}
    
    .task-list{display:flex;flex-direction:column;gap:8px}
    .task-item{display:flex;align-items:center;gap:10px;padding:10px;background:#0b1220;border:1px solid #1e293b;border-radius:8px;transition:all .2s;position:relative}
    .task-item:hover{border-color:#334155;background:#0f172a}
    .task-item input[type="checkbox"]{width:18px;height:18px;accent-color:#4f46e5;cursor:pointer;flex-shrink:0}
    .task-item label{flex:1;color:#cbd5e1;font-size:13px;cursor:pointer;user-select:none}
    .task-item.completed label{text-decoration:line-through;color:#64748b}
    .task-item .delete-task-btn{background:#7f1d1d;border:0;color:white;border-radius:4px;padding:4px 8px;font-size:11px;cursor:pointer;transition:all .2s;flex-shrink:0;opacity:0}
    .task-item:hover .delete-task-btn{opacity:1}
    .task-item .delete-task-btn:hover{background:#991b1b;transform:scale(1.05)}
    
    .priority-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:999px;font-size:12px;font-weight:600}
    .priority-baja{background:rgba(16,185,129,.1);color:#6ee7b7;border:1px solid rgba(16,185,129,.3)}
    .priority-media{background:rgba(245,158,11,.1);color:#fcd34d;border:1px solid rgba(245,158,11,.3)}
    .priority-alta{background:rgba(239,68,68,.1);color:#fca5a5;border:1px solid rgba(239,68,68,.3)}
    .priority-critica{background:#7f1d1d;color:#fca5a5;border:1px solid #991b1b;animation:pulse 2s infinite}
    
    @keyframes pulse{0%,100%{opacity:1}50%{opacity:.7}}
    
    .badge{font-size:11px;padding:3px 8px;border-radius:999px;border:1px solid;display:inline-block}
    .badge-preventivo{background:#0f2a18;color:#6ee7b7;border-color:#047857}
    .badge-correctivo{background:#331f2b;color:#fca5a5;border-color:#991b1b}
    .badge-predictivo{background:#1e3a8a;color:#93c5fd;border-color:#1e40af}
    
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .form-group{margin-bottom:14px}
    .form-group label{display:block;margin-bottom:6px;color:#cbd5e1;font-size:13px;font-weight:500}
    .form-group input, .form-group select, .form-group textarea{width:100%;padding:10px;border-radius:10px;border:1px solid #243044;background:#0b1220;color:#e5e7eb;transition:all .2s}
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus{outline:none;border-color:#4f46e5;box-shadow:0 0 0 3px rgba(79,70,229,.1)}
    .form-group textarea{min-height:80px;resize:vertical}
    
    .btn-secondary{background:#374151;border:0;color:white;border-radius:10px;padding:10px 16px;flex:1;cursor:pointer;transition:all .2s}
    .btn-secondary:hover{background:#475569}
    
    .scroll-indicator{text-align:center;padding:8px;color:#64748b;font-size:11px;animation:bounce 2s infinite}
    @keyframes bounce{0%,100%{transform:translateY(0)}50%{transform:translateY(-5px)}}
    @keyframes spin{to{transform:rotate(360deg)}}
    
    @media(max-width:1200px){.board{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:768px){
      .board{grid-template-columns:1fr}
      .info-grid{grid-template-columns:1fr}
      .form-grid{grid-template-columns:1fr}
      .modal-content{width:95%;max-height:95vh}
    }
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
          <div class="cardx" onclick="verDetalle(<?= $t['id'] ?>)" id="mant-<?= $t['id'] ?>">
            <form method="post" action="<?= ENV_APP['BASE_URL'] ?>/mantenimiento/delete/<?= $t['id'] ?>" onsubmit="return confirm('¿Eliminar este mantenimiento?')" style="display:inline" onclick="event.stopPropagation()">
              <button class="delete-btn" type="submit">🗑️</button>
            </form>
            
            <div class="meta">
              #<?= $t['id'] ?> • 
              <b><?= safe($t['equipo_nombre']) ?></b>
            </div>
            
            <div style="margin:6px 0">
              <b><?= safe($t['titulo']) ?></b>
            </div>
            
            <div style="display:flex;gap:4px;margin:6px 0;flex-wrap:wrap">
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

            <div class="actions" onclick="event.stopPropagation()">
              <?php foreach($order as $kk): if($kk===$k) continue; ?>
                <button class="btn" onclick="event.stopPropagation(); mover(<?= $t['id'] ?>,'<?= $kk ?>')">
                  → <?= substr($names[$kk], 2) ?>
                </button>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="modal" id="modalPreview">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="previewTitle">Detalles del Mantenimiento</h3>
        <button class="modal-close" onclick="cerrarPreview()">✕</button>
      </div>
      <div class="modal-body" id="previewBody">
        <div class="scroll-indicator">⬇️ Desliza para ver más</div>
        <div id="previewContent"></div>
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" onclick="cerrarPreview()">Cerrar</button>
      </div>
    </div>
  </div>

  <div class="modal" id="modalNuevo">
    <div class="modal-content">
      <div class="modal-header">
        <h3>➕ Nuevo Mantenimiento</h3>
        <button class="modal-close" onclick="cerrarModal()">✕</button>
      </div>
      <div class="modal-body">
        <form method="post" action="<?= ENV_APP['BASE_URL'] ?>/mantenimiento/store" id="formNuevo">
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
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="formNuevo" class="btn-primary">💾 Guardar</button>
        <button type="button" class="btn-secondary" onclick="cerrarModal()">Cancelar</button>
      </div>
    </div>
  </div>

  <script>
    let currentMantenimientoId = null;
    
    async function verDetalle(id) {
      currentMantenimientoId = id;
      const modal = document.getElementById('modalPreview');
      const content = document.getElementById('previewContent');
      
      content.innerHTML = '<div style="text-align:center;padding:40px"><div style="animation:spin 1s linear infinite;font-size:2rem">⚙️</div><p>Cargando...</p></div>';
      modal.classList.add('active');
      
      try {
        const response = await fetch(`<?= ENV_APP['BASE_URL'] ?>/mantenimiento/obtener/${id}`);
        const data = await response.json();
        
        if (data) {
          document.getElementById('previewTitle').textContent = `#${data.id} - ${data.titulo}`;
          
          const prioridadClass = `priority-${data.prioridad}`;
          const prioridadIcon = {
            'baja': '🟢',
            'media': '🟡',
            'alta': '🔴',
            'critica': '🚨'
          }[data.prioridad] || '⚪';
          
          const tipoClass = `badge-${data.tipo}`;
          
          const estadoEmoji = {
            'pendiente': '⏳',
            'en_progreso': '🔧',
            'completado': '✅',
            'cancelado': '❌'
          }[data.estado] || '❓';
          
          content.innerHTML = `
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">Equipo</div>
                <div class="info-value">🔧 ${data.equipo_nombre}</div>
              </div>
              <div class="info-item">
                <div class="info-label">Estado</div>
                <div class="info-value">${estadoEmoji} ${data.estado.replace('_', ' ')}</div>
              </div>
              <div class="info-item">
                <div class="info-label">Fecha Programada</div>
                <div class="info-value">📅 ${new Date(data.fecha_programada).toLocaleString('es-PA')}</div>
              </div>
              <div class="info-item">
                <div class="info-label">Costo Estimado</div>
                <div class="info-value">💰 $${data.costo_estimado || '0.00'}</div>
              </div>
            </div>
            
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">Tipo</div>
                <div class="info-value"><span class="badge ${tipoClass}">${data.tipo}</span></div>
              </div>
              <div class="info-item">
                <div class="info-label">Prioridad</div>
                <div class="info-value"><span class="priority-badge ${prioridadClass}">${prioridadIcon} ${data.prioridad}</span></div>
              </div>
            </div>
            
            ${data.descripcion ? `
              <div class="preview-section">
                <h4>📝 Descripción</h4>
                <p>${data.descripcion}</p>
              </div>
            ` : ''}
            
            ${data.tareas && data.tareas.length > 0 ? `
              <div class="preview-section">
                <h4>✅ Tareas (${data.tareas.filter(t => t.hecho).length}/${data.tareas.length})</h4>
                <div class="task-list">
                  ${data.tareas.map(tarea => `
                    <div class="task-item ${tarea.hecho ? 'completed' : ''}">
                      <input type="checkbox" 
                             ${tarea.hecho ? 'checked' : ''} 
                             onchange="toggleTarea(${tarea.id}, this)"
                             id="task-${tarea.id}">
                      <label for="task-${tarea.id}">${tarea.titulo}</label>
                      <button class="delete-task-btn" onclick="eliminarTarea(${tarea.id}, ${id})" type="button">🗑️</button>
                    </div>
                  `).join('')}
                </div>
              </div>
            ` : ''}
            
            <div class="preview-section" style="margin-bottom:0">
              <h4>➕ Agregar Nueva Tarea</h4>
              <div class="newtask">
                <input placeholder="Descripción de la tarea..." id="newTaskInput-${id}">
                <button class="btn-primary" onclick="crearTarea(${id})">Agregar</button>
              </div>
            </div>
          `;
          
          setTimeout(() => {
            const indicator = content.previousElementSibling;
            if (indicator && indicator.classList.contains('scroll-indicator')) {
              indicator.style.display = 'none';
            }
          }, 2000);
        }
      } catch (error) {
        content.innerHTML = `
          <div style="text-align:center;padding:40px;color:#ef4444">
            <div style="font-size:3rem;margin-bottom:16px">❌</div>
            <h4>Error al cargar</h4>
            <p>No se pudo obtener la información del mantenimiento</p>
          </div>
        `;
      }
    }
    
    function cerrarPreview() {
      document.getElementById('modalPreview').classList.remove('active');
      currentMantenimientoId = null;
    }
    
    async function eliminarTarea(tarea_id, mantenimiento_id) {
      if (!confirm('¿Estás seguro de eliminar esta tarea?')) {
        return;
      }
      
      try {
        const r = await fetch('<?= ENV_APP['BASE_URL'] ?>/mantenimiento/tareaEliminar', {
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: `tarea_id=${tarea_id}`
        });
        
        const data = await r.json();
        
        if (r.ok && data.ok) {
          if (currentMantenimientoId) {
            verDetalle(currentMantenimientoId);
          }
        } else {
          alert('Error al eliminar la tarea: ' + (data.error || 'Error desconocido'));
        }
      } catch (error) {
        alert('Error de conexión al eliminar la tarea');
        console.error('Error:', error);
      }
    }
    
    async function mover(id, estado) {
      const r = await fetch('<?= ENV_APP['BASE_URL'] ?>/mantenimiento/mover', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `id=${id}&estado=${encodeURIComponent(estado)}`
      });
      if (r.ok) location.reload();
    }
    
    async function toggleTarea(tarea_id, el) {
      const r = await fetch('<?= ENV_APP['BASE_URL'] ?>/mantenimiento/tareaToggle', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `tarea_id=${tarea_id}&hecho=${el.checked ? 1 : 0}`
      });
      if (r.ok) {
        if (currentMantenimientoId) {
          verDetalle(currentMantenimientoId);
        }
      }
    }
    
    async function crearTarea(mid) {
      const input = document.getElementById('newTaskInput-' + mid);
      const val = input.value.trim();
      if (!val) return;
      
      const r = await fetch('<?= ENV_APP['BASE_URL'] ?>/mantenimiento/tareaNueva', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `mantenimiento_id=${mid}&titulo=${encodeURIComponent(val)}`
      });
      
      if (r.ok) {
        input.value = '';
        verDetalle(mid);
      }
    }
    
    function cerrarModal() {
      document.getElementById('modalNuevo').classList.remove('active');
    }
    
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        cerrarModal();
        cerrarPreview();
      }
    });
    
    document.getElementById('modalNuevo').addEventListener('click', function(e) {
      if (e.target === this) cerrarModal();
    });
    
    document.getElementById('modalPreview').addEventListener('click', function(e) {
      if (e.target === this) cerrarPreview();
    });
  </script>
</section>