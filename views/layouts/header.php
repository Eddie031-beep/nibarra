<?php Auth::start(); $u=Auth::user(); ?>
<!doctype html><html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"/>
<title><?= safe(APP_NAME) ?></title>
<style>
:root{--bg:#0e1726;--panel:#131b2e;--muted:#94a3b8;--brand:#4f46e5;--ok:#16a34a;--warn:#f59e0b;--err:#ef4444;}
*{box-sizing:border-box} body{margin:0;background:#0b1220;color:#e5e7eb;font-family:Inter,system-ui,Segoe UI,Roboto}
a{color:#a5b4fc;text-decoration:none} a:hover{text-decoration:underline}
.header{display:flex;gap:16px;align-items:center;justify-content:space-between;padding:14px 18px;background:linear-gradient(90deg,#0b1220,#111A2E);}
.logo{display:flex;align-items:center;gap:10px;font-weight:600}
.badge{background:#1f2937;border:1px solid #2b364b;padding:4px 8px;border-radius:8px;font-size:12px;color:#9ca3af}
.main{max-width:1100px;margin:18px auto;padding:0 16px}
.card{background:#0f172a;border:1px solid #1e293b;border-radius:14px;box-shadow:0 10px 30px rgba(2,6,23,.25)}
nav a{padding:8px 10px;display:inline-block;border-radius:8px}
nav a.active, nav a:hover{background:#111827}
.nocopy{user-select:none;-webkit-user-select:none}
.cb-btn{position:fixed;right:20px;bottom:20px;border-radius:999px;border:0;padding:12px 16px;background:var(--brand);color:white;font-weight:600;box-shadow:0 10px 25px rgba(79,70,229,.4);cursor:pointer}
.cb-box{position:fixed;right:20px;bottom:72px;width:320px;max-height:420px;display:none;flex-direction:column;background:#0b1220;border:1px solid #1e293b;border-radius:16px;overflow:hidden}
.cb-box.open{display:flex}
.cb-head{padding:10px 12px;background:#111827;border-bottom:1px solid #1e293b;font-weight:700}
.cb-log{padding:10px;gap:6px;display:flex;flex-direction:column;overflow:auto}
.cb-log .me{align-self:flex-end;background:#1e293b;padding:8px 10px;border-radius:12px}
.cb-log .bot{align-self:flex-start;background:#162033;padding:8px 10px;border-radius:12px}
.cb-input{display:flex;gap:6px;padding:10px;border-top:1px solid #1e293b}
.cb-input input{flex:1;background:#0f172a;border:1px solid #243044;color:#e5e7eb;border-radius:10px;padding:8px}
.cb-input button{background:#334155;border:0;color:#e5e7eb;border-radius:10px;padding:8px 10px}
.jobs{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-top:14px}
.job{padding:14px;border:1px solid #1e293b;border-radius:12px;background:linear-gradient(180deg,#0f172a,#0b1220)}
.job h4{margin:0 0 6px 0}
@media(max-width:900px){.jobs{grid-template-columns:1fr}}
</style>
<script>
// Anti-copia (punto D)
document.addEventListener('contextmenu', e => e.preventDefault());
document.addEventListener('keydown', e => {
  const k=e.key?.toLowerCase();
  if((e.ctrlKey||e.metaKey) && ['u','s','c','p'].includes(k)) e.preventDefault();
});
</script>
</head><body class="nocopy">
<header class="header">
  <div class="logo">
    <span>🛠️</span><span><?= safe(APP_NAME) ?></span>
    <span class="badge"><?= $u? 'Hola, '.safe($u['username']) : 'Invitado' ?></span>
  </div>
  <nav>
    <a href="<?= ENV_APP['BASE_URL'].'/equipos' ?>" class="<?= $uri??''==='/equipos'?'active':''?>">Equipos</a>
    <a href="<?= ENV_APP['BASE_URL'].'/calendario' ?>" class="<?= $uri??''==='/calendario'?'active':''?>">Calendario</a>
    <a href="<?= ENV_APP['BASE_URL'].'/mantenimiento' ?>" class="<?= $uri??''==='/mantenimiento'?'active':''?>">Mantenimiento</a>
    <?php if($u): ?><a href="<?= ENV_APP['BASE_URL'].'/logout' ?>">Salir</a><?php else: ?>
    <a href="<?= ENV_APP['BASE_URL'].'/login' ?>">Ingresar</a><?php endif; ?>
  </nav>
</header>
<main class="main">
