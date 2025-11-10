<section class="card" style="padding:16px">
  <h2 style="margin:0 0 10px 0">Mantenimiento • Kanban + % por tareas</h2>
  <style>
    .board{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
    .col{border:1px solid #1e293b;border-radius:12px;background:#0b1220;min-height:260px;padding:10px}
    .col h3{margin:0 0 8px 0;color:#cbd5e1;font-size:14px}
    .cardx{background:#0f172a;border:1px solid #233157;border-radius:10px;padding:8px;margin-bottom:8px}
    .meta{font-size:12px;color:#94a3b8;margin-bottom:6px}
    .progress{height:8px;background:#1f2937;border-radius:999px;overflow:hidden;margin:8px 0}
    .bar{height:8px;background:#4f46e5}
    .actions{display:flex;gap:6px;margin-top:6px;flex-wrap:wrap}
    .btn{border:1px solid #2b364b;background:#111827;color:#e5e7eb;border-radius:8px;padding:6px 8px;cursor:pointer}
    .tasks{margin-top:6px}
    .task{display:flex;align-items:center;gap:6px;font-size:12px}
    .task input{accent-color:#4f46e5}
    .newtask{display:flex;gap:6px;margin-top:6px}
    .newtask input{flex:1;padding:6px;border-radius:8px;border:1px solid #243044;background:#0b1220;color:#e5e7eb}
  </style>
  <?php
    $names=['pendiente'=>'Pendiente','en_progreso'=>'En progreso','completado'=>'Completado','cancelado'=>'Cancelado'];
    $order=['pendiente','en_progreso','completado','cancelado'];
  ?>
  <div class="board">
    <?php foreach($order as $k): $title=$names[$k]; ?>
      <div class="col">
        <h3><?= $title ?></h3>
        <?php foreach($cols[$k] as $t): ?>
          <div class="cardx" id="mant-<?= $t['id'] ?>">
            <div class="meta">#<?= $t['id'] ?> • Equipo: <b><?= safe($t['equipo_nombre']) ?></b> • Tipo: <?= safe($t['tipo']) ?> • Pri: <?= safe($t['prioridad']) ?></div>
            <div><?= safe($t['titulo']) ?></div>
            <div class="progress"><div class="bar" style="width:<?= (int)$t['pct'] ?>%"></div></div>
            <small style="color:#94a3b8"><?= (int)$t['hechas_tareas'] ?>/<?= (int)$t['total_tareas'] ?> tareas (<?= (int)$t['pct'] ?>%)</small>

            <div class="tasks" data-mid="<?= $t['id'] ?>">
              <!-- Carga rápida de tareas por fetch cuando se expanda (para mantener simpleza, mostramos dos entradas dummy si total>0) -->
            </div>

            <div class="newtask">
              <input placeholder="Nueva tarea..." id="new-<?= $t['id'] ?>">
              <button class="btn" onclick="crearTarea(<?= $t['id'] ?>)">Añadir</button>
            </div>

            <div class="actions">
              <?php foreach($order as $kk): if($kk===$k) continue; ?>
                <button class="btn" onclick="mover(<?= $t['id'] ?>,'<?= $kk ?>')"><?= $names[$kk] ?></button>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
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
      const val=document.getElementById('new-'+mid).value.trim(); if(!val) return;
      const r=await fetch('<?= ENV_APP['BASE_URL'] ?>/mantenimiento/tareaNueva',
        {method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`mantenimiento_id=${mid}&titulo=${encodeURIComponent(val)}`});
      if(r.ok) location.reload();
    }
  </script>
</section>
