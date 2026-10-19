# Datos sembrados (Seeder)

Se generan con:

```bash
cd TransAndino
php artisan migrate:fresh --seed
```

## Cantidades

| Entidad | Cantidad |
|---------|---------:|
| users | 2 |
| routes | 8 |
| buses | 6 |
| bus_route | ~20 |
| departures | 120 |
| tickets | ~1198 (1083 vendidos / 115 anulados) |

## Usuarios

| Usuario | Contraseña | Rol |
|---------|-----------|-----|
| `admin` | `1234` | Administrador |
| `carlos` | `1234` | Boletero |

Los datos incluyen pasajes vendidos y anulados, con distinta ocupación por salida, para poder probar las consultas y las reglas del sistema.
