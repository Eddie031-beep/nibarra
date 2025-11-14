<style>
.factura-detalle{
  max-width:900px;
  margin:0 auto;
}

.factura-header-detail{
  background:linear-gradient(135deg, #10b981, #059669);
  color:white;
  padding:2rem;
  border-radius:1rem 1rem 0 0;
  display:flex;
  justify-content:space-between;
  align-items:start;
  box-shadow:0 4px 12px rgba(16,185,129,0.3);
}

.factura-numero{
  font-size:2rem;
  font-weight:800;
  margin-bottom:0.5rem;
}

.factura-fecha{
  font-size:0.875rem;
  opacity:0.9;
}

.factura-actions{
  display:flex;
  gap:0.5rem;
  flex-wrap:wrap;
}

.factura-body{
  padding:2rem;
  background:#0f172a;
  border:1px solid #1e293b;
  border-radius:0 0 1rem 1rem;
}

.info-section{
  margin-bottom:2rem;
}

.info-section h3{
  margin:0 0 1rem 0;
  color:#e5e7eb;
  font-size:1.125rem;
  border-bottom:2px solid #334155;
  padding-bottom:0.5rem;
}

.info-row{
  display:grid;
  grid-template-columns:150px 1fr;
  padding:0.75rem 0;
  border-bottom:1px solid #1e293b;
}

.info-label{
  font-weight:600;
  color:#94a3b8;
}

.info-value{
  color:#cbd5e1;
}

.items-table{
  width:100%;
  border-collapse:collapse;
  margin:1rem 0;
}

.items-table th{
  background:#1e293b;
  padding:0.75rem;
  text-align:left;
  font-size:0.875rem;
  color:#94a3b8;
  border-bottom:2px solid #334155;
}

.items-table td{
  padding:0.75rem;
  border-bottom:1px solid #1e293b;
  color:#cbd5e1;
}

.totales{
  margin-top:2rem;
  padding:1.5rem;
  background:linear-gradient(135deg, #1e293b 0%, #111827 100%);
  border-radius:0.75rem;
  border:1px solid #334155;
}

.total-row{
  display:flex;
  justify-content:space-between;
  padding:0.5rem 0;
  color:#cbd5e1;
}

.total-row.final{
  font-size:1.5rem;
  font-weight:800;
  color:#10b981;
  border-top:2px solid #334155;
  padding-top:1rem;
  margin-top:0.5rem;
}

.action-btn-factura{
  padding:0.75rem 1.5rem;
  border-radius:0.5rem;
  border:1px solid;
  font-weight:700;
  cursor:pointer;
  transition:all .2s;
  text-decoration:none;
  display:inline-flex;
  align-items:center;
  gap:0.5rem;
}

.action-btn-factura:hover{
  transform:translateY(-2px);
  box-shadow:0 4px 12px rgba(0,0,0,0.3);
}

/* Modal de edición mejorado */
.modal{
  display:none;
  position:fixed;
  top:0;
  left:0;
  right:0;
  bottom:0;
  background:rgba(0,0,0,0.92);
  z-index:10000;
  align-items:center;
  justify-content:center;
  animation:fadeIn .3s ease;
}

.modal.active{
  display:flex;
}

.modal-content{
  background:linear-gradient(135deg, #1a1f35 0%, #0f172a 100%);
  border:1px solid #334155;
  border-radius:1rem;
  max-width:800px;
  width:90%;
  max-height:90vh;
  overflow:hidden;
  box-shadow:0 20px 60px rgba(0,0,0,.5);
}

.modal-header{
  padding:20px 24px;
  background:linear-gradient(135deg, #10b981, #059669);
  color:white;
  display:flex;
  align-items:center;
  justify-content:space-between;
}

.modal-header h3{
  margin:0;
  font-size:1.25rem;
  font-weight:700;
}

.modal-close{
  background:rgba(255,255,255,.2);
  border:0;
  color:white;
  width:32px;
  height:32px;
  border-radius:50%;
  cursor:pointer;
  font-size:1.25rem;
  display:flex;
  align-items:center;
  justify-content:center;
  transition:all .2s;
}

.modal-close:hover{
  background:rgba(255,255,255,.3);
  transform:rotate(90deg);
}

.modal-body{
  padding:24px;
  max-height:calc(90vh - 180px);
  overflow-y:auto;
  background:#0f172a;
}

.modal-body::-webkit-scrollbar{
  width:8px;
}

.modal-body::-webkit-scrollbar-track{
  background:#0b1220;
  border-radius:4px;
}

.modal-body::-webkit-scrollbar-thumb{
  background:#334155;
  border-radius:4px;
}

.modal-footer{
  padding:16px 24px;
  background:#0b1220;
  border-top:1px solid #334155;
  display:flex;
  gap:8px;
}

.editor-section{
  background:linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
  border:1px solid #334155;
  border-radius:0.75rem;
  padding:1rem;
  margin-bottom:1rem;
  box-shadow:0 4px 12px rgba(16,185,129,0.1);
}

.editor-section h4{
  margin:0 0 1rem 0;
  color:#10b981;
  font-size:1rem;
  display:flex;
  align-items:center;
  gap:0.5rem;
}

.items-editor{
  display:flex;
  flex-direction:column;
  gap:0.75rem;
}

.item-row{
  display:grid;
  grid-template-columns:2fr 0.7fr 1fr auto;
  gap:0.5rem;
  align-items:center;
  padding:0.75rem;
  background:linear-gradient(135deg, #1a1f35 0%, #111827 100%);
  border:1px solid #374151;
  border-radius:0.5rem;
}

.item-row input{
  padding:0.5rem;
  border:1px solid #374151;
  background:#0b1220;
  color:#e5e7eb;
  border-radius:0.375rem;
  font-size:0.875rem;
}

.item-row input:focus{
  outline:none;
  border-color:#10b981;
  box-shadow:0 0 0 3px rgba(16,185,129,0.1);
}

.item-row .btn-delete{
  padding:0.5rem;
  background:#7f1d1d;
  border:none;
  color:white;
  border-radius:0.375rem;
  cursor:pointer;
  transition:all .2s;
}

.item-row .btn-delete:hover{
  background:#991b1b;
}

.btn-add-item{
  padding:0.75rem 1rem;
  background:#10b981;
  border:none;
  color:white;
  border-radius:0.5rem;
  cursor:pointer;
  font-weight:600;
  transition:all .2s;
}

.btn-add-item:hover{
  background:#059669;
  transform:translateY(-2px);
  box-shadow:0 4px 12px rgba(16,185,129,0.3);
}

.totales-preview{
  background:linear-gradient(135deg, #1e293b 0%, #111827 100%);
  border:1px solid #334155;
  border-radius:0.75rem;
  padding:1rem;
}

.totales-preview .total-row{
  display:flex;
  justify-content:space-between;
  padding:0.5rem 0;
  font-size:0.9375rem;
  color:#cbd5e1;
}

.totales-preview .total-row.final{
  font-size:1.25rem;
  font-weight:800;
  color:#10b981;
  border-top:2px solid #334155;
  margin-top:0.5rem;
  padding-top:1rem;
}

#costoReal{
  background:#111827 !important;
  border:1px solid #10b981 !important;
  color:#10b981 !important;
  font-weight:700 !important;
}

#costoReal:focus{
  box-shadow:0 0 0 4px rgba(16,185,129,0.2) !important;
}

@media print {
  .factura-actions, .no-print {
    display: none !important;
  }
}

@media(max-width:768px){
  .item-row{
    grid-template-columns:1fr;
  }
  
  .factura-actions{
    flex-direction:column;
  }
  
  .action-btn-factura{
    width:100%;
    justify-content:center;
  }
}
</style>

<div class="factura-detalle">
  <section class="card">
    <div class="factura-header-detail">
      <div>
        <div class="factura-numero">🧾 <?= safe($factura['numero_factura']) ?></div>
        <div class="factura-fecha">
          Emitida: <?= date('d/m/Y H:i', strtotime($factura['fecha_emision'])) ?>
        </div>
      </div>
      
      <div class="factura-actions no-print">
        <?php 
          $user = Auth::user();
          $canEdit = $user && in_array($user['rol'], ['admin', 'tecnico']);
        ?>
        
        <?php if($canEdit): ?>
          <button class="action-btn-factura" 
                  onclick="abrirEditor()"
                  style="background:#f59e0b;border-color:#f59e0b;color:white">
            ✏️ Editar Factura
          </button>
        <?php endif; ?>
        
        <a href="<?= ENV_APP['BASE_URL'] ?>/facturas/pdf/<?= $factura['id'] ?>" 
           class="action-btn-factura"
           style="background:white;color:#059669;border-color:#059669"
           target="_blank">
          📄 Descargar PDF
        </a>
        
        <a href="<?= ENV_APP['BASE_URL'] ?>/facturas" 
           class="action-btn-factura"
           style="background:rgba(255,255,255,.2);border-color:rgba(255,255,255,.4);color:white">
          ← Volver
        </a>
      </div>
    </div>
    
    <div class="factura-body">
      <!-- Información del Mantenimiento -->
      <div class="info-section">
        <h3>📋 Información del Servicio Completado</h3>
        <div style="padding:12px;background:rgba(16,185,129,.05);border:1px solid rgba(16,185,129,.2);border-radius:8px;margin-bottom:12px;font-size:13px;color:#6ee7b7">
          <strong>ℹ️ Mantenimiento completado:</strong> Este servicio fue completado exitosamente y se encuentra archivado. 
          Los mantenimientos completados y facturados se ocultan automáticamente del tablero Kanban.
        </div>
        <div class="info-row">
          <div class="info-label">Mantenimiento:</div>
          <div class="info-value"><strong><?= safe($factura['mantenimiento_titulo']) ?></strong></div>
        </div>
        <div class="info-row">
          <div class="info-label">Tipo:</div>
          <div class="info-value"><?= ucfirst(safe($factura['mantenimiento_tipo'])) ?></div>
        </div>
        <div class="info-row">
          <div class="info-label">Equipo:</div>
          <div class="info-value"><?= safe($factura['equipo_nombre']) ?> (<?= safe($factura['equipo_codigo']) ?>)</div>
        </div>
        <div class="info-row">
          <div class="info-label">Costo Real:</div>
          <div class="info-value">
            <strong>$<?= number_format($factura['mantenimiento_costo_real'] ?? $factura['mantenimiento_costo_estimado'] ?? 0, 2) ?></strong>
            <?php if($factura['mantenimiento_costo_real'] === null): ?>
              <span style="font-size:0.75rem;color:#94a3b8">(estimado)</span>
            <?php endif; ?>
          </div>
        </div>
        <div class="info-row">
          <div class="info-label">Estado:</div>
          <div class="info-value">
            <span class="estado-factura estado-<?= $factura['estado'] ?>">
              <?= ucfirst($factura['estado']) ?>
            </span>
          </div>
        </div>
      </div>
      
      <!-- Items de la Factura -->
      <div class="info-section">
        <h3>📝 Detalle de Servicios</h3>
        <table class="items-table">
          <thead>
            <tr>
              <th>Descripción</th>
              <th style="text-align:center">Cantidad</th>
              <th style="text-align:right">Precio Unit.</th>
              <th style="text-align:right">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($factura['items'] as $item): ?>
              <tr>
                <td><?= safe($item['descripcion']) ?></td>
                <td style="text-align:center"><?= number_format($item['cantidad'], 2) ?></td>
                <td style="text-align:right">$<?= number_format($item['precio_unitario'], 2) ?></td>
                <td style="text-align:right"><strong>$<?= number_format($item['subtotal'], 2) ?></strong></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      
      <!-- Totales -->
      <div class="totales">
        <div class="total-row">
          <span>Subtotal:</span>
          <span><strong>$<?= number_format($factura['subtotal'], 2) ?></strong></span>
        </div>
        <div class="total-row">
          <span>ITBMS (7%):</span>
          <span><strong>$<?= number_format($factura['impuesto'], 2) ?></strong></span>
        </div>
        <div class="total-row final">
          <span>TOTAL:</span>
          <span>$<?= number_format($factura['total'], 2) ?></span>
        </div>
      </div>
      
      <!-- Notas -->
      <?php if($factura['notas']): ?>
        <div class="info-section">
          <h3>📌 Notas</h3>
          <p style="color:#94a3b8;line-height:1.6">
            <?= nl2br(safe($factura['notas'])) ?>
          </p>
        </div>
      <?php endif; ?>
      
      <!-- Acciones -->
      <div class="info-section no-print">
        <h3>⚡ Acciones</h3>
        <div style="display:flex;gap:1rem;flex-wrap:wrap">
          <?php if($factura['estado'] === 'pendiente'): ?>
            <button class="action-btn-factura" 
                    style="background:#10b981;border-color:#10b981;color:white"
                    onclick="cambiarEstado(<?= $factura['id'] ?>, 'pagada')">
              ✓ Marcar como Pagada
            </button>
            <button class="action-btn-factura" 
                    style="background:#ef4444;border-color:#ef4444;color:white"
                    onclick="cambiarEstado(<?= $factura['id'] ?>, 'cancelada')">
              ✕ Cancelar Factura
            </button>
          <?php endif; ?>
          
          <?php if($factura['estado'] === 'pagada'): ?>
            <button class="action-btn-factura" 
                    style="background:#f59e0b;border-color:#f59e0b;color:white"
                    onclick="cambiarEstado(<?= $factura['id'] ?>, 'pendiente')">
              ⟲ Marcar como Pendiente
            </button>
          <?php endif; ?>
          
          <button class="action-btn-factura" 
                  style="background:#374151;border-color:#374151;color:white"
                  onclick="window.print()">
            🖨️ Imprimir
          </button>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal de Edición -->
<div class="modal" id="modalEditor">
  <div class="modal-content">
    <div class="modal-header">
      <h3>✏️ Editar Factura <?= safe($factura['numero_factura']) ?></h3>
      <button class="modal-close" onclick="cerrarEditor()">✕</button>
    </div>
    
    <div class="modal-body">
      <div style="padding:12px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);border-radius:8px;margin-bottom:16px;color:#fcd34d;font-size:13px">
        <strong>⚠️ Importante:</strong> Los cambios modificarán el costo real del mantenimiento en la base de datos.
      </div>
      
      <!-- Editor de Costo Real -->
      <div class="editor-section">
        <h4>💰 Costo Real del Mantenimiento</h4>
        <input type="number" 
               step="0.01" 
               id="costoReal" 
               value="<?= $factura['mantenimiento_costo_real'] ?? $factura['mantenimiento_costo_estimado'] ?? 0 ?>"
               placeholder="0.00"
               style="width:100%;padding:12px;border-radius:0.5rem;font-size:1.125rem"
               oninput="actualizarPrimerItem()">
        <p style="font-size:0.75rem;color:#94a3b8;margin-top:0.5rem">
          Este valor se usará como base para el primer item de la factura
        </p>
      </div>
      
      <!-- Editor de Items -->
      <div class="editor-section">
        <h4>📝 Items de la Factura</h4>
        <div class="items-editor" id="itemsEditor">
          <!-- Se llenará dinámicamente -->
        </div>
        <button class="btn-add-item" onclick="agregarItem()" style="margin-top:1rem;width:100%">
          ➕ Agregar Item
        </button>
      </div>
      
      <!-- Vista previa de totales -->
      <div class="totales-preview">
        <h4 style="margin:0 0 1rem 0;color:#10b981">💵 Vista Previa</h4>
        <div class="total-row">
          <span>Subtotal:</span>
          <span id="previewSubtotal">$0.00</span>
        </div>
        <div class="total-row">
          <span>ITBMS (7%):</span>
          <span id="previewImpuesto">$0.00</span>
        </div>
        <div class="total-row final">
          <span>TOTAL:</span>
          <span id="previewTotal">$0.00</span>
        </div>
      </div>
    </div>
    
    <div class="modal-footer">
      <button type="button" 
              class="action-btn-factura" 
              onclick="guardarFactura()"
              style="background:#10b981;border-color:#10b981;color:white;flex:1">
        💾 Guardar Cambios
      </button>
      <button type="button" 
              class="action-btn-factura" 
              onclick="cerrarEditor()"
              style="background:#374151;border-color:#374151;color:white">
        Cancelar
      </button>
    </div>
  </div>
</div>

<script>
const facturaId = <?= $factura['id'] ?>;
let items = <?= json_encode($factura['items']) ?>;

function abrirEditor() {
  document.getElementById('modalEditor').classList.add('active');
  renderizarItems();
  calcularTotales();
}

function cerrarEditor() {
  document.getElementById('modalEditor').classList.remove('active');
}

function renderizarItems() {
  const container = document.getElementById('itemsEditor');
  container.innerHTML = '';
  
  items.forEach((item, index) => {
    const row = document.createElement('div');
    row.className = 'item-row';
    row.innerHTML = `
      <input type="text" 
             placeholder="Descripción" 
             value="${item.descripcion || ''}"
             onchange="actualizarItem(${index}, 'descripcion', this.value)">
      
      <input type="number" 
             step="0.01" 
             placeholder="Cant." 
             value="${item.cantidad || 1}"
             onchange="actualizarItem(${index}, 'cantidad', this.value)">
      
      <input type="number" 
             step="0.01" 
             placeholder="Precio" 
             value="${item.precio_unitario || 0}"
             onchange="actualizarItem(${index}, 'precio_unitario', this.value)">
      
      <button class="btn-delete" onclick="eliminarItem(${index})" ${items.length === 1 ? 'disabled' : ''}>
        🗑️
      </button>
    `;
    container.appendChild(row);
  });
}

function actualizarPrimerItem() {
  const costoReal = parseFloat(document.getElementById('costoReal').value) || 0;
  if (items.length > 0) {
    items[0].precio_unitario = costoReal;
    renderizarItems();
    calcularTotales();
  }
}

function actualizarItem(index, campo, valor) {
  if (campo === 'cantidad' || campo === 'precio_unitario') {
    items[index][campo] = parseFloat(valor) || 0;
  } else {
    items[index][campo] = valor;
  }
  calcularTotales();
}

function agregarItem() {
  items.push({
    descripcion: '',
    cantidad: 1,
    precio_unitario: 0
  });
  renderizarItems();
  calcularTotales();
}

function eliminarItem(index) {
  if (items.length > 1) {
    items.splice(index, 1);
    renderizarItems();
    calcularTotales();
  }
}

function calcularTotales() {
  let subtotal = 0;
  
  items.forEach(item => {
    const cantidad = parseFloat(item.cantidad) || 0;
    const precio = parseFloat(item.precio_unitario) || 0;
    subtotal += cantidad * precio;
  });
  
  const impuesto = Math.round(subtotal * 0.07 * 100) / 100;
  const total = subtotal + impuesto;
  
  document.getElementById('previewSubtotal').textContent = `$${subtotal.toFixed(2)}`;
  document.getElementById('previewImpuesto').textContent = `$${impuesto.toFixed(2)}`;
  document.getElementById('previewTotal').textContent = `$${total.toFixed(2)}`;
}

async function guardarFactura() {
  // Validar items
  for (let i = 0; i < items.length; i++) {
    if (!items[i].descripcion || items[i].descripcion.trim() === '') {
      alert(`⚠️ El item ${i + 1} necesita una descripción`);
      return;
    }
  }
  
  const costoReal = parseFloat(document.getElementById('costoReal').value) || 0;
  
  if (!confirm('¿Guardar los cambios en la factura?\n\nEsto actualizará:\n• Items de la factura\n• Totales\n• Costo real del mantenimiento')) {
    return;
  }
  
  try {
    const response = await fetch('<?= ENV_APP['BASE_URL'] ?>/facturas/actualizar', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        factura_id: facturaId,
        items: JSON.stringify(items),
        costo_real: costoReal
      })
    });
    
    const data = await response.json();
    
    if (data.ok) {
      alert(`✅ Factura actualizada correctamente\n\nNuevo total: $${data.total.toFixed(2)}`);
      location.reload();
    } else {
      alert(`❌ Error: ${data.error || 'No se pudo actualizar la factura'}`);
    }
  } catch (error) {
    console.error('Error:', error);
    alert('❌ Error de conexión. Intenta de nuevo.');
  }
}

async function cambiarEstado(id, estado) {
  const mensajes = {
    'pagada': '¿Marcar esta factura como pagada?',
    'cancelada': '¿Cancelar esta factura? Esta acción no se puede deshacer.',
    'pendiente': '¿Marcar esta factura como pendiente nuevamente?'
  };
  
  if (!confirm(mensajes[estado])) return;
  
  try {
    const r = await fetch('<?= ENV_APP['BASE_URL'] ?>/facturas/actualizar-estado', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `factura_id=${id}&estado=${estado}`
    });
    
    const data = await r.json();
    
    if (data.ok) {
      location.reload();
    } else {
      alert('Error: ' + (data.error || 'No se pudo actualizar'));
    }
  } catch (error) {
    alert('Error de conexión');
  }
}

// Cerrar modal con ESC
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') cerrarEditor();
});

// Cerrar al hacer clic fuera
document.getElementById('modalEditor').addEventListener('click', (e) => {
  if (e.target.id === 'modalEditor') cerrarEditor();
});
</script>