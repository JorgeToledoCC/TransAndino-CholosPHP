# cholosPHP

Sistema de venta de pasajes interdepartamentales "Trans Andino" (Laravel + PostgreSQL).

## Carpetas

- `TransAndino\` → proyecto Laravel (código fuente).
- `docs\` → diagramas del modelo de datos (punto 1).

## Documentos

- `migraciones-y-modelos.md` → migraciones, modelos y factories.
- `datos-sembrados.md` → datos que genera el seeder.
- `consultas-tinker\` → las 10 consultas de Tinker (`consultas.md`) y sus capturas (`capturas\`).

## Cómo levantar el proyecto

```bash
cd TransAndino
composer install
php artisan migrate:fresh --seed
php artisan serve
```

## Usuarios de prueba

- `admin` / `1234` — administrador.
- `carlos` / `1234` — boletero.
