</main>
<footer style="max-width:1100px;margin:10px auto 30px;padding:10px 16px;color:#9ca3af">
  <div class="jobs">
    <!-- “Caja de trabajos” (punto D: innovar una caja de trabajos en CSS) -->
    <div class="job"><h4>Alta de equipo</h4><p>Registro de ingreso y costos.</p></div>
    <div class="job"><h4>Agenda técnica</h4><p>Calendario mensual de visitas.</p></div>
    <div class="job"><h4>Kanban mantenimiento</h4><p>Flujo por estados + % avance.</p></div>
  </div>
  <p style="margin-top:12px">© <?= date('Y') ?> <?= safe(APP_NAME) ?>. Todos los derechos reservados.</p>
</footer>

<!-- ChatBot (punto E) -->
<button class="cb-btn" id="cb-btn">Chat Bot</button>
<div class="cb-box" id="cb-box">
  <div class="cb-head">Asistente Nibarra</div>
  <div class="cb-log" id="cb-log"></div>
  <div class="cb-input">
    <input id="cb-input" placeholder="Escribe tu pregunta...">
    <button id="cb-send">Enviar</button>
  </div>
</div>
<script>
(function(){
  const btn=document.getElementById('cb-btn'), box=document.getElementById('cb-box'),
        log=document.getElementById('cb-log'), inp=document.getElementById('cb-input'),
        send=document.getElementById('cb-send');
  btn.onclick=()=>box.classList.toggle('open');
  function push(cls,txt){ const d=document.createElement('div'); d.className=cls; d.textContent=txt; log.appendChild(d); log.scrollTop=log.scrollHeight; }
  function reply(q){
    const faqs=[
      {k:/ingreso|equipo|registr/i,a:'Ir a Equipos → “Nuevo ingreso”, completa y guarda.'},
      {k:/calendario|agenda/i,a:'En Calendario puedes ver/crear eventos por día.'},
      {k:/mantenim|avance|porcentaje|kanban/i,a:'En Mantenimiento mueve tarjetas y ajusta % avance.'},
      {k:/replica|servidor|windows|3307/i,a:'La app escribe en local; la réplica copia al remoto (3307).'}
    ];
    const f=faqs.find(x=>x.k.test(q)); return f?f.a:'Anotado. Un operador te responderá.'; 
  }
  send.onclick=()=>{ const q=inp.value.trim(); if(!q) return; push('me',q); push('bot',reply(q)); inp.value=''; inp.focus(); }
})();
</script>
</body></html>
