<style>
.facturas-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:1.5rem;
  background:linear-gradient(135deg, #10b981, #059669);
  border-radius:1rem 1rem 0 0;
}

.facturas-header h2{
  margin:0;
  color:white;
  font-size:1.75rem;
  font-weight:700;
  display:flex;
  align-items:center;
  gap:0.75rem;
}

.stats-facturas{
  display:grid;
  grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));
  gap:1rem;
  padding:1.5rem;
  background:var(--bg-secondary);
}

.stat-factura{
  background:var(--bg-card);
  border:1px solid var(--border-color);
  padding:1rem;
  border-radius:0.75rem;
  text-align:center;
}

.stat-factura-value{
  font-size:2rem;
  font-weight:700;
  margin-bottom:0.25rem;
}

.stat-factura-label{
  font-size:0.875rem;
  color:var(--text-secondary);
}

.facturas-table-container{
  padding:1.5rem;
}

.facturas-table{
  width:100%;
  border-collapse:collapse;
}

.facturas-table thead{
  background:var(--bg-secondary);
}

.facturas-table th{
  padding:1rem;
  text-align:left;
  font-weight:600;
  color:var(--text-secondary);
  border-bottom:2px solid var(--border-color);
  font-size:0.875rem;
}

.facturas-table td{
  padding:1rem;
  border-bottom:1px solid var(--border-color);
  color:var(--text-primary);
}

.facturas-table tbody tr{
  transition:all .2s;
  cursor:pointer;
}

.facturas-table tbody tr:hover{
  background:var(--bg-secondary);
}

.estado-factura{
  display:inline-block;
  padding:0.375rem 0.75rem;
  border-radius:2rem;
  font-size:0.75rem;
  font-weight:600;
}

.estado-pendiente{
  background:rgba(245,158,11,.1);
  color:#fcd34d;
  border:1px solid rgba(245,158,11,.3);
}

.estado-pagada{
  background:rgba(16,185,129,.1);
  color:#6ee7b7;
  border:1px solid rgba(16,185,129,.3);
}

.estado-cancelada{
  background:rgba(239,68,68,.1);
  color:#fca5a5;
  border:1px solid rgba(239,68,68,.3);
}

.action-btn-factura{
  padding:0.5rem 1rem;
  background:var(--bg-hover);
  border:1px solid var(--border-accent);
  border-radius:0.5rem;
  color:var(--text-primary);
  font-weight:600;
  cursor:pointer;
  transition:all .2s;
  font-size:0.875rem;
  text-decoration:none;
  display:inline-block;
}

.action-btn-factura:hover{
  background:var(--primary);
  border-color:var(--primary);
  color:white;
  transform:translateY(-1px);
}

.empty-state{
  text-align:center;
  padding:3rem;
  color:var(--text-muted);
}

.empty-icon{
  font-size:4rem;
  margin-bottom:1rem;
  opacity:0.5;
}

@media(max-width:768px){
  .facturas-table{
    font-size:0.8125rem;
  }
  
  .facturas-table th,
  .facturas-table td{
    padding:0.75rem 0.5rem;
  }
}
</style>

<section class="card">
  <div class="facturas-header">
    <h2>🧾 Facturas de Mantenimiento</h2>
  </div>
  
  <!-- Mensaje informativo -->
  <div style="padding:16px;background:#0b1220;border-bottom:1px solid var(--border-color)">
    <div style="padding:12px;background:rgba(59,130,246,.1);border:1px solid rgba(59,130,246,.3);border-radius:10px;font-size:13px;color:#93c5fd">
      <strong>💡 Información:</strong> Aquí se registran automáticamente las facturas de todos los mantenimientos completados. 
      Los mantenimientos facturados se ocultan del <a href="<?= ENV_APP['BASE_URL'] ?>/mantenimiento"style="color:#93c5fd;text-decoration:underline;font-weight:600">tablero Kanban</a> para mantenerlo organizado.
