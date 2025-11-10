<?php
$first = new DateTime(sprintf('%04d-%02d-01',$y,$m));
$days  = (int)$first->format('t');
$startDow = (int)$first->format('N');
$prev = (clone $first)->modify('-1 month'); $next=(clone $first)->modify('+1 month');
$map=[]; foreach($eventos as $ev){ $d=(new DateTime($ev['inicio']))->format('Y-m-d'); $map[$d][]=$ev; }
?>
<section class="card" style="padding:16px">
  <h2 style="margin:0 0 10px 0">Calendario <?= $first->format('F Y') ?></h2>
  <style>
    .cal{display:grid;grid-template-columns:repeat(7,1fr);gap:6px}
    .cell{border:1px solid #1e293b;border-radius:10px;min-height:110px;padding:6px;background:#0b1220}
    .cell h4{margin:0 0 6px 0;font-size:12px;color:#94a3b8}
    .event{font-size:12px;border-radius:8px;padding:4px 6px;margin:4px 0;border:1px solid #233157}
    .toolbar{display:flex;gap:8px;margin-bottom:8px;align-items:center}
    .toolbar a{border:1px solid #233157;padding:6px 8px;border-radius:8px}
    form.add{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px}
    form.add input, form.add textarea, form.add select{padding:8px;border-radius:8px;border:1px solid #243044;background:#0b1220;color:#e5e7eb}
    .primary{background:#4f46e5;border:0;color:white;border-radius:10px;padding:8px 12px;font-weight:700}
  </style>
  <div class="toolbar">
    <a href="<?= ENV_APP['BASE_URL'].'/calendario?y='.$prev->format('Y').'&m='.$prev->format('n') ?>">← Anterior</a>
    <div style="flex:1;text-align:center;color:#cbd5e1"><?= $first->format('F Y') ?></div>
    <a href="<?= ENV_APP['BASE_URL'].'/calendario?y='.$next->format('Y').'&m='.$next->format('n') ?>">Siguiente →</a>
  </div>
  <div class="cal">
    <?php
      $dows=['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'];
      foreach($dows as $n) echo "<div style='text-align:center;color:#94a3b8'>$n</div>";
      for($i=1;$i<$startDow;$i++) echo "<div></div>";
      for($d=1;$d<=$days;$d++):
        $date = sprintf('%04d-%02d-%02d',$y,$m,$d);
    ?>
      <div class="cell">
        <h4><?= $d ?></h4>
        <?php if(!empty($map[$date])): foreach($map[$date] as $ev): ?>
          <div class="event" style="background:<?= safe($ev['color'] ?? '#111d35') ?>">
            <div><b><?= safe($ev['titulo']) ?></b></div>
            <div style="color:#e5e7eb">
              <?php if((int)$ev['all_day']===1): ?>
                Todo el día
              <?php else: ?>
                <?= safe(date('H:i', strtotime($ev['inicio']))) ?><?php if($ev['fin']): ?>–<?= safe(date('H:i', strtotime($ev['fin']))) ?><?php endif; ?>
              <?php endif; ?>
            </div>
            <form method="post" action="<?= ENV_APP['BASE_URL'].'/calendario/delete/'.$ev['id'] ?>" onsubmit="return confirm('¿Eliminar evento?')">
              <button style="margin-top:4px;border:0;background:#7f1d1d;color:white;border-radius:8px;padding:4px 6px">Eliminar</button>
            </form>
          </div>
        <?php endforeach; endif; ?>
      </div>
    <?php endfor; ?>
  </div>

  <form class="add" method="post" action="<?= ENV_APP['BASE_URL'] ?>/calendario/store">
    <input type="hidden" name="y" value="<?= $y ?>"><input type="hidden" name="m" value="<?= $m ?>">
    <input name="titulo" placeholder="Título" required>
    <input type="datetime-local" name="inicio" required>
    <input type="datetime-local" name="fin" placeholder="Fin (opcional)">
    <label style="display:flex;align-items:center;gap:6px">
      <input type="checkbox" name="all_day" value="1"> Todo el día
    </label>
    <input type="color" name="color" value="#2563eb" title="Color">
    <input name="mantenimiento_id" placeholder="ID Mantenimiento (opcional)">
    <button class="primary">Añadir</button>
  </form>
</section>
