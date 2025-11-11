<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Registro | Nibarra</title>
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
      max-width: 480px;
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
      font-size: 48px;
      margin-bottom: 12px;
      display: inline-block;
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% {
        transform: translateY(0px);
      }
      50% {
        transform: translateY(-10px);
      }
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
      background: rgba(15, 23, 42, 0.8);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(30, 41, 59, 0.5);
      border-radius: 24px;
      padding: 40px 32px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    }

    h1 {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 8px;
      color: #f1f5f9;
    }

    .subtitle {
      color: #94a3b8;
      font-size: 14px;
      margin-bottom: 32px;
    }

    .error, .success {
      padding: 12px 16px;
      border-radius: 12px;
      margin-bottom: 20px;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
      animation: shake 0.5s ease;
    }

    .error {
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.3);
      color: #fca5a5;
    }

    .success {
      background: rgba(16, 185, 129, 0.1);
      border: 1px solid rgba(16, 185, 129, 0.3);
      color: #6ee7b7;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-10px); }
      75% { transform: translateX(10px); }
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      color: #cbd5e1;
      font-weight: 500;
      font-size: 14px;
    }

    .input-wrapper {
      position: relative;
    }

    input, select {
      width: 100%;
      padding: 14px 16px;
      border-radius: 12px;
      border: 1px solid #334155;
      background: #0b1220;
      color: #e5e7eb;
      font-size: 15px;
      transition: all 0.3s;
      outline: none;
    }

    input:focus, select:focus {
      border-color: #4f46e5;
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    input::placeholder {
      color: #64748b;
    }

    .toggle-password {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #94a3b8;
      cursor: pointer;
      font-size: 20px;
      padding: 4px;
      transition: color 0.2s;
    }

    .toggle-password:hover {
      color: #cbd5e1;
    }

    .password-strength {
      height: 4px;
      background: #1e293b;
      border-radius: 2px;
      margin-top: 8px;
      overflow: hidden;
    }

    .password-strength-bar {
      height: 100%;
      width: 0%;
      transition: all 0.3s;
    }

    .strength-weak { background: #ef4444; width: 33%; }
    .strength-medium { background: #f59e0b; width: 66%; }
    .strength-strong { background: #10b981; width: 100%; }

    .password-hint {
      font-size: 12px;
      color: #94a3b8;
      margin-top: 6px;
    }

    button[type="submit"] {
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

    button[type="submit"]:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(79, 70, 229, 0.5);
    }

    button[type="submit"]:active {
      transform: translateY(0);
    }

    .login-link {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
      color: #94a3b8;
    }

    .login-link a {
      color: #a5b4fc;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s;
    }

    .login-link a:hover {
      color: #c7d2fe;
      text-decoration: underline;
    }

    .terms {
      font-size: 12px;
      color: #64748b;
      margin-top: 16px;
      text-align: center;
      line-height: 1.6;
    }

    .terms a {
      color: #a5b4fc;
      text-decoration: none;
    }

    .terms a:hover {
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .card {
        padding: 28px 20px;
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

    <div class="card">
      <h1>Crear cuenta</h1>
      <p class="subtitle">Completa el formulario para registrarte</p>

      <?php if(!empty($error)): ?>
        <div class="error">
          <span>⚠️</span>
          <span><?= htmlspecialchars($error) ?></span>
        </div>
      <?php endif; ?>

      <?php if(!empty($success)): ?>
        <div class="success">
          <span>✓</span>
          <span><?= htmlspecialchars($success) ?></span>
        </div>
      <?php endif; ?>

      <form method="post" action="<?= ENV_APP['BASE_URL'] ?>/register" id="registerForm">
        <div class="form-group">
          <label for="nombre">Nombre completo</label>
          <input 
            type="text" 
            id="nombre" 
            name="nombre" 
            placeholder="Ej: Juan Pérez" 
            required 
            autofocus
            minlength="3"
            value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
          >
        </div>

        <div class="form-group">
          <label for="email">Correo electrónico</label>
          <input 
            type="email" 
            id="email" 
            name="email" 
            placeholder="usuario@ejemplo.com" 
            required
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
          >
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <div class="input-wrapper">
            <input 
              type="password" 
              id="password" 
              name="password" 
              placeholder="Mínimo 6 caracteres" 
              required
              minlength="6"
            >
            <button type="button" class="toggle-password" onclick="togglePassword('password')">
              👁️
            </button>
          </div>
          <div class="password-strength">
            <div class="password-strength-bar" id="strengthBar"></div>
          </div>
          <div class="password-hint" id="strengthText">Ingresa una contraseña segura</div>
        </div>

        <div class="form-group">
          <label for="password_confirm">Confirmar contraseña</label>
          <div class="input-wrapper">
            <input 
              type="password" 
              id="password_confirm" 
              name="password_confirm" 
              placeholder="Repite tu contraseña" 
              required
              minlength="6"
            >
            <button type="button" class="toggle-password" onclick="togglePassword('password_confirm')">
              👁️
            </button>
          </div>
        </div>

        <div class="form-group">
          <label for="role_id">Rol</label>
          <select id="role_id" name="role_id" required>
            <option value="">Selecciona un rol</option>
            <option value="2">Técnico - Gestiona mantenimientos</option>
            <option value="3" selected>Visor - Solo lectura</option>
          </select>
        </div>

        <button type="submit">Crear cuenta</button>

        <div class="terms">
          Al registrarte, aceptas los <a href="#">Términos de servicio</a> y la <a href="#">Política de privacidad</a>
        </div>
      </form>

      <div class="login-link">
        ¿Ya tienes cuenta? <a href="<?= ENV_APP['BASE_URL'] ?>/login">Inicia sesión aquí</a>
      </div>
    </div>
  </div>

  <script>
    // Toggle mostrar/ocultar contraseña
    function togglePassword(fieldId) {
      const input = document.getElementById(fieldId);
      const btn = input.nextElementSibling;
      
      if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
      } else {
        input.type = 'password';
        btn.textContent = '👁️';
      }
    }

    // Validar fuerza de contraseña
    document.getElementById('password').addEventListener('input', function(e) {
      const password = e.target.value;
      const bar = document.getElementById('strengthBar');
      const text = document.getElementById('strengthText');
      
      let strength = 0;
      
      if (password.length >= 6) strength++;
      if (password.length >= 10) strength++;
      if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
      if (/\d/.test(password)) strength++;
      if (/[^a-zA-Z0-9]/.test(password)) strength++;
      
      bar.className = 'password-strength-bar';
      
      if (strength <= 2) {
        bar.classList.add('strength-weak');
        text.textContent = 'Contraseña débil';
        text.style.color = '#ef4444';
      } else if (strength <= 3) {
        bar.classList.add('strength-medium');
        text.textContent = 'Contraseña media';
        text.style.color = '#f59e0b';
      } else {
        bar.classList.add('strength-strong');
        text.textContent = 'Contraseña fuerte';
        text.style.color = '#10b981';
      }
    });

    // Validar que las contraseñas coincidan
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      const password = document.getElementById('password').value;
      const confirm = document.getElementById('password_confirm').value;
      
      if (password !== confirm) {
        e.preventDefault();
        showToast('❌ Las contraseñas no coinciden', 'error');
        document.getElementById('password_confirm').focus();
      }
    });

    // Toast notification
    function showToast(message, type = 'success') {
      const toast = document.createElement('div');
      toast.textContent = message;
      
      const colors = {
        success: 'linear-gradient(135deg, #10b981, #059669)',
        error: 'linear-gradient(135deg, #ef4444, #dc2626)'
      };
      
      toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${colors[type]};
        color: white;
        padding: 12px 20px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        z-index: 10000;
        animation: slideIn 0.3s ease;
      `;
      document.body.appendChild(toast);
      
      setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    }

    // Animaciones
    const style = document.createElement('style');
    style.textContent = `
      @keyframes slideIn {
        from {
          transform: translateX(400px);
          opacity: 0;
        }
        to {
          transform: translateX(0);
          opacity: 1;
        }
      }
      
      @keyframes slideOut {
        from {
          transform: translateX(0);
          opacity: 1;
        }
        to {
          transform: translateX(400px);
          opacity: 0;
        }
      }
    `;
    document.head.appendChild(style);

    // Prevenir clic derecho
    document.addEventListener('contextmenu', e => {
      if (!e.target.matches('input')) {
        e.preventDefault();
      }
    });
  </script>
</body>
</html>