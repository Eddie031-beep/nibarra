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
  overflow-x:auto;
}

.table-section h3{
  margin:0 0 1rem 0;
  color:var(--text-primary);
}

.equipos-table{
  width:100%;
  border-collapse:collapse;
}

.equipos-table thead{
  background:var(--bg-secondary);
}

.equipos-table th{
  padding:1rem;
  text-align:left;
  font-weight:600;
  color:var(--text-secondary);
  border-bottom:2px solid var(--border-color);
  font-size:0.875rem;
  white-space:nowrap;
}

.equipos-table td{
  padding:1rem;
  border-bottom:1px solid var(--border-color);
  color:var(--text-primary);
}

.equipos-table tbody tr{
  transition:all .2s;
}

.equipos-table tbody tr:hover{
  background:var(--bg-secondary);
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
  background: rgba(0, 0, 0, 0.8);
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
  padding: 2rem;
  max-width: 800px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
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

@media(max-width:768px){
  .form-grid{
    grid-template-columns:1fr;
  }
  
  .stats-grid{
    grid-template-columns:repeat(2, 1fr);
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
    <h3>📋 Listado de equipos</h3>
    
    <table class="equipos-table">
      <thead>
        <tr>
          <th>Código</th>
          <th>Nombre</th>
          <th>Categoría</th>
          <th>Marca</th>
          <th>Modelo</th>
          <th>Serie</th>
          <th>Ubicación</th>
          <th>Compra</th>
          <th>Costo</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($equipos)): ?>
          <tr>
            <td colspan="11" style="text-align:center;padding:2rem;color:var(--text-secondary)">
              No hay equipos registrados aún
            </td>
          </tr>
        <?php else: ?>
          <?php foreach($equipos as $e): ?>
            <tr>
              <td><strong><?= safe($e['codigo']) ?></strong></td>
              <td><?= safe($e['nombre']) ?></td>
              <td><?= safe($e['categoria']) ?></td>
              <td><?= safe($e['marca']) ?></td>
              <td><?= safe($e['modelo']) ?></td>
              <td><?= safe($e['nro_serie']) ?></td>
              <td><?= safe($e['ubicacion']) ?></td>
              <td><?= safe($e['fecha_compra']) ?></td>
              <td><?= $e['costo']!==null ? '$'.number_format($e['costo'],2) : '-' ?></td>
              <td>
                <span class="status-badge status-<?= safe($e['estado']) ?>">
                  <?= ucfirst(str_replace('_', ' ', safe($e['estado']))) ?>
                </span>
              </td>
              <td>
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
                      <button type="submit" class="action-btn">🗑️ Eliminar</button>
                    </form>
                  <?php endif; ?>
                  
                  <?php if(!$canEdit && !$canDelete): ?>
                    <span style="color:var(--text-muted);font-size:0.875rem">Sin permisos</span>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<!-- Modal de Edición -->
<div class="modal" id="modalEditar">
  <div class="modal-content">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
      <h3 style="margin:0;color:var(--text-primary)">✏️ Editar Equipo</h3>
      <button onclick="cerrarModalEditar()" style="background:none;border:none;color:var(--text-secondary);font-size:1.5rem;cursor:pointer;padding:0.5rem">✕</button>
    </div>
    
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
      
      <div style="display:flex;gap:0.75rem;margin-top:1.5rem">
        <button type="submit" class="submit-btn" style="flex:1">💾 Guardar cambios</button>
        <button type="button" onclick="cerrarModalEditar()" style="flex:1;padding:0.75rem;background:var(--bg-secondary);border:1px solid var(--border-color);border-radius:0.5rem;color:var(--text-primary);cursor:pointer;font-weight:600">
          Cancelar
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function editarEquipo(id, equipo) {
  // Llenar el formulario con los datos del equipo
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
  
  // Cambiar la acción del formulario
  document.getElementById('formEditar').action = '<?= ENV_APP['BASE_URL'] ?>/equipos/update/' + id;
  
  // Mostrar modal
  document.getElementById('modalEditar').classList.add('active');
}

function cerrarModalEditar() {
  document.getElementById('modalEditar').classList.remove('active');
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalEditar')?.addEventListener('click', function(e) {
  if (e.target === this) {
    cerrarModalEditar();
  }
});

// ESC para cerrar
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    cerrarModalEditar();
  }
});
</script>