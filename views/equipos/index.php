<style>
/* 🎨 PALETA INDUSTRIAL PROFESIONAL */
.equipos-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:14px 18px;
  background:linear-gradient(135deg, #ff6b35, #d85a2a);
  border-radius:12px 12px 0 0;
}

.equipos-header h2{
  margin:0;
  color:white;
  font-size:1.4rem;
  font-weight:700;
  display:flex;
  align-items:center;
  gap:10px;
}

/* 📊 Stats Grid Compacto */
.stats-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit, minmax(140px, 1fr));
  gap:10px;
  padding:14px 18px;
  background:#161b22;
}

.stat-card{
  background:#1c2128;
  border:1px solid #30363d;
  padding:12px;
  border-radius:10px;
  text-align:center;
  transition:all 0.3s;
}

.stat-card:hover{
  border-color:#ff6b35;
  transform:translateY(-2px);
  box-shadow:0 4px 12px rgba(255,107,53,0.3);
}

.stat-value{
  font-size:1.8rem;
  font-weight:800;
  color:#e6edf3;
  margin-bottom:4px;
}

.stat-label{
  font-size:11px;
  color:#7d8590;
  text-transform:uppercase;
  font-weight:600;
  letter-spacing:0.5px;
}

/* 📝 Formulario Compacto */
.form-section{
  padding:16px 18px;
  background:#161b22;
  border-top:1px solid #30363d;
}

.form-section h3{
  margin:0 0 12px 0;
  color:#e6edf3;
  font-size:1.1rem;
  display:flex;
  align-items:center;
  gap:8px;
}

.form-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
  gap:10px;
  margin-bottom:12px;
}

.form-group{
  display:flex;
  flex-direction:column;
  gap:5px;
}

.form-group label{
  font-size:12px;
  color:#7d8590;
  font-weight:600;
  text-transform:uppercase;
  letter-spacing:0.3px;
}

.form-group input,
.form-group select{
  padding:9px 12px;
  background:#0d1117;
  border:1.5px solid #30363d;
  border-radius:8px;
  color:#e6edf3;
  font-size:13px;
  transition:all 0.2s;
}

.form-group input:hover,
.form-group select:hover{
  border-color:#484f58;
}

.form-group input:focus,
.form-group select:focus{
  outline:none;
  border-color:#ff6b35;
  box-shadow:0 0 0 3px rgba(255,107,53,0.1);
  background:#161b22;
}

