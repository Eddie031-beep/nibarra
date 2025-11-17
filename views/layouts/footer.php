<!-- ChatBot mejorado con IA Avanzada -->
<button class="chatbot-trigger" id="chatbot-trigger" title="Asistente IA">
  <div class="ai-icon">🤖</div>
  <span class="pulse-ring"></span>
</button>

<div class="chatbot-container" id="chatbot-container">
  <div class="chatbot-header">
    <div class="chatbot-avatar-container">
      <div class="chatbot-avatar">🤖</div>
      <div class="status-dot"></div>
    </div>
    <div style="flex:1">
      <h3>Nibarra AI Assistant</h3>
      <span class="chatbot-status">● Entrenado en tu sistema</span>
    </div>
    <button class="chatbot-minimize" id="chatbot-minimize" title="Minimizar">─</button>
    <button class="chatbot-close" id="chatbot-close">✕</button>
  </div>
  
  <div class="chatbot-welcome" id="chatbot-welcome">
    <div class="welcome-icon">✨</div>
    <h4>¡Hola! Soy tu asistente inteligente</h4>
    <p>Puedo ayudarte con análisis, predicciones y recomendaciones sobre tu sistema de mantenimiento.</p>
    
    <div class="quick-actions">
      <button class="quick-action-btn" onclick="sendQuickMessage('analiza el sistema')">
        📊 Analizar sistema
      </button>
      <button class="quick-action-btn" onclick="sendQuickMessage('recomienda acciones')">
        💡 Recomendaciones
      </button>
      <button class="quick-action-btn" onclick="sendQuickMessage('predice mantenimientos')">
        🔮 Predicciones
      </button>
      <button class="quick-action-btn" onclick="sendQuickMessage('calcula costos totales')">
        💰 Calcular costos
      </button>
    </div>
  </div>
  
  <div class="chatbot-messages" id="chatbot-messages">
    <!-- Los mensajes se agregarán dinámicamente -->
  </div>
  
  <div class="chatbot-suggestions" id="chatbot-suggestions" style="display:none">
    <!-- Las sugerencias aparecerán aquí -->
  </div>
  
  <div class="chatbot-input-wrapper">
    <button class="chatbot-attach" title="Próximamente">📎</button>
    <input 
      type="text" 
      class="chatbot-input" 
      id="chatbot-input" 
      placeholder="Escribe tu pregunta... (Ej: analiza el sistema)"
      autocomplete="off"
    >
    <button class="chatbot-send" id="chatbot-send" title="Enviar">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
      </svg>
    </button>
  </div>
  
  <div class="chatbot-footer-info">
    <span>💡 Tip: Puedes hablar naturalmente</span>
  </div>
</div>

