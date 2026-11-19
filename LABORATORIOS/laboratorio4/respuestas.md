# SIS-256 — Laboratorio 4 · Preguntas de análisis

**Integrantes:** Rodrigo Yucra y [Integrante 2]

---

## 1. ¿Qué diferencia hay entre `textContent` e `innerHTML`? ¿Por qué en el Ejercicio 2 los datos del servidor pasan por `escapar()` y qué ataque se evita?

- `textContent` inserta el texto **tal cual**: si el texto contiene `<b>hola</b>`, en pantalla se ven los signos `<` `>`.
- `innerHTML` **interpreta** el texto como HTML: crea etiquetas reales (`<b>`, `<img>`, `<script>`...).

En el Ejercicio 2 las tarjetas se arman con `map()` + template literals + `innerHTML`, y los textos (nombre, descripción, etc.) vienen de la base de datos, donde alguien pudo guardar cualquier cosa (por ejemplo, con el formulario de alta). Si no se protege, un nombre como `<img src=x onerror="alert(1)">` se ejecutaría en el navegador de **todos** los que abran el menú.

Ese ataque se llama **XSS (Cross-Site Scripting)**. `escapar()` reemplaza `&`, `<`, `>`, `"` y `'` por entidades (`&lt;`, `&gt;`, ...), de modo que el navegador muestra el texto y no lo ejecuta (prueba 9: se ve `<b>Prueba</b>` y no una palabra en negrita).

## 2. Explique la delegación de eventos. ¿Qué ocurriría con los botones Agregar si el listener se registrara en cada botón y luego el usuario filtrara el catálogo?

La **delegación** consiste en registrar **un solo listener en un contenedor fijo** (`#catalogo`) que siempre existe, y dentro del manejador preguntar quién originó el clic con `e.target.matches(".btn-agregar")`. Aprovecha que los eventos "suben" (bubbling) desde el botón hasta sus padres.

Si el listener se registrara en cada botón, al filtrar, `mostrarProductos()` vacía el catálogo y **crea tarjetas nuevas**: los botones antiguos se destruyen junto con sus listeners y los nuevos botones **nacen sin listener**. El resultado: los botones Agregar dejarían de responder después de filtrar. Con la delegación siguen funcionando (prueba 1).

## 3. `fetch()` no rechaza la promesa con un 404 o un 500. ¿Cómo lo detecta su código? ¿En qué casos sí se ejecuta el `catch`?

Para `fetch()`, un 404 o un 500 **es una respuesta válida**: el servidor contestó. Por eso el código revisa la propiedad `response.ok` (que es `true` solo para 200-299) y, si es falso, lanza el error a mano:

```js
if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
```

El `catch` se ejecuta cuando:
- **Falla la red**: servidor apagado, sin internet, DNS, bloqueo CORS, página abierta con `file://` ("Failed to fetch").
- **Nosotros lanzamos el error** con `throw new Error(...)` al detectar `!resp.ok`.
- **`resp.json()` falla** porque la respuesta no era JSON válido (por ejemplo, PHP imprimió un warning en HTML).
- Cualquier otra excepción dentro del `try` (por ejemplo, un `TypeError`).

## 4. Función `cargarVista(url)` con `XMLHttpRequest` y comparación con `fetch` + `async/await`

**Versión XMLHttpRequest (clase del 21 de octubre):**

```js
function cargarVista(url) {
    contenido.innerHTML = '<p class="cargando">Cargando...</p>';

    const xhr = new XMLHttpRequest();
    xhr.open("GET", url);

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            contenido.innerHTML = xhr.responseText;
        } else {
            contenido.innerHTML = `<div class="aviso aviso-error">HTTP ${xhr.status}</div>`;
        }
    };

    xhr.onerror = function () {
        contenido.innerHTML = '<div class="aviso aviso-error">Error de red</div>';
    };

    xhr.send();
}
```

**Versión fetch + async/await (la del laboratorio):**

```js
async function cargarVista(url) {
    contenido.innerHTML = '<p class="cargando">Cargando...</p>';
    try {
        const resp = await fetch(url);
        if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
        contenido.innerHTML = await resp.text();
    } catch (error) {
        contenido.innerHTML = `<div class="aviso aviso-error">${escapar(error.message)}</div>`;
    }
}
```

**Comparación:**

