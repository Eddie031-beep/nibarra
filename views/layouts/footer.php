</main>

<footer style="max-width:1400px;margin:2rem auto;padding:2rem;color:#9ba6b8;border-top:1px solid #2a3347">
  <div class="jobs-grid">
    <div class="job-card">
      <div class="job-icon">🔧</div>
      <h4>Alta de equipos</h4>
      <p>Registro completo de activos con seguimiento de costos y estados</p>
    </div>
    <div class="job-card">
      <div class="job-icon">📅</div>
      <h4>Agenda técnica</h4>
      <p>Calendario visual para programar visitas y eventos de mantenimiento</p>
    </div>
    <div class="job-card">
      <div class="job-icon">📊</div>
      <h4>Kanban inteligente</h4>
      <p>Flujo de trabajo visual con seguimiento de progreso por tareas</p>
    </div>
  </div>
  <p style="margin-top:2rem;text-align:center">© <?= date('Y') ?> <?= safe(APP_NAME) ?>. Sistema de Gestión de Mantenimiento.</p>
</footer>

<!-- ChatBot mejorado con Mint Professional -->
<button class="chatbot-trigger" id="chatbot-trigger" title="Asistente Virtual">
  <span style="font-size:1.75rem">🤖</span>
  <span class="pulse-ring"></span>
</button>

<div class="chatbot-container" id="chatbot-container">
  <div class="chatbot-header">
    <div class="chatbot-avatar">🤖</div>
    <div style="flex:1">
      <h3>Asistente Nibarra</h3>
      <span class="chatbot-status">● En línea</span>
    </div>
    <button class="chatbot-close" id="chatbot-close">✕</button>
  </div>
  
  <div class="chatbot-messages" id="chatbot-messages">
    <div class="message bot-message">
      <div class="message-avatar">🤖</div>
      <div class="message-content">
        <strong>¡Hola! Soy tu asistente virtual.</strong><br><br>
        Puedo ayudarte con información sobre:<br>
        <ul style="margin:0.5rem 0;padding-left:1.2rem">
          <li>📊 Estado de equipos</li>
          <li>📋 Mantenimientos pendientes</li>
          <li>📅 Eventos del calendario</li>
          <li>💰 Resumen de costos</li>
        </ul>
        Escribe <strong>ayuda</strong> para ver todos los comandos.
      </div>
    </div>
  </div>
  
  <div class="chatbot-input-wrapper">
    <input 
      type="text" 
      class="chatbot-input" 
      id="chatbot-input" 
      placeholder="Escribe tu pregunta..."
      autocomplete="off"
    >
    <button class="chatbot-send" id="chatbot-send" title="Enviar">
      ➤
    </button>
  </div>
</div>

<style>
/* 🎨 Jobs Grid con Mint Professional */
.jobs-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));
  gap:1.5rem;
  margin-top:1.5rem;
}

.job-card{
  background:#1a1f35;
  border:1px solid #2a3347;
  border-radius:1rem;
  padding:1.5rem;
  transition:all .3s;
}

.job-card:hover{
  transform:translateY(-4px);
  box-shadow:0 8px 30px rgba(16,185,129,0.2);
  border-color:#10b981;
}

.job-icon{
  font-size:2.5rem;
  margin-bottom:1rem;
  filter:drop-shadow(0 0 10px rgba(16,185,129,0.3));
}

.job-card h4{
  margin-bottom:0.5rem;
  color:#10b981;
  font-size:1.125rem;
}

.job-card p{
  color:#9ba6b8;
  font-size:0.875rem;
  line-height:1.5;
}

