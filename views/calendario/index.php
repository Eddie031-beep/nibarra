<?php
// views/calendario/index.php
require_once dirname(__DIR__, 2) . '/src/helpers/db.php';

$db = db_ubuntu();

// Obtener eventos del calendario con información de mantenimientos
$sql = "SELECT 
          ce.id,
          ce.titulo,
          ce.inicio,
          ce.fin,
          ce.all_day,
          ce.color,
          ce.mantenimiento_id,
          m.tipo as mant_tipo,
          m.prioridad as mant_prioridad,
          m.estado as mant_estado,
          e.nombre as equipo_nombre,
          e.codigo as equipo_codigo
        FROM calendario_eventos ce
        LEFT JOIN mantenimientos m ON ce.mantenimiento_id = m.id
        LEFT JOIN equipos e ON m.equipo_id = e.id
        ORDER BY ce.inicio DESC";

$eventos = $db->query($sql)->fetchAll();
?>

<div class="card">
  <div class="card-header">
    <div>
      <h2 class="card-title">📅 Calendario de Mantenimientos</h2>
      <p style="color:var(--text-secondary);margin:0;font-size:0.9rem">
        Visualización mensual de eventos programados
      </p>
    </div>
    <div class="flex gap-2">
      <button onclick="showLegend()" class="btn btn-secondary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="16" x2="12" y2="12"></line>
          <line x1="12" y1="8" x2="12.01" y2="8"></line>
        </svg>
        Leyenda
      </button>
      <button onclick="todayView()" class="btn btn-secondary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
          <line x1="16" y1="2" x2="16" y2="6"></line>
          <line x1="8" y1="2" x2="8" y2="6"></line>
          <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
        Hoy
      </button>
      <button onclick="exportCalendar()" class="btn btn-secondary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
          <polyline points="7 10 12 15 17 10"></polyline>
          <line x1="12" y1="15" x2="12" y2="3"></line>
        </svg>
        Exportar
      </button>
    </div>
  </div>

  <!-- Leyenda (oculta por defecto) -->
  <div id="legendPanel" style="display:none;padding:1rem;background:var(--bg-hover);border-radius:0.5rem;margin-bottom:1rem">
    <h4 style="margin-bottom:1rem;font-size:1rem">🎨 Código de Colores</h4>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem">
      <div style="display:flex;align-items:center;gap:0.5rem">
        <div style="width:20px;height:20px;background:#2563eb;border-radius:4px"></div>
        <span>Preventivo</span>
      </div>
      <div style="display:flex;align-items:center;gap:0.5rem">
        <div style="width:20px;height:20px;background:#ef4444;border-radius:4px"></div>
        <span>Correctivo</span>
      </div>
      <div style="display:flex;align-items:center;gap:0.5rem">
        <div style="width:20px;height:20px;background:#8b5cf6;border-radius:4px"></div>
        <span>Inspección</span>
      </div>
      <div style="display:flex;align-items:center;gap:0.5rem">
        <div style="width:20px;height:20px;background:#10b981;border-radius:4px"></div>
        <span>Evento General</span>
      </div>
    </div>
  </div>

  <!-- Filtros rápidos -->
  <div style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap">
    <button class="btn btn-sm btn-secondary filter-btn active" data-filter="all" onclick="filterEvents('all')">
      Todos (<?= count($eventos) ?>)
    </button>
    <button class="btn btn-sm btn-secondary filter-btn" data-filter="preventivo" onclick="filterEvents('preventivo')">
      🛡️ Preventivo
    </button>
    <button class="btn btn-sm btn-secondary filter-btn" data-filter="correctivo" onclick="filterEvents('correctivo')">
      🔨 Correctivo
    </button>
    <button class="btn btn-sm btn-secondary filter-btn" data-filter="inspeccion" onclick="filterEvents('inspeccion')">
      🔍 Inspección
    </button>
  </div>

  <!-- Calendario -->
  <div id="calendar" style="background:var(--bg-card);padding:1rem;border-radius:0.5rem"></div>

  <!-- Resumen de próximos eventos -->
  <div style="margin-top:2rem">
    <h3 style="font-size:1.1rem;margin-bottom:1rem;border-bottom:2px solid var(--border-color);padding-bottom:0.5rem">
      📋 Próximos Eventos (7 días)
    </h3>
    <div id="upcomingEvents" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1rem">
      <!-- Se llena dinámicamente -->
    </div>
  </div>
</div>

