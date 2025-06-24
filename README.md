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
- (Opcional) Docker y Docker Compose para despliegue rápido

## Instalación y configuración
1. **Clona o copia el proyecto en tu servidor local o hosting.**
2. **Configura la base de datos:**
   - Crea una base de datos en MySQL llamada `veranum`.
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

## Base de datos y usuarios de prueba
- El archivo `database/veranum.sql` incluye:
  - Estructura de todas las tablas necesarias.
  - Datos de ejemplo para hoteles, habitaciones, reservas, servicios y usuarios de todos los roles.
- **Usuarios de prueba incluidos:**
  - **Admin Demo:** admin@demo.com / admin123
  - **Recepcionista:** recep@demo.com / recep123
  - **Cocinero:** cocina@demo.com / cocina123
  - **Gerente:** gerente@demo.com / gerente123
  - **Cliente Demo:** cliente@demo.com / cliente123
- **Hoteles de ejemplo:**
  - Veranum Santiago
  - Veranum Vta Región
- **Para importar la base:**
  - Usa phpMyAdmin, HeidiSQL o el cliente de tu preferencia para importar `database/veranum.sql` en una base llamada `veranum`.

## Uso en Docker
1. **Asegúrate de tener Docker y Docker Compose instalados.**
2. **Clona el repositorio y entra a la carpeta del proyecto.**
3. **Levanta el entorno:**
   ```bash
   docker-compose up --build
   ```
4. **Accede al sistema:**
   - Usualmente en `http://localhost:8080` (o el puerto definido en tu docker-compose).
5. **Base de datos:**
   - Si tu `docker-compose.yml` está configurado para inicializar la base con `veranum.sql`, se cargará automáticamente.
   - Si no, entra al contenedor de MySQL y ejecuta el SQL manualmente:
     ```bash
     docker-compose exec db mysql -u root -p veranum < database/veranum.sql
     ```

## Migración a otra PC
- Clona el repositorio o copia la carpeta completa.
- Importa la base de datos como se indica arriba.
- Configura `config/database.php` según el entorno.
- Si usas Docker, solo necesitas los comandos de arriba.
- Prueba el sistema antes de la presentación.

## Uso básico
- Accede a la URL principal (por ejemplo, `http://localhost/veranum/public/app/pages/login.html`).
- Ingresa con los usuarios de práctica.
- Navega por el sistema según los permisos de tu rol.
- El gerente puede acceder a los reportes y filtrar por fechas.

## Notas
- El sistema está listo para pruebas y entrega.
- Si tienes dudas o necesitas soporte, revisa los comentarios en el código o contacta al desarrollador.

---
¡Gracias por usar Veranum! 