<style>
/* 🎨 ChatBot Styles - Diseño tipo ChatGPT */
.chatbot-trigger{
  position:fixed;
  right:2rem;
  bottom:2rem;
  width:64px;
  height:64px;
  border-radius:50%;
  border:none;
  background:linear-gradient(135deg, #10b981 0%, #059669 100%);
  color:white;
  cursor:pointer;
  box-shadow:0 8px 30px rgba(16,185,129,0.4), 0 0 0 0 rgba(16,185,129,0.4);
  transition:all .3s cubic-bezier(0.4, 0, 0.2, 1);
  z-index:999;
  display:flex;
  align-items:center;
  justify-content:center;
  animation:float 3s ease-in-out infinite;
}

.chatbot-trigger:hover{
  transform:scale(1.1) translateY(-2px);
  box-shadow:0 12px 40px rgba(16,185,129,0.6);
}

.ai-icon{
  font-size:2rem;
  animation:rotate 20s linear infinite;
}

@keyframes rotate{
  from{ transform:rotate(0deg); }
  to{ transform:rotate(360deg); }
}

.pulse-ring{
  position:absolute;
  width:100%;
  height:100%;
  border-radius:50%;
  border:3px solid #10b981;
  animation:pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse{
  0%{ transform:scale(1); opacity:1; }
  100%{ transform:scale(1.6); opacity:0; }
}

@keyframes float{
  0%, 100%{ transform:translateY(0); }
  50%{ transform:translateY(-10px); }
}

.chatbot-container{
  position:fixed;
  right:2rem;
  bottom:6rem;
  width:420px;
  max-width:calc(100vw - 4rem);
  height:650px;
  max-height:calc(100vh - 10rem);
  background:#ffffff;
  border:1px solid #e5e7eb;
  border-radius:16px;
  box-shadow:0 20px 60px rgba(0,0,0,0.15), 0 0 0 1px rgba(0,0,0,0.05);
  display:none;
  flex-direction:column;
  z-index:1000;
  animation:slideUp .3s cubic-bezier(0.4, 0, 0.2, 1);
  overflow:hidden;
}

.chatbot-container.open{
  display:flex;
}

.chatbot-container.minimized{
  height:60px;
}

.chatbot-container.minimized .chatbot-messages,
.chatbot-container.minimized .chatbot-welcome,
.chatbot-container.minimized .chatbot-suggestions,
.chatbot-container.minimized .chatbot-input-wrapper,
.chatbot-container.minimized .chatbot-footer-info{
  display:none;
}

@keyframes slideUp{
  from{
    opacity:0;
    transform:translateY(20px) scale(0.95);
  }
  to{
    opacity:1;
    transform:translateY(0) scale(1);
  }
}

.chatbot-header{
  padding:1rem 1.25rem;
  background:linear-gradient(135deg, #10b981 0%, #059669 100%);
  display:flex;
  align-items:center;
  gap:0.75rem;
  color:white;
  box-shadow:0 2px 8px rgba(16,185,129,0.2);
  flex-shrink:0;
}

.chatbot-avatar-container{
  position:relative;
}

.chatbot-avatar{
  font-size:1.75rem;
  width:42px;
  height:42px;
  display:flex;
  align-items:center;
  justify-content:center;
  background:rgba(255,255,255,0.2);
  border-radius:50%;
  backdrop-filter:blur(10px);
}

.status-dot{
  position:absolute;
  bottom:2px;
  right:2px;
  width:10px;
  height:10px;
  background:#34d399;
  border:2px solid white;
  border-radius:50%;
  animation:pulse-dot 2s infinite;
}

@keyframes pulse-dot{
  0%, 100%{ opacity:1; }
  50%{ opacity:0.5; }
}

.chatbot-header h3{
  flex:1;
  font-size:1rem;
  margin:0;
  font-weight:700;
}

.chatbot-status{
  font-size:0.75rem;
  opacity:0.95;
  display:block;
  margin-top:2px;
}

.chatbot-minimize,
.chatbot-close{
  background:rgba(255,255,255,0.2);
  border:none;
  color:white;
  font-size:1.25rem;
  cursor:pointer;
  width:32px;
  height:32px;
  border-radius:8px;
  display:flex;
  align-items:center;
  justify-content:center;
  transition:all .2s;
  font-weight:700;
}

.chatbot-minimize:hover,
.chatbot-close:hover{
  background:rgba(255,255,255,0.3);
}

.chatbot-close:hover{
  transform:rotate(90deg);
}

.chatbot-welcome{
  padding:2rem 1.5rem;
  text-align:center;
  background:linear-gradient(180deg, #f9fafb 0%, #ffffff 100%);
  border-bottom:1px solid #e5e7eb;
}

.welcome-icon{
  font-size:3rem;
  margin-bottom:1rem;
  animation:bounce 2s ease-in-out infinite;
}

@keyframes bounce{
  0%, 100%{ transform:translateY(0); }
  50%{ transform:translateY(-10px); }
}

.chatbot-welcome h4{
  margin:0 0 0.5rem 0;
  color:#111827;
  font-size:1.25rem;
  font-weight:700;
}

.chatbot-welcome p{
  margin:0 0 1.5rem 0;
  color:#6b7280;
  font-size:0.875rem;
  line-height:1.5;
}

.quick-actions{
  display:grid;
  grid-template-columns:repeat(2, 1fr);
  gap:0.5rem;
}

.quick-action-btn{
  padding:0.75rem 1rem;
  background:white;
  border:1px solid #e5e7eb;
  border-radius:10px;
  color:#374151;
  font-size:0.8125rem;
  font-weight:600;
  cursor:pointer;
  transition:all .2s;
  text-align:left;
}

.quick-action-btn:hover{
  background:#f9fafb;
  border-color:#10b981;
  color:#10b981;
  transform:translateY(-2px);
  box-shadow:0 4px 12px rgba(16,185,129,0.15);
}

.chatbot-messages{
  flex:1;
  overflow-y:auto;
  padding:1.25rem;
  display:flex;
  flex-direction:column;
  gap:1rem;
  background:#ffffff;
}

.chatbot-messages::-webkit-scrollbar{
  width:6px;
}

.chatbot-messages::-webkit-scrollbar-track{
  background:transparent;
}

.chatbot-messages::-webkit-scrollbar-thumb{
  background:#d1d5db;
  border-radius:3px;
}

.chatbot-messages::-webkit-scrollbar-thumb:hover{
  background:#9ca3af;
}

.message{
  display:flex;
  gap:0.75rem;
  animation:fadeIn .3s ease;
  max-width:90%;
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
  width:36px;
  height:36px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:1.25rem;
  flex-shrink:0;
  box-shadow:0 2px 8px rgba(0,0,0,0.1);
}

.bot-message{
  align-self:flex-start;
}

.bot-message .message-avatar{
  background:linear-gradient(135deg, #10b981, #059669);
}

.user-message{
  flex-direction:row-reverse;
  align-self:flex-end;
}

.user-message .message-avatar{
  background:linear-gradient(135deg, #6366f1, #4f46e5);
}

.message-content{
  flex:1;
  padding:0.875rem 1rem;
  border-radius:16px;
  line-height:1.6;
  font-size:0.9375rem;
}

.bot-message .message-content{
  background:#f3f4f6;
  color:#111827;
  border-bottom-left-radius:4px;
}

.user-message .message-content{
  background:linear-gradient(135deg, #10b981, #059669);
  color:white;
  border-bottom-right-radius:4px;
}

.message-content strong{
  font-weight:700;
}

.message-content ul{
  margin:0.5rem 0 0 1.25rem;
  padding:0;
}

.message-content li{
  margin:0.25rem 0;
}

.typing-indicator{
  display:flex;
  gap:0.375rem;
  padding:0.75rem 1rem;
  background:#f3f4f6;
  border-radius:16px;
  width:fit-content;
}

.typing-dot{
  width:8px;
  height:8px;
  background:#9ca3af;
  border-radius:50%;
  animation:typing 1.4s infinite;
}

.typing-dot:nth-child(2){ animation-delay:0.2s; }
.typing-dot:nth-child(3){ animation-delay:0.4s; }

@keyframes typing{
  0%, 60%, 100%{
    transform:translateY(0);
    opacity:0.7;
  }
  30%{
    transform:translateY(-8px);
    opacity:1;
  }
}

.chatbot-suggestions{
  padding:0.75rem 1.25rem;
  border-top:1px solid #e5e7eb;
  background:#f9fafb;
}

.suggestion-chips{
  display:flex;
  flex-wrap:wrap;
  gap:0.5rem;
}

.suggestion-chip{
  padding:0.5rem 0.875rem;
  background:white;
  border:1px solid #e5e7eb;
  border-radius:20px;
  font-size:0.8125rem;
  color:#374151;
  cursor:pointer;
  transition:all .2s;
}

.suggestion-chip:hover{
  background:#10b981;
  border-color:#10b981;
  color:white;
  transform:translateY(-2px);
}

.chatbot-input-wrapper{
  padding:1rem 1.25rem;
  border-top:1px solid #e5e7eb;
  display:flex;
  gap:0.625rem;
  align-items:center;
  background:white;
}

.chatbot-attach{
  width:36px;
  height:36px;
  border-radius:8px;
  border:none;
  background:#f3f4f6;
  color:#6b7280;
  font-size:1.125rem;
  cursor:not-allowed;
  opacity:0.5;
  transition:all .2s;
  display:flex;
  align-items:center;
  justify-content:center;
}

.chatbot-input{
  flex:1;
  padding:0.75rem 1rem;
  border:1px solid #e5e7eb;
  background:#f9fafb;
  color:#111827;
  border-radius:24px;
  outline:none;
  transition:all .2s;
  font-size:0.9375rem;
}

.chatbot-input:focus{
  border-color:#10b981;
  background:white;
  box-shadow:0 0 0 3px rgba(16,185,129,0.1);
}

.chatbot-send{
  width:40px;
  height:40px;
  border-radius:50%;
  border:none;
  background:linear-gradient(135deg, #10b981, #059669);
  color:white;
  cursor:pointer;
  transition:all .2s;
  display:flex;
  align-items:center;
  justify-content:center;
  box-shadow:0 4px 12px rgba(16,185,129,0.3);
}

.chatbot-send:hover:not(:disabled){
  transform:scale(1.1);
  box-shadow:0 6px 20px rgba(16,185,129,0.4);
}

.chatbot-send:disabled{
  opacity:0.5;
  cursor:not-allowed;
}

.chatbot-footer-info{
  padding:0.625rem 1.25rem;
  background:#f9fafb;
  border-top:1px solid #e5e7eb;
  text-align:center;
  font-size:0.75rem;
  color:#6b7280;
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
  
  .quick-actions{
    grid-template-columns:1fr;
  }
}
</style>

<script>
(function(){
  const trigger = document.getElementById('chatbot-trigger');
  const container = document.getElementById('chatbot-container');
  const close = document.getElementById('chatbot-close');
  const minimize = document.getElementById('chatbot-minimize');
  const messages = document.getElementById('chatbot-messages');
  const suggestions = document.getElementById('chatbot-suggestions');
  const welcome = document.getElementById('chatbot-welcome');
  const input = document.getElementById('chatbot-input');
  const send = document.getElementById('chatbot-send');
  
  let isFirstMessage = true;
  
  // Toggle chatbot
  trigger.onclick = () => {
    container.classList.toggle('open');
    if(container.classList.contains('open')){
      container.classList.remove('minimized');
      input.focus();
    }
  };
  
  close.onclick = () => {
    container.classList.remove('open');
  };
  
  minimize.onclick = () => {
    container.classList.toggle('minimized');
  };
  
  // Acción rápida
  window.sendQuickMessage = function(message) {
    if(welcome) welcome.style.display = 'none';
    input.value = message;
    sendMessage();
  };
  
  // Agregar mensaje
  function addMessage(content, isUser = false){
    if(isFirstMessage && welcome) {
      welcome.style.display = 'none';
      isFirstMessage = false;
    }
    
    const msg = document.createElement('div');
    msg.className = `message ${isUser ? 'user-message' : 'bot-message'}`;
    
    const avatar = document.createElement('div');
    avatar.className = 'message-avatar';
    avatar.textContent = isUser ? '👤' : '🤖';
    
    const contentDiv = document.createElement('div');
    contentDiv.className = 'message-content';
    
    // Convertir markdown a HTML
    if(typeof content === 'string') {
      let formatted = content
        .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
        .replace(/### (.+)/g, '<h4 style="margin:0.75rem 0 0.5rem 0;font-size:1rem">$1</h4>')
        .replace(/## (.+)/g, '<h3 style="margin:0.875rem 0 0.5rem 0;font-size:1.125rem">$1</h3>')
        .replace(/• (.+)/g, '<div style="margin:0.25rem 0">• $1</div>')
        .replace(/\n\n/g, '<br><br>')
        .replace(/\n/g, '<br>');
      contentDiv.innerHTML = formatted;
    } else {
      contentDiv.textContent = content;
    }
    
    msg.appendChild(avatar);
    msg.appendChild(contentDiv);
    messages.appendChild(msg);
    
    // Scroll to bottom
    messages.scrollTop = messages.scrollHeight;
  }
  
  // Mostrar sugerencias
  function showSugerencias(sugs) {
    if(!sugs || sugs.length === 0) {
      suggestions.style.display = 'none';
      return;
    }
    
    suggestions.innerHTML = '<div class="suggestion-chips"></div>';
    const chips = suggestions.querySelector('.suggestion-chips');
    
    sugs.forEach(sug => {
      const chip = document.createElement('button');
      chip.className = 'suggestion-chip';
      chip.textContent = sug;
      chip.onclick = () => {
        input.value = sug;
        sendMessage();
      };
      chips.appendChild(chip);
    });
    
    suggestions.style.display = 'block';
  }
  
  // Indicador de escritura
  function showTyping(){
    const typing = document.createElement('div');
    typing.className = 'message bot-message typing-message';
    typing.innerHTML = `
      <div class="message-avatar">🤖</div>
      <div class="typing-indicator">
        <div class="typing-dot"></div>
        <div class="typing-dot"></div>
        <div class="typing-dot"></div>
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
        addMessage(data.respuesta, false);
        
        // Mostrar sugerencias si las hay
        if(data.sugerencias && data.sugerencias.length > 0) {
          showSugerencias(data.sugerencias);
        } else {
          suggestions.style.display = 'none';
        }
      } else {
        addMessage('Lo siento, ocurrió un error. Intenta de nuevo.', false);
      }
    } catch(error) {
      typing.remove();
      addMessage('❌ Error de conexión. Por favor, verifica tu conexión a internet.', false);
    } finally {
      send.disabled = false;
      input.focus();
    }
  }
  
  // Eventos
  send.onclick = sendMessage;
  
  input.addEventListener('keypress', (e) => {
    if(e.key === 'Enter' && !e.shiftKey){
      e.preventDefault();
      sendMessage();
    }
  });
  
  // Cerrar con ESC
  document.addEventListener('keydown', (e) => {
    if(e.key === 'Escape' && container.classList.contains('open')){
      container.classList.remove('open');
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