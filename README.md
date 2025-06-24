# Veranum - Sistema de Gestión Hotelera

Veranum es un sistema web moderno para la gestión integral de hoteles, habitaciones, reservas, servicios, insumos y reportes. Permite a diferentes roles (gerente, administrador, recepcionista, cocinero, cliente) operar y consultar información relevante de manera segura y eficiente.

## Características principales
- Gestión de hoteles y habitaciones
- Reservas online y confirmación por parte del cliente
- Check-in y check-out por recepcionista
- Asignación y gestión de servicios adicionales
- Gestión de insumos y stock de cocina
- Reportes de ocupación e ingresos por periodo para el gerente
- Seguridad y navegación según el rol del usuario

## Roles y funcionalidades
- **Gerente:** Consulta reportes de ocupación e ingresos detallados por periodo.
- **Administrador:** Gestiona hoteles y habitaciones.
- **Recepcionista:** Registra ingresos de pasajeros, coordina servicios y gestiona reservas.
- **Cocinero:** Gestiona insumos, stock y reportes de consumo.
- **Cliente:** Reserva habitaciones y confirma reservas online.

## Requerimientos
- PHP >= 7.4
- MySQL/MariaDB
- Servidor web (Apache, Nginx, Laragon, XAMPP, etc.)
- Navegador moderno
- (Opcional) Composer y/o npm si deseas actualizar dependencias

## Instalación y configuración
1. **Clona o copia el proyecto en tu servidor local o hosting.**
2. **Configura la base de datos:**
   - Crea una base de datos en MySQL.
   - Importa el archivo `database/veranum.sql` para crear las tablas y datos iniciales.
3. **Configura la conexión a la base de datos:**
   - Edita `config/database.php` con tus credenciales de MySQL.
4. **Asegúrate de que la carpeta `public/` sea accesible desde el navegador.**
5. **(Opcional) Instala dependencias:**
   - Si usas Composer: `composer install`
   - Si usas npm para Tailwind: `npm install && npm run build`

## Estructura de carpetas
```
veranum/
├── config/              # Configuración de la base de datos
├── database/            # Scripts SQL para la base de datos
├── public/              # Archivos públicos (frontend, API)
│   ├── app/
│   │   ├── assets/css/  # Estilos (Tailwind)
│   │   ├── js/          # Scripts JS del sistema
│   │   ├── components/  # Componentes HTML reutilizables
│   │   └── pages/       # Páginas del sistema
│   └── api.php          # Endpoint principal de la API
├── src/                 # Código fuente PHP (módulos)
│   └── modules/         # Módulos por dominio (habitacion, reserva, servicio, etc.)
├── Dockerfile, docker-compose.yml (opcional)
├── README.md
└── ...
```

## Uso básico
- Accede a la URL principal (por ejemplo, `http://localhost/veranum/public/app/pages/login.html`).
- Ingresa con el usuario correspondiente a tu rol.
- Navega por el sistema según los permisos de tu rol.
- El gerente puede acceder a los reportes y filtrar por fechas.

## Recomendaciones para migrar a otra PC
- Copia todo el proyecto, incluyendo las carpetas `config/`, `database/`, `public/`, `src/` y los archivos de configuración.
- Asegúrate de importar la base de datos (`database/veranum.sql`) en el nuevo entorno.
- Revisa y actualiza las credenciales en `config/database.php`.
- Si usas rutas absolutas, ajústalas según el nuevo entorno.
- Si usas Docker, puedes levantar el entorno con `docker-compose up`.

## Notas
- El sistema está listo para pruebas y entrega.
- Si tienes dudas o necesitas soporte, revisa los comentarios en el código o contacta al desarrollador.

---
¡Gracias por usar Veranum! 