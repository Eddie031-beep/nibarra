<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Ingresar | Nibarra</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', system-ui, sans-serif;
      background: #0d1117;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #e6edf3;
      padding: 20px;
    }

    .container {
      width: 100%;
      max-width: 420px;
      animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .logo-section {
      text-align: center;
      margin-bottom: 28px;
    }

    .logo {
      font-size: 48px;
      margin-bottom: 10px;
      filter: drop-shadow(0 0 12px #ff6b35);
    }

    .logo-text {
      font-size: 28px;
      font-weight: 800;
      color: #e6edf3;
      margin-bottom: 4px;
    }

    .logo-subtitle {
      font-size: 13px;
      color: #7d8590;
    }

    .card {
      background: #1c2128;
      border: 1px solid #30363d;
      border-radius: 16px;
      padding: 32px 28px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.6);
    }

    h1 {
      font-size: 22px;
      font-weight: 700;
      margin-bottom: 6px;
      color: #e6edf3;
    }

    .subtitle {
      color: #7d8590;
      font-size: 13px;
      margin-bottom: 24px;
    }

    .error {
      background: rgba(248, 81, 73, 0.1);
      border: 1px solid rgba(248, 81, 73, 0.3);
      color: #ff7b72;
      padding: 12px 14px;
      border-radius: 10px;
      margin-bottom: 18px;
      font-size: 13px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      color: #e6edf3;
      font-weight: 500;
      font-size: 13px;
    }

    input {
      width: 100%;
      padding: 11px 14px;
      border-radius: 10px;
      border: 1.5px solid #30363d;
      background: #0d1117;
      color: #e6edf3;
      font-size: 14px;
      transition: all 0.3s;
      outline: none;
      margin-bottom: 14px;
      font-family: inherit;
    }

    input:hover {
      border-color: #484f58;
    }

    input:focus {
      border-color: #ff6b35;
      background: #161b22;
      box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
    }

    input::placeholder {
      color: #484f58;
    }

    input:not(:placeholder-shown) {
      border-color: #00d9ff;
    }

    button {
      width: 100%;
      padding: 12px;
      border-radius: 10px;
      border: 0;
      font-weight: 700;
      font-size: 14px;
      background: linear-gradient(135deg, #ff6b35, #d85a2a);
      color: white;
      cursor: pointer;
      transition: all 0.3s;
      box-shadow: 0 4px 14px rgba(255, 107, 53, 0.4);
      margin-top: 6px;
    }

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(255, 107, 53, 0.5);
      background: linear-gradient(135deg, #d85a2a, #b04820);
    }

    button:active {
      transform: translateY(0);
    }

    .register-link {
      text-align: center;
      margin-top: 18px;
      font-size: 13px;
      color: #7d8590;
    }

    .register-link a {
      color: #00d9ff;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s;
    }

    .register-link a:hover {
      color: #00b8d9;
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .card {
        padding: 26px 22px;
      }
      h1 {
        font-size: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo-section">
      <div class="logo">🛠️</div>
      <div class="logo-text">Nibarra</div>
      <div class="logo-subtitle">Sistema de Gestión de Mantenimiento</div>
    </div>

    <form class="card" method="post" action="<?= ENV_APP['BASE_URL'] ?>/login">
      <h1>Bienvenido</h1>
      <p class="subtitle">Ingresa tus credenciales para continuar</p>

      <?php if(!empty($error)): ?>
        <div class="error">
          <span>⚠️</span>
          <span><?= htmlspecialchars($error) ?></span>
        </div>
      <?php endif; ?>

      <label>Correo electrónico</label>
      <input name="email" type="email" placeholder="usuario@ejemplo.com" required autofocus>

      <label>Contraseña</label>
      <input type="password" name="password" placeholder="••••••••" required>

      <button type="submit">Ingresar</button>

      <div class="register-link">
        ¿No tienes cuenta? <a href="<?= ENV_APP['BASE_URL'] ?>/register">Regístrate aquí</a>
      </div>
    </form>
  </div>

  <script>
    document.addEventListener('contextmenu', e => e.preventDefault());
    
    document.querySelector('form').addEventListener('submit', function(e) {
      const email = this.querySelector('[name="email"]').value;
      const password = this.querySelector('[name="password"]').value;
      
      if (!email || !password) {
        e.preventDefault();
        alert('⚠️ Por favor completa todos los campos');
      }
    });
  </script>
</body>
</html>