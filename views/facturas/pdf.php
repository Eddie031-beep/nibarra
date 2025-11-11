<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Factura <?= safe($factura['numero_factura']) ?></title>
  <style>
    @media print {
      @page { margin: 1cm; }
      body { margin: 0; }
    }
    
    * { box-sizing: border-box; }
    
    body {
      font-family: 'Arial', sans-serif;
      max-width: 21cm;
      margin: 0 auto;
      padding: 20px;
      background: white;
      color: #333;
    }
    
    .factura-container {
      border: 2px solid #10b981;
      border-radius: 10px;
      overflow: hidden;
    }
    
    .factura-header {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
      padding: 30px;
      display: flex;
      justify-content: space-between;
      align-items: start;
    }
    
    .company-info h1 {
      margin: 0 0 10px 0;
      font-size: 28px;
      font-weight: 800;
    }
    
    .company-info p {
      margin: 5px 0;
      opacity: 0.95;
      font-size: 14px;
    }
    
    .invoice-info {
      text-align: right;
    }
    
    .invoice-number {
      font-size: 24px;
      font-weight: 800;
      margin-bottom: 10px;
    }
    
    .invoice-date {
      font-size: 14px;
      opacity: 0.9;
    }
    
    .factura-body {
      padding: 30px;
    }
    
    .section {
      margin-bottom: 30px;
    }
    
    .section-title {
      font-size: 16px;
      font-weight: 700;
      color: #10b981;
      margin-bottom: 15px;
      padding-bottom: 8px;
      border-bottom: 2px solid #10b981;
    }
    
    .info-grid {
      display: grid;
      grid-template-columns: 150px 1fr;
      gap: 10px 20px;
      margin-bottom: 10px;
    }
    
    .info-label {
      font-weight: 600;
      color: #666;
    }
    
    .info-value {
      color: #333;
    }
    
    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }
    
    .items-table thead {
      background: #f3f4f6;
    }
    
    .items-table th {
      padding: 12px;
      text-align: left;
      font-size: 13px;
      font-weight: 700;
      color: #374151;
      border-bottom: 2px solid #10b981;
    }
    
    .items-table td {
      padding: 10px 12px;
      border-bottom: 1px solid #e5e7eb;
      font-size: 14px;
    }
    
    .items-table tbody tr:last-child td {
      border-bottom: none;
    }
    
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    
    .totales {
      margin-top: 30px;
      padding: 20px;
      background: #f9fafb;
      border-radius: 8px;
      border: 1px solid #e5e7eb;
    }
    
    .total-row {
      display: flex;
      justify-content: space-between;
      padding: 8px 0;
      font-size: 15px;
    }
    
    .total-row.final {
      font-size: 22px;
      font-weight: 800;
      color: #10b981;
      border-top: 2px solid #10b981;
      padding-top: 15px;
      margin-top: 10px;
    }
    
    .footer {
      margin-top: 40px;
      padding-top: 20px;
      border-top: 1px solid #e5e7eb;
      text-align: center;
      font-size: 12px;
      color: #6b7280;
    }
    
    .estado-badge {
      display: inline-block;
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
    }
    
    .estado-pendiente {
      background: #fef3c7;
      color: #92400e;
    }
    
    .estado-pagada {
      background: #d1fae5;
      color: #065f46;
    }
    
    .estado-cancelada {
      background: #fee2e2;
      color: #991b1b;
    }
    
    .watermark {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-45deg);
      font-size: 120px;
      font-weight: 900;
      opacity: 0.05;
      z-index: -1;
      color: #10b981;
      pointer-events: none;
    }
  </style>
</head>
<body>
  <div class="watermark">NIBARRA</div>
  
  <div class="factura-container">
    <div class="factura-header">
      <div class="company-info">
        <h1>🛠️ NIBARRA</h1>
        <p><strong>Sistema de Gestión de Mantenimiento</strong></p>
        <p>📧 info@nibarra.com</p>
        <p>📞 +507 6000-0000</p>
        <p>📍 Panamá, República de Panamá</p>
      </div>
      
      <div class="invoice-info">
        <div class="invoice-number">🧾 <?= safe($factura['numero_factura']) ?></div>
        <div class="invoice-date">
          <?= date('d/m/Y H:i', strtotime($factura['fecha_emision'])) ?>
        </div>
        <div style="margin-top:15px">
          <span class="estado-badge estado-<?= $factura['estado'] ?>">
            <?= ucfirst($factura['estado']) ?>
          </span>
        </div>
      </div>
    </div>
    
    <div class="factura-body">
      <!-- Información del Servicio -->
      <div class="section">
        <div class="section-title">📋 INFORMACIÓN DEL SERVICIO</div>
        <div class="info-grid">
          <div class="info-label">Mantenimiento:</div>
          <div class="info-value"><strong><?= safe($factura['mantenimiento_titulo']) ?></strong></div>
          
          <div class="info-label">Tipo:</div>
          <div class="info-value"><?= ucfirst(safe($factura['mantenimiento_tipo'])) ?></div>
          
          <div class="info-label">Equipo:</div>
          <div class="info-value"><?= safe($factura['equipo_nombre']) ?></div>
          
          <div class="info-label">Código:</div>
          <div class="info-value"><?= safe($factura['equipo_codigo']) ?></div>
        </div>
      </div>
      
      <!-- Detalle de Servicios -->
      <div class="section">
        <div class="section-title">📝 DETALLE DE SERVICIOS PRESTADOS</div>
        <table class="items-table">
          <thead>
            <tr>
              <th>Descripción</th>
              <th class="text-center">Cantidad</th>
              <th class="text-right">Precio Unit.</th>
              <th class="text-right">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($factura['items'] as $item): ?>
              <tr>
                <td><?= safe($item['descripcion']) ?></td>
                <td class="text-center"><?= number_format($item['cantidad'], 2) ?></td>
                <td class="text-right">$<?= number_format($item['precio_unitario'], 2) ?></td>
                <td class="text-right"><strong>$<?= number_format($item['subtotal'], 2) ?></strong></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      
      <!-- Totales -->
      <div class="totales">
        <div class="total-row">
          <span>Subtotal:</span>
          <span><strong>$<?= number_format($factura['subtotal'], 2) ?></strong></span>
        </div>
        <div class="total-row">
          <span>ITBMS (7%):</span>
          <span><strong>$<?= number_format($factura['impuesto'], 2) ?></strong></span>
        </div>
        <div class="total-row final">
          <span>TOTAL A PAGAR:</span>
          <span>$<?= number_format($factura['total'], 2) ?></span>
        </div>
      </div>
      
      <!-- Notas -->
      <?php if($factura['notas']): ?>
        <div class="section">
          <div class="section-title">📌 NOTAS ADICIONALES</div>
          <p style="line-height:1.8;color:#4b5563">
            <?= nl2br(safe($factura['notas'])) ?>
          </p>
        </div>
      <?php endif; ?>
      
      <!-- Footer -->
      <div class="footer">
        <p><strong>Gracias por confiar en NIBARRA</strong></p>
        <p>Este documento ha sido generado automáticamente por el Sistema de Gestión de Mantenimiento</p>
        <p>Para consultas: info@nibarra.com | +507 6000-0000</p>
        <p style="margin-top:15px;font-size:11px">
          Generado el <?= date('d/m/Y H:i:s') ?>
        </p>
      </div>
    </div>
  </div>
  
  <script>
    // Auto-imprimir al cargar (opcional)
    // window.onload = () => window.print();
  </script>
</body>
</html>