</div>
  </div>
  
  <?php
    $total = count($facturas);
    $pendientes = count(array_filter($facturas, fn($f) => $f['estado'] === 'pendiente'));
    $pagadas = count(array_filter($facturas, fn($f) => $f['estado'] === 'pagada'));
    $monto_total = array_sum(array_column($facturas, 'total'));
    $monto_pendiente = array_sum(array_map(fn($f) => $f['estado'] === 'pendiente' ? $f['total'] : 0, $facturas));
  ?>
  
  <div class="stats-facturas">
    <div class="stat-factura">
      <div class="stat-factura-value"><?= $total ?></div>
      <div class="stat-factura-label">Total Facturas</div>
    </div>
    <div class="stat-factura">
      <div class="stat-factura-value" style="color:var(--warning)"><?= $pendientes ?></div>
      <div class="stat-factura-label">Pendientes</div>
    </div>
    <div class="stat-factura">
      <div class="stat-factura-value" style="color:var(--success)"><?= $pagadas ?></div>
      <div class="stat-factura-label">Pagadas</div>
    </div>
    <div class="stat-factura">
      <div class="stat-factura-value" style="color:var(--primary)">$<?= number_format($monto_total, 2) ?></div>
      <div class="stat-factura-label">Monto Total</div>
    </div>
    <div class="stat-factura">
      <div class="stat-factura-value" style="color:var(--warning)">$<?= number_format($monto_pendiente, 2) ?></div>
      <div class="stat-factura-label">Por Cobrar</div>
    </div>
  </div>
  
  <div class="facturas-table-container">
    <?php if(empty($facturas)): ?>
      <div class="empty-state">
        <div class="empty-icon">🧾</div>
        <h3>No hay facturas generadas</h3>
        <p>Las facturas se generan automáticamente al completar mantenimientos</p>
      </div>
    <?php else: ?>
      <table class="facturas-table">
        <thead>
          <tr>
            <th>Número</th>
            <th>Mantenimiento</th>
            <th>Equipo</th>
            <th>Fecha</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($facturas as $f): ?>
            <tr onclick="window.location='<?= ENV_APP['BASE_URL'] ?>/facturas/ver/<?= $f['id'] ?>'">
              <td><strong><?= safe($f['numero_factura']) ?></strong></td>
              <td><?= safe($f['mantenimiento_titulo']) ?></td>
              <td><?= safe($f['equipo_nombre']) ?></td>
              <td><?= date('d/m/Y', strtotime($f['fecha_emision'])) ?></td>
              <td><strong>$<?= number_format($f['total'], 2) ?></strong></td>
              <td>
                <span class="estado-factura estado-<?= $f['estado'] ?>">
                  <?= ucfirst($f['estado']) ?>
                </span>
              </td>
              <td onclick="event.stopPropagation()">
                <div style="display:flex;gap:0.5rem;flex-wrap:wrap">
                  <a href="<?= ENV_APP['BASE_URL'] ?>/facturas/ver/<?= $f['id'] ?>" 
                     class="action-btn-factura">
                    👁️ Ver
                  </a>
                  <a href="<?= ENV_APP['BASE_URL'] ?>/facturas/pdf/<?= $f['id'] ?>" 
                     class="action-btn-factura"
                     target="_blank">
                    📄 PDF
                  </a>
                  <?php if($f['estado'] === 'pendiente'): ?>
                    <button class="action-btn-factura" 
                            style="background:var(--success);border-color:var(--success);color:white"
                            onclick="marcarPagada(<?= $f['id'] ?>)">
                      ✓ Pagada
                    </button>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</section>

<script>
async function marcarPagada(id) {
  if (!confirm('¿Marcar esta factura como pagada?')) return;
  
  try {
    const r = await fetch('<?= ENV_APP['BASE_URL'] ?>/facturas/actualizar-estado', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `factura_id=${id}&estado=pagada`
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