.submit-btn{
  padding:10px 20px;
  background:linear-gradient(135deg, #ff6b35, #d85a2a);
  border:none;
  border-radius:8px;
  color:white;
  font-weight:700;
  font-size:13px;
  cursor:pointer;
  transition:all 0.3s;
  box-shadow:0 4px 12px rgba(255,107,53,0.4);
}

.submit-btn:hover:not(:disabled){
  transform:translateY(-2px);
  box-shadow:0 6px 18px rgba(255,107,53,0.5);
}

.submit-btn:disabled{
  opacity:0.5;
  cursor:not-allowed;
}

/* 📊 TABLA MEJORADA - SIN SUPERPOSICIÓN */
.table-section{
  padding:16px 18px;
}

.table-section h3{
  margin:0 0 12px 0;
  color:#e6edf3;
  font-size:1.1rem;
}

.table-scroll-container{
  max-height:500px;
  overflow-y:auto;
  overflow-x:auto;
  border:1px solid #30363d;
  border-radius:10px;
  position:relative;
  background:#0d1117;
}

.table-scroll-container::-webkit-scrollbar {
  width:8px;
  height:8px;
}

.table-scroll-container::-webkit-scrollbar-track {
  background:#0d1117;
  border-radius:4px;
}

.table-scroll-container::-webkit-scrollbar-thumb {
  background:#30363d;
  border-radius:4px;
}

.table-scroll-container::-webkit-scrollbar-thumb:hover {
  background:#484f58;
}

.equipos-table{
  width:100%;
  border-collapse:collapse;
  min-width:800px;
}

/* ✅ HEADER STICKY ARREGLADO */
.equipos-table thead{
  background:#1c2128;
  position:sticky;
  top:0;
  z-index:10;
  box-shadow:0 2px 8px rgba(0,0,0,0.4);
}

.equipos-table th{
  padding:12px 14px;
  text-align:left;
  font-weight:700;
  color:#7d8590;
  border-bottom:2px solid #30363d;
  font-size:11px;
  text-transform:uppercase;
  letter-spacing:0.5px;
  white-space:nowrap;
  background:#1c2128;
}

.equipos-table td{
  padding:12px 14px;
  border-bottom:1px solid #21262d;
  color:#e6edf3;
  font-size:13px;
  background:#0d1117;
}

.equipos-table tbody tr{
  transition:all 0.2s;
  cursor:pointer;
}

.equipos-table tbody tr:hover{
  background:#161b22 !important;
  transform:translateX(2px);
}

/* 🏷️ Badges Mejorados */
.status-badge{
  display:inline-block;
  padding:4px 10px;
  border-radius:999px;
  font-size:11px;
  font-weight:700;
  text-transform:uppercase;
  letter-spacing:0.3px;
}

.status-operativo{
  background:rgba(63,185,80,0.15);
  color:#3fb950;
  border:1px solid rgba(63,185,80,0.4);
}

.status-fuera_de_servicio{
  background:rgba(240,136,62,0.15);
  color:#f0883e;
  border:1px solid rgba(240,136,62,0.4);
}

.status-baja{
  background:rgba(248,81,73,0.15);
  color:#f85149;
  border:1px solid rgba(248,81,73,0.4);
}

/* 🔘 Botones de Acción */
.action-btn{
  padding:6px 12px;
  background:#f85149;
  border:none;
  border-radius:6px;
  color:white;
  font-weight:600;
  cursor:pointer;
  transition:all 0.2s;
  font-size:11px;
}

.action-btn:hover{
  transform:translateY(-1px);
  box-shadow:0 4px 12px rgba(248,81,73,0.4);
}

.action-btn.edit{
  background:#f0883e;
}

.action-btn.edit:hover{
  box-shadow:0 4px 12px rgba(240,136,62,0.4);
}

/* 🪟 Modales Mejorados */
.modal {
  display:none;
  position:fixed;
  top:0;
  left:0;
  right:0;
  bottom:0;
  background:rgba(0,0,0,0.85);
  z-index:10000;
  align-items:center;
  justify-content:center;
  animation:fadeIn 0.3s ease;
}

.modal.active {
  display:flex;
}

.modal-content {
  background:#1c2128;
  border:1px solid #30363d;
  border-radius:16px;
  padding:0;
  max-width:700px;
  width:90%;
  max-height:90vh;
  overflow:hidden;
  box-shadow:0 20px 60px rgba(0,0,0,0.6);
  animation:slideUp 0.3s ease;
}

@keyframes fadeIn {
  from {opacity:0}
  to {opacity:1}
}

@keyframes slideUp {
  from {opacity:0;transform:translateY(30px)}
  to {opacity:1;transform:translateY(0)}
}

.modal-header {
  padding:18px 22px;
  background:linear-gradient(135deg,#ff6b35,#d85a2a);
  color:white;
  display:flex;
  align-items:center;
  justify-content:space-between;
}

.modal-header h3 {
  margin:0;
  font-size:1.15rem;
  font-weight:700;
}

.modal-close {
  background:rgba(255,255,255,0.2);
  border:0;
  color:white;
  width:30px;
  height:30px;
  border-radius:50%;
  cursor:pointer;
  font-size:1.2rem;
  display:flex;
  align-items:center;
  justify-content:center;
  transition:all 0.2s;
}

.modal-close:hover {
  background:rgba(255,255,255,0.3);
  transform:rotate(90deg);
}

.modal-body {
  padding:22px;
  max-height:calc(90vh - 170px);
  overflow-y:auto;
  background:#0d1117;
}

.modal-body::-webkit-scrollbar {
  width:6px;
}

.modal-body::-webkit-scrollbar-track {
  background:#0d1117;
  border-radius:3px;
}

.modal-body::-webkit-scrollbar-thumb {
  background:#30363d;
  border-radius:3px;
}

.modal-footer {
  padding:14px 22px;
  background:#161b22;
  border-top:1px solid #30363d;
  display:flex;
  gap:8px;
}

.info-grid {
  display:grid;
  grid-template-columns:repeat(2,1fr);
  gap:12px;
  margin-bottom:18px;
}

.info-item {
  background:#161b22;
  border:1px solid #30363d;
  border-radius:10px;
  padding:10px;
}

.info-label {
  font-size:10px;
  color:#7d8590;
  font-weight:700;
  text-transform:uppercase;
  letter-spacing:0.5px;
  margin-bottom:5px;
}

.info-value {
  font-size:13px;
  color:#e6edf3;
  font-weight:600;
  word-break:break-word;
}

@media(max-width:768px){
  .form-grid{
    grid-template-columns:1fr;
  }
  
  .stats-grid{
    grid-template-columns:repeat(2,1fr);
  }
  
  .info-grid {
    grid-template-columns:1fr;
  }
  
  .modal-content {
    width:95%;
    max-height:95vh;
  }
  
  .table-scroll-container {
    max-height:400px;
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
          <label>Fecha de Entrega</label>
          <input type="date" name="fecha_compra">
        </div>
        
        <div class="form-group">
          <label>Cliente</label>
          <input name="cliente" placeholder="Ej: Edgar">
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
            <label>Fecha de Entrega</label>
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