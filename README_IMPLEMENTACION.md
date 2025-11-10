# 🚀 NIBARRA - Sistema de Mantenimiento
## Guía de Implementación Completa

---

## 📋 CHECKLIST DE REQUISITOS DEL EXAMEN

### ✅ Requisito A: Diseño con Header, Footer, Estructura
- [x] Header profesional con navegación
- [x] Footer con información de contacto
- [x] Estructura responsive con dark theme
- [x] Diseño profesional moderno

### ✅ Requisito B: Sección Servicios (CRUD + Calendario + Mantenimiento)
- [x] **PESTAÑA EQUIPOS**: CRUD completo
  - Create: `/nibarra/public/equipos/crear`
  - Read: `/nibarra/public/equipos`
  - Update: `/nibarra/public/equipos/editar`
  - Delete: Funcional con confirmación
- [x] **PESTAÑA CALENDARIO**: Vista mensual con FullCalendar
- [x] **PESTAÑA MANTENIMIENTO**: Sistema Kanban
  - Por hacer / En espera / En revisión / Terminada
  - Tipos: Preventivo, Correctivo, Predictivo
  - Porcentaje de avance visualizable

### ✅ Requisito C: Acceso Controlado
- [x] Sistema de login con usuario/contraseña
- [x] Archivo SQL exportable (`database/schema.sql`)
- [x] Sistema de roles (admin, técnico, visor)

### ✅ Requisito D: Protección de Código
- [x] Deshabilitado clic derecho
- [x] Deshabilitado Ctrl+U, Ctrl+S, Ctrl+C, F12
- [x] Marca de agua en consola
- [x] User-select deshabilitado en CSS

### ✅ Requisito E: ChatBot Implementado
- [x] ChatBot funcional en footer
- [x] Respuestas contextuales
- [x] Sugerencias rápidas
- [x] Interfaz moderna

### ✅ Requisito F: Diagrama de Red LAN
- [x] Diseño completo con 2 Routers Capa 3
- [x] 2 Switches Capa 3
- [x] VPN IPsec entre Chiriquí y Panamá
- [x] Documentación detallada

---

## 🔧 ARCHIVOS CORREGIDOS Y CREADOS

### **Archivos Principales Modificados**

1. **`views/layout/header.php`**
   - ✅ Rutas CSS corregidas
   - ✅ Protección contra copia implementada
   - ✅ Navegación activa mejorada

2. **`views/layout/footer.php`**
   - ✅ ChatBot completo
   - ✅ Footer profesional
   - ✅ Scripts de FullCalendar

3. **`public/css/style.css`**
   - ✅ Ya existe y está completo
   - ✅ Dark theme profesional
   - ✅ Responsive design

4. **`public/assets/js/app.js`**
   - ✅ Sistema de notificaciones (toast)
   - ✅ Protección de código
   - ✅ Utilidades globales

### **Nuevos Archivos Creados**

1. **Equipos (CRUD completo)**
   - `views/equipos/index.php` - Lista con filtros
   - `views/equipos/create.php` - Formulario mejorado
   - `public/equipos/store.php` - Backend de guardado
   - `public/equipos/eliminar.php` - API de eliminación

2. **Mantenimiento (Sistema Kanban)**
   - `views/mantenimiento/index.php` - Tablero Kanban
   - `public/mantenimiento/cambiar-estado.php` - API drag & drop

3. **Calendario (FullCalendar)**
   - `views/calendario/index.php` - Calendario funcional
   - Integración con eventos de mantenimiento

4. **Documentación**
   - `DIAGRAMA_RED_LAN_NIBARRA.md` - Requisito F completo

---

## 📁 ESTRUCTURA DE CARPETAS FINAL

```
/var/www/nibarra/
│
├── config/
│   ├── app.php           ✅ Configuración general
│   ├── config.php        ✅ Credenciales DB
│   └── database.php      ✅ Conexiones DB
│
├── database/
│   ├── schema.sql        ✅ Esquema completo
│   └── seedes.sql        ✅ Datos iniciales
│
├── public/
│   ├── .htaccess         ✅ Rewrite rules
│   ├── index.php         ✅ Router principal
│   │
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css              ✅ Estilos completos
│   │   └── js/
│   │       └── app.js                 ✅ JavaScript principal
│   │
│   ├── equipos/
│   │   ├── store.php                  ✅ Guardar equipo
│   │   └── eliminar.php               ✅ Eliminar equipo
│   │
│   └── mantenimiento/
│       └── cambiar-estado.php         ✅ Cambiar estado
│
├── src/
│   └── helpers/
│       ├── db.php        ✅ Conexiones PDO
│       └── sync.php      ✅ Sincronización DB
│
├── sync/
│   ├── pending/          ✅ Cola de sincronización
│   ├── sync-nibarra.php  ✅ Script de sync
│   └── sync-nibarra.sh   ✅ Script bash
│
├── views/
│   ├── layout/
│   │   ├── header.php    ✅ CORREGIDO
│   │   └── footer.php    ✅ CORREGIDO con ChatBot
│   │
│   ├── equipos/
│   │   ├── index.php     ✅ NUEVO - Lista
│   │   └── create.php    ✅ NUEVO - Formulario
│   │
│   ├── mantenimiento/
│   │   └── index.php     ✅ NUEVO - Kanban
│   │
│   ├── calendario/
│   │   └── index.php     ✅ NUEVO - FullCalendar
│   │
│   └── auth/
│       ├── login.php     ✅ Login
│       └── register.php  ✅ Registro
│
└── DOCUMENTACIÓN/
    ├── README_IMPLEMENTACION.md       ✅ Este archivo
    └── DIAGRAMA_RED_LAN_NIBARRA.md    ✅ Requisito F
```

