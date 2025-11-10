<!doctype html><html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Ingresar | Nibarra</title>
<style>
body{margin:0;background:#0b1220;color:#e5e7eb;font-family:Inter,system-ui}
.wrap{min-height:100vh;display:grid;place-items:center}
.card{background:#0f172a;border:1px solid #1e293b;border-radius:16px;max-width:360px;width:100%;padding:18px}
h1{margin:0 0 8px 0} label{display:block;margin:8px 0 4px 0;color:#cbd5e1}
input{width:100%;padding:10px;border-radius:10px;border:1px solid #243044;background:#0b1220;color:#e5e7eb}
button{width:100%;margin-top:10px;padding:10px;border-radius:10px;border:0;font-weight:700;background:#4f46e5;color:white}
.error{background:#331f2b;border:1px solid #3b2130;color:#fca5a5;padding:8px;border-radius:8px;margin-bottom:8px}
</style>
<script>document.addEventListener('contextmenu', e=>e.preventDefault());</script>
</head><body>
<div class="wrap">
  <form class="card" method="post" action="<?= ENV_APP['BASE_URL'] ?>/login">
    <h1>Ingreso</h1>
    <?php if(!empty($error)): ?><div class="error"><?= safe($error) ?></div><?php endif; ?>
    <label>Email</label>
    <input name="email" type="email" required>
    <label>Contraseña</label>
    <input type="password" name="password" required>
    <button>Entrar</button>
    <p style="margin-top:10px;color:#94a3b8">Usuario demo: <b>admin@nibarra.local</b> / contraseña estilo <i>bcrypt</i> (prueba “password”).</p>
  </form>
</div>
</body></html>