<!-- Modal para detalles del evento -->
<div id="eventModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.7);z-index:9999;padding:2rem" onclick="closeModal()">
  <div style="max-width:600px;margin:auto;background:var(--bg-card);border-radius:1rem;padding:2rem;border:1px solid var(--border-color)" onclick="event.stopPropagation()">
    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:1.5rem">
      <h3 id="modalTitle" style="font-size:1.3rem;margin:0"></h3>
      <button onclick="closeModal()" style="background:none;border:none;color:var(--text-secondary);font-size:1.5rem;cursor:pointer">×</button>
    </div>
    <div id="modalContent"></div>
    <div style="margin-top:1.5rem;padding-top:1rem;border-top:1px solid var(--border-color);display:flex;gap:0.5rem;justify-content:flex-end">
      <button onclick="closeModal()" class="btn btn-secondary">Cerrar</button>
      <button onclick="editEvent()" class="btn btn-primary">Editar</button>
    </div>
  </div>
</div>

<script>
// Datos de eventos desde PHP
const eventosData = <?= json_encode($eventos) ?>;

// Configurar colores por tipo
const tipoColors = {
  'preventivo': '#2563eb',
  'correctivo': '#ef4444',
  'inspeccion': '#8b5cf6',
  'default': '#10b981'
};

// Preparar eventos para FullCalendar
const calendarEvents = eventosData.map(e => ({
  id: e.id,
  title: e.titulo,
  start: e.inicio,
  end: e.fin,
  allDay: e.all_day == 1,
  backgroundColor: e.color || tipoColors[e.mant_tipo] || tipoColors.default,
  borderColor: 'transparent',
  extendedProps: {
    mantId: e.mantenimiento_id,
    tipo: e.mant_tipo,
    prioridad: e.mant_prioridad,
    estado: e.mant_estado,
    equipo: e.equipo_nombre,
    codigo: e.equipo_codigo
  }
}));

// Inicializar calendario
let calendar;
document.addEventListener('DOMContentLoaded', function() {
  const calendarEl = document.getElementById('calendar');
  
  calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'es',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,listWeek'
    },
    buttonText: {
      today: 'Hoy',
      month: 'Mes',
      week: 'Semana',
      list: 'Lista'
    },
    events: calendarEvents,
    eventClick: function(info) {
      showEventDetails(info.event);
    },
    eventDidMount: function(info) {
      // Añadir tooltip
      info.el.title = `${info.event.title}\n${info.event.extendedProps.equipo || ''}`;
    }
  });
  
  calendar.render();
  
  // Mostrar próximos eventos
  showUpcomingEvents();
});

// Mostrar detalles del evento
let currentEventId = null;
function showEventDetails(event) {
  currentEventId = event.id;
  const props = event.extendedProps;
  
  document.getElementById('modalTitle').textContent = event.title;
  
  const content = `
    <div style="display:grid;gap:1rem">
      <div>
        <label style="font-size:0.85rem;color:var(--text-secondary)">Fecha y Hora</label>
        <div style="font-size:1rem;margin-top:0.25rem">
          📅 ${new Date(event.start).toLocaleString('es-PA')}
          ${event.end ? ' → ' + new Date(event.end).toLocaleString('es-PA') : ''}
        </div>
      </div>
      
      ${props.equipo ? `
        <div>
          <label style="font-size:0.85rem;color:var(--text-secondary)">Equipo</label>
          <div style="font-size:1rem;margin-top:0.25rem">
            ⚙️ ${props.codigo} - ${props.equipo}
          </div>
        </div>
      ` : ''}
      
      ${props.tipo ? `
        <div>
          <label style="font-size:0.85rem;color:var(--text-secondary)">Tipo de Mantenimiento</label>
          <div style="margin-top:0.25rem">
            <span class="badge badge-${props.tipo === 'preventivo' ? 'info' : props.tipo === 'correctivo' ? 'danger' : 'secondary'}">
              ${props.tipo.toUpperCase()}
            </span>
          </div>
        </div>
      ` : ''}
      
      ${props.prioridad ? `
        <div>
          <label style="font-size:0.85rem;color:var(--text-secondary)">Prioridad</label>
          <div style="margin-top:0.25rem">
            <span class="badge badge-${props.prioridad === 'critica' ? 'danger' : props.prioridad === 'alta' ? 'warning' : 'info'}">
              ${props.prioridad.toUpperCase()}
            </span>
          </div>
        </div>
      ` : ''}
      
      ${props.estado ? `
        <div>
          <label style="font-size:0.85rem;color:var(--text-secondary)">Estado</label>
          <div style="margin-top:0.25rem">
            <span class="badge badge-${props.estado === 'completado' ? 'success' : props.estado === 'en_progreso' ? 'warning' : 'info'}">
              ${props.estado.toUpperCase().replace('_', ' ')}
            </span>
          </div>
        </div>
      ` : ''}
      
      ${props.mantId ? `
        <div style="margin-top:1rem">
          <a href="/nibarra/public/mantenimiento/detalle?id=${props.mantId}" class="btn btn-sm btn-primary" style="width:100%">
            Ver Orden de Mantenimiento →
          </a>
        </div>
      ` : ''}
    </div>
  `;
  
  document.getElementById('modalContent').innerHTML = content;
  document.getElementById('eventModal').style.display = 'block';
}

