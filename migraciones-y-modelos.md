# Migraciones, modelos y factories

Código en `TransAndino\`. Base de datos: PostgreSQL (base `cholosPHP`).

## Migraciones

Ubicación: `TransAndino\database\migrations\`

| Tabla | Migración |
|-------|-----------|
| users | 0001_01_01_000000_create_users_table |
| cache | 0001_01_01_000001_create_cache_table |
| jobs | 0001_01_01_000002_create_jobs_table |
| routes | 2026_10_18_000001_create_routes_table |
| buses | 2026_10_18_000002_create_buses_table |
| departures | 2026_10_18_000003_create_departures_table |
| tickets | 2026_10_18_000004_create_tickets_table |
| users (avatar) | 2026_10_18_000005_add_avatar_to_users_table |
| users (perfil) | 2026_10_18_000006_add_profile_fields_to_users_table |
| activity_logs | 2026_10_18_000007_create_activity_logs_table |
| bus_route | 2026_10_18_000008_create_bus_route_table |

### Restricciones principales

- `buses.placa`: UNIQUE.
- `users.name`: UNIQUE; `users.email`: nullable.
- `bus_route(bus_id, route_id)`: UNIQUE.
- `tickets(departure_id, numero_asiento) WHERE estado = 'vendido'`: índice único parcial (evita vender dos veces el mismo asiento).
- Claves foráneas: `departures` → `routes` y `buses`; `tickets` → `departures`; `bus_route` → `buses` y `routes`; `activity_logs` → `users`.

## Modelos

Ubicación: `TransAndino\app\Models\`

| Modelo | Relaciones |
|--------|-----------|
| Route | hasMany(Departure), belongsToMany(Bus) |
| Bus | hasMany(Departure), belongsToMany(Route) |
| Departure | belongsTo(Route), belongsTo(Bus), hasMany(Ticket) |
| Ticket | belongsTo(Departure) |
| User | perfil de usuario |
| ActivityLog | auditoría de acciones |

## Factories

Ubicación: `TransAndino\database\factories\`

UserFactory, RouteFactory, BusFactory, DepartureFactory, TicketFactory.
