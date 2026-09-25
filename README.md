# DDP Noticias

> **Sistema web de gestión y publicación de contenidos periodísticos**, desarrollado con PHP y MySQL, con panel administrativo y despliegue automatizado mediante GitHub Actions sobre infraestructura de hosting compartido.

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php\&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?logo=mysql\&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.x-7952B3?logo=bootstrap\&logoColor=white)](https://getbootstrap.com/)
[![GitHub Actions](https://img.shields.io/badge/CI%2FCD-GitHub%20Actions-2088FF?logo=githubactions\&logoColor=white)](https://github.com/features/actions)
[![Hosting](https://img.shields.io/badge/Hosting-InfinityFree-0B0B0B)](https://www.infinityfree.com/)

---

## 1. Descripción

**DDP Noticias** es una aplicación web dinámica orientada a la publicación y administración de contenidos informativos.

El sistema separa las funcionalidades destinadas al público general de las funcionalidades administrativas, permitiendo gestionar contenidos desde un panel privado y publicarlos automáticamente en el portal.

La aplicación está construida sobre una arquitectura web tradicional basada en:

* **PHP** para la lógica del servidor.
* **MySQL/MariaDB** para la persistencia de información.
* **HTML5, CSS3 y JavaScript** para la interfaz.
* **Bootstrap** para el diseño responsive.
* **Chart.js** para visualización de estadísticas.
* **FullCalendar** para la gestión visual de publicaciones.
* **GitHub** como plataforma de control de versiones.
* **GitHub Actions** como plataforma de automatización CI/CD.
* **FTP** como mecanismo de transferencia hacia InfinityFree.

---

# 2. Objetivos del sistema

El proyecto tiene como objetivos principales:

1. Centralizar la gestión de contenidos periodísticos.
2. Proporcionar un portal público para la consulta de publicaciones.
3. Facilitar la administración de contenidos mediante un panel privado.
4. Gestionar archivos multimedia asociados a las publicaciones.
5. Mantener una separación entre la interfaz pública y la administración.
6. Automatizar el proceso de despliegue mediante integración con GitHub Actions.
7. Reducir la intervención manual necesaria para actualizar el sistema en producción.

---

# 3. Arquitectura general

La solución utiliza una arquitectura web cliente-servidor.

```text
┌─────────────────────────────────────────────────────────────┐
│                        USUARIO                              │
│                  Navegador Web / Cliente                    │
└──────────────────────────┬──────────────────────────────────┘
                           │ HTTP / HTTPS
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                     INFINITYFREE                            │
│                                                             │
│  ┌───────────────────────────────────────────────────────┐  │
│  │                    Servidor Web                       │  │
│  │                                                       │  │
│  │                     PHP 8.x                           │  │
│  │                                                       │  │
│  │  ┌─────────────────┐     ┌────────────────────────┐  │  │
│  │  │ Portal Público  │     │ Panel Administrativo   │  │  │
│  │  │                 │     │                        │  │  │
│  │  │ index.php       │     │ login.php              │  │  │
│  │  │ reportajes.php  │     │ index.php              │  │  │
│  │  │ podcast.php     │     │ gestion.php            │  │  │
│  │  │ boletin.php     │     │ logout.php             │  │  │
│  │  └────────┬────────┘     └──────────┬─────────────┘  │  │
│  │           │                         │                │  │
│  │           └────────────┬────────────┘                │  │
│  │                        ▼                             │  │
│  │              ┌──────────────────┐                   │  │
│  │              │ PHP / Config     │                   │  │
│  │              │ / Includes       │                   │  │
│  │              └────────┬─────────┘                   │  │
│  └───────────────────────┼─────────────────────────────┘  │
│                          │                                │
│                          ▼                                │
│                 ┌──────────────────┐                      │
│                 │  MySQL / MariaDB │                      │
│                 └──────────────────┘                      │
└─────────────────────────────────────────────────────────────┘


                    PROCESO CI/CD

┌──────────────┐
│ Desarrollador│
└──────┬───────┘
       │ git push
       ▼
┌──────────────────┐
│ GitHub Repository│
└────────┬─────────┘
         │
         ▼
┌──────────────────────┐
│    GitHub Actions    │
│                      │
│ Checkout             │
│       ↓              │
│ Validación / Build   │
│       ↓              │
│ FTP Deployment       │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│     InfinityFree     │
│      /htdocs/        │
└──────────────────────┘
```

---

# 4. Funcionalidades

## 4.1 Portal público

El portal permite a los visitantes consultar los contenidos publicados.

### Página principal

```text
index.php
```

Presenta:

* Contenido destacado.
* Publicaciones recientes.
* Acceso a las diferentes secciones del portal.

### Reportajes

```text
reportajes.php
detalle_reportaje.php
```

Permite:

* Visualizar el listado de reportajes.
* Consultar información detallada.
* Mostrar imágenes asociadas.
* Acceder a documentos adjuntos cuando corresponda.

### Podcasts

```text
podcast.php
```

Sección destinada a la publicación y consulta de contenido de tipo podcast.

### Boletines

```text
boletin.php
```

Sección destinada a la publicación y consulta de boletines informativos.

---

# 5. Panel administrativo

El panel administrativo se encuentra en:

```text
/admin
```

Su acceso está protegido mediante autenticación.

## Funcionalidades principales

### Autenticación

```text
/admin/login.php
/admin/logout.php
```

Permite iniciar y cerrar sesiones administrativas.

### Dashboard

```text
/admin/index.php
```

Proporciona una vista general de la información administrada por el sistema.

Incluye componentes de visualización mediante:

* Chart.js
* FullCalendar

### Gestión de contenidos

```text
/admin/gestion.php
```

Permite realizar operaciones CRUD sobre los contenidos administrados por el sistema.

Las operaciones principales son:

```text
Create
Read
Update
Delete
```

---

# 6. Stack tecnológico

| Capa                    | Tecnología      |
| ----------------------- | --------------- |
| Frontend                | HTML5           |
| Estilos                 | CSS3            |
| Framework UI            | Bootstrap       |
| JavaScript              | JavaScript ES6+ |
| Gráficos                | Chart.js        |
| Calendario              | FullCalendar    |
| Backend                 | PHP 8.x         |
| Base de datos           | MySQL / MariaDB |
| Control de versiones    | Git             |
| Repositorio             | GitHub          |
| Automatización          | GitHub Actions  |
| Protocolo de despliegue | FTP             |
| Hosting                 | InfinityFree    |

---

# 7. Estructura del proyecto

```text
sistema_noticias/
│
├── .github/
│   └── workflows/
│       └── deploy.yml
│
├── admin/
│   │
│   ├── assets/
│   │   └── ...
│   │
│   ├── config/
│   │   └── ...
│   │
│   ├── includes/
│   │   └── ...
│   │
│   ├── uploads/
│   │   └── ...
│   │
│   ├── gestion.php
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   └── sql_instalacion.sql
│
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── config/
│   └── conexion.php
│
├── includes/
│   ├── header.php
│   └── footer.php
│
├── uploads/
│
├── index.php
├── reportajes.php
├── detalle_reportaje.php
├── podcast.php
├── boletin.php
│
└── README.md
```

> La estructura puede variar ligeramente según la versión del proyecto desplegada.

---

# 8. Base de datos

El sistema utiliza una base de datos relacional MySQL/MariaDB.

El script inicial se encuentra en:

```text
admin/sql_instalacion.sql
```

Entre las entidades principales utilizadas por el sistema se encuentran:

```text
usuarios
autores
reportajes
noticias
boletines
podcasts
videos
```

La estructura permite mantener separada la información de usuarios, autores y diferentes tipos de publicaciones.

---

# 9. Instalación en entorno local

## Requisitos

Para ejecutar el proyecto localmente se requiere:

* XAMPP.
* Apache.
* PHP 8.x.
* MySQL/MariaDB.
* Navegador web.
* Git.

---

## 9.1 Clonar el repositorio

```bash
git clone https://github.com/USUARIO/REPOSITORIO.git
```

Ingresar al proyecto:

```bash
cd sistema_noticias
```

---

## 9.2 Ubicar el proyecto en XAMPP

Copiar el proyecto dentro de:

```text
C:\xampp\htdocs\
```

La estructura resultante debe ser:

```text
C:\xampp\htdocs\sistema_noticias\
```

---

## 9.3 Iniciar XAMPP

Activar:

```text
Apache
MySQL
```

---

## 9.4 Crear la base de datos

Abrir:

```text
http://localhost/phpmyadmin
```

Crear:

```sql
CREATE DATABASE sistema_noticias;
```

Posteriormente importar:

```text
admin/sql_instalacion.sql
```

---

## 9.5 Configurar la conexión

Editar:

```text
config/conexion.php
```

Configurar los parámetros correspondientes al entorno local:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "sistema_noticias";
```

> Los valores deben ajustarse a la configuración de cada entorno.

---

## 9.6 Ejecutar el sistema

Abrir:

```text
http://localhost/sistema_noticias/
```

Panel administrativo:

```text
http://localhost/sistema_noticias/admin/
```

---

# 10. Despliegue en InfinityFree

El sistema se encuentra preparado para ejecutarse en un entorno de hosting compartido mediante InfinityFree.

El proceso de despliegue utiliza FTP y es automatizado mediante GitHub Actions.

## Flujo

```text
Código fuente
     │
     ▼
Git commit
     │
     ▼
Git push
     │
     ▼
GitHub
     │
     ▼
GitHub Actions
     │
     ▼
FTP
     │
     ▼
InfinityFree
     │
     ▼
htdocs/
```

---

# 11. Configuración de InfinityFree

Antes del despliegue es necesario crear una cuenta y un sitio web dentro de InfinityFree.

El hosting proporciona los datos necesarios para realizar la conexión FTP y configurar la base de datos.

Los datos necesarios dependen de la cuenta creada y deben obtenerse directamente desde el panel de administración del hosting.

### Información requerida

```text
FTP Host
FTP Username
FTP Password
FTP Directory
MySQL Host
MySQL Database
MySQL Username
MySQL Password
```

Estos valores **no deben almacenarse directamente en el repositorio público**.

---

# 12. Configuración de la base de datos en producción

La base de datos de producción debe crearse desde el panel de InfinityFree.

Después de crearla:

1. Obtener las credenciales MySQL.
2. Acceder al administrador de base de datos proporcionado por el hosting.
3. Crear/importar la estructura de la base de datos.
4. Importar el archivo:

```text
admin/sql_instalacion.sql
```

5. Configurar las credenciales de producción en el archivo de conexión correspondiente.

### Importante

Las credenciales utilizadas en producción son diferentes de las utilizadas en el entorno local.

Por ejemplo:

```text
LOCAL

Host: localhost
User: root
Password: ""
Database: sistema_noticias
```

Mientras que producción utiliza los datos proporcionados por InfinityFree.

---

# 13. Configuración de GitHub Secrets

Para evitar almacenar credenciales sensibles directamente en el código fuente, GitHub Actions utiliza **Repository Secrets**.

Acceder a:

```text
GitHub
→ Repository
→ Settings
→ Secrets and variables
→ Actions
```

Crear los secretos correspondientes.

Por ejemplo:

```text
FTP_SERVER
FTP_USERNAME
FTP_PASSWORD
FTP_DIR
```

Los valores reales deben corresponder a los datos proporcionados por InfinityFree.

### Seguridad

Nunca colocar credenciales reales directamente dentro de:

```text
README.md
.php
.yml
.js
```

ni realizar commits que contengan contraseñas.

---

# 14. GitHub Actions

El proceso de despliegue está definido dentro de:

```text
.github/workflows/deploy.yml
```

El workflow se ejecuta automáticamente cuando se realiza un `push` sobre la rama configurada para producción.

Ejemplo conceptual:

```yaml
name: Deploy to InfinityFree

on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:

      - name: Checkout repository
        uses: actions/checkout@v4

      - name: Deploy via FTP
        uses: SamKirkland/FTP-Deploy-Action@v4
        with:
          server: ${{ secrets.FTP_SERVER }}
          username: ${{ secrets.FTP_USERNAME }}
          password: ${{ secrets.FTP_PASSWORD }}
          server-dir: ${{ secrets.FTP_DIR }}
```

> El workflow mostrado es una referencia. Debe mantenerse exactamente la configuración utilizada por el proyecto en producción.

---

# 15. Proceso CI/CD

El proyecto implementa un flujo de integración y despliegue continuo.

### Integración

El desarrollador realiza cambios en el código:

```bash
git add .
git commit -m "Descripción del cambio"
git push origin main
```

GitHub recibe el nuevo commit y activa automáticamente el workflow.

### Despliegue

GitHub Actions:

1. Obtiene el código del repositorio.
2. Ejecuta el workflow configurado.
3. Establece una conexión FTP.
4. Transfiere los archivos al servidor.
5. Actualiza el contenido ubicado en el directorio configurado de InfinityFree.

---

# 16. Estrategia de ramas

La rama principal utilizada para producción es:

```text
main
```

Flujo recomendado:

```text
feature/*
      │
      ▼
  desarrollo
      │
      ▼
 Pull Request
      │
      ▼
    main
      │
      ▼
GitHub Actions
      │
      ▼
InfinityFree
```

Esto permite separar el desarrollo de la versión publicada.

---

# 17. Gestión de archivos

El proyecto utiliza directorios específicos para recursos multimedia.

```text
/uploads/
```

y:

```text
/admin/uploads/
```

Estos directorios pueden contener:

* Imágenes.
* Documentos.
* Recursos multimedia asociados a las publicaciones.

Durante el despliegue debe garantizarse que las rutas necesarias existan en el servidor y cuenten con los permisos adecuados para la aplicación.

---

# 18. Seguridad

Se consideran las siguientes prácticas:

### Credenciales

Las credenciales de producción deben mantenerse fuera del repositorio.

### Contraseñas

Las contraseñas de usuarios deben almacenarse utilizando mecanismos de hash apropiados, como:

```php
password_hash()
```

y verificarse mediante:

```php
password_verify()
```

### Sesiones

El panel administrativo utiliza sesiones PHP para controlar el acceso a las funcionalidades protegidas.

### Archivos

Las cargas de archivos deben validarse antes de almacenarse en el servidor.

### Secrets

Las credenciales utilizadas por GitHub Actions deben almacenarse como:

```text
GitHub Secrets
```

y nunca como texto plano dentro del workflow.

---

# 19. Verificación del despliegue

Después de ejecutar el workflow se debe comprobar:

### GitHub Actions

```text
Repository
→ Actions
→ Deploy
```

El workflow debe finalizar correctamente.

### Aplicación

Verificar:

```text
Página principal
Reportajes
Detalle de reportaje
Podcast
Boletín
Login administrativo
Dashboard
Gestión de contenidos
Carga de archivos
```

### Base de datos

Comprobar que:

* Las publicaciones se muestran correctamente.
* Los registros pueden ser consultados.
* Las operaciones CRUD funcionan.
* Los archivos multimedia se cargan correctamente.

---

# 20. Troubleshooting

## Error de conexión con MySQL

Verificar:

```text
Host
Username
Password
Database
Port
```

No asumir que el host de producción es:

```text
localhost
```

El servidor MySQL de InfinityFree puede utilizar un host diferente al entorno local.

---

## Error de conexión FTP

Comprobar:

```text
FTP_SERVER
FTP_USERNAME
FTP_PASSWORD
FTP_DIR
```

También verificar que las credenciales correspondan al servidor FTP proporcionado por InfinityFree.

---

## El sitio muestra una página en blanco

Revisar:

* Errores de PHP.
* Rutas `require` / `include`.
* Credenciales de base de datos.
* Compatibilidad de la versión de PHP.
* Permisos de archivos.

Durante el desarrollo puede habilitarse temporalmente:

```php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
```

No se recomienda mantener la visualización de errores habilitada en producción.

---

## Los archivos multimedia no aparecen

Comprobar:

```text
/uploads/
```

y:

```text
/admin/uploads/
```

Además, revisar:

* Rutas utilizadas por PHP.
* Permisos.
* Nombre y extensión del archivo.
* URL generada por la aplicación.

---

# 21. Desarrollo y despliegue

Una modificación típica sigue este flujo:

```bash
# 1. Obtener cambios
git pull origin main

# 2. Realizar modificaciones

# 3. Revisar cambios
git status

# 4. Agregar archivos
git add .

# 5. Crear commit
git commit -m "feat: descripción del cambio"

# 6. Enviar cambios
git push origin main
```

Después del `push`, GitHub Actions inicia automáticamente el proceso de despliegue.

---

# 22. Convención de commits

Se recomienda utilizar mensajes de commit descriptivos.

Ejemplos:

```text
feat: agregar gestión de reportajes
fix: corregir conexión con base de datos
docs: actualizar documentación
style: mejorar diseño del dashboard
refactor: reorganizar conexión de base de datos
chore: actualizar configuración CI/CD
```

---

# 23. Estado del proyecto

| Componente            | Estado       |
| --------------------- | ------------ |
| Portal público        | Implementado |
| Panel administrativo  | Implementado |
| Autenticación         | Implementado |
| Gestión de contenidos | Implementado |
| Base de datos         | Implementada |
| Gestión multimedia    | Implementada |
| GitHub                | Configurado  |
| GitHub Actions        | Configurado  |
| Despliegue FTP        | Configurado  |
| Hosting InfinityFree  | Configurado  |

---

# 24. Roadmap

Las futuras versiones pueden contemplar:

* [ ] Sistema avanzado de búsqueda.
* [ ] Optimización SEO.
* [ ] Sistema de etiquetas.
* [ ] API REST.
* [ ] Mejoras de caché.
* [ ] Optimización de imágenes.
* [ ] Sistema avanzado de estadísticas.
* [ ] Pruebas automatizadas.
* [ ] Mejoras adicionales de seguridad.

---

# 25. Autor

**Edgar Mauricio Gamarra Laura**

Estudiante de Ingeniería de Sistemas
Universidad Andina del Cusco

---

# 26. Licencia

Este proyecto ha sido desarrollado con fines académicos.

Todos los derechos sobre el código y los recursos utilizados corresponden a sus respectivos autores y/o propietarios, según corresponda.

---

## Documentación de despliegue

El proceso completo de publicación puede resumirse de la siguiente manera:

```text
┌─────────────────────┐
│     Desarrollo      │
│                     │
│ PHP + MySQL         │
│ HTML + CSS + JS     │
└──────────┬──────────┘
           │
           │ Git
           ▼
┌─────────────────────┐
│       GitHub        │
│                     │
│ Repository          │
│ Branch: main        │
└──────────┬──────────┘
           │
           │ Push
           ▼
┌─────────────────────┐
│   GitHub Actions    │
│                     │
│ Checkout            │
│        ↓            │
│ Deployment          │
│        ↓            │
│ FTP                 │
└──────────┬──────────┘
           │
           │ FTP
           ▼
┌─────────────────────┐
│     InfinityFree    │
│                     │
│      /htdocs/       │
│                     │
│ PHP Application     │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│     MySQL/MariaDB   │
│                     │
│ Production Database │
└─────────────────────┘
```

> **Resultado:** cada cambio aprobado y enviado a la rama de producción puede ser transferido automáticamente al servidor de InfinityFree mediante el pipeline de GitHub Actions, manteniendo el código fuente centralizado en GitHub y el entorno de ejecución en el hosting.

