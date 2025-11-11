<?php
$first = new DateTime(sprintf('%04d-%02d-01',$y,$m));
$days  = (int)$first->format('t');
$startDow = (int)$first->format('N');
$prev = (clone $first)->modify('-1 month'); 
$next = (clone $first)->modify('+1 month');
$map = []; 
foreach($eventos as $ev){ 
  $d = (new DateTime($ev['inicio']))->format('Y-m-d'); 
  $map[$d][] = $ev; 
}
$monthNames = [
  1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',
  7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'
];
?>

<style>
.calendar-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:1.5rem;
  background:linear-gradient(135deg, var(--accent-blue), var(--accent-purple));
  border-radius:1rem 1rem 0 0;
}

.calendar-header h2{
  margin:0;
  color:white;
  font-size:1.75rem;
  font-weight:700;
}

.calendar-nav{
  display:flex;
  gap:0.5rem;
}

.calendar-nav-btn{
  padding:0.5rem 1rem;
  background:rgba(255,255,255,.2);
  border:1px solid rgba(255,255,255,.3);
  border-radius:0.5rem;
  color:white;
  font-weight:600;
  transition:all .2s;
  display:flex;
  align-items:center;
  gap:0.5rem;
}

.calendar-nav-btn:hover{
  background:rgba(255,255,255,.3);
  transform:translateY(-2px);
  text-decoration:none;
}

.calendar-body{
  padding:1.5rem;
}

.calendar-weekdays{
  display:grid;
  grid-template-columns:repeat(7, 1fr);
  gap:0.5rem;
  margin-bottom:0.5rem;
}

.weekday{
  text-align:center;
  padding:0.75rem;
  font-weight:600;
  color:var(--accent-blue);
  font-size:0.875rem;
}

.calendar-days{
  display:grid;
  grid-template-columns:repeat(7, 1fr);
  gap:0.5rem;
}

.calendar-day{
  background:var(--bg-secondary);
  border:1px solid var(--border-color);
  border-radius:0.75rem;
  min-height:120px;
  padding:0.75rem;
  transition:all .2s;
  position:relative;
}

.calendar-day:hover{
  border-color:var(--accent-blue);
  box-shadow:0 4px 15px rgba(33,150,243,.2);
  transform:translateY(-2px);
}

.calendar-day.today{
  border-color:var(--accent-green);
  background:rgba(0,200,83,.05);
}

.day-number{
  font-weight:700;
  color:var(--text-primary);
  margin-bottom:0.5rem;
  font-size:1.125rem;
}

.calendar-day.today .day-number{
  color:var(--accent-green);
}

.event-item{
  background:var(--accent-blue);
  color:white;
  padding:0.375rem 0.5rem;
  border-radius:0.375rem;
  font-size:0.75rem;
  margin-bottom:0.375rem;
  cursor:pointer;
  transition:all .2s;
  position:relative;
  overflow:hidden;
}

.event-item:hover{
  transform:scale(1.02);
  box-shadow:0 2px 8px rgba(0,0,0,.3);
}

.event-item.all-day{
  background:var(--accent-purple);
}

.event-title{
  font-weight:600;
  margin-bottom:0.125rem;
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
}

.event-time{
  opacity:0.9;
  font-size:0.7rem;
}

.event-delete{
  position:absolute;
  top:0;
  right:0;
  background:rgba(244,67,54,.9);
  border:none;
  color:white;
  padding:0.25rem 0.5rem;
  border-radius:0 0.375rem 0 0.375rem;
  cursor:pointer;
  opacity:0;
  transition:opacity .2s;
  font-size:0.7rem;
}

.event-item:hover .event-delete{
  opacity:1;
}

.add-event-section{
  margin-top:2rem;
  padding:1.5rem;
  background:var(--bg-secondary);
  border:1px solid var(--border-color);
  border-radius:1rem;
}

.add-event-section h3{
  margin:0 0 1rem 0;
  color:var(--text-primary);
  display:flex;
  align-items:center;
  gap:0.5rem;
}

.event-form{
  display:grid;
  grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));
  gap:1rem;
}

.form-group{
  display:flex;
  flex-direction:column;
  gap:0.5rem;
}

.form-group label{
  font-size:0.875rem;
  color:var(--text-secondary);
  font-weight:500;
}

.form-group input,
.form-group select{
  padding:0.75rem;
  background:var(--bg-primary);
  border:1px solid var(--border-color);
  border-radius:0.5rem;
  color:var(--text-primary);
  transition:all .2s;
}

.form-group input:focus,
.form-group select:focus{
  outline:none;
  border-color:var(--accent-blue);
  box-shadow:0 0 0 3px rgba(33,150,243,.1);
}

.form-group input[type="color"]{
  height:45px;
  cursor:pointer;
}

.checkbox-group{
  display:flex;
  align-items:center;
  gap:0.5rem;
}

.checkbox-group input[type="checkbox"]{
  width:20px;
  height:20px;
  cursor:pointer;
  accent-color:var(--accent-blue);
}

.submit-btn{
  padding:0.75rem 2rem;
  background:linear-gradient(135deg, var(--accent-blue), var(--accent-purple));
  border:none;
  border-radius:0.5rem;
  color:white;
  font-weight:700;
  cursor:pointer;
  transition:all .3s;
  box-shadow:0 4px 15px rgba(33,150,243,.4);
}

.submit-btn:hover{
  transform:translateY(-2px);
  box-shadow:0 6px 20px rgba(33,150,243,.5);
}

@media(max-width:768px){
  .calendar-days{
    gap:0.25rem;
  }
  
  .calendar-day{
    min-height:80px;
    padding:0.5rem;
  }
  
  .day-number{
    font-size:0.875rem;
  }
  
  .event-item{
    font-size:0.65rem;
  }
  
  .event-form{
    grid-template-columns:1fr;
  }
}
</style>

