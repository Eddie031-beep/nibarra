<style>
.equipos-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:1.5rem;
  background:linear-gradient(135deg, var(--accent-blue), var(--accent-purple));
  border-radius:1rem 1rem 0 0;
}

.equipos-header h2{
  margin:0;
  color:white;
  font-size:1.75rem;
  font-weight:700;
  display:flex;
  align-items:center;
  gap:0.75rem;
}

.stats-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit, minmax(150px, 1fr));
  gap:0.75rem;
  padding:1.5rem;
  background:var(--bg-secondary);
}

.stat-card{
  background:var(--bg-card);
  border:1px solid var(--border-color);
  padding:1rem;
  border-radius:0.75rem;
  text-align:center;
}

.stat-value{
  font-size:2rem;
  font-weight:700;
  color:var(--accent-blue);
}

.stat-label{
  font-size:0.875rem;
  color:var(--text-secondary);
  margin-top:0.25rem;
}

.form-section{
  padding:1.5rem;
  background:var(--bg-secondary);
  border-top:1px solid var(--border-color);
}

.form-section h3{
  margin:0 0 1rem 0;
  color:var(--text-primary);
  display:flex;
  align-items:center;
  gap:0.5rem;
}

.form-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));
  gap:1rem;
  margin-bottom:1rem;
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
.form-group select{
  padding:0.75rem;
  background:var(--bg-primary);
  border:1px solid var(--border-color);
  border-radius:0.5rem;
  color:var(--text-primary);
  transition:all .2s;
}

.form-group input:focus,
.form-group select:focus{
  outline:none;
  border-color:var(--accent-blue);
  box-shadow:0 0 0 3px rgba(33,150,243,.1);
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

.submit-btn:hover:not(:disabled){
  transform:translateY(-2px);
  box-shadow:0 6px 20px rgba(33,150,243,.5);
}

.submit-btn:disabled{
  opacity:0.5;
  cursor:not-allowed;
}

.table-section{
  padding:1.5rem;
}

.table-section h3{
  margin:0 0 1rem 0;
  color:var(--text-primary);
}

.table-scroll-container{
  max-height: 600px;
  overflow-y: auto;
  overflow-x: auto;
  border: 1px solid var(--border-color);
  border-radius: 0.75rem;
  position: relative;
}

.table-scroll-container::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}

.table-scroll-container::-webkit-scrollbar-track {
  background: var(--bg-primary);
  border-radius: 5px;
}

.table-scroll-container::-webkit-scrollbar-thumb {
  background: #334155;
  border-radius: 5px;
}

.table-scroll-container::-webkit-scrollbar-thumb:hover {
  background: #475569;
}

.table-scroll-container::-webkit-scrollbar-corner {
  background: var(--bg-primary);
}

.scroll-hint {
  position: sticky;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(to top, rgba(11, 18, 32, 0.95), transparent);
  text-align: center;
  padding: 12px;
  font-size: 12px;
  color: #94a3b8;
  pointer-events: none;
  z-index: 10;
}

.scroll-hint.hidden {
  display: none;
}

.equipos-table{
  width:100%;
  border-collapse:collapse;
  min-width: 800px;
}

.equipos-table thead{
  background:var(--bg-secondary);
  position: sticky;
  top: 0;
  z-index: 5;
}

.equipos-table th{
  padding:1rem;
  text-align:left;
  font-weight:600;
  color:var(--text-secondary);
  border-bottom:2px solid var(--border-color);
  font-size:0.875rem;
  white-space:nowrap;
  background: var(--bg-secondary);
}

.equipos-table td{
  padding:1rem;
  border-bottom:1px solid var(--border-color);
  color:var(--text-primary);
}

.equipos-table tbody tr{
  transition:all .2s;
  cursor:pointer;
}

.equipos-table tbody tr:hover{
  background:var(--bg-secondary);
  transform:translateX(4px);
}

.status-badge{
  display:inline-block;
  padding:0.375rem 0.75rem;
  border-radius:2rem;
  font-size:0.75rem;
  font-weight:600;
}

.status-operativo{
  background:rgba(0,200,83,.1);
  color:var(--accent-green);
  border:1px solid rgba(0,200,83,.3);
}

.status-fuera_de_servicio{
  background:rgba(255,152,0,.1);
  color:var(--accent-orange);
  border:1px solid rgba(255,152,0,.3);
}

.status-baja{
  background:rgba(244,67,54,.1);
  color:var(--accent-red);
  border:1px solid rgba(244,67,54,.3);
}

