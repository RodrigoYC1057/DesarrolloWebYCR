// =====================================================
// TAREA 4 - Pagina web personal con AJAX (Fetch API)
// SIS 256 Tecnologia y Desarrollo Web - USFX
// =====================================================

const contenido = document.querySelector("#contenido");
const menu = document.querySelector("nav");

// -----------------------------------------------------
// Utilidades
// -----------------------------------------------------

// Evita XSS: convierte el texto en HTML seguro (como textContent)
function escapar(texto) {
    const div = document.createElement("div");
    div.textContent = texto;
    return div.innerHTML;
}

// Envia JSON al servidor (POST, PUT) y devuelve la respuesta ya parseada
async function enviarJSON(url, metodo, cuerpo) {
    const resp = await fetch(url, {
        method: metodo,
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(cuerpo)
    });
    const resultado = await resp.json();
    if (!resp.ok || !resultado.exito) {
        throw new Error(resultado.error || `Error HTTP: ${resp.status}`);
    }
    return resultado;
}

// -----------------------------------------------------
// 1. NAVEGACION CON AJAX: carga cada pagina sin recargar
// -----------------------------------------------------

async function cargarPagina(nombre) {
    contenido.innerHTML = '<p class="cargando">Cargando...</p>';

    // Marcar la opcion activa del menu
    menu.querySelectorAll("li").forEach(li => li.classList.remove("activo"));
    const enlace = menu.querySelector(`a[data-pagina="${nombre}"]`);
    if (enlace) enlace.parentElement.classList.add("activo");

    try {
        const resp = await fetch(`paginas/${nombre}.html`);
        if (!resp.ok) {
            throw new Error(`Error HTTP: ${resp.status}`);
        }
        contenido.innerHTML = await resp.text();
        cargarListas(); // carga los datos de la base de datos (si la pagina los usa)
    } catch (error) {
        contenido.innerHTML =
            `<p class="error">No se pudo cargar la pagina: ${escapar(error.message)}</p>`;
    }
}

// Event delegation sobre el menu
menu.addEventListener("click", (e) => {
    const enlace = e.target.closest("a[data-pagina]");
    if (!enlace) return;
    e.preventDefault();
    cargarPagina(enlace.dataset.pagina);
});

// -----------------------------------------------------
// 2. LISTAR (READ): lee datos de la API y los dibuja
// -----------------------------------------------------

function dibujarMensajes(mensajes) {
    if (mensajes.length === 0) {
        return '<p class="colorLetras">Aun no hay mensajes.</p>';
    }
    return `<ul class="resaltado3">` + mensajes.map(m => `
        <li>
            ${escapar(m.mensaje)} <small>(${escapar(m.fecha)})</small>
            <button data-accion="eliminar" data-tipo="mensajes" data-id="${m.id}">X</button>
        </li>`).join("") + `</ul>`;
}

function dibujarContactos(contactos) {
    if (contactos.length === 0) {
        return '<p class="colorLetras">Aun no hay correos registrados.</p>';
    }
    return `
        <table class="tablacentrada" border="1">
            <tr><th>CORREO</th><th>EDAD</th><th>ACCIONES</th></tr>
            ${contactos.map(c => `
            <tr>
                <td>${escapar(c.correo)}</td>
                <td>${c.edad}</td>
                <td>
                    <button data-accion="editar" data-tipo="contactos" data-id="${c.id}">Editar</button>
                    <button data-accion="eliminar" data-tipo="contactos" data-id="${c.id}">Eliminar</button>
                </td>
            </tr>`).join("")}
        </table>`;
}

function dibujarPreferencias(resultados) {
    if (resultados.length === 0) {
        return '<p class="colorLetras">Se el primero en votar.</p>';
    }
    return `<ul class="resaltado4">` + resultados.map(r =>
        `<li>${escapar(r.valor)}: ${r.votos} voto(s)</li>`).join("") + `</ul>`;
}

const dibujantes = {
    mensajes: dibujarMensajes,
    contactos: dibujarContactos,
    preferencias: dibujarPreferencias
};

async function cargarLista(lista) {
    const tipo = lista.dataset.lista;
    const params = new URLSearchParams();
    if (lista.dataset.origen) params.set("origen", lista.dataset.origen);
    if (lista.dataset.categoria) params.set("categoria", lista.dataset.categoria);

    try {
        const resp = await fetch(`api/${tipo}.php?${params}`);
        if (!resp.ok) {
            throw new Error(`Error HTTP: ${resp.status}`);
        }
        const datos = await resp.json();
        lista.innerHTML = dibujantes[tipo](datos);
    } catch (error) {
        lista.innerHTML =
            `<p class="error">Error al cargar los datos: ${escapar(error.message)}</p>`;
    }
}

// Recarga todas las listas [data-lista] que haya en la pagina actual
function cargarListas() {
    contenido.querySelectorAll("[data-lista]").forEach(cargarLista);
}

// -----------------------------------------------------
// 3. FORMULARIOS (CREATE): envian JSON con fetch POST
// -----------------------------------------------------

contenido.addEventListener("submit", async (e) => {
    const form = e.target.closest("form[data-api]");
    if (!form) return;
    e.preventDefault(); // evita que la pagina se recargue

    const estado = form.querySelector(".estado");
    const boton = form.querySelector('input[type="submit"]');
    const datos = Object.fromEntries(new FormData(form));

    boton.disabled = true;
    estado.className = "estado";
    estado.textContent = "Enviando...";

    try {
        const resultado = await enviarJSON(form.dataset.api, "POST", datos);
        estado.textContent = resultado.mensaje || "Guardado correctamente";
        // Limpiar solo los campos de texto (no los hidden ni los select)
        form.querySelectorAll("textarea, input[type='email'], input[type='number']")
            .forEach(campo => campo.value = "");
        cargarListas();
    } catch (error) {
        estado.className = "estado error";
        estado.textContent = error.message;
    } finally {
        boton.disabled = false;
    }
});

// -----------------------------------------------------
// 4. EDITAR (UPDATE) y ELIMINAR (DELETE)
// -----------------------------------------------------

contenido.addEventListener("click", async (e) => {
    const boton = e.target.closest("button[data-accion]");
    if (!boton) return;

    const { accion, tipo, id } = boton.dataset;

    try {
        if (accion === "eliminar") {
            if (!confirm("Seguro que desea eliminar?")) return;
            const resp = await fetch(`api/${tipo}.php?id=${id}`, { method: "DELETE" });
            if (!resp.ok) {
                throw new Error(`Error HTTP: ${resp.status}`);
            }
        }

        if (accion === "editar") {
            // Se leen los valores actuales desde la fila de la tabla
            const celdas = boton.closest("tr").children;
            const correo = prompt("Nuevo correo:", celdas[0].textContent);
            if (correo === null) return; // el usuario cancelo
            const edad = prompt("Nueva edad:", celdas[1].textContent);
            if (edad === null) return;
            await enviarJSON(`api/${tipo}.php`, "PUT", {
                id: Number(id),
                correo: correo.trim(),
                edad: Number(edad)
            });
        }

        cargarListas();
    } catch (error) {
        alert("Error: " + error.message);
    }
});

// -----------------------------------------------------
// Inicio: cargar la pagina de bienvenida
// -----------------------------------------------------
document.addEventListener("DOMContentLoaded", () => cargarPagina("inicio"));