<section class="card">
  <div class="calendar-header">
    <h2>📅 <?= $monthNames[(int)$m] ?> <?= $y ?></h2>
    <div class="calendar-nav">
      <a href="<?= ENV_APP['BASE_URL'].'/calendario?y='.$prev->format('Y').'&m='.$prev->format('n') ?>" class="calendar-nav-btn">
        ◀ Anterior
      </a>
      <a href="<?= ENV_APP['BASE_URL'].'/calendario' ?>" class="calendar-nav-btn" style="background:rgba(0,200,83,.2);border-color:rgba(0,200,83,.3)">
        • Hoy
      </a>
      <a href="<?= ENV_APP['BASE_URL'].'/calendario?y='.$next->format('Y').'&m='.$next->format('n') ?>" class="calendar-nav-btn">
        Siguiente ▶
      </a>
    </div>
  </div>
  
  <div class="calendar-body">
    <?php
      $totalEventos = count($eventos);
      $eventosDelMes = array_filter($eventos, function($ev) use($y, $m) {
        $evDate = new DateTime($ev['inicio']);
        return (int)$evDate->format('Y') === $y && (int)$evDate->format('n') === $m;
      });
      $countMes = count($eventosDelMes);
    ?>
    
    <?php if($countMes > 0): ?>
      <div style="padding:1rem;background:rgba(0,200,83,.1);border:1px solid rgba(0,200,83,.3);border-radius:0.75rem;margin-bottom:1rem">
        📊 <strong><?= $countMes ?></strong> evento(s) en <?= $monthNames[(int)$m] ?> <?= $y ?>
      </div>
    <?php endif; ?>
    
    <div class="calendar-weekdays">
      <div class="weekday">Lun</div>
      <div class="weekday">Mar</div>
      <div class="weekday">Mié</div>
      <div class="weekday">Jue</div>
      <div class="weekday">Vie</div>
      <div class="weekday">Sáb</div>
      <div class="weekday">Dom</div>
    </div>
    
    <div class="calendar-days">
      <?php
        // Días vacíos antes del primero
        for($i=1; $i<$startDow; $i++) {
          echo "<div></div>";
        }
        
        // Días del mes
        $today = date('Y-m-d');
        for($d=1; $d<=$days; $d++):
          $date = sprintf('%04d-%02d-%02d', $y, $m, $d);
          $isToday = $date === $today;
      ?>
        <div class="calendar-day <?= $isToday ? 'today' : '' ?>">
          <div class="day-number"><?= $d ?></div>
          
          <?php if(!empty($map[$date])): ?>
            <?php foreach($map[$date] as $ev): ?>
              <div class="event-item <?= (int)$ev['all_day'] === 1 ? 'all-day' : '' ?>" 
                   style="<?= $ev['color'] ? 'background:'.safe($ev['color']) : '' ?>"
                   title="<?= safe($ev['titulo']) ?>">
                <div class="event-title"><?= safe($ev['titulo']) ?></div>
                <div class="event-time">
                  <?php if((int)$ev['all_day'] === 1): ?>
                    Todo el día
                  <?php else: ?>
                    <?= date('H:i', strtotime($ev['inicio'])) ?>
                    <?php if($ev['fin']): ?>
                      - <?= date('H:i', strtotime($ev['fin'])) ?>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
                <form method="post" action="<?= ENV_APP['BASE_URL'].'/calendario/delete/'.$ev['id'] ?>" 
                      onsubmit="return confirm('¿Eliminar evento?')" style="display:inline">
                  <button type="submit" class="event-delete">✕</button>
                </form>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      <?php endfor; ?>
    </div>
  </div>
  
  <div class="add-event-section">
    <h3>➕ Agregar nuevo evento</h3>
    <div style="background:rgba(33,150,243,.1);border:1px solid rgba(33,150,243,.3);padding:1rem;border-radius:0.5rem;margin-bottom:1rem;color:var(--text-secondary)">
      ℹ️ <strong>Nota:</strong> Los eventos se muestran en el mes y año correspondiente a su fecha de inicio. 
      Actualmente estás viendo: <strong><?= $monthNames[(int)$m] ?> <?= $y ?></strong>
    </div>
    <form class="event-form" method="post" action="<?= ENV_APP['BASE_URL'] ?>/calendario/store">
      <input type="hidden" name="y" value="<?= $y ?>">
      <input type="hidden" name="m" value="<?= $m ?>">
      
      <div class="form-group">
        <label>Título *</label>
        <input name="titulo" placeholder="Ej: Mantenimiento preventivo" required>
      </div>
      
      <div class="form-group">
        <label>Fecha y hora inicio *</label>
        <input type="datetime-local" name="inicio" required value="<?= date('Y-m-d\TH:i') ?>">
      </div>
      
      <div class="form-group">
        <label>Fecha y hora fin</label>
        <input type="datetime-local" name="fin" placeholder="Opcional" value="<?= date('Y-m-d\TH:i', strtotime('+1 hour')) ?>">
      </div>
      
      <div class="form-group">
        <label>Color</label>
        <input type="color" name="color" value="#2196f3">
      </div>
      
      <div class="form-group">
        <label>ID Mantenimiento</label>
        <input name="mantenimiento_id" type="number" placeholder="Opcional">
      </div>
      
      <div class="form-group">
        <label class="checkbox-group">
          <input type="checkbox" name="all_day" value="1">
          <span>Evento de todo el día</span>
        </label>
      </div>
      
      <div class="form-group">
        <button type="submit" class="submit-btn">✓ Crear evento</button>
      </div>
    </form>
  </div>
</section>