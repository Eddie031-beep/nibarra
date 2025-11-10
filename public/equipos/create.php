<?php require_once dirname(__DIR__,2).'/config/config.php'; ?>
<!doctype html>
<meta charset="utf-8">
<title>Crear equipo</title>

<?php if (!empty($_GET['ok'])): ?>
  <div style="padding:.6rem;background:#e6ffed;border:1px solid #b7eb8f;margin:.6rem 0">
    Equipo guardado correctamente (sincronizado/encolado).
  </div>
<?php endif; ?>

<?php if (!empty($_GET['err'])): ?>
  <div style="padding:.6rem;background:#ffecec;border:1px solid #ffb3b3;margin:.6rem 0">
    Error: <?= htmlspecialchars($_GET['err']) ?>
  </div>
<?php endif; ?>

<h1>Crear equipo</h1>
<form method="post" action="/equipos/store.php">
  <label>Código: <input name="codigo" placeholder="EQ-001" required></label><br>
  <label>Nombre: <input name="nombre" required></label><br>
  <label>Categoría: <input name="categoria" placeholder="Eléctrico / Herramienta"></label><br>
  <label>Estado:
 <select name="estado">
  <option value="operativo">operativo</option>
  <option value="fuera_de_servicio">fuera_de_servicio</option>
  <option value="baja">baja</option>
</select>
  </label><br><br>
  <button type="submit">Guardar</button>
</form>
