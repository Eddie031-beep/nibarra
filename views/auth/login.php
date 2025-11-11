<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Ingresar | Nibarra</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
      background: linear-gradient(135deg, #0b1220 0%, #1a1f35 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #e5e7eb;
      padding: 20px;
    }

    .container {
      width: 100%;
      max-width: 400px;
      animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .logo-section {
      text-align: center;
      margin-bottom: 32px;
    }

    .logo {
      font-size: 56px;
      margin-bottom: 12px;
      display: inline-block;
    }

    .logo-text {
      font-size: 32px;
      font-weight: 800;
      background: linear-gradient(135deg, #4f46e5, #7c3aed);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .logo-subtitle {
      font-size: 14px;
      color: #94a3b8;
      margin-top: 8px;
    }

    .card {
      background: rgba(15, 23, 42, 0.95);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(30, 41, 59, 0.5);
      border-radius: 20px;
      padding: 36px 32px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    }

    h1 {
      font-size: 26px;
      font-weight: 700;
      margin-bottom: 8px;
      color: #f1f5f9;
    }

    .subtitle {
      color: #94a3b8;
      font-size: 14px;
      margin-bottom: 28px;
    }

    .error {
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.3);
      color: #fca5a5;
      padding: 12px 16px;
      border-radius: 10px;
      margin-bottom: 20px;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      color: #cbd5e1;
      font-weight: 500;
      font-size: 14px;
    }

    input {
      width: 100%;
      padding: 14px 16px;
      border-radius: 12px;
      border: 1px solid #334155;
      background: #0b1220;
      color: #e5e7eb;
      font-size: 15px;
      transition: all 0.3s;
      outline: none;
      margin-bottom: 16px;
    }

    input:focus {
      border-color: #4f46e5;
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    input::placeholder {
      color: #64748b;
    }

    button {
      width: 100%;
      padding: 14px;
      border-radius: 12px;
      border: 0;
      font-weight: 700;
      font-size: 15px;
      background: linear-gradient(135deg, #4f46e5, #7c3aed);
      color: white;
      cursor: pointer;
      transition: all 0.3s;
      box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
      margin-top: 8px;
    }

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(79, 70, 229, 0.5);
    }

    button:active {
      transform: translateY(0);
    }

    .register-link {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
      color: #94a3b8;
    }

    .register-link a {
      color: #a5b4fc;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s;
    }

    .register-link a:hover {
      color: #c7d2fe;
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .card {
        padding: 28px 24px;
      }

      h1 {
        font-size: 24px;
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
    // Prevenir clic derecho
    document.addEventListener('contextmenu', e => e.preventDefault());
    
    // Validación básica
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