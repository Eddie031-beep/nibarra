<?php Auth::start(); $u=Auth::user(); ?>
<!doctype html><html lang="es"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title><?= safe(APP_NAME ?? 'Nibarra') ?></title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?= ENV_APP['ASSETS_URL'] ?>/css/base.css">
<link rel="stylesheet" href="<?= ENV_APP['ASSETS_URL'] ?>/css/calendar.css">
<link rel="stylesheet" href="<?= ENV_APP['ASSETS_URL'] ?>/css/kanban.css">

<style>
/* 🎨 Nueva Paleta Industrial Profesional */
:root{
  --bg-dark:#0d1117;
  --bg-medium:#161b22;
  --bg-card:#1c2128;
  --bg-elevated:#21262d;
  
  --border-main:#30363d;
  --border-light:#484f58;
  
  --text-primary:#e6edf3;
  --text-secondary:#7d8590;
  --text-muted:#484f58;
  
  --accent-orange:#ff6b35;
  --accent-cyan:#00d9ff;
  --accent-green:#3fb950;
  --accent-yellow:#f0883e;
  
  --ok:#3fb950;
  --warn:#f0883e;
  --err:#f85149;
}

*{box-sizing:border-box} 

body{
  margin:0;
  background:var(--bg-dark);
  color:var(--text-primary);
  font-family:Inter,system-ui,Segoe UI,Roboto;
  font-size:14px;
  line-height:1.5;
}

