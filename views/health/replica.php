<section class="card" style="padding:16px">
  <h2 style="margin:0 0 10px 0">Estado de réplica</h2>
  <p style="color:#94a3b8;margin:6px 0">Destino: <b><?= safe($host) ?>:<?= (int)$port ?></b></p>
  <?php if ($ok): ?>
    <div style="background:#0f2a18;border:1px solid #14532d;color:#a7f3d0;padding:10px;border-radius:10px">
      ✅ Conexión OK a la réplica.
    </div>
  <?php else: ?>
    <div style="background:#331f2b;border:1px solid #7f1d1d;color:#fecaca;padding:10px;border-radius:10px">
      ❌ No se pudo conectar a la réplica.<br>
      Verifica firewall/puerto (<?= (int)$port ?>), <code>bind-address</code> y permisos del usuario en Windows.
    </div>
  <?php endif; ?>
  <div style="margin-top:10px">
    <a class="badge" href="<?= ENV_APP['BASE_URL'] ?>/health/replica?json=1" style="border:1px solid #2b364b;padding:6px 8px;border-radius:8px">Ver JSON</a>
  </div>
</section>
