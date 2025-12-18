# 💰 Finanzas MVC - Gestor de Gastos Compartidos

¡Bienvenido! Este es un proyecto de **Finanzas Colaborativas** (tipo SaaS) creado para aprender cómo funciona una aplicación web profesional usando **PHP, MVC, Docker y PostgreSQL**.

Es ideal para principiantes que quieren entender cómo se organizan los archivos en una arquitectura de software real.

---

## 🚀 ¿Qué hace esta aplicación?
Esta web permite a los usuarios:
1. **Registrarse e Iniciar Sesión** (Sistema de Usuarios).
2. **Crear Planes Financieros** (Ej: "Viaje a Japón", "Gastos de Casa").
3. **Invitar a otros usuarios** a esos planes.
4. **Gestionar Roles**:
   - **Admin (Creador):** Puede añadir gastos, eliminar gastos, invitar gente y expulsar gente.
   - **Miembro:** Solo puede ver y añadir gastos. No puede borrar nada ni echar a nadie.
5. **Controlar Gastos:** Ver quién gastó qué y cuánto suma.

---

## 🛠️ Instalación Rápida (Paso a Paso)

Solo necesitas tener **Docker Desktop** instalado.

1. **Descarga el código** y colócalo en una carpeta.
2. Abre tu terminal en esa carpeta.
3. Ejecuta el siguiente comando para encender el servidor y la base de datos:
   ```bash
   docker-compose up -d --build
   ```
4. Espera unos segundos a que arranque.
5. Abre tu navegador y entra en:
   👉 **http://localhost:8080**

> **Nota:** Si es la primera vez que lo arrancas, la base de datos se creará automáticamente.

---

## 📂 Estructura de Carpetas (Explicación para "No Programadores")

Este proyecto usa **MVC** (Modelo - Vista - Controlador). Imagina que es un **Restaurante**:

### 1. `public/` (La Puerta de Entrada)
Aquí está el archivo `index.php`. Es como el recepcionista del restaurante.
- **Función:** Recibe TODAS las visitas de los usuarios.
- **Qué hace:** Mira qué quieres hacer (ej: "¿quieres ver el dashboard?") y llama al Controlador adecuado.

### 2. `controllers/` (Los Camareros 🤵)
Son los jefes de la lógica. Reciben el pedido del cliente y coordinan todo.
- **`UserController.php`**: Se encarga de logins, registros y logout.
- **`PlanController.php`**: Gestiona la creación de planes y mostrar el dashboard.
- **`ExpenseController.php`**: Se encarga de guardar y borrar gastos.

### 3. `models/` (La Cocina 👨‍🍳)
Aquí es donde se "cocinan" los datos. Son los únicos que tocan la Base de Datos.
- **`User.php`**: Sabe cómo buscar usuarios o guardarlos en la BD.
- **`Plan.php`**: Sabe crear planes, buscar miembros y verificar roles (Admin/Member).
- **`Expense.php`**: Sabe guardar, listar y borrar gastos.

### 4. `views/` (Los Platos 🍽️)
Es lo que finalmente ve el cliente (el HTML bonito).
- **`layout/`**: Cabecera y pie de página (el menú común).
- **`auth/`**: Formularios de login y registro.
- **`plans/`**: El diseño del dashboard y de la lista de gastos.
- **Estilo:** Usamos **TailwindCSS** (vía CDN) para que se vea moderno sin escribir CSS a mano.

### 5. `config/` (Instalaciones)
- **`Database.php`**: Es la tubería que conecta PHP con PostgreSQL.

---

## 🧠 ¿Cómo funciona el flujo?

Cuando haces clic en "Ver Plan":

1. **Navegador:** Envía la petición `index.php?action=view_plan&id=5`.
2. **Router (`index.php`):** Ve `action=view_plan` y avisa al `PlanController`.
3. **Controlador (`PlanController`):**
   - Pregunta al **Modelo**: "¿Oye, dame los datos del plan 5 y sus gastos?".
   - El **Modelo** hace la consulta SQL y devuelve los datos.
   - El **Controlador** comprueba si eres Admin o Miembro.
4. **Vista (`views/plans/show.php`):**
   - El Controlador le pasa los datos a la Vista.
   - La Vista "dibuja" el HTML. Si eres Admin, dibuja los botones de borrar. Si no, los esconde.
5. **Navegador:** Recibe el HTML y tú ves la página.

---

## 🔐 Usuarios de Prueba

Puedes registrarte tú mismo, pero si has cargado los datos de ejemplo (`database.sql`), existe un admin por defecto:

- **Email:** `admin@test.com`
- **Contraseña:** `1234` (o la que hayas definido en el hash).

---

## 🆘 Solución de Problemas Comunes

- **Error: "Connection refused"** -> Docker aún se está encendiendo. Espera 10 segundos y recarga.
- **Error: "Relation does not exist"** -> La base de datos está vacía. Ejecuta:
  ```bash
  docker-compose down -v
  docker-compose up -d --build
  ```
  *(Esto borra todo y lo recrea desde cero)*.

---

¡Disfruta programando! 🚀