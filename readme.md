# 🛡️ Odin - Gestor de Finanzas Compartidas

**Odin** es una plataforma web robusta diseñada para la gestión eficiente de gastos compartidos en grupos (Planes). Construida sobre una arquitectura MVC nativa en PHP 8.1 y contenerizada con Docker, prioriza la seguridad, la escalabilidad y la automatización de procesos mediante n8n.

---

## 🚀 Características Principales

* **Gestión de Planes y Roles:** Creación de grupos con roles granulares (`admin` vs `member`).
* **Registro de Gastos:** Subida de tickets y facturas con validación de seguridad MIME real.
* **Automatización (n8n):** Notificaciones por correo electrónico asíncronas mediante Webhooks seguros.
* **Seguridad Avanzada:** Protección contra XSS, Inyección SQL, CSRF, Session Fixation y subida de archivos maliciosos.
* **Sistema RAG:** Recomendación de planes relacionados basada en contexto.
* **Logs Centralizados:** Sistema de registro de errores y auditoría de seguridad.

---

## 🛠️ Stack Tecnológico

* **Backend:** PHP 8.1 (Nativo, Arquitectura MVC).
* **Base de Datos:** PostgreSQL 15.
* **Servidor Web:** Apache (con módulos `rewrite` y configuración `.htaccess`).
* **Automatización:** n8n (Dockerizado).
* **Infraestructura:** Docker & Docker Compose.
* **Frontend:** HTML5, CSS3, JavaScript (Vanilla).

---

## 📂 Estructura del Proyecto

```text
/odin_project
├── config/             # Configuración y conexión a BD
│   ├── Config.php      # Variables de entorno y constantes
│   └── Database.php    # Conexión PDO PostgreSQL
├── controllers/        # Lógica de negocio (Controladores)
├── helpers/            # Utilidades estáticas (Security, Logger, Webhook)
├── logs/               # Archivos de registro de errores (Volumen Docker)
├── models/             # Acceso a datos (DAO)
├── public/             # Punto de entrada (Assets públicos)
├── uploads/            # Almacenamiento de recibos (Volumen Docker)
├── views/              # Plantillas HTML
├── .htaccess           # Reglas de seguridad y enrutamiento Apache
├── docker-compose.yml  # Orquestación de contenedores
├── Dockerfile          # Construcción de la imagen PHP
└── index.php           # Router principal
```

---

## 🔧 Requisitos Previos

* Docker Engine
* Docker Compose

---

## 📦 Instalación y Despliegue

Sigue estos pasos para levantar el entorno de producción local:

### 1. Clonar el repositorio
```bash
git clone [https://github.com/tu-usuario/odin-finance.git](https://github.com/tu-usuario/odin-finance.git)
cd odin-finance
```

### 2. Configurar Variables de Entorno
El archivo `docker-compose.yml` ya incluye las variables necesarias por defecto. Asegúrate de configurar:

* `APP_BASE_URL`: La URL donde se servirá la web (ej: `http://localhost:8081`).
* `N8N_WEBHOOK_URL`: La dirección del webhook de n8n.
* `N8N_API_SECRET`: El token de seguridad para validar peticiones.

### 3. Iniciar Contenedores
```bash
docker-compose up -d --build
```

### 4. Configuración de Permisos (Crítico)
Para que PHP pueda escribir logs y guardar archivos, ajusta los permisos de las carpetas de volumen:

```bash
# Entrar en el contenedor web (ajusta el nombre del servicio si es necesario)
docker exec -it odin_web_1 sh

# Ejecutar comandos de permisos dentro del contenedor
mkdir -p logs uploads
chown -R www-data:www-data logs uploads
chmod -R 775 logs uploads
exit
```

### 5. Inicializar Base de Datos
Si no se ha cargado automáticamente el script SQL al inicio:
```bash
cat database.sql | docker exec -i odin_db_1 psql -U postgres -d finanzas_db
```

---

## 🔒 Protocolos de Seguridad Implementados

### 1. Sistema de Logs y Auditoría
El sistema utiliza un helper `Logger::safeRun()` que envuelve la ejecución de los controladores.
* **Ubicación:** `/logs/odin_errors.log`
* **Monitorización:** `tail -f logs/odin_errors.log`

### 2. Validación de Archivos (Anti-Malware)
No confiamos en la extensión del archivo. Se utiliza `finfo_file` para verificar el **MIME Type** real del binario.
* Tipos permitidos: `image/jpeg`, `image/png`, `application/pdf`.

### 3. Protección de Sesiones
* **Anti-Session Fixation:** Se ejecuta `session_regenerate_id(true)` tras cada login exitoso.
* **Cookies Seguras:** Parámetros `HttpOnly` y `Secure` (si hay HTTPS) activados.

### 4. Sanitización (Anti-XSS & SQLi)
* **Inputs:** Todo `$_POST` y `$_GET` pasa por `Security::clean()` (htmlspecialchars).
* **Base de Datos:** Uso estricto de **PDO Prepared Statements**.

### 5. Seguridad de n8n
La comunicación entre PHP y n8n está protegida por un token en cabecera:
* **Header:** `X-Odin-Token`
* Si el token no coincide con `N8N_API_SECRET`, n8n rechazará la petición (403 Forbidden).

---

## 🧪 Testing y Verificación

### Verificar Logs en tiempo real
Para depurar errores o ver intentos de acceso no autorizado:
```bash
docker exec -it odin_web_1 tail -f logs/odin_errors.log
```

### Pruebas Manuales Recomendadas
1.  **Subida de Archivos:** Intentar subir un `.exe` renombrado a `.jpg`. El sistema debe rechazarlo y generar una alerta de seguridad en el log.
2.  **Inyección SQL:** Intentar acceder a `?id=1 OR 1=1`. El sistema sanitizará a `int(1)` o `0`.
3.  **Acceso Directo:** Intentar navegar a `/controllers/UserController.php`. El `.htaccess` debe devolver **403 Forbidden**.

---

## 🗺️ Roadmap y Mejoras Futuras

* [ ] Despliegue en VPS con Certificado SSL (Let's Encrypt).
* [ ] Implementación de CDN (AWS S3) para servir imágenes en emails.
* [ ] Migración a Framework (Laravel/Symfony) para escalabilidad.
* [ ] Integración de notificaciones vía Telegram/Discord en n8n.
* [ ] Algoritmo de simplificación de deudas (Splitwise-like).

---

## 👥 Autores

Proyecto desarrollado por **Jan Vizcaíno** para la asignatura de **Desarrollo Web en Entorno Servidor**.
Informe de diseño disponible en el Moodle de Diseño de Interfaces Web.
