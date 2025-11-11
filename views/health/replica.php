<section class="card" style="padding:1.5rem">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
    <h2 style="margin:0">Estado de Réplica Windows</h2>
    <button onclick="location.reload()" style="padding:0.5rem 1rem;background:#4f46e5;border:0;color:white;border-radius:8px;cursor:pointer">
      🔄 Actualizar
    </button>
  </div>
  
  <style>
    .status-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;margin-bottom:1.5rem}
    @media(max-width:768px){.status-grid{grid-template-columns:1fr}}
    .status-card{padding:1rem;border:1px solid #1e293b;border-radius:10px;background:#0b1220}
    .status-card h3{margin:0 0 0.5rem 0;font-size:0.875rem;color:#94a3b8;font-weight:600}
    .status-value{font-size:1.25rem;font-weight:700;margin:0.25rem 0}
    .status-ok{color:#10b981}
    .status-error{color:#ef4444}
    .status-info{color:#3b82f6}
    .diagnostic{background:#0f172a;border:1px solid #1e293b;border-radius:10px;padding:1rem;margin-top:1rem}
    .diagnostic h3{margin:0 0 0.75rem 0;font-size:1rem;color:#cbd5e1}
    .diagnostic-item{display:flex;align-items:flex-start;gap:0.75rem;padding:0.5rem 0;border-bottom:1px solid #1e293b}
    .diagnostic-item:last-child{border-bottom:0}
    .diagnostic-icon{font-size:1.25rem;flex-shrink:0}
    .diagnostic-content{flex:1}
    .diagnostic-label{font-size:0.875rem;font-weight:600;color:#cbd5e1;margin-bottom:0.25rem}
    .diagnostic-value{font-size:0.8125rem;color:#94a3b8;font-family:monospace}
    .alert{padding:1rem;border-radius:10px;margin-bottom:1rem}
    .alert-success{background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);color:#6ee7b7}
    .alert-error{background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5}
    .alert-warning{background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.3);color:#fcd34d}
    .recommendations{background:#0f172a;border:1px solid #1e293b;border-radius:10px;padding:1rem;margin-top:1rem}
    .recommendations h3{margin:0 0 0.75rem 0;font-size:1rem;color:#cbd5e1}
    .recommendations ul{margin:0;padding-left:1.5rem;color:#94a3b8;font-size:0.875rem}
    .recommendations li{margin:0.5rem 0}
    .code{background:#000;color:#0f0;padding:0.5rem;border-radius:5px;font-family:monospace;font-size:0.8125rem;margin:0.5rem 0}
  </style>

  <?php if ($pdoOk): ?>
    <!-- CONEXIÓN EXITOSA -->
    <div class="alert alert-success">
      <div style="display:flex;align-items:center;gap:0.75rem">
        <span style="font-size:1.5rem">✅</span>
        <div>
          <div style="font-weight:700;margin-bottom:0.25rem">Conexión Exitosa</div>
          <div style="font-size:0.875rem;opacity:0.9">La réplica en Windows está funcionando correctamente</div>
        </div>
      </div>
    </div>
    
    <div class="status-grid">
      <div class="status-card">
        <h3>🖥️ Servidor</h3>
        <div class="status-value status-ok"><?= htmlspecialchars($host) ?>:<?= (int)$port ?></div>
      </div>
      <div class="status-card">
        <h3>📊 Base de Datos</h3>
        <div class="status-value status-info"><?= htmlspecialchars($db) ?></div>
      </div>
      <div class="status-card">
        <h3>🔢 Versión</h3>
        <div class="status-value status-ok"><?= htmlspecialchars($version) ?></div>
      </div>
      <div class="status-card">
        <h3>🕐 Hora del Servidor</h3>
        <div class="status-value status-info"><?= htmlspecialchars($serverTime) ?></div>
      </div>
    </div>
    
  <?php else: ?>
    <!-- ERROR DE CONEXIÓN -->
    <div class="alert alert-error">
      <div style="display:flex;align-items:center;gap:0.75rem">
        <span style="font-size:1.5rem">❌</span>
        <div>
          <div style="font-weight:700;margin-bottom:0.25rem">No se pudo conectar a la réplica</div>
          <div style="font-size:0.875rem;opacity:0.9">Revisa la configuración y los pasos de diagnóstico abajo</div>
        </div>
      </div>
    </div>
    
    <div class="status-grid">
      <div class="status-card">
        <h3>🖥️ Servidor Destino</h3>
        <div class="status-value status-info"><?= htmlspecialchars($host) ?>:<?= (int)$port ?></div>
      </div>
      <div class="status-card">
        <h3>👤 Usuario</h3>
        <div class="status-value status-info"><?= htmlspecialchars($user) ?></div>
      </div>
    </div>
  <?php endif; ?>

  <!-- DIAGNÓSTICO DETALLADO -->
  <div class="diagnostic">
    <h3>🔍 Diagnóstico Detallado</h3>
    
    <div class="diagnostic-item">
      <div class="diagnostic-icon"><?= $socketOk ? '✅' : '❌' ?></div>
      <div class="diagnostic-content">
        <div class="diagnostic-label">Test de Socket (Puerto <?= (int)$port ?>)</div>
        <div class="diagnostic-value">
          <?php if ($socketOk): ?>
            Puerto abierto y accesible
          <?php else: ?>
            ❌ Error: <?= htmlspecialchars($socketError) ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    <div class="diagnostic-item">
      <div class="diagnostic-icon"><?= $pdoOk ? '✅' : '❌' ?></div>
      <div class="diagnostic-content">
        <div class="diagnostic-label">Test de Conexión MySQL/PDO</div>
        <div class="diagnostic-value">
          <?php if ($pdoOk): ?>
            Conexión establecida correctamente
          <?php else: ?>
            ❌ Error: <?= htmlspecialchars($pdoError) ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <?php if (!$pdoOk): ?>
    <!-- RECOMENDACIONES SI HAY ERROR -->
    <div class="recommendations">
      <h3>💡 Pasos para Solucionar</h3>
      
      <?php if (!$socketOk): ?>
        <div class="alert alert-warning" style="margin-bottom:1rem">
          <strong>⚠️ El puerto <?= (int)$port ?> no es accesible</strong><br>
          Esto significa que MySQL/MariaDB no está escuchando en ese puerto, o el firewall lo está bloqueando.
        </div>
        
        <h4 style="color:#cbd5e1;margin:1rem 0 0.5rem 0">1. Verificar que MySQL está corriendo en Windows</h4>
        <ul>
          <li>Abre XAMPP Control Panel</li>
          <li>Verifica que MySQL esté en estado "Running" (verde)</li>
          <li>Si no está corriendo, haz click en "Start"</li>
        </ul>
        
        <h4 style="color:#cbd5e1;margin:1rem 0 0.5rem 0">2. Verificar el puerto en Windows CMD</h4>
        <div class="code">netstat -ano | findstr :<?= (int)$port ?></div>
        <ul>
          <li>Si no aparece nada, MySQL no está usando ese puerto</li>
          <li>Verifica <code>C:\xampp\mysql\bin\my.ini</code></li>
          <li>Busca la línea <code>port=<?= (int)$port ?></code></li>
        </ul>
        
        <h4 style="color:#cbd5e1;margin:1rem 0 0.5rem 0">3. Configurar Firewall de Windows</h4>
        <div class="code">New-NetFirewallRule -DisplayName "MySQL <?= (int)$port ?>" -Direction Inbound -Protocol TCP -LocalPort <?= (int)$port ?> -Action Allow</div>
        <ul>
          <li>Ejecuta este comando en PowerShell como Administrador</li>
          <li>O configura manualmente en Firewall de Windows</li>
        </ul>
        
      <?php else: ?>
        <div class="alert alert-warning" style="margin-bottom:1rem">
          <strong>⚠️ El puerto es accesible pero la autenticación falló</strong><br>
          El problema está en usuario/contraseña o permisos.
        </div>
        
        <h4 style="color:#cbd5e1;margin:1rem 0 0.5rem 0">1. Verificar usuario en MySQL</h4>
        <div class="code">mysql -u root -p</div>
        <p style="color:#94a3b8;margin:0.5rem 0">Luego ejecuta:</p>
        <div class="code">CREATE USER IF NOT EXISTS '<?= htmlspecialchars($user) ?>'@'%' IDENTIFIED BY 'tu_contraseña';
GRANT ALL PRIVILEGES ON *.* TO '<?= htmlspecialchars($user) ?>'@'%';
FLUSH PRIVILEGES;</div>
        
        <h4 style="color:#cbd5e1;margin:1rem 0 0.5rem 0">2. Verificar bind-address</h4>
        <p style="color:#94a3b8;margin:0.5rem 0">En <code>C:\xampp\mysql\bin\my.ini</code>:</p>
        <div class="code">[mysqld]
bind-address = 0.0.0.0
port = <?= (int)$port ?></div>
        <ul>
          <li>Después de cambiar, reinicia MySQL en XAMPP</li>
        </ul>
      <?php endif; ?>
      
      <h4 style="color:#cbd5e1;margin:1rem 0 0.5rem 0">4. Verificar IP en Ubuntu</h4>
      <div class="code">mysql -h <?= htmlspecialchars($host) ?> -P <?= (int)$port ?> -u <?= htmlspecialchars($user) ?> -p -e "SELECT VERSION();"</div>
      <ul>
        <li>Si este comando funciona, el problema está en tu aplicación PHP</li>
        <li>Si no funciona, el problema está en la configuración de Windows</li>
      </ul>
    </div>
  <?php endif; ?>

  <!-- INFORMACIÓN ADICIONAL -->
  <div class="recommendations" style="margin-top:1rem">
    <h3>ℹ️ Información</h3>
    <ul>
      <li><strong>La réplica es opcional:</strong> El sistema funciona perfectamente sin ella</li>
      <li><strong>Propósito:</strong> La réplica sirve para verificación y respaldo, no es necesaria para el funcionamiento</li>
      <li><strong>Escritura:</strong> El sistema siempre escribe en la base de datos local (Ubuntu, puerto 3306)</li>
      <li><strong>Lectura:</strong> Puedes configurar réplica para distribuir carga de lectura (avanzado)</li>
    </ul>
  </div>

  <!-- BOTONES DE ACCIÓN -->
  <div style="display:flex;gap:1rem;margin-top:1.5rem;flex-wrap:wrap">
    <a href="<?= ENV_APP['BASE_URL'] ?>/health/replica?json=1" target="_blank" 
       style="padding:0.75rem 1rem;background:#111827;border:1px solid #2b364b;border-radius:8px;text-decoration:none">
      📄 Ver JSON
    </a>
    <a href="<?= ENV_APP['BASE_URL'] ?>/equipos" 
       style="padding:0.75rem 1rem;background:#4f46e5;border:0;color:white;border-radius:8px;text-decoration:none">
      ← Volver a Equipos
    </a>
    <?php if (!$pdoOk): ?>
    <a href="https://www.google.com/search?q=xampp+mysql+port+3307+not+working" target="_blank"
       style="padding:0.75rem 1rem;background:#1f2937;border:1px solid #2b364b;border-radius:8px;text-decoration:none">
      🔍 Buscar ayuda en Google
    </a>
    <?php endif; ?>
  </div>
</section>

<script>
// Auto-refrescar cada 30 segundos si hay error
<?php if (!$pdoOk): ?>
setTimeout(() => {
  const refresh = confirm('¿Quieres actualizar el estado de la réplica?');
  if (refresh) location.reload();
}, 30000);
<?php endif; ?>
</script>