function closeModal() {
  document.getElementById('eventModal').style.display = 'none';
}

function editEvent() {
  const props = calendar.getEventById(currentEventId).extendedProps;
  if (props.mantId) {
    location.href = `/nibarra/public/mantenimiento/editar?id=${props.mantId}`;
  } else {
    alert('Esta funcionalidad estará disponible próximamente');
  }
}

// Filtrar eventos por tipo
function filterEvents(tipo) {
  // Actualizar botones activos
  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.classList.remove('active');
  });
  event.target.classList.add('active');
  
  // Filtrar eventos
  const allEvents = calendar.getEvents();
  allEvents.forEach(ev => {
    if (tipo === 'all' || ev.extendedProps.tipo === tipo) {
      ev.setProp('display', 'auto');
    } else {
      ev.setProp('display', 'none');
    }
  });
}

// Vista de hoy
function todayView() {
  calendar.today();
}

// Mostrar/ocultar leyenda
function showLegend() {
  const panel = document.getElementById('legendPanel');
  panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}

// Exportar calendario
function exportCalendar() {
  const eventos = calendar.getEvents().map(e => ({
    titulo: e.title,
    inicio: e.start.toISOString(),
    fin: e.end ? e.end.toISOString() : null
  }));
  
  const dataStr = JSON.stringify(eventos, null, 2);
  const dataBlob = new Blob([dataStr], {type: 'application/json'});
  const url = URL.createObjectURL(dataBlob);
  const link = document.createElement('a');
  link.href = url;
  link.download = `calendario_nibarra_${new Date().toISOString().split('T')[0]}.json`;
  link.click();
}

// Mostrar próximos eventos
function showUpcomingEvents() {
  const now = new Date();
  const nextWeek = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
  
  const upcoming = eventosData
    .filter(e => {
      const start = new Date(e.inicio);
      return start >= now && start <= nextWeek;
    })
    .sort((a, b) => new Date(a.inicio) - new Date(b.inicio))
    .slice(0, 6);
  
  const container = document.getElementById('upcomingEvents');
  
  if (upcoming.length === 0) {
    container.innerHTML = '<div style="color:var(--text-muted);text-align:center;padding:2rem;grid-column:1/-1">No hay eventos próximos en los próximos 7 días</div>';
    return;
  }
  
  container.innerHTML = upcoming.map(e => {
    const color = e.color || tipoColors[e.mant_tipo] || tipoColors.default;
    const date = new Date(e.inicio);
    const daysUntil = Math.ceil((date - now) / (1000 * 60 * 60 * 24));
    
    return `
      <div style="padding:1rem;background:var(--bg-hover);border-radius:0.5rem;border-left:4px solid ${color};cursor:pointer" onclick="gotoDate('${e.inicio}')">
        <div style="font-weight:600;margin-bottom:0.5rem">${e.titulo}</div>
        <div style="font-size:0.85rem;color:var(--text-secondary);margin-bottom:0.25rem">
          📅 ${date.toLocaleDateString('es-PA', {weekday:'short', day:'numeric', month:'short'})}
        </div>
        ${e.equipo_nombre ? `
          <div style="font-size:0.85rem;color:var(--text-secondary)">
            ⚙️ ${e.equipo_codigo} - ${e.equipo_nombre}
          </div>
        ` : ''}
        <div style="margin-top:0.5rem;font-size:0.75rem;color:${color}">
          ${daysUntil === 0 ? '¡HOY!' : daysUntil === 1 ? 'Mañana' : `En ${daysUntil} días`}
        </div>
      </div>
    `;
  }).join('');
}

function gotoDate(dateStr) {
  calendar.gotoDate(dateStr);
}
</script>

<style>
/* Estilos personalizados para FullCalendar */
.fc {
  --fc-border-color: var(--border-color);
  --fc-button-bg-color: var(--primary);
  --fc-button-border-color: var(--primary);
  --fc-button-hover-bg-color: var(--primary-dark);
  --fc-button-hover-border-color: var(--primary-dark);
  --fc-button-active-bg-color: var(--primary-dark);
  --fc-button-active-border-color: var(--primary-dark);
  --fc-today-bg-color: rgba(59, 130, 246, 0.1);
}

.fc .fc-button {
  text-transform: capitalize;
}

.fc-event {
  cursor: pointer;
  font-size: 0.85rem;
}

.fc-daygrid-event {
  padding: 2px 4px;
  border-radius: 3px;
}

.filter-btn.active {
  background: var(--primary);
  color: white;
  border-color: var(--primary);
}
</style>