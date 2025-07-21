# Gestor de Tareas

Un proyecto de gestión de tareas simple y eficiente, desarrollado con PHP y una arquitectura modular, ideal para aprender y aplicar buenas prácticas de desarrollo web.

## 🚀 Características

* **Gestión de Tareas:** Crea y visualiza tareas.
* **Diseño Limpio:** Interfaz de usuario minimalista y moderna construida con Tailwind CSS.
* **Enrutamiento Sencillo:** Sistema de enrutamiento basado en un *front controller* que gestiona todas las peticiones.
* **Entorno Contenerizado:** Entorno de desarrollo aislado con Docker para un despliegue y una configuración consistentes.

## ⚙️ Tecnologías Utilizadas

* **Backend:** PHP 8.2+
* **Servidor Web:** Nginx
* **Gestor de Dependencias:** Composer
* **Base de Datos:** SQLite 3
* **Frontend:** HTML5, CSS3, JavaScript
* **Framework CSS:** Tailwind CSS

## 📋 Requisitos Previos

Asegúrate de tener instalado **Docker** y **Docker Compose** en tu sistema.

## 📦 Instalación y Configuración

Sigue estos pasos para poner en marcha el proyecto:

1. **Clona el repositorio:**

    ```bash
    git clone https://github.com/luistalero/PHP-project.git
    cd src
    ```

2. **Construye y levanta los contenedores:**
    Este comando construirá las imágenes de Docker, instalará las dependencias de Composer y pondrá en marcha todos los servicios definidos en `docker-compose.yml`.

    ```bash
    docker-compose up -d --build
    ```

3. **Accede a la aplicación:**
    Una vez que los contenedores estén listos, puedes acceder a la aplicación desde tu navegador:

    ```
    http://localhost:1817
    ```

## 📂 Estructura del Proyecto

## La arquitectura del proyecto está organizada para separar las responsabilidades de manera clara

```bash

├── docker-compose.yml         # Configuración de los servicios Docker
├── Dockerfile                 # Receta para construir la imagen de PHP
├── nginx.conf                 # Configuración del servidor Nginx
├── public/                    # Archivos públicos accesibles desde el navegador
│   ├── js/
│   └── index.php              # Punto de entrada de la aplicación
└── src/                       # Código fuente de la aplicación
├── Controllers/           # Lógica de la aplicación
├── Models/                # Lógica de la base de datos
├── Views/                 # Archivos de la interfaz de usuario (HTML/PHP)
└── ...
```

## 👷‍♂️ Uso

* Visita `http://localhost:1817` para ver la página de inicio.
* Haz clic en "Ver mis tareas" para ir a la lista.
* Usa el formulario para crear nuevas tareas.

---
**Desarrollado con ❤️ por Luis Talero**
