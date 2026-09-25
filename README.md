# 📰 Sistema de Noticias - DDP Noticias

Portal web dinámico orientado a la publicación de noticias, reportajes, boletines y podcasts, desarrollado con **PHP y MySQL**, e integrado con un flujo de **Integración y Despliegue Continuo (CI/CD)** mediante **GitHub Actions** hacia un servidor de hosting compartido en **InfinityFree**.

---

## 📌 Descripción del Proyecto

DDP Noticias es un sistema web de gestión de contenidos (CMS) que permite administrar publicaciones periodísticas desde un panel privado y mostrarlas en un portal público accesible desde cualquier dispositivo.

El sistema cuenta con:

* Portal público para visitantes.
* Panel de administración protegido.
* Gestión de reportajes, noticias, podcasts y boletines.
* Carga de imágenes y archivos multimedia.
* Dashboard con estadísticas.
* Despliegue automático mediante GitHub Actions.

---

## 🚀 Características Principales

### 🌐 Portal Público

* **Inicio (`index.php`)**

  * Noticias destacadas.
  * Últimas publicaciones.
  * Contenido dinámico.

* **Reportajes**

  * Listado de reportajes.
  * Vista individual detallada.

* **Podcasts**

  * Reproductor integrado.
  * Gestión desde panel administrativo.

* **Boletines**

  * Publicación y consulta de boletines.

* **Diseño Responsive**

  * Compatible con computadoras, tablets y dispositivos móviles.

---

### ⚙️ Panel de Administración

Ubicación:

```text
/admin
```

Funcionalidades:

* Inicio de sesión seguro.
* Gestión de usuarios.
* CRUD de contenidos.
* Gestión de imágenes.
* Dashboard estadístico.
* Calendario de publicaciones.
* Administración de archivos multimedia.

---

## 🛠️ Tecnologías Utilizadas

| Tecnología      | Uso                  |
| --------------- | -------------------- |
| PHP 8.x         | Backend              |
| MySQL / MariaDB | Base de datos        |
| HTML5           | Estructura           |
| CSS3            | Estilos              |
| JavaScript ES6  | Interactividad       |
| Bootstrap       | Diseño responsive    |
| Chart.js        | Gráficos             |
| FullCalendar    | Calendario           |
| Git             | Control de versiones |
| GitHub          | Repositorio          |
| GitHub Actions  | CI/CD                |
| FTP             | Despliegue           |
| InfinityFree    | Hosting              |

---

## 📁 Estructura del Proyecto

```text
sistema_noticias/
│
├── .github/
│   └── workflows/
│       └── deploy.yml
│
├── admin/
│   ├── assets/
│   ├── config/
│   ├── includes/
│   ├── uploads/
│   ├── gestion.php
│   ├── login.php
│   ├── logout.php
│   ├── index.php
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
└── README.md
```

---

## 🗄️ Base de Datos

El sistema utiliza MySQL/MariaDB.

Importar el archivo:

```text
admin/sql_instalacion.sql
```

Tablas principales:

* usuarios
* autores
* reportajes
* noticias
* podcasts
* boletines
* videos

---

## ⚙️ Configuración Local

### 1. Clonar el repositorio

```bash
git clone https://github.com/usuario/sistema_noticias.git
```

### 2. Copiar el proyecto a XAMPP

```text
C:\xampp\htdocs\sistema_noticias
```

### 3. Crear la base de datos

```sql
CREATE DATABASE sistema_noticias;
```

### 4. Importar

```text
sql_instalacion.sql
```

### 5. Configurar conexión

Archivo:

```text
config/conexion.php
```

Ejemplo:

```php
$host = "localhost";
$user = "root";
$pass = "";
$db = "sistema_noticias";

$conn = new mysqli($host, $user, $pass, $db);
```

---

## ☁️ Despliegue en InfinityFree

El proyecto se despliega automáticamente mediante GitHub Actions utilizando conexión FTP.

Servidor:

```text
InfinityFree
```

Carpeta destino:

```text
htdocs/
```

---

## 🔐 Configuración de Secrets en GitHub

Ir a:

```text
Repository → Settings → Secrets and variables → Actions
```

Agregar:

```text
FTP_SERVER
FTP_USERNAME
FTP_PASSWORD
FTP_DIR
```

Ejemplo:

```text
FTP_SERVER=ftpupload.net
FTP_USERNAME=epiz_xxxxxxxx
FTP_PASSWORD=********
FTP_DIR=/htdocs/
```

---

## 🔄 Integración Continua (CI/CD)

Archivo:

```text
.github/workflows/deploy.yml
```

Ejemplo:

```yaml
name: Deploy InfinityFree

on:
  push:
    branches:
      - main

jobs:
  ftp-deploy:
    runs-on: ubuntu-latest

    steps:
      - name: Descargar repositorio
        uses: actions/checkout@v4

      - name: Subir archivos por FTP
        uses: SamKirkland/FTP-Deploy-Action@v4.3.5
        with:
          server: ${{ secrets.FTP_SERVER }}
          username: ${{ secrets.FTP_USERNAME }}
          password: ${{ secrets.FTP_PASSWORD }}
          server-dir: ${{ secrets.FTP_DIR }}
```

---

## 🔄 Flujo de Despliegue

```text
Desarrollador
       ↓
Git Commit
       ↓
GitHub Repository
       ↓
GitHub Actions
       ↓
FTP
       ↓
InfinityFree
       ↓
Sitio Web Actualizado
```

---

## 📸 Capturas del Sistema

Agregar imágenes:

```text
docs/
├── home.png
├── dashboard.png
├── login.png
└── gestion.png
```

Ejemplo:

```markdown
![Inicio](docs/home.png)
```

---

## 👥 Roles del Sistema

### Administrador

* Gestionar usuarios.
* Crear publicaciones.
* Editar contenido.
* Eliminar registros.
* Administrar multimedia.

### Reportero

* Crear publicaciones.
* Editar sus contenidos.

---

## 🔒 Seguridad Implementada

* Inicio de sesión protegido.
* Sesiones PHP.
* Hash de contraseñas.
* Validación de formularios.
* Protección de rutas administrativas.

---

## 📈 Futuras Mejoras

* Buscador avanzado.
* Comentarios.
* Etiquetas.
* SEO dinámico.
* API REST.
* Notificaciones.
* Estadísticas avanzadas.

---

## 👨‍💻 Autor

**Edgar Mauricio Gamarra Laura**

Estudiante de Ingeniería de Sistemas
Universidad Andina del Cusco

---

## 📄 Licencia

Proyecto desarrollado con fines académicos y de investigación.
