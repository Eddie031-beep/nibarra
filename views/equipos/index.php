<section class="card" style="padding:16px">
  <div style="display:flex;align-items:center;justify-content:space-between;gap:10px">
    <h2 style="margin:0">Equipos</h2>
    <div style="display:flex;align-items:center;gap:10px">
      <!-- Estado en vivo de la réplica -->
      <span id="replicaStatus" class="tag" style="display:inline-block;padding:4px 10px;border-radius:999px;border:1px solid #374151">
        Checando réplica…
      </span>
      <!-- Botón para abrir la página de salud -->
      <a href="<?= ENV_APP['BASE_URL'] ?>/health/replica" target="_blank" class="tag" style="display:inline-block;padding:4px 10px;border-radius:999px;border:1px solid #374151">
        Ver réplica
      </a>
    </div>
  </div>

  <style>
    .grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:8px}
    .grid label{font-size:12px;color:#94a3b8}
    .grid input, .grid select{padding:8px;border-radius:8px;border:1px solid #243044;background:#0b1220;color:#e5e7eb}
    .primary{background:#4f46e5;border:0;color:white;border-radius:10px;padding:10px;font-weight:700}
    table{width:100%;border-collapse:collapse;margin-top:12px}
    th,td{padding:8px;border-bottom:1px solid #1e293b;text-align:left}
    th{color:#94a3b8;font-weight:600}
    .actions form{display:inline}
    .tag{font-size:12px;padding:2px 6px;border-radius:999px;border:1px solid #374151}
    .pill-ok{background:#0f2a18;color:#a7f3d0;border-color:#14532d}
    .pill-bad{background:#331f2b;color:#fecaca;border-color:#7f1d1d}
  </style>

  <form method="post" action="<?= ENV_APP['BASE_URL'] ?>/equipos/store" style="margin-top:12px">
    <h3>Nuevo equipo</h3>
    <div class="grid">
      <div><label>Código</label><input name="codigo" required></div>
      <div><label>Nombre</label><input name="nombre" required></div>
      <div><label>Categoría</label><input name="categoria"></div>
      <div><label>Marca</label><input name="marca"></div>
      <div><label>Modelo</label><input name="modelo"></div>
      <div><label>Nro. Serie</label><input name="nro_serie"></div>
      <div><label>Ubicación</label><input name="ubicacion"></div>
      <div><label>Fecha Compra</label><input type="date" name="fecha_compra"></div>
      <div><label>Proveedor</label><input name="proveedor"></div>
      <div><label>Costo</label><input type="number" step="0.01" name="costo"></div>
      <div>
        <label>Estado</label>
        <select name="estado">
          <option value="operativo">operativo</option>
          <option value="fuera_de_servicio">fuera_de_servicio</option>
          <option value="baja">baja</option>
        </select>
      </div>
    </div>
    <button class="primary" style="margin-top:10px">Guardar</button>
  </form>

  <h3 style="margin:14px 0 6px 0">Listado</h3>
  <table>
    <thead><tr>
      <th>Código</th><th>Nombre</th><th>Cat.</th><th>Marca</th><th>Modelo</th><th>Serie</th><th>Ubicación</th><th>Compra</th><th>Costo</th><th>Estado</th><th>Acciones</th>
    </tr></thead>
    <tbody>
      <?php foreach($equipos as $e): ?>
        <tr>
          <td><?= safe($e['codigo']) ?></td>
          <td><?= safe($e['nombre']) ?></td>
          <td><?= safe($e['categoria']) ?></td>
          <td><?= safe($e['marca']) ?></td>
          <td><?= safe($e['modelo']) ?></td>
          <td><?= safe($e['nro_serie']) ?></td>
          <td><?= safe($e['ubicacion']) ?></td>
          <td><?= safe($e['fecha_compra']) ?></td>
          <td><?= $e['costo']!==null?number_format($e['costo'],2):'' ?></td>
          <td><span class="tag"><?= safe($e['estado']) ?></span></td>
          <td class="actions">
            <form method="post" action="<?= ENV_APP['BASE_URL'] ?>/equipos/delete/<?= (int)$e['id'] ?>" onsubmit="return confirm('¿Eliminar?')">
              <button style="background:#7f1d1d;border:0;color:white;border-radius:8px;padding:6px 8px">Borrar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <script>
    // Probe de réplica: pinta el pill verde/rojo
    (async ()=>{
      try{
        const r = await fetch('<?= ENV_APP['BASE_URL'] ?>/health/replica?json=1', {headers:{'X-Requested-With':'XMLHttpRequest'}});
        const el = document.getElementById('replicaStatus');
        if(!el) return;
        if(!r.ok){ el.textContent = 'Réplica: error HTTP'; el.classList.add('pill-bad'); return; }
        const j = await r.json();
        if(j.replica_ok){
          el.textContent = 'Réplica OK';
          el.classList.add('pill-ok');
        }else{
          el.textContent = 'Réplica sin conexión';
          el.classList.add('pill-bad');
        }
      }catch(e){
        const el = document.getElementById('replicaStatus');
        if(el){ el.textContent = 'Réplica: fallo'; el.classList.add('pill-bad'); }
      }
    })();
  </script>
</section>
