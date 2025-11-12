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
}

.factura-body{
  padding:2rem;
  background:var(--bg-card);
}

.info-section{
  margin-bottom:2rem;
}

.info-section h3{
  margin:0 0 1rem 0;
  color:var(--text-primary);
  font-size:1.125rem;
  border-bottom:2px solid var(--border-color);
  padding-bottom:0.5rem;
}

.info-row{
  display:grid;
  grid-template-columns:150px 1fr;
  padding:0.75rem 0;
  border-bottom:1px solid var(--border-color);
}

.info-label{
  font-weight:600;
  color:var(--text-secondary);
}

.info-value{
  color:var(--text-primary);
}

.items-table{
  width:100%;
  border-collapse:collapse;
  margin:1rem 0;
}

.items-table th{
  background:var(--bg-secondary);
  padding:0.75rem;
  text-align:left;
  font-size:0.875rem;
  color:var(--text-secondary);
  border-bottom:2px solid var(--border-color);
}

.items-table td{
  padding:0.75rem;
  border-bottom:1px solid var(--border-color);
}

.totales{
  margin-top:2rem;
  padding:1.5rem;
  background:var(--bg-secondary);
  border-radius:0.75rem;
}

.total-row{
  display:flex;
  justify-content:space-between;
  padding:0.5rem 0;
}

.total-row.final{
  font-size:1.5rem;
  font-weight:800;
  color:var(--success);
  border-top:2px solid var(--border-color);
  padding-top:1rem;
  margin-top:0.5rem;
}

@media print {
  .factura-actions, .no-print {
    display: none !important;
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
        <a href="<?= ENV_APP['BASE_URL'] ?>/facturas/pdf/<?= $factura['id'] ?>" 
           class="action-btn-factura"
           style="background:white;color:#059669;font-weight:700"
           target="_blank">
          📄 Descargar PDF
        </a>
        <a href="<?= ENV_APP['BASE_URL'] ?>/facturas" 
           class="action-btn-factura"
           style="background:rgba(255,255,255,.2);border-color:rgba(255,255,255,.4)">
          ← Volver
        </a>
      </div>
    </div>
    
    <div class="factura-body">
<!-- Información del Mantenimiento -->
      <div class="info-section">
        <h3>📋 Información del Servicio Completado</h3>
        <div style="padding:12px;background:rgba(16,185,129,.05);border:1px solid rgba(16,185,129,.2);border-radius:8px;margin-bottom:12px;font-size:13px;color:#94a3b8">
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
          <p style="color:var(--text-secondary);line-height:1.6">
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
                    style="background:var(--success);border-color:var(--success);color:white"
                    onclick="cambiarEstado(<?= $factura['id'] ?>, 'pagada')">
              ✓ Marcar como Pagada
            </button>
            <button class="action-btn-factura" 
                    style="background:var(--danger);border-color:var(--danger);color:white"
                    onclick="cambiarEstado(<?= $factura['id'] ?>, 'cancelada')">
              ✕ Cancelar Factura
            </button>
          <?php endif; ?>
          
          <?php if($factura['estado'] === 'pagada'): ?>
            <button class="action-btn-factura" 
                    style="background:var(--warning);border-color:var(--warning);color:white"
                    onclick="cambiarEstado(<?= $factura['id'] ?>, 'pendiente')">
              ⟲ Marcar como Pendiente
            </button>
          <?php endif; ?>
          
          <button class="action-btn-factura" onclick="window.print()">
            🖨️ Imprimir
          </button>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
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
</script>