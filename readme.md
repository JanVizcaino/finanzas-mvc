# Finanzas MVC - Gestor de Gastos Compartidos

Aplicacion web para gestionar gastos compartidos entre usuarios. Desarrollada con PHP, MVC, Docker y PostgreSQL.

## Funcionalidades

- Sistema de usuarios (registro e inicio de sesion)
- Creacion de planes financieros (ej: "Viaje a Japon", "Gastos de Casa")
- Invitacion de usuarios a planes
- Gestion de gastos compartidos
- Sistema de roles (Admin y Miembro)
- Panel de administracion global
- Panel de configuracion por plan

## Roles de Usuario

**Admin del Plan (Creador):**
- Anadir y eliminar gastos
- Invitar y expulsar miembros
- Acceder al panel de configuracion del plan (cambiar nombre, currency, gestionar miembros)

**Miembro:**
- Ver gastos del plan
- Anadir nuevos gastos

**Administrador Global:**
- Acceso al panel de administracion
- Eliminar planes y usuarios del sistema

## Instalacion

Requisitos: Docker Desktop

```bash
docker-compose up -d --build
```

Acceder en: http://localhost:8080

## Estructura del Proyecto (MVC)

**public/**
- Punto de entrada (index.php)
- Enruta todas las peticiones

**controllers/**
- UserController.php: Login, registro, logout
- PlanController.php: Gestion de planes, dashboard
- ExpenseController.php: CRUD de gastos

**models/**
- User.php: Operaciones de usuarios en BD
- Plan.php: Operaciones de planes en BD
- Expense.php: Operaciones de gastos en BD

**views/**
- layout/: Header y footer
- auth/: Formularios de login y registro
- plans/: Dashboard y vistas de planes
- Estilos: TailwindCSS via CDN

**config/**
- Database.php: Conexion a PostgreSQL

**design/**
- Prototipo HTML estatico del nuevo diseno
- Representa la direccion visual futura del proyecto
- El codigo actual usa el diseno antiguo

## Flujo de Funcionamiento

1. Usuario hace peticion (ej: ver plan)
2. Router (index.php) identifica la accion
3. Controlador solicita datos al Modelo
4. Modelo consulta la base de datos
5. Controlador pasa datos a la Vista
6. Vista renderiza HTML
7. Navegador muestra la pagina

## Usuario de Prueba

Si ejecutaste database.sql:
- Email: admin@test.com
- Contrasena: 1234

## Solucionar Problemas

**Error: "Connection refused"**
```bash
# Espera 10 segundos y recarga
```

**Error: "Relation does not exist"**
```bash
docker-compose down -v
docker-compose up -d --build
```