a{color:#00d9ff;text-decoration:none} 
a:hover{text-decoration:underline}

.header{
  display:flex;
  gap:12px;
  align-items:center;
  justify-content:space-between;
  padding:10px 16px;
  background:var(--bg-card);
  border-bottom:1px solid var(--border-main);
  position:sticky;
  top:0;
  z-index:100;
  box-shadow:0 1px 3px rgba(0,0,0,0.3);
}

.logo{
  display:flex;
  align-items:center;
  gap:8px;
  font-weight:700;
  font-size:1.1rem;
  color:var(--text-primary);
}

.logo span:first-child{
  font-size:1.5rem;
  filter:drop-shadow(0 0 8px var(--accent-orange));
}

.badge{
  background:var(--bg-elevated);
  border:1px solid var(--border-light);
  padding:3px 10px;
  border-radius:999px;
  font-size:11px;
  color:var(--accent-cyan);
  font-weight:600;
}

.main{
  max-width:1300px;
  margin:12px auto;
  padding:0 12px;
}

.card{
  background:var(--bg-card);
  border:1px solid var(--border-main);
  border-radius:12px;
  box-shadow:0 2px 8px rgba(0,0,0,0.3);
  overflow:hidden;
}

nav{
  display:flex;
  gap:6px;
}

nav a{
  padding:8px 12px;
  display:inline-block;
  border-radius:8px;
  color:var(--text-secondary);
  font-weight:500;
  font-size:13px;
  transition:all 0.2s;
  border:1px solid transparent;
}

nav a:hover{
  background:var(--bg-elevated);
  text-decoration:none;
  color:var(--accent-orange);
  border-color:var(--border-light);
}

nav a.active{
  background:linear-gradient(135deg, var(--accent-orange), #d85a2a);
  color:white;
  border-color:var(--accent-orange);
  box-shadow:0 0 15px rgba(255,107,53,0.4);
}

.nocopy{
  user-select:none;
  -webkit-user-select:none;
}

/* ChatBot */
.cb-btn{
  position:fixed;
  right:20px;
  bottom:20px;
  border-radius:999px;
  border:0;
  padding:12px 16px;
  background:linear-gradient(135deg, var(--accent-orange), #d85a2a);
  color:white;
  font-weight:600;
  box-shadow:0 10px 25px rgba(255,107,53,0.4);
  cursor:pointer;
  z-index:9999;
  transition:all 0.3s;
}

.cb-btn:hover{
  transform:scale(1.1);
  box-shadow:0 12px 30px rgba(255,107,53,0.6);
}

.cb-box{
  position:fixed;
  right:20px;
  bottom:72px;
  width:320px;
  max-height:420px;
  display:none;
  flex-direction:column;
  background:var(--bg-card);
  border:1px solid var(--border-main);
  border-radius:16px;
  overflow:hidden;
  z-index:9999;
  box-shadow:0 20px 60px rgba(0,0,0,0.6);
}

.cb-box.open{display:flex}

.cb-head{
  padding:12px 14px;
  background:linear-gradient(135deg, var(--accent-orange), #d85a2a);
  border-bottom:1px solid var(--border-main);
  font-weight:700;
  color:white;
}

.cb-log{
  padding:10px;
  gap:6px;
  display:flex;
  flex-direction:column;
  overflow:auto;
  flex:1;
}

.cb-log .me{
  align-self:flex-end;
  background:var(--bg-elevated);
  padding:8px 10px;
  border-radius:12px;
}

.cb-log .bot{
  align-self:flex-start;
  background:var(--bg-medium);
  padding:8px 10px;
  border-radius:12px;
}

.cb-input{
  display:flex;
  gap:6px;
  padding:10px;
  border-top:1px solid var(--border-main);
}

.cb-input input{
  flex:1;
  background:var(--bg-dark);
  border:1px solid var(--border-light);
  color:var(--text-primary);
  border-radius:10px;
  padding:8px;
}

.cb-input button{
  background:var(--accent-orange);
  border:0;
  color:white;
  border-radius:10px;
  padding:8px 12px;
  cursor:pointer;
  transition:all 0.2s;
}

.cb-input button:hover{
  background:#d85a2a;
  transform:scale(1.05);
}

/* Jobs Footer */
.jobs{
  display:grid;
  grid-template-columns:repeat(3,minmax(0,1fr));
  gap:12px;
  margin-top:12px;
}

.job{
  padding:14px;
  border:1px solid var(--border-main);
  border-radius:12px;
  background:var(--bg-card);
  transition:all 0.3s;
}

.job:hover{
  border-color:var(--accent-orange);
  box-shadow:0 4px 12px rgba(255,107,53,0.2);
  transform:translateY(-2px);
}

.job h4{
  margin:0 0 6px 0;
  color:var(--accent-orange);
}

@media(max-width:900px){
  .jobs{grid-template-columns:1fr}
  .header{flex-direction:column;gap:12px}
  nav{flex-wrap:wrap}
}
</style>

<script>
document.addEventListener('contextmenu', e => {
  if (!e.target.matches('input, textarea')) {
    e.preventDefault();
  }
});

document.addEventListener('keydown', e => {
  const k=e.key?.toLowerCase();
  if((e.ctrlKey||e.metaKey) && ['u','s','p'].includes(k)) {
    e.preventDefault();
  }
  if((e.ctrlKey||e.metaKey) && k === 'c' && 
     (e.target.matches('input, textarea') || window.getSelection().toString())) {
    return true;
  }
});
</script>
</head>
<?php
  $route = $route ?? parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
  $basePath = parse_url(ENV_APP['BASE_URL'], PHP_URL_PATH) ?: '';
  if ($basePath && str_starts_with($route, $basePath)) {
    $route = substr($route, strlen($basePath));
  }
  $route = $route === '' ? '/' : $route;
?>
<body class="nocopy">
<header class="header">
  <div class="logo">
    <span>🛠️</span>
    <span><?= safe(APP_NAME ?? 'Nibarra') ?></span>
    <span class="badge">
      <?= $u ? 'Hola, '.safe($u['nombre'] ?? $u['email'] ?? 'Usuario') : 'Invitado' ?>
    </span>
  </div>
  <nav>
    <?php
      $base = ENV_APP['BASE_URL'];
      $is = function($p) use($route){ 
        $normalized = rtrim($route, '/') ?: '/';
        return $normalized === $p || ($p !== '/' && str_starts_with($normalized, $p)); 
      };
    ?>
    <a href="<?= $base ?>/equipos" class="<?= $is('/equipos')?'active':'' ?>">Equipos</a>
    <a href="<?= $base ?>/calendario" class="<?= $is('/calendario')?'active':'' ?>">Calendario</a>
    <a href="<?= $base ?>/mantenimiento" class="<?= $is('/mantenimiento')?'active':'' ?>">Mantenimiento</a>
    <a href="<?= $base ?>/facturas" class="<?= $is('/facturas')?'active':'' ?>">Facturas</a>
    <?php if($u): ?>
      <a href="<?= $base ?>/logout">Salir</a>
    <?php else: ?>
      <a href="<?= $base ?>/login">Ingresar</a>
    <?php endif; ?>
  </nav>
</header>
<main class="main">