/* 🤖 ChatBot Styles con Mint Professional */
.chatbot-trigger{
  position:fixed;
  right:2rem;
  bottom:2rem;
  width:60px;
  height:60px;
  border-radius:50%;
  border:none;
  background:linear-gradient(135deg, #10b981, #059669);
  color:white;
  font-size:1.75rem;
  cursor:pointer;
  box-shadow:0 8px 30px rgba(16,185,129,0.5);
  transition:all .3s;
  z-index:999;
  display:flex;
  align-items:center;
  justify-content:center;
  animation:float 3s ease-in-out infinite;
}

.chatbot-trigger:hover{
  transform:scale(1.1);
  box-shadow:0 12px 40px rgba(16,185,129,0.7);
  background:linear-gradient(135deg, #059669, #047857);
}

.pulse-ring{
  position:absolute;
  width:100%;
  height:100%;
  border-radius:50%;
  border:3px solid #10b981;
  animation:pulse 2s infinite;
}

@keyframes pulse{
  0%{
    transform:scale(1);
    opacity:1;
  }
  100%{
    transform:scale(1.5);
    opacity:0;
  }
}

@keyframes float{
  0%, 100%{
    transform:translateY(0);
  }
  50%{
    transform:translateY(-10px);
  }
}

.chatbot-container{
  position:fixed;
  right:2rem;
  bottom:5.5rem;
  width:400px;
  max-width:calc(100vw - 4rem);
  height:600px;
  max-height:calc(100vh - 10rem);
  background:#1a1f35;
  border:1px solid #2a3347;
  border-radius:1rem;
  box-shadow:0 20px 60px rgba(16,185,129,0.2);
  display:none;
  flex-direction:column;
  z-index:1000;
  animation:slideUp .3s ease;
}

.chatbot-container.open{
  display:flex;
}

@keyframes slideUp{
  from{
    opacity:0;
    transform:translateY(20px);
  }
  to{
    opacity:1;
    transform:translateY(0);
  }
}

.chatbot-header{
  padding:1rem 1.5rem;
  background:linear-gradient(135deg, #10b981, #059669);
  display:flex;
  align-items:center;
  gap:1rem;
  color:white;
  box-shadow:0 4px 12px rgba(16,185,129,0.3);
}

.chatbot-avatar{
  font-size:2rem;
  width:40px;
  height:40px;
  display:flex;
  align-items:center;
  justify-content:center;
  background:rgba(255,255,255,.2);
  border-radius:50%;
}

.chatbot-header h3{
  flex:1;
  font-size:1.125rem;
  margin:0;
}

.chatbot-status{
  font-size:0.75rem;
  opacity:0.9;
  display:block;
}

.chatbot-close{
  background:rgba(255,255,255,.2);
  border:none;
  color:white;
  font-size:1.5rem;
  cursor:pointer;
  width:32px;
  height:32px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  transition:all .2s;
}

.chatbot-close:hover{
  background:rgba(255,255,255,.3);
  transform:rotate(90deg);
}

.chatbot-messages{
  flex:1;
  overflow-y:auto;
  padding:1.5rem;
  display:flex;
  flex-direction:column;
  gap:1rem;
}

.chatbot-messages::-webkit-scrollbar{
  width:6px;
}

.chatbot-messages::-webkit-scrollbar-track{
  background:transparent;
}

.chatbot-messages::-webkit-scrollbar-thumb{
  background:#2a3347;
  border-radius:3px;
}

.message{
  display:flex;
  gap:0.75rem;
  animation:fadeIn .3s ease;
}

@keyframes fadeIn{
  from{
    opacity:0;
    transform:translateY(10px);
  }
  to{
    opacity:1;
    transform:translateY(0);
  }
}

.message-avatar{
  width:32px;
  height:32px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:1.25rem;
  flex-shrink:0;
}

.bot-message .message-avatar{
  background:linear-gradient(135deg, #10b981, #059669);
}

.user-message{
  flex-direction:row-reverse;
}

.user-message .message-avatar{
  background:linear-gradient(135deg, #1e3a8a, #3b82f6);
}

.message-content{
  max-width:80%;
  padding:0.75rem 1rem;
  border-radius:1rem;
  line-height:1.5;
  font-size:0.9rem;
}

.bot-message .message-content{
  background:#111827;
  border:1px solid #2a3347;
  border-bottom-left-radius:0.25rem;
}

.user-message .message-content{
  background:linear-gradient(135deg, #10b981, #059669);
  color:white;
  border-bottom-right-radius:0.25rem;
}

.message-content ul{
  margin:0.5rem 0 0 1rem;
  padding:0;
}

.message-content li{
  margin:0.25rem 0;
}

.chatbot-input-wrapper{
  padding:1rem 1.5rem;
  border-top:1px solid #2a3347;
  display:flex;
  gap:0.75rem;
}

.chatbot-input{
  flex:1;
  padding:0.75rem 1rem;
  border:1px solid #334155;
  background:#111827;
  color:#f0f4f8;
  border-radius:2rem;
  outline:none;
  transition:all .2s;
}

.chatbot-input:focus{
  border-color:#10b981;
  box-shadow:0 0 0 3px rgba(16,185,129,.1);
}

.chatbot-send{
  width:40px;
  height:40px;
  border-radius:50%;
  border:none;
  background:linear-gradient(135deg, #10b981, #059669);
  color:white;
  font-size:1.25rem;
  cursor:pointer;
  transition:all .2s;
  display:flex;
  align-items:center;
  justify-content:center;
}

.chatbot-send:hover{
  transform:scale(1.1);
  box-shadow:0 4px 15px rgba(16,185,129,0.4);
}

.chatbot-send:disabled{
  opacity:0.5;
  cursor:not-allowed;
}

.typing-indicator{
  display:flex;
  gap:0.25rem;
  padding:0.5rem;
}

.typing-dot{
  width:8px;
  height:8px;
  background:#9ba6b8;
  border-radius:50%;
  animation:typing 1.4s infinite;
}

.typing-dot:nth-child(2){
  animation-delay:0.2s;
}

.typing-dot:nth-child(3){
  animation-delay:0.4s;
}

@keyframes typing{
  0%, 60%, 100%{
    transform:translateY(0);
    opacity:0.7;
  }
  30%{
    transform:translateY(-10px);
    opacity:1;
  }
}

@media(max-width:768px){
  .chatbot-container{
    right:1rem;
    bottom:5rem;
    width:calc(100vw - 2rem);
    height:calc(100vh - 8rem);
  }
  
  .chatbot-trigger{
    right:1rem;
    bottom:1rem;
  }
}
</style>

<script>
(function(){
  const trigger = document.getElementById('chatbot-trigger');
  const container = document.getElementById('chatbot-container');
  const close = document.getElementById('chatbot-close');
  const messages = document.getElementById('chatbot-messages');
  const input = document.getElementById('chatbot-input');
  const send = document.getElementById('chatbot-send');
  
  // Toggle chatbot
  trigger.onclick = () => {
    container.classList.toggle('open');
    if(container.classList.contains('open')){
      input.focus();
    }
  };
  
  close.onclick = () => {
    container.classList.remove('open');
  };
  
  // Agregar mensaje
  function addMessage(content, isUser = false){
    const msg = document.createElement('div');
    msg.className = `message ${isUser ? 'user-message' : 'bot-message'}`;
    
    const avatar = document.createElement('div');
    avatar.className = 'message-avatar';
    avatar.textContent = isUser ? '👤' : '🤖';
    
    const contentDiv = document.createElement('div');
    contentDiv.className = 'message-content';
    
    // Permitir HTML básico para formato
    if(content.includes('<')) {
      contentDiv.innerHTML = content;
    } else {
      contentDiv.textContent = content;
    }
    
    msg.appendChild(avatar);
    msg.appendChild(contentDiv);
    messages.appendChild(msg);
    
    // Scroll to bottom
    messages.scrollTop = messages.scrollHeight;
  }
  
  // Indicador de escritura
  function showTyping(){
    const typing = document.createElement('div');
    typing.className = 'message bot-message typing-message';
    typing.innerHTML = `
      <div class="message-avatar">🤖</div>
      <div class="message-content">
        <div class="typing-indicator">
          <div class="typing-dot"></div>
          <div class="typing-dot"></div>
          <div class="typing-dot"></div>
        </div>
      </div>
    `;
    messages.appendChild(typing);
    messages.scrollTop = messages.scrollHeight;
    return typing;
  }
  
  // Enviar mensaje
  async function sendMessage(){
    const question = input.value.trim();
    if(!question) return;
    
    addMessage(question, true);
    input.value = '';
    send.disabled = true;
    
    const typing = showTyping();
    
    try {
      const response = await fetch('<?= ENV_APP['BASE_URL'] ?>/chatbot/query', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `pregunta=${encodeURIComponent(question)}`
      });
      
      const data = await response.json();
      
      typing.remove();
      
      if(data.ok && data.respuesta){
        // Convertir markdown básico a HTML
        let formatted = data.respuesta
          .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
          .replace(/\n/g, '<br>');
        
        addMessage(formatted, false);
      } else {
        addMessage('Lo siento, ocurrió un error. Intenta de nuevo.', false);
      }
    } catch(error) {
      typing.remove();
      addMessage('Error de conexión. Por favor, verifica tu conexión a internet.', false);
    } finally {
      send.disabled = false;
      input.focus();
    }
  }
  
  // Eventos
  send.onclick = sendMessage;
  
  input.addEventListener('keypress', (e) => {
    if(e.key === 'Enter'){
      e.preventDefault();
      sendMessage();
    }
  });
  
  // Anti-copy solo fuera de inputs
  document.addEventListener('contextmenu', e => {
    if(!e.target.matches('input, textarea')){
      e.preventDefault();
    }
  });
  
  document.addEventListener('keydown', e => {
    const k = e.key?.toLowerCase();
    if((e.ctrlKey || e.metaKey) && ['u','s','p'].includes(k) && !e.target.matches('input, textarea')){
      e.preventDefault();
    }
  });
})();
</script>
</body></html>