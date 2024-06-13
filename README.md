# X-Padel

X-Padel es una aplicación web diseñada para mejorar la gestión de clubes de pádel y la experiencia de los jugadores. Esta aplicación permite a los usuarios reservar pistas, inscribirse en torneos, acceder a información relevante sobre su club y mantener una comunicación fluida con los administradores.

## Características principales

- Reserva de pistas: Los usuarios pueden reservar pistas de pádel de forma rápida y sencilla a través de la aplicación.
- Inscripción en torneos: Participa en torneos de pádel organizados por tu club y mantente al tanto de los próximos eventos.
- Comunicación: Facilita la comunicación entre los jugadores y los administradores del club, permitiendo una gestión más eficiente.
- Gestión de perfiles: Cada usuario tiene su propio perfil donde puede actualizar su información personal y consultar su historial de reservas y torneos.

## Tecnologías utilizadas

- Frontend: HTML, CSS, JavaScript.
- Backend: PHP con Laravel Framework
- Base de datos: Mysql

## Instalación

1. Clona este repositorio.
2. Accede al directorio del proyecto: cd x-padel
3. Instala las dependencias de Composer: composer install
4. Copia el archivo de configuración de ejemplo y configura tu entorno: cp .env.example .env
5. Genera una nueva clave de aplicación: php artisan key:generate
6. Configura tu base de datos en el archivo .env. Por ejemplo:
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nombre_de_tu_base_de_datos
    DB_USERNAME=usuario_de_tu_base_de_datos
    DB_PASSWORD=contraseña_de_tu_base_de_datos
7. Ejecuta las migraciones y los seeders para crear la estructura de la base de datos y poblarla con datos de prueba (opcional): php artisan migrate --seed
8. Inicia el servidor de desarrollo: php artisan serve
9. Una vez completados estos pasos, podrás acceder a X-Padel en tu navegador visitando http://localhost:8000.
