# Documentación Tienda de Segunda Mano

**Curso:** DAW
**Proyecto:** Intermodular

---

## Índice

1. [Introducción](#1-introducción)
2. [Parte funcional](#2-parte-funcional)
   - 2.1. Objetivos
   - 2.2. Tipos de usuario
   - 2.3. Funcionalidades
   - 2.4. Diseño de la interfaz
   - 2.5. Capturas de pantalla
3. [Parte técnica](#3-parte-técnica)
   - 3.1. Tecnologías usadas
   - 3.2. Estructura del proyecto
   - 3.3. Base de datos
   - 3.4. Cómo se guardan los datos
   - 3.5. API REST
   - 3.6. Frontend
   - 3.7. Carrito de la compra
   - 3.8. Autenticación
   - 3.9. Sistema de reseñas
   - 3.10. Fragmentos de código

---

## 1. Introducción

Tienda de Segunda Mano es una aplicación web para comprar y vender artículos de segunda mano. Está hecha con Vue 3 para la parte del cliente y Laravel 12 para el servidor con una base de datos MySQL. La idea es que los usuarios puedan ver productos, comprarlos, dejar opiniones y que los administradores puedan gestionar todo.

---

## 2. Parte funcional

### 2.1. Objetivos

- Crear una web donde la gente pueda comprar artículos de segunda mano.
- Que sea fácil encontrar productos con filtros.
- Que los usuarios puedan registrarse y tener su perfil.
- Que los administradores puedan gestionar los artículos, categorías y usuarios.
- Tener un carrito de compra funcional.
- Poder dejar reseñas y puntuaciones.

### 2.2. Tipos de usuario

- **Visitante:** Puede ver artículos y categorías pero no comprar ni dejar reseñas.
- **Usuario registrado:** Puede comprar, gestionar su perfil, ver historial y dejar reseñas.
- **Administrador:** Puede crear, editar y borrar artículos, categorías y gestionar usuarios.

### 2.3. Funcionalidades

#### Para cualquier usuario (visitante o registrado)

- **Página principal** con un carrusel de imágenes y las categorías principales.
- **Listado de artículos** con tarjetas de producto.
- **Filtros** para buscar por nombre (con autocompletado), categoría, precio mínimo/máximo y si está en oferta.
- **Página de detalle** del artículo con fotos, descripción, precio, stock y reseñas.

#### Para usuarios registrados

- **Registro** con nombre, email, teléfono, fecha de nacimiento, ciudad, código postal, dirección de envío y facturación.
- **Inicio de sesión** con email y contraseña.
- **Perfil** donde se puede editar la información personal, cambiar contraseña y foto de perfil.
- **Carrito de compra** que se despliega desde un botón. Se pueden añadir/quitar artículos, cambiar cantidades y se calcula el total automáticamente.
- **Compra** simulada con selección de método de pago (tarjeta, PayPal, contra reembolso).
- **Historial de compras** para ver pedidos anteriores.
- **Lista de deseos** (de momento con datos de ejemplo).
- **Reseñas** de 1 a 5 estrellas con comentario. Cada usuario solo puede reseñar un artículo una vez.

#### Para administradores

- **Panel de administración** con estadísticas (total artículos, en oferta, stock bajo).
- **Gráfico de barras** del stock de cada producto.
- **CRUD de artículos:** crear con hasta 5 imágenes, editar (gestionando las imágenes), borrar.
- **CRUD de categorías:** crear con imagen, editar, borrar.
- **Gestión de usuarios:** listar, editar, hacer admin/quitarlo, borrar (no se puede borrar uno mismo).

### 2.4. Diseño de la interfaz

Los colores principales son:

- Rojo oscuro (#70191d) para títulos y cosas importantes.
- Azul (#5686bb) para botones.
- Naranja (#e07c3c) para ofertas y alertas.
- Fondo beige claro (#f5f0eb).
- Tarjetas y contenedores en blanco.

La web se adapta a móvil, tablet y ordenador. También tiene modo oscuro automático según el sistema operativo.

**Organización de la página:**

```
CABECERA
  ├── Logo
  ├── Menú: Inicio, Artículos, Categorías
  ├── Carrito (icono)
  └── Usuario: Login / Registro / Mi cuenta

CUERPO (cambia según la página)
  ├── Home: carrusel + categorías
  ├── Artículos: filtros + cuadrícula de productos
  ├── Detalle: fotos + info + reseñas
  └── Admin: estadísticas + gráfico + tablas

PIE DE PÁGINA
  ├── Enlaces legales
  └── Redes sociales
```

### 2.5. Capturas de pantalla

*(Aquí se pondrían las capturas en la entrega final. Recomiendo capturar:)*

1. Página principal con el carrusel.
2. Listado de artículos con filtros.
3. Detalle de un artículo con reseñas.
4. Carrito desplegado con productos.
5. Página de checkout.
6. Panel de administración.
7. Formulario de crear artículo.
8. Perfil de usuario.
9. Formulario de registro.

---

## 3. Parte técnica

### 3.1. Tecnologías usadas

| Para qué | Tecnología |
|----------|-----------|
| Frontend | Vue 3 con TypeScript |
| Estado global | Pinia |
| Enrutador | Vue Router |
| Componentes UI | PrimeVue 4 + Volt UI (hechos por nosotros) |
| CSS | Tailwind CSS v4 |
| Iconos | PrimeIcons |
| Llamadas al servidor | Axios |
| Backend | Laravel 12 |
| Autenticación API | Laravel Sanctum |
| Base de datos | MySQL |
| Build | Vite |

### 3.2. Estructura del proyecto

El proyecto se divide en dos partes principales:

**Backend (Laravel)**
- `app/Models/` → Aquí están los modelos Article, Category, Review y User.
- `app/Http/Controllers/Api/` → Controladores de la API.
- `app/Http/Requests/` → Validaciones de los formularios.
- `routes/api.php` → Definición de las rutas de la API.
- `database/migrations/` → Esquemas de las tablas de la base de datos.

**Frontend (Vue 3)**
- `resources/ts/views/` → Las páginas de la web (17 vistas).
- `resources/ts/components/` → Componentes reutilizables (14).
- `resources/ts/stores/` → Gestión del estado con Pinia (3 stores).
- `resources/ts/composables/` → Funciones reutilizables (2).
- `resources/ts/router/` → Definición de rutas del frontend.
- `resources/ts/types.ts` → Interfaces de TypeScript.
- `src/volt/` → Componentes UI personalizados (16).

### 3.3. Base de datos

La base de datos se llama `tiendasegundamano` y tiene 4 tablas principales:

#### Tabla `users` (usuarios)

Guarda los datos de los usuarios: nombre, email, contraseña (encriptada), teléfono, fecha de nacimiento, ciudad, código postal, direcciones, foto de perfil y si es administrador o no.

#### Tabla `categories` (categorías)

Guarda el nombre de la categoría y la ruta de la imagen.

#### Tabla `articles` (artículos)

Es la tabla más importante. Guarda:
- Nombre, descripción, precios (actual y anterior).
- Si está en oferta o no.
- Las imágenes (se guardan como un JSON con las rutas).
- El estado del artículo (1=como nuevo, 2=bueno, 3=aceptable).
- La categoría (relacionada con la tabla categories).
- El stock disponible.
- La valoración media y el número de valoraciones.
- Cuántas veces se ha vendido.

#### Tabla `reviews` (reseñas)

Guarda la reseña que hace un usuario sobre un artículo. Tiene la puntuación (1-5), un comentario opcional y las relaciones con el usuario y el artículo. Hay una restricción para que un usuario no pueda reseñar el mismo artículo más de una vez.

### 3.4. Cómo se guardan los datos

#### Las imágenes

Las imágenes se guardan en el disco del servidor, en la carpeta `storage/app/public/`. Dentro hay subcarpetas:
- `articles/` → Imágenes de los artículos.
- `categories/` → Imágenes de las categorías.
- `profile-images/` → Fotos de perfil.

En la base de datos solo se guarda la ruta (ej: `articles/imagen123.jpg`). Luego el frontend construye la URL completa para mostrarlas.

**Los artículos pueden tener hasta 5 imágenes.** Se guardan como un array JSON en la columna `images`. Por ejemplo:

```json
["articles/foto1.jpg", "articles/foto2.jpg", "articles/foto3.jpg"]
```

Cuando se actualiza un artículo, el sistema:
1. Mantiene las imágenes que el usuario quiere conservar.
2. Añade las nuevas imágenes que sube.
3. Borra del disco las imágenes que ya no están.

#### Los artículos en el frontend

Cuando el frontend recibe los artículos del backend, vienen en **snake_case** (porque es lo que usa PHP/Laravel). El frontend los convierte a **camelCase** (lo normal en JavaScript) para trabajar más cómodamente.

Para eso se usan dos interfaces de TypeScript diferentes:

```typescript
// Lo que viene del backend (snake_case)
interface RawArticle {
    id: number;
    name: string;
    on_sale: boolean;
    old_price: number;
    category_id: number;
    images: string[];
}

// Lo que usa el frontend (camelCase)
interface Article {
    id: number;
    name: string;
    onSale: boolean;
    oldPrice: number;
    categoryId: number;
    images: string[];
    img: string; // URL de la primera imagen para mostrar
}
```

Y se mapean así:

```typescript
articles.value = rawData.map((art: RawArticle): Article => ({
    ...art,
    onSale: Boolean(art.on_sale),
    oldPrice: art.old_price,
    categoryId: art.category_id,
    img: construirUrlImagen(art.images),
}));
```

#### El carrito (localStorage)

El carrito se guarda en el **localStorage** del navegador para que no se pierda aunque se cierre la página. Cada vez que el usuario añade o quita algo, se guarda automáticamente.

Se guarda así en el navegador:

```
localStorage.setItem("cart", JSON.stringify([
    { article: { id: 1, name: "Mesa", price: 25, ... }, quantity: 2 },
    { article: { id: 5, name: "Silla", price: 15, ... }, quantity: 1 }
]));
```

#### Los filtros

Los filtros se hacen en el frontend mediante una **propiedad computada** que filtra el array de todos los artículos. No se hace una llamada al servidor cada vez que se cambia un filtro, sino que se filtra directamente en el navegador.

### 3.5. API REST

El backend funciona como una API REST. Todas las rutas empiezan con `http://localhost:8000/api/`.

#### Rutas públicas (no necesitan login)

| Método | Ruta | Qué hace |
|--------|------|----------|
| POST | /api/register | Registrar usuario |
| POST | /api/login | Iniciar sesión |
| GET | /api/categories | Listar categorías |
| GET | /api/articles | Listar artículos |
| GET | /api/articles/{id} | Ver un artículo |
| GET | /api/articles/{article}/reviews | Ver reseñas de un artículo |

#### Rutas protegidas (necesitan token)

| Método | Ruta | Qué hace |
|--------|------|----------|
| POST | /api/logout | Cerrar sesión |
| POST | /api/user/profile | Actualizar perfil |
| POST | /api/articles | Crear artículo |
| POST | /api/articles/{id} | Actualizar artículo |
| DELETE | /api/articles/{id} | Borrar artículo |
| POST | /api/articles/{id}/buy | Comprar artículo |
| POST | /api/articles/{article}/reviews | Crear reseña |
| DELETE | /api/reviews/{review} | Borrar reseña |
| GET | /api/admin/users | Listar usuarios (admin) |
| PATCH | /api/admin/users/{id}/make-admin | Hacer admin |
| PATCH | /api/admin/users/{id}/remove-admin | Quitar admin |

**Una cosa importante:** Para las actualizaciones usamos POST en lugar de PUT/PATCH porque PHP funciona mejor con formularios multipart (para subir imágenes) usando POST.

### 3.6. Frontend

#### Cómo está organizado

El frontend es una **SPA (Single Page Application)**, es decir, una sola página que cambia de contenido según la ruta sin recargar completamente.

Las rutas se cargan de forma **perezosa (lazy loading)**, lo que significa que cada vista solo se descarga cuando se necesita. Esto hace que la página cargue más rápido al principio.

#### Gestión del estado (Pinia)

Usamos tres almacenes (stores) de Pinia:

1. **auth store** → Guarda el usuario logueado y el token. Se persiste automáticamente en localStorage.
2. **cart store** → Guarda los artículos del carrito. Se persiste manualmente en localStorage.
3. **articleFilters store** → Guarda los filtros que ha seleccionado el usuario. No se persiste.

#### Componentes reutilizables

Hemos creado componentes para:
- Header y Footer.
- Tarjetas de artículos.
- Filtros.
- Tablas de datos.
- Gráfico de stock (dibujado con Canvas).

Además, tenemos la librería **Volt UI** con 16 componentes personalizados (botones, inputs, selects, etc.) que envuelven a los de PrimeVue para que tengan el estilo de la web.

### 3.7. Carrito de la compra

El carrito funciona así:

1. **Estructura:** Es un array de objetos con el artículo y la cantidad.
2. **Añadir:** Si el artículo ya está, aumenta la cantidad (sin superar el stock). Si no está, lo añade con cantidad 1.
3. **Quitar:** Filtra el array para eliminar el artículo.
4. **Cambiar cantidad:** Validando que no baje de 1 ni supere el stock.
5. **Calcular total:** Suma el precio × cantidad de cada artículo.
6. **Persistencia:** Se guarda en localStorage cada vez que cambia.

```typescript
// Añadir al carrito
function add(article: Article) {
    const found = items.value.find((i) => i.article.id === article.id);
    if (found) {
        if ((found.quantity) < (article.stock ?? 999)) {
            found.quantity++;
        }
    } else {
        items.value.push({ article, quantity: 1 });
    }
}

// Calcular total
const total = computed(() =>
    items.value.reduce((acc, i) => acc + i.article.price * i.quantity, 0)
);

// Guardar en localStorage
watch(items, () => {
    localStorage.setItem("cart", JSON.stringify(items.value));
}, { deep: true });
```

### 3.8. Autenticación

La autenticación funciona con **tokens**. Cuando un usuario inicia sesión:

1. El backend comprueba email y contraseña.
2. Si son correctos, genera un token con `$user->createToken('auth_token')`.
3. El frontend guarda el token en localStorage.
4. En cada petición, se envía el token en la cabecera `Authorization: Bearer {token}`.

Para que el token se envíe automáticamente en todas las peticiones, usamos un interceptor de Axios:

```typescript
api.interceptors.request.use((config) => {
    const token = localStorage.getItem("auth_token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});
```

**Medida de seguridad importante:** El campo `is_admin` no se puede rellenar al crear un usuario. Solo los administradores pueden dar permisos de admin mediante endpoints específicos. Así evitamos que alguien se haga admin modificando la petición de registro.

### 3.9. Sistema de reseñas

Las reseñas tienen estas características:

- **Puntuación:** De 1 a 5 estrellas.
- **Comentario:** Opcional, máximo 1000 caracteres.
- **Límite:** Un usuario solo puede reseñar un artículo una vez (se controla tanto en la base de datos como en el código).
- **Cálculo de media:** Cuando se añade una reseña, se recalcula la valoración media del artículo.

Para que el cálculo de la media sea fiable aunque ocurra un error, usamos **transacciones** de base de datos. Si falla la creación de la reseña o el cálculo de la media, se deshace todo:

```php
$review = DB::transaction(function () use ($request, $article, $userId) {
    // 1. Guardamos la reseña
    $newReview = Review::create([
        'article_id' => $article->id,
        'user_id' => $userId,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    // 2. Recalculamos la media
    $stats = Review::where('article_id', $article->id)
        ->selectRaw('COUNT(*) as total_count, AVG(rating) as avg_rating')
        ->first();

    $article->update([
        'product_count' => $stats->total_count,
        'product_avg' => round($stats->avg_rating, 2)
    ]);

    return $newReview;
});
```

Para borrar una reseña, el backend comprueba que el usuario que la pide es el mismo que la creó:

```php
if ($request->user()->id !== $review->user_id) {
    return response()->json(['message' => 'No tienes permisos'], 403);
}
```

### 3.10. Fragmentos de código

#### Mapeo de datos del backend al frontend

Cuando recibimos artículos del backend, convertimos snake_case a camelCase:

```typescript
articles.value = rawData.map((art: RawArticle): Article => ({
    ...art,
    onSale: Boolean(art.on_sale),
    oldPrice: art.old_price,
    categoryId: art.category_id,
    img: construirUrl(art.images),
}));
```

#### Accessor de imagen en Categoría (Laravel)

Convierte automáticamente la ruta guardada en URL completa:

```php
protected function image(): Attribute
{
    return Attribute::make(
        get: fn($value) => $value ? url('storage/' . $value) : null,
    );
}
```

#### Filtrado de artículos en el frontend

Se hace con una propiedad computada que filtra según los criterios del store:

```typescript
const filteredArticles = computed(() => {
    return articles.value.filter((article) => {
        const matchesCategory = store.categoryId === 0 || article.categoryId === store.categoryId;
        const matchesName = !store.articleName ||
            article.name.toLowerCase().includes(store.articleName.toLowerCase());
        const matchesPrice = article.price >= store.minPrice && article.price <= store.maxPrice;
        const matchesSale = !store.onSale || article.onSale;
        return matchesCategory && matchesName && matchesPrice && matchesSale;
    });
});
```

#### Compra de un artículo (backend)

Decrementa el stock y aumenta el contador de ventas:

```php
public function buy(Request $request, $id)
{
    $article = Article::findOrFail($id);

    if ($article->stock < $request->quantity) {
        return response()->json(['message' => 'Stock insuficiente'], 400);
    }

    $article->decrement('stock', $request->quantity);
    $article->increment('sell_count', $request->quantity);

    return response()->json(['message' => 'Compra realizada con éxito']);
}
```

#### Componente dinámico en el panel de admin

Usamos `<component :is="...">` para cambiar entre las tablas de artículos, categorías y usuarios sin recargar la página:

```vue
<SelectButton v-model="selectedOption" :options="options" optionLabel="name" />

<component :is="selectedOption.component" :data="getData()" @deleted="handleDelete" />
```

#### Persistencia del carrito

Carga y guarda el carrito desde localStorage:

```typescript
function load(): CartItem[] {
    const data = localStorage.getItem("cart");
    return data ? JSON.parse(data) : [];
}

function persist() {
    localStorage.setItem("cart", JSON.stringify(items.value));
}

watch(items, persist, { deep: true });
```

---

*Documentación del proyecto Tienda de Segunda Mano*