---

## 🚀 PASOS PARA IMPLEMENTAR

### **1. Actualizar Archivos Existentes**

```bash
# Backup de archivos actuales
cd /var/www/nibarra
cp -r . ../nibarra_backup_$(date +%Y%m%d)

# Reemplazar archivos corregidos
# Copia el contenido de los artifacts en los archivos correspondientes
```

### **2. Crear Archivos Nuevos**

```bash
# Crear carpetas faltantes
mkdir -p public/assets/js
mkdir -p public/mantenimiento
mkdir -p DOCUMENTACIÓN

# Copiar nuevos archivos desde los artifacts
# views/equipos/index.php
# views/equipos/create.php
# views/mantenimiento/index.php
# views/calendario/index.php
# public/assets/js/app.js
# public/equipos/eliminar.php
# public/mantenimiento/cambiar-estado.php
```

### **3. Verificar Base de Datos**

```bash
# Conectar a MySQL
mysql -u win -p12345

# Verificar que la BD existe
SHOW DATABASES LIKE 'nibarra_db';

# Verificar tablas
USE nibarra_db;
SHOW TABLES;

# Si falta algo, reimportar
mysql -u win -p12345 nibarra_db < database/schema.sql
mysql -u win -p12345 nibarra_db < database/seedes.sql
```

### **4. Configurar Permisos**

```bash
# Dar permisos correctos
sudo chown -R www-data:www-data /var/www/nibarra
sudo chmod -R 755 /var/www/nibarra
sudo chmod -R 775 /var/www/nibarra/sync/pending
```

### **5. Configurar Apache**

```bash
# Habilitar mod_rewrite
sudo a2enmod rewrite

# Crear Virtual Host
sudo nano /etc/apache2/sites-available/nibarra.conf
```

Contenido del VirtualHost:
```apache
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot /var/www/nibarra/public
    
    <Directory /var/www/nibarra/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/nibarra_error.log
    CustomLog ${APACHE_LOG_DIR}/nibarra_access.log combined
</VirtualHost>
```

```bash
# Habilitar sitio y reiniciar
sudo a2ensite nibarra.conf
sudo systemctl reload apache2
```

### **6. Probar Funcionalidades**

#### **Test 1: Equipos CRUD**
1. Ir a `http://localhost/nibarra/public/equipos`
2. Click en "Nuevo Equipo"
3. Llenar formulario y guardar
4. Verificar que aparece en la lista
5. Probar editar y eliminar

#### **Test 2: Mantenimiento Kanban**
1. Ir a `http://localhost/nibarra/public/mantenimiento`
2. Verificar que se muestran las 4 columnas
3. Probar drag & drop de tarjetas
4. Verificar que cambia el estado

#### **Test 3: Calendario**
1. Ir a `http://localhost/nibarra/public/calendario`
2. Verificar que se muestra el calendario
3. Click en un evento para ver detalles
4. Probar cambio de vista (mes/semana/lista)

#### **Test 4: ChatBot**
1. En cualquier página, verificar botón flotante
2. Click para abrir chat
3. Escribir "ayuda" y verificar respuesta
4. Probar sugerencias rápidas

#### **Test 5: Protección de Código**
1. Click derecho → debe estar bloqueado
2. Ctrl+U → debe estar bloqueado
3. F12 → debe estar bloqueado
4. Intentar seleccionar texto → no debe funcionar (excepto en inputs)

---

## 🔐 CREDENCIALES POR DEFECTO

### Base de Datos
```
Usuario: win
Password: 12345
Host: 127.0.0.1 (Ubuntu)
Puerto: 3306
Base de datos: nibarra_db
```

### Sistema Web
```
Usuario: admin@nibarra.local
Password: password
Rol: Administrador
```

---

## 🐛 TROUBLESHOOTING