| | XMLHttpRequest | fetch + async/await |
|---|---|---|
| Estilo | Callbacks (`onload`, `onerror`) | Promesas; el código se lee de arriba hacia abajo |
| Manejo de errores | Dos lugares: `onerror` (red) y revisar `status` en `onload` | Un solo `try/catch` |
| Lectura de datos | `responseText` / `JSON.parse` manual | `resp.text()`, `resp.json()`, `resp.blob()` |
| Configuración | `open`, `setRequestHeader`, `send` | Un objeto de opciones `{ method, headers, body }` |
| Código | Más largo y anidado | Más corto y limpio |
| Estado | API antigua (legado) | API moderna recomendada |

## 5. ¿Por qué no se debe fijar `Content-Type` al enviar un `FormData` con un archivo? ¿Qué pasa en `$_POST` y `$_FILES` si se pone `"application/json"`?

Un `FormData` con archivo viaja como **`multipart/form-data`**, y ese tipo necesita un parámetro `boundary` (la cadena que separa cada campo). **El navegador genera el `boundary` y escribe la cabecera completa automáticamente**, pero solo si nosotros no la fijamos.

Si ponemos `Content-Type: application/json` a mano, el cuerpo sigue siendo multipart pero la cabecera dice que es JSON y no trae `boundary`. PHP no sabe interpretar el cuerpo: **`$_POST` y `$_FILES` llegan vacíos** (`[]`), y `insertar.php` respondería 422 con todos los errores de validación, aunque el usuario haya llenado el formulario.

## 6. Si JavaScript ya valida el formulario, ¿por qué `insertar.php` vuelve a validar? Relacione la respuesta con la prueba 7.

Porque la validación del cliente es solo una **comodidad para el usuario**, no una medida de seguridad: cualquiera puede saltársela (herramientas del navegador, consola, Postman, `curl`) y enviar datos directamente al servidor. El servidor es el único lugar de confianza.

En la prueba 7 se ejecuta en la consola `fetch("insertar.php", { method: "POST", body: new FormData() })`: se envía una petición **sin pasar por el formulario ni por la validación de `app.js`**. Aun así, `insertar.php` responde **422 con la lista de errores**, y no guarda nada. Eso demuestra que el servidor valida aunque se salte JavaScript.

## 7. Compare `localStorage`, `sessionStorage` y la cookie del Laboratorio 2

| | `localStorage` | `sessionStorage` | Cookie (`setcookie`) |
|---|---|---|---|
| Duración | Permanente: no expira, sobrevive al cerrar el navegador | Solo mientras dura la pestaña (se borra al cerrarla) | La define quien la crea (`time() + segundos`); sin expiración, dura la sesión del navegador |
| Quién la lee | Solo el **cliente** (JavaScript) | Solo el **cliente** (JavaScript) | **Cliente y servidor**: el navegador la envía en cada petición HTTP |
| Capacidad aprox. | ~5 MB | ~5 MB | ~4 KB |
| Se envía al servidor | No | No | Sí, en cada petición |
| Conviene para | Datos de la interfaz que deben sobrevivir: el pedido de la cafetería (`pedidoCafeteria`), preferencias | Datos temporales de una sola pestaña: un borrador, un paso de un asistente | Datos que el servidor necesita leer: identificar a un usuario, "recordarme", sesiones |

## 8. ¿Qué es el debounce? ¿Cuántas peticiones se enviarían al escribir "pollo" sin él, y por qué importa en una aplicación con muchos usuarios?

El **debounce** es una técnica que retrasa la ejecución de una función hasta que el usuario **deja de hacer algo** durante un tiempo. En el laboratorio: cada tecla hace `clearTimeout(temporizador)` y programa de nuevo `setTimeout(cargarProductos, 300)`; solo cuando pasan 300 ms sin teclear se envía la petición.

Sin debounce, el evento `input` se dispara por cada tecla: al escribir **"pollo" (5 letras) se enviarían 5 peticiones** (`p`, `po`, `pol`, `poll`, `pollo`). Con debounce, escribiendo de corrido se envía **una sola** (prueba 5: Network registra una única petición a `productos.php`).

Importa porque cada petición consume servidor, base de datos y ancho de banda. Con muchos usuarios buscando a la vez, 5 peticiones por búsqueda multiplican la carga innecesariamente (por ejemplo, 100 usuarios generarían 500 consultas en vez de 100), y además las respuestas pueden llegar desordenadas y mostrar resultados que ya no corresponden a lo escrito.
