<?php Auth::start(); $u=Auth::user(); ?>
<!doctype html><html lang="es"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title><?= safe(APP_NAME ?? 'Nibarra') ?></title>

<!-- CSS Externos -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- CSS del Sistema -->
<link rel="stylesheet" href="<?= ENV_APP['ASSETS_URL'] ?>/css/base.css">
<link rel="stylesheet" href="<?= ENV_APP['ASSETS_URL'] ?>/css/calendar.css">
<link rel="stylesheet" href="<?= ENV_APP['ASSETS_URL'] ?>/css/kanban.css">

<style>
/* 🎨 Estilos inline críticos - Mint Professional Theme */
:root{
  --bg:#0a0e1a;
  --panel:#1a1f35;
  --muted:#9ba6b8;
  --brand:#10b981;
  --ok:#10b981;
  --warn:#f59e0b;
  --err:#ef4444;
}

*{box-sizing:border-box} 

body{
  margin:0;
  background:#0a0e1a;
  background-image:radial-gradient(at 0% 0%, rgba(16,185,129,0.08) 0px, transparent 50%),
                   radial-gradient(at 100% 100%, rgba(30,58,138,0.08) 0px, transparent 50%);
  background-attachment:fixed;
  color:#f0f4f8;
  font-family:Inter,system-ui,Segoe UI,Roboto;
}

a{color:#a5b4fc;text-decoration:none} 
a:hover{text-decoration:underline}

.header{
  display:flex;
  gap:16px;
  align-items:center;
  justify-content:space-between;
  padding:14px 18px;
  background:linear-gradient(135deg, #1a1f35, #111827);
  border-bottom:1px solid #2a3347;
  backdrop-filter:blur(10px);
  box-shadow:0 4px 12px rgba(16,185,129,0.1);
  position:sticky;
  top:0;
  z-index:100;
}

.logo{
  display:flex;
  align-items:center;
  gap:10px;
  font-weight:700;
  font-size:1.2rem;
  background:linear-gradient(135deg, #10b981, #34d399);
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
  background-clip:text;
}

.logo span:first-child{
  font-size:1.8rem;
  filter:drop-shadow(0 0 10px rgba(16,185,129,0.3));
  -webkit-text-fill-color:initial;
}

.badge{
  background:#1e293b;
  border:1px solid #3d4b66;
  padding:4px 10px;
  border-radius:999px;
  font-size:12px;
  color:#d1d9e3;
  font-weight:500;
}

.main{
  max-width:1400px;
  margin:18px auto;
  padding:0 16px;
}

.card{
  background:#1a1f35;
  border:1px solid #2a3347;
  border-radius:16px;
  box-shadow:0 10px 30px rgba(16,185,129,0.15);
  overflow:hidden;
}

nav{
  display:flex;
  gap:8px;
}

nav a{
  padding:10px 14px;
  display:inline-block;
  border-radius:10px;
  color:#d1d9e3;
  font-weight:500;
  transition:all 0.2s;
  position:relative;
}

nav a::before{
  content:'';
  position:absolute;
  bottom:0;
  left:0;
  right:0;
  height:2px;
  background:linear-gradient(90deg, #10b981, #34d399);
  transform:scaleX(0);
  transition:transform 0.2s;
}

nav a:hover{
  background:#1e293b;
  text-decoration:none;
  color:#10b981;
}

nav a.active{
  background:linear-gradient(135deg, #10b981, #059669);
  color:white;
  box-shadow:0 0 20px rgba(16,185,129,0.3);
}

nav a.active::before{
  transform:scaleX(1);
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
  background:linear-gradient(135deg, #10b981, #059669);
  color:white;
  font-weight:600;
  box-shadow:0 10px 25px rgba(16,185,129,0.4);
  cursor:pointer;
  z-index:9999;
  transition:all 0.3s;
}

.cb-btn:hover{
  transform:scale(1.1);
  box-shadow:0 12px 30px rgba(16,185,129,0.6);
}

.cb-box{
  position:fixed;
  right:20px;
  bottom:72px;
  width:320px;
  max-height:420px;
  display:none;
  flex-direction:column;
  background:#1a1f35;
  border:1px solid #2a3347;
  border-radius:16px;
  overflow:hidden;
  z-index:9999;
  box-shadow:0 20px 60px rgba(16,185,129,0.2);
}

.cb-box.open{display:flex}

.cb-head{
  padding:12px 14px;
  background:linear-gradient(135deg, #10b981, #059669);
  border-bottom:1px solid #2a3347;
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
  background:#1e293b;
  padding:8px 10px;
  border-radius:12px;
}

.cb-log .bot{
  align-self:flex-start;
  background:#111827;
  padding:8px 10px;
  border-radius:12px;
}

.cb-input{
  display:flex;
  gap:6px;
  padding:10px;
  border-top:1px solid #2a3347;
}

.cb-input input{
  flex:1;
  background:#111827;
  border:1px solid #334155;
  color:#f0f4f8;
  border-radius:10px;
  padding:8px;
}

.cb-input button{
  background:#10b981;
  border:0;
  color:white;
  border-radius:10px;
  padding:8px 12px;
  cursor:pointer;
  transition:all 0.2s;
}

.cb-input button:hover{
  background:#059669;
  transform:scale(1.05);
}

/* Jobs Footer */
.jobs{
  display:grid;
  grid-template-columns:repeat(3,minmax(0,1fr));
  gap:14px;
  margin-top:14px;
}

.job{
  padding:16px;
  border:1px solid #2a3347;
  border-radius:12px;
  background:linear-gradient(135deg,#1a1f35,#111827);
  transition:all 0.3s;
}

.job:hover{
  border-color:#10b981;
  box-shadow:0 4px 12px rgba(16,185,129,0.2);
  transform:translateY(-2px);
}

.job h4{
  margin:0 0 8px 0;
  color:#10b981;
}

@media(max-width:900px){
  .jobs{grid-template-columns:1fr}
  .header{flex-direction:column;gap:12px}
  nav{flex-wrap:wrap}
}
</style>

<script>
// Anti-copia (punto D)
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
  // Permitir Ctrl+C en inputs y textareas
  if((e.ctrlKey||e.metaKey) && k === 'c' && 
     (e.target.matches('input, textarea') || window.getSelection().toString())) {
    return true;
  }
});
</script>
</head>
<?php
  // $route lo setea el front controller; si no existe, lo derivamos de REQUEST_URI
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