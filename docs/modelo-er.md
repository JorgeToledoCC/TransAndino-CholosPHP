# Modelo Entidad-Relación — Trans Andino

Sistema de venta de pasajes interdepartamentales.

## Entidades

| Entidad | Descripción |
|---------|-------------|
| `routes` | Rutas (origen, destino, duración y precio base). |
| `buses` | Flota de buses (placa, tipo de servicio y capacidad). |
| `bus_route` | Tabla pivote: habilitación de buses por ruta (M:N). |
| `departures` | Salidas programadas (ruta + bus + chofer + fecha/hora + precio final). |
| `tickets` | Pasajes vendidos sobre una salida. |
| `users` | Usuarios del sistema (boleteros y administrador). |
| `activity_logs` | Bitácora de auditoría de acciones. |

## Cardinalidades

- `routes` **1:N** `departures` — una ruta tiene muchas salidas.
- `buses` **1:N** `departures` — un bus opera muchas salidas.
- `departures` **1:N** `tickets` — una salida tiene muchos pasajes.
- `routes` **M:N** `buses` (a través de `bus_route`) — un bus cubre varias rutas y una ruta es cubierta por varios buses.
- `users` **1:N** `activity_logs` — un usuario genera muchos registros (FK opcional/nullable).
- `activity_logs` **relación polimórfica** con las demás entidades vía `subject_type` + `subject_id`.

## Diagramas

- `modelo-er.png` / `modelo-er.svg` — diagrama Entidad-Relación.
- `diagrama-clases.png` / `diagrama-clases.svg` — diagrama de clases de los modelos Eloquent.

## Restricciones e índices destacados

- **Claves primarias**: `id` autoincremental en todas las tablas.
- **Claves foráneas y acciones**:
  - `departures.route_id` → `routes.id` (`RESTRICT` al borrar, `CASCADE` al actualizar).
  - `departures.bus_id` → `buses.id` (`RESTRICT` / `CASCADE`).
  - `tickets.departure_id` → `departures.id` (`CASCADE` al borrar).
  - `bus_route.bus_id` / `bus_route.route_id` → `CASCADE`.
  - `activity_logs.user_id` → `users.id` (`NULL ON DELETE`).
- **UNIQUE**:
  - `buses.placa`.
  - `users.name` (el login es por usuario).
  - `bus_route(bus_id, route_id)` — no repetir el mismo bus en la misma ruta.
  - **Índice único PARCIAL** `tickets(departure_id, numero_asiento) WHERE estado = 'vendido'` — impide la doble venta del mismo asiento, pero **libera el asiento cuando el pasaje se anula**.
- **NULLABLE**: `users.email`, `users.avatar`, campos de perfil, `activity_logs.user_id`/`subject_*`/`ip_address`.
- **DEFAULT**: `tickets.estado = 'vendido'`, `users.is_admin = false`, `bus_route.activo = true`.
- **ÍNDICES**: `departures(route_id)`, `departures(bus_id)`, `departures(fecha)`, `tickets(departure_id)`, `bus_route(route_id)`, `bus_route(bus_id)`.

## Reglas de negocio reflejadas en el modelo

1. `departures.precio_final = routes.precio_base + recargo(buses.tipo)` (se congela al crear la salida).
2. Un asiento solo puede venderse una vez por salida (índice único parcial).
3. Un pasaje solo se anula si faltan **más de 2 horas** para la salida (validado en el modelo `Ticket`).
4. `numero_asiento` debe estar dentro de la capacidad del bus (`buses.numero_asientos`).