.action-btn{
  padding:0.5rem 1rem;
  background:var(--accent-red);
  border:none;
  border-radius:0.5rem;
  color:white;
  font-weight:600;
  cursor:pointer;
  transition:all .2s;
  font-size:0.875rem;
}

.action-btn:hover{
  transform:translateY(-2px);
  box-shadow:0 4px 12px rgba(244,67,54,.4);
}

.action-btn.edit{
  background:var(--accent-orange);
}

.action-btn.edit:hover{
  box-shadow:0 4px 12px rgba(255,152,0,.4);
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
  max-width: 800px;
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

@keyframes bounceArrow {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-8px); }
}

.modal-header {
  padding: 20px 24px;
  background: linear-gradient(135deg, #4f46e5, #7c3aed);
  color: white;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-radius: 1rem 1rem 0 0;
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

.modal-body::-webkit-scrollbar-thumb:hover {
  background: #475569;
}

.modal-footer {
  padding: 16px 24px;
  background: var(--bg-secondary);
  border-top: 1px solid var(--border-color);
  display: flex;
  gap: 8px;
  border-radius: 0 0 1rem 1rem;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
  margin-bottom: 20px;
}

.info-item {
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 10px;
  padding: 12px;
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
  word-break: break-word;
}

.preview-section {
  margin-bottom: 20px;
}

.preview-section h4 {
  margin: 0 0 12px 0;
  color: var(--text-primary);
  font-size: 14px;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 8px;
}

.preview-section p {
  margin: 0;
  color: var(--text-secondary);
  font-size: 13px;
  line-height: 1.6;
}

.cost-display {
  font-size: 2rem;
  font-weight: 800;
  color: var(--accent-green);
  text-align: center;
  padding: 20px;
  background: rgba(16, 185, 129, 0.05);
  border: 1px solid rgba(16, 185, 129, 0.2);
  border-radius: 10px;
  margin: 20px 0;
}

.scroll-indicator {
  text-align: center;
  padding: 8px;
  color: #64748b;
  font-size: 11px;
  animation: bounceArrow 2s infinite;
}

@media(max-width:768px){
  .form-grid{
    grid-template-columns:1fr;
  }
  
  .stats-grid{
    grid-template-columns:repeat(2, 1fr);
  }
  
  .info-grid {
    grid-template-columns: 1fr;
  }
  
  .modal-content {
    width: 95%;
    max-height: 95vh;
  }
  
  .table-scroll-container {
    max-height: 400px;
  }
}
</style>

<section class="card">
  <div class="equipos-header">
    <h2>🔧 Gestión de Equipos</h2>
  </div>
  
  <div class="stats-grid">
    <?php
      $total = count($equipos);
      $operativos = count(array_filter($equipos, fn($e) => $e['estado'] === 'operativo'));
      $fuera = count(array_filter($equipos, fn($e) => $e['estado'] === 'fuera_de_servicio'));
      $baja = count(array_filter($equipos, fn($e) => $e['estado'] === 'baja'));
    ?>
    <div class="stat-card">
      <div class="stat-value"><?= $total ?></div>
      <div class="stat-label">Total de equipos</div>
    </div>
    <div class="stat-card">
      <div class="stat-value" style="color:var(--accent-green)"><?= $operativos ?></div>
      <div class="stat-label">Operativos</div>
    </div>
    <div class="stat-card">
      <div class="stat-value" style="color:var(--accent-orange)"><?= $fuera ?></div>
      <div class="stat-label">Fuera de servicio</div>
    </div>
    <div class="stat-card">
      <div class="stat-value" style="color:var(--accent-red)"><?= $baja ?></div>
      <div class="stat-label">Dados de baja</div>
    </div>
  </div>
  
  <form method="post" action="<?= ENV_APP['BASE_URL'] ?>/equipos/store" class="form-section">
    <h3>➕ Agregar nuevo equipo</h3>
    
    <?php 
      $user = Auth::user();
      $canCreate = $user && in_array($user['rol'], ['admin', 'tecnico']);
    ?>
    
    <?php if(!$canCreate): ?>
      <div style="padding:1rem;background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.3);border-radius:0.75rem;color:#fcd34d;margin-bottom:1rem">
        ⚠️ No tienes permisos para agregar equipos. Solo usuarios <strong>Admin</strong> y <strong>Técnico</strong> pueden crear equipos.
      </div>
    <?php endif; ?>
    
    <fieldset <?= !$canCreate ? 'disabled' : '' ?> style="border:0;padding:0;margin:0">
      <div class="form-grid">
        <div class="form-group">
          <label>Código *</label>
          <input name="codigo" placeholder="Ej: EQ-001" required>
        </div>
        
        <div class="form-group">
          <label>Nombre *</label>
          <input name="nombre" placeholder="Ej: Computadora Dell" required>
        </div>
        
        <div class="form-group">
          <label>Categoría</label>
          <input name="categoria" placeholder="Ej: Computación">
        </div>
        
        <div class="form-group">
          <label>Marca</label>
          <input name="marca" placeholder="Ej: Dell">
        </div>
        
        <div class="form-group">
          <label>Modelo</label>
          <input name="modelo" placeholder="Ej: Optiplex 7090">
        </div>
        
        <div class="form-group">
          <label>Nro. Serie</label>
          <input name="nro_serie" placeholder="Ej: 123456789">
        </div>
        
        <div class="form-group">
          <label>Ubicación</label>
          <input name="ubicacion" placeholder="Ej: Oficina Principal">
        </div>
        
        <div class="form-group">
          <label>Fecha de Compra</label>
          <input type="date" name="fecha_compra">
        </div>
        
        <div class="form-group">
          <label>Proveedor</label>
          <input name="proveedor" placeholder="Ej: TechStore SA">
        </div>
        
        <div class="form-group">
          <label>Costo ($)</label>
          <input type="number" step="0.01" name="costo" placeholder="0.00">
        </div>
        
        <div class="form-group">
          <label>Estado *</label>
          <select name="estado" required>
            <option value="operativo">Operativo</option>
            <option value="fuera_de_servicio">Fuera de servicio</option>
            <option value="baja">Baja</option>
          </select>
        </div>
      </div>
      
      <button type="submit" class="submit-btn" <?= !$canCreate ? 'disabled' : '' ?>>✓ Guardar equipo</button>
    </fieldset>
  </form>
  
  <div class="table-section">
    <h3>📋 Listado de equipos (<?= count($equipos) ?>)</h3>
    
    <div class="table-scroll-container" id="tableScroll">
      <table class="equipos-table">
        <thead>
          <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Marca/Modelo</th>
            <th>Ubicación</th>
            <th>Estado</th>
            <th>Costo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($equipos)): ?>
            <tr>
              <td colspan="8" style="text-align:center;padding:2rem;color:var(--text-secondary)">
                No hay equipos registrados aún
              </td>
            </tr>
          <?php else: ?>
            <?php foreach($equipos as $e): ?>
              <tr onclick="verDetalleEquipo(<?= (int)$e['id'] ?>, <?= htmlspecialchars(json_encode($e), ENT_QUOTES) ?>)">
                <td><strong><?= safe($e['codigo']) ?></strong></td>
                <td><?= safe($e['nombre']) ?></td>
                <td><?= safe($e['categoria']) ?></td>
                <td><?= safe($e['marca']) ?> <?= safe($e['modelo']) ?></td>
                <td><?= safe($e['ubicacion']) ?></td>
                <td>
                  <span class="status-badge status-<?= safe($e['estado']) ?>">
                    <?= ucfirst(str_replace('_', ' ', safe($e['estado']))) ?>
                  </span>
                </td>
                <td><?= $e['costo']!==null ? '$'.number_format($e['costo'],2) : '-' ?></td>
                <td onclick="event.stopPropagation()">
                  <div style="display:flex;gap:0.5rem;flex-wrap:wrap">
                    <?php 
                      $user = Auth::user();
                      $canEdit = $user && in_array($user['rol'], ['admin', 'tecnico']);
                      $canDelete = $user && $user['rol'] === 'admin';
                    ?>
                    
                    <?php if($canEdit): ?>
                      <button class="action-btn edit" onclick="editarEquipo(<?= (int)$e['id'] ?>, <?= htmlspecialchars(json_encode($e), ENT_QUOTES) ?>)">
                        ✏️ Editar
                      </button>
                    <?php endif; ?>
                    
                    <?php if($canDelete): ?>
                      <form method="post" 
                            action="<?= ENV_APP['BASE_URL'] ?>/equipos/delete/<?= (int)$e['id'] ?>" 
                            onsubmit="return confirm('¿Está seguro de eliminar este equipo?')"
                            style="display:inline">
                        <button type="submit" class="action-btn">🗑️</button>
                      </form>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
      
      <div class="scroll-hint" id="scrollHint">
        ⬇️ Desliza para ver más equipos
      </div>
    </div>
  </div>
</section>

<!-- Modal Vista Previa Equipo -->
<div class="modal" id="modalPreviewEquipo">
  <div class="modal-content">
    <div class="modal-header">
      <h3 id="previewEquipoTitle">Detalles del Equipo</h3>
      <button class="modal-close" onclick="cerrarPreviewEquipo()">✕</button>
    </div>
    <div class="modal-body" id="previewEquipoBody">
      <div class="scroll-indicator">⬇️ Desliza para ver más información</div>
      <div id="previewEquipoContent"></div>
    </div>
    <div class="modal-footer">
      <button class="action-btn edit" onclick="editarDesdePreview()">✏️ Editar</button>
      <button class="submit-btn" onclick="cerrarPreviewEquipo()">Cerrar</button>
    </div>
  </div>
</div>

<!-- Modal de Edición -->
<div class="modal" id="modalEditar">
  <div class="modal-content">
    <div class="modal-header">
      <h3>✏️ Editar Equipo</h3>
      <button class="modal-close" onclick="cerrarModalEditar()">✕</button>
    </div>
    
    <div class="modal-body">
      <form method="post" id="formEditar">
        <div class="form-grid">
          <div class="form-group">
            <label>Código *</label>
            <input name="codigo" id="edit_codigo" required>
          </div>
          
          <div class="form-group">
            <label>Nombre *</label>
            <input name="nombre" id="edit_nombre" required>
          </div>
          
          <div class="form-group">
            <label>Categoría</label>
            <input name="categoria" id="edit_categoria">
          </div>
          
          <div class="form-group">
            <label>Marca</label>
            <input name="marca" id="edit_marca">
          </div>
          
          <div class="form-group">
            <label>Modelo</label>
            <input name="modelo" id="edit_modelo">
          </div>
          
          <div class="form-group">
            <label>Nro. Serie</label>
            <input name="nro_serie" id="edit_nro_serie">
          </div>
          
          <div class="form-group">
            <label>Ubicación</label>
            <input name="ubicacion" id="edit_ubicacion">
          </div>
          
          <div class="form-group">
            <label>Fecha de Compra</label>
            <input type="date" name="fecha_compra" id="edit_fecha_compra">
          </div>
          
          <div class="form-group">
            <label>Proveedor</label>
            <input name="proveedor" id="edit_proveedor">
          </div>
          
          <div class="form-group">
            <label>Costo ($)</label>
            <input type="number" step="0.01" name="costo" id="edit_costo">
          </div>
          
          <div class="form-group">
            <label>Estado *</label>
            <select name="estado" id="edit_estado" required>
              <option value="operativo">Operativo</option>
              <option value="fuera_de_servicio">Fuera de servicio</option>
              <option value="baja">Baja</option>
            </select>
          </div>
        </div>
      </form>
    </div>
    
    <div class="modal-footer">
      <button type="submit" form="formEditar" class="submit-btn">💾 Guardar cambios</button>
      <button type="button" class="action-btn" onclick="cerrarModalEditar()">Cancelar</button>
    </div>
  </div>
</div>

<script>
let currentEquipoData = null;

// Detectar scroll y ocultar indicador
document.addEventListener('DOMContentLoaded', function() {
  const scrollContainer = document.getElementById('tableScroll');
  const scrollHint = document.getElementById('scrollHint');
  
  if (scrollContainer && scrollHint) {
    scrollContainer.addEventListener('scroll', function() {
      if (this.scrollTop > 50) {
        scrollHint.classList.add('hidden');
      }
    });
    
    setTimeout(() => {
      scrollHint.classList.add('hidden');
    }, 3000);
    
    if (scrollContainer.scrollHeight <= scrollContainer.clientHeight) {
      scrollHint.classList.add('hidden');
    }
  }
});

function verDetalleEquipo(id, equipo) {
  currentEquipoData = equipo;
  const modal = document.getElementById('modalPreviewEquipo');
  const content = document.getElementById('previewEquipoContent');
  
  document.getElementById('previewEquipoTitle').textContent = `${equipo.codigo} - ${equipo.nombre}`;
  
  const estadoEmoji = {
    'operativo': '✅',
    'fuera_de_servicio': '⚠️',
    'baja': '❌'
  }[equipo.estado] || '❓';
  
  const estadoClass = `status-${equipo.estado}`;
  const estadoLabel = equipo.estado.replace('_', ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
  
  content.innerHTML = `
    <div class="info-grid">
      <div class="info-item">
        <div class="info-label">🔧 Equipo</div>
        <div class="info-value">${equipo.nombre || 'N/A'}</div>
      </div>
      <div class="info-item">
        <div class="info-label">📋 Código</div>
        <div class="info-value">${equipo.codigo || 'N/A'}</div>
      </div>
      <div class="info-item">
        <div class="info-label">📁 Categoría</div>
        <div class="info-value">${equipo.categoria || 'Sin categoría'}</div>
      </div>
      <div class="info-item">
        <div class="info-label">🏢 Marca</div>
        <div class="info-value">${equipo.marca || 'N/A'}</div>
      </div>
      <div class="info-item">
        <div class="info-label">🔢 Modelo</div>
        <div class="info-value">${equipo.modelo || 'N/A'}</div>
      </div>
      <div class="info-item">
        <div class="info-label">🔖 Nro. Serie</div>
        <div class="info-value">${equipo.nro_serie || 'N/A'}</div>
      </div>
      <div class="info-item">
        <div class="info-label">📍 Ubicación</div>
        <div class="info-value">${equipo.ubicacion || 'Sin ubicación'}</div>
      </div>
      <div class="info-item">
        <div class="info-label">📅 Fecha Compra</div>
        <div class="info-value">${equipo.fecha_compra ? new Date(equipo.fecha_compra).toLocaleDateString('es-PA') : 'No registrada'}</div>
      </div>
      <div class="info-item">
        <div class="info-label">🏪 Proveedor</div>
        <div class="info-value">${equipo.proveedor || 'No especificado'}</div>
      </div>
      <div class="info-item">
        <div class="info-label">${estadoEmoji} Estado</div>
        <div class="info-value"><span class="status-badge ${estadoClass}">${estadoLabel}</span></div>
      </div>
    </div>
    
    ${equipo.costo ? `
      <div class="cost-display">
        💰 $${parseFloat(equipo.costo).toLocaleString('es-PA', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
      </div>
    ` : ''}
    
    <div class="preview-section" style="margin-bottom:0">
      <h4>📊 Información Adicional</h4>
      <p><strong>Creado:</strong> ${equipo.created_at ? new Date(equipo.created_at).toLocaleString('es-PA') : 'No disponible'}</p>
      <p><strong>Última actualización:</strong> ${equipo.updated_at ? new Date(equipo.updated_at).toLocaleString('es-PA') : 'No disponible'}</p>
    </div>
  `;
  
  modal.classList.add('active');
}

function cerrarPreviewEquipo() {
  document.getElementById('modalPreviewEquipo').classList.remove('active');
  currentEquipoData = null;
}

function editarDesdePreview() {
  if (currentEquipoData) {
    editarEquipo(currentEquipoData.id, currentEquipoData);
    cerrarPreviewEquipo();
  }
}

function editarEquipo(id, equipo) {
  // Cerrar modal de preview si está abierto
  cerrarPreviewEquipo();
  
  // Llenar el formulario con los datos actuales
  document.getElementById('edit_codigo').value = equipo.codigo || '';
  document.getElementById('edit_nombre').value = equipo.nombre || '';
  document.getElementById('edit_categoria').value = equipo.categoria || '';
  document.getElementById('edit_marca').value = equipo.marca || '';
  document.getElementById('edit_modelo').value = equipo.modelo || '';
  document.getElementById('edit_nro_serie').value = equipo.nro_serie || '';
  document.getElementById('edit_ubicacion').value = equipo.ubicacion || '';
  document.getElementById('edit_fecha_compra').value = equipo.fecha_compra || '';
  document.getElementById('edit_proveedor').value = equipo.proveedor || '';
  document.getElementById('edit_costo').value = equipo.costo || '';
  document.getElementById('edit_estado').value = equipo.estado || 'operativo';
  
  // Configurar el action del formulario
  const form = document.getElementById('formEditar');
  form.action = '<?= ENV_APP['BASE_URL'] ?>/equipos/update/' + id;
  
  // Mostrar el modal
  document.getElementById('modalEditar').classList.add('active');
}

function cerrarModalEditar() {
  document.getElementById('modalEditar').classList.remove('active');
}

// Cerrar modales con ESC
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    cerrarPreviewEquipo();
    cerrarModalEditar();
  }
});

// Cerrar modal al hacer clic fuera
document.getElementById('modalPreviewEquipo').addEventListener('click', function(e) {
  if (e.target === this) {
    cerrarPreviewEquipo();
  }
});

document.getElementById('modalEditar').addEventListener('click', function(e) {
  if (e.target === this) {
    cerrarModalEditar();
  }
});
</script>