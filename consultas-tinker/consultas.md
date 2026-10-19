# Consultas de Tinker

## Integridad de datos

### 1. Doble venta del mismo asiento

**¿Por qué?** Comprueba que no se venda dos veces el mismo asiento (índice único parcial).

```php
App\Models\Ticket::where('estado', 'vendido')->selectRaw('departure_id, numero_asiento, count(*) as veces')->groupBy('departure_id', 'numero_asiento')->havingRaw('count(*) > 1')->count();
```

**Resultado:** `0`. Ningún asiento vendido se repite en una salida.

### 2. Placas de bus duplicadas

**¿Por qué?** Comprueba la restricción `UNIQUE` de `buses.placa`.

```php
App\Models\Bus::select('placa')->groupBy('placa')->havingRaw('count(*) > 1')->count();
```

**Resultado:** `0`. Cada bus tiene una placa única.

### 3. Pasajes con salida inexistente

**¿Por qué?** Comprueba la clave foránea `tickets.departure_id → departures.id`.

```php
App\Models\Ticket::whereDoesntHave('departure')->count();
```

**Resultado:** `0`. No hay pasajes huérfanos.

### 4. Precio final mal calculado

**¿Por qué?** Comprueba la regla `precio_final = precio_base + recargo` del bus.

```php
App\Models\Departure::with('route', 'bus')->get()->filter(fn ($d) => abs($d->precio_final - $d->route->precio_base - $d->bus->recargo()) > 0.001)->count();
```

**Resultado:** `0`. Todos los precios finales están bien calculados.

## Consultas con Collections

### 5. Rutas con precio base mayor a 100 Bs (`filter`)

```php
App\Models\Route::all()->filter(fn ($r) => $r->precio_base > 100)->map(fn ($r) => $r->nombre.' = Bs '.$r->precio_base)->values()->all();
```

**Resultado:**

```
["La Paz - Tarija = Bs 120.00","La Paz - Sucre = Bs 110.00","La Paz - Santa Cruz = Bs 180.00","La Paz - Arica = Bs 150.00"]
```

**Explicación:** se queda con las rutas que cuestan más de 100 Bs (son 4).

### 6. Buses agrupados por tipo (`groupBy`)

```php
App\Models\Bus::all()->groupBy(fn ($b) => $b->tipo->label())->map->count()->all();
```

**Resultado:**

```
{"Normal":2,"Semicama":2,"Cama":2}
```

**Explicación:** cuenta cuántos buses hay de cada tipo de servicio.

### 7. Las 3 rutas más caras (`sortByDesc` + `take`)

```php
App\Models\Route::all()->sortByDesc('precio_base')->take(3)->map(fn ($r) => $r->nombre.' = Bs '.$r->precio_base)->values()->all();
```

**Resultado:**

```
["La Paz - Santa Cruz = Bs 180.00","La Paz - Arica = Bs 150.00","La Paz - Tarija = Bs 120.00"]
```

**Explicación:** ordena las rutas de mayor a menor precio y muestra las 3 primeras.

## Consultas de agregación

### 8. Total vendidos y recaudación (`count` + `sum`)

```php
['vendidos' => App\Models\Ticket::vendidos()->count(), 'recaudado_bs' => (float) App\Models\Ticket::vendidos()->join('departures', 'departures.id', '=', 'tickets.departure_id')->sum('departures.precio_final')];
```

**Resultado:**

```
{"vendidos":1083,"recaudado_bs":141990}
```

**Explicación:** cuenta los pasajes vendidos y suma la recaudación total (141.990 Bs).

### 9. Ocupación promedio por salida (`avg`)

```php
round(App\Models\Ticket::vendidos()->count() / max(1, App\Models\Departure::count()), 2);
```

**Resultado:** `9.03`.

**Explicación:** promedio de pasajes vendidos por salida (~9).

### 10. Rutas con ingresos mayores a 5000 Bs (`groupBy` + `having` + `sum`)

```php
App\Models\Ticket::vendidos()->join('departures', 'departures.id', '=', 'tickets.departure_id')->join('routes', 'routes.id', '=', 'departures.route_id')->selectRaw("routes.origen, routes.destino, count(*) as pasajes, sum(departures.precio_final) as ingresos")->groupBy('routes.origen', 'routes.destino')->havingRaw('sum(departures.precio_final) > 5000')->orderByDesc('ingresos')->get();
```

**Resultado:**

```
[{"origen":"La Paz","destino":"Santa Cruz","pasajes":137,"ingresos":"30300.00"},
 {"origen":"La Paz","destino":"Uyuni","pasajes":178,"ingresos":"23570.00"},
 {"origen":"La Paz","destino":"Arica","pasajes":102,"ingresos":"18390.00"},
 {"origen":"La Paz","destino":"Tarija","pasajes":114,"ingresos":"17160.00"},
 {"origen":"La Paz","destino":"Sucre","pasajes":127,"ingresos":"15410.00"},
 {"origen":"La Paz","destino":"Potosí","pasajes":127,"ingresos":"14210.00"},
 {"origen":"La Paz","destino":"Cochabamba","pasajes":150,"ingresos":"12450.00"},
 {"origen":"La Paz","destino":"Oruro","pasajes":148,"ingresos":"10500.00"}]
```

**Explicación:** agrupa los ingresos por ruta y solo muestra las que superaron los 5000 Bs (son 8).