### Problema 1: CSS no carga
```bash
# Verificar ruta en header.php
<link rel="stylesheet" href="/nibarra/public/css/style.css">

# Verificar permisos
sudo chmod 644 /var/www/nibarra/public/css/style.css
```

### Problema 2: Error 404 en rutas
```bash
# Verificar .htaccess en public/
RewriteEngine On
RewriteBase /nibarra/public/

# Verificar que mod_rewrite está activo
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Problema 3: Error de conexión DB
```php
// Verificar config/config.php
define('UB_HOST', '127.0.0.1');
define('UB_PORT', 3306);
define('UB_DB',   'nibarra_db');
define('UB_USER', 'win');
define('UB_PASS', '12345');

// Probar conexión
php public/diag/db_check.php
```

### Problema 4: FullCalendar no se muestra
```html
<!-- Verificar en footer.php -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
```

---

## 📊 EXPORTAR PARA ENTREGA

### **1. Exportar SQL**
```bash
# Exportar esquema completo
mysqldump -u win -p12345 nibarra_db > nibarra_export_$(date +%Y%m%d).sql

# Incluir en carpeta database/
cp nibarra_export_*.sql database/
```

### **2. Comprimir Proyecto**
```bash
cd /var/www
tar -czf nibarra_final_$(date +%Y%m%d).tar.gz \
  --exclude='nibarra/vendor' \
  --exclude='nibarra/.git' \
  --exclude='nibarra/sync/pending/*' \
  nibarra/

# O usar ZIP
zip -r nibarra_final_$(date +%Y%m%d).zip nibarra/ \
  -x "*/vendor/*" "*/.git/*" "*/sync/pending/*"
```

### **3. Documentación a Incluir**
```
nibarra_entrega/
├── nibarra_final.zip              # Código completo
├── nibarra_db.sql                 # Base de datos
├── DIAGRAMA_RED_LAN_NIBARRA.md   # Requisito F
├── README_IMPLEMENTACION.md       # Este documento
├── capturas/                      # Screenshots del sistema
│   ├── 01_login.png
│   ├── 02_equipos_lista.png
│   ├── 03_equipos_crear.png
│   ├── 04_mantenimiento_kanban.png
│   ├── 05_calendario.png
│   └── 06_chatbot.png
└── video_demo.mp4                 # (Opcional) Demo en video
```

---

## ✅ LISTA DE VERIFICACIÓN FINAL

Antes de entregar, verifica:

- [ ] Todos los archivos están en el ZIP
- [ ] Base de datos `.sql` incluida
- [ ] Diagrama de red documentado
- [ ] Screenshots del sistema funcionando
- [ ] README con instrucciones claras
- [ ] Código protegido contra copia
- [ ] ChatBot funciona correctamente
- [ ] CRUD de equipos completo
- [ ] Sistema Kanban funciona
- [ ] Calendario muestra eventos
- [ ] Replicación DB configurada
- [ ] Sin errores de PHP en logs
- [ ] Todas las rutas funcionan
- [ ] Responsive en móvil
- [ ] Tested en Chrome y Firefox

---

## 📞 SOPORTE

**Desarrollo**: Sistema Nibarra  
**Fecha de entrega**: 11/11/2025 - 12:50 PM  
**Plataforma**: Team  
**Formato**: ZIP

---

## 🎓 NOTAS IMPORTANTES PARA LA SUSTENTACIÓN

### Puntos Clave a Demostrar:

1. **Arquitectura del Sistema** (15%)
   - Estructura MVC adaptada
   - Separación de responsabilidades
   - Sistema de rutas con .htaccess

2. **Funcionalidades** (70%)
   - CRUD completo de equipos
   - Sistema Kanban con drag & drop
   - Calendario interactivo
   - ChatBot funcional
   - Sincronización de bases de datos

3. **Seguridad y Protección** (15%)
   - Código protegido contra copia
   - Validación de inputs
   - Prepared statements (SQL injection)
   - Sistema de roles
   - VPN y encriptación en red

### Preguntas Frecuentes en Sustentación:

**Q: ¿Cómo funciona la sincronización entre DB?**  
A: Sistema de cola con archivos JSON en `sync/pending/`. Si Windows no está disponible, se encola y un cron reintenta.

**Q: ¿Por qué usar 2 bases de datos?**  
A: Requisito del examen. Simula una arquitectura distribuida con replicación para alta disponibilidad.

**Q: ¿Cómo se protege el código?**  
A: JavaScript deshabilita clic derecho, teclas F12/Ctrl+U/Ctrl+S. CSS con `user-select: none`.

**Q: ¿El ChatBot usa IA?**  
A: No, es un chatbot basado en reglas con respuestas predefinidas. Podría integrarse Claude AI mediante la API.

---

**¡Éxito en tu examen! 🚀**