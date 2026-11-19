// =====================================================
// PREGUNTA 4 - Catalogo con fetch() + Modal + Recarga automatica
// =====================================================

const contenido = document.querySelector("#contenido");
let recetas = []; // recetas que devuelve catalogo.php

// Convierte texto a HTML seguro (evita XSS)
function escapar(texto) {
    const div = document.createElement("div");
    div.textContent = texto;
    return div.innerHTML.replace(/"/g, "&quot;").replace(/'/g, "&#39;");
}

// Llama a catalogo.php con fetch y muestra las tarjetas dentro de #contenido
async function pregunta4() {
    contenido.innerHTML = "<p>Cargando...</p>";

    try {
        const resp = await fetch("catalogo.php");
        if (!resp.ok) {
            throw new Error(`Error HTTP: ${resp.status}`);
        }
        recetas = await resp.json();

        const tarjetas = recetas.map(r => `
            <div class="tarjeta" data-id="${r.id}">
                <img src="${escapar(r.imagen)}" alt="${escapar(r.titulo)}">
                <p class="titulo">${escapar(r.titulo)}</p>
                <p class="tipo">${escapar(r.tipo)}</p>
            </div>`).join("");

        contenido.innerHTML = `
            <div class="panel">
                <h2>fetch &rarr; catalogo.php</h2>
                ${recetas.length === 0
                    ? "<p>No hay recetas en el catalogo.</p>"
                    : `<div class="catalogo">${tarjetas}</div>`}
            </div>

            <!-- Modal: oculto hasta hacer clic en una tarjeta -->
            <div id="modalReceta" class="modal">
                <div class="modal-caja">
                    <div class="modal-encabezado">Detalle Receta</div>
                    <div class="modal-cuerpo" id="modalCuerpo"></div>
                </div>
            </div>`;
    } catch (error) {
        contenido.innerHTML =
            `<p class="error">Error al cargar el catalogo: ${escapar(error.message)}</p>`;
    }
}

// Abre el modal con el detalle de la receta
function abrirModal(id) {
    const r = recetas.find(x => Number(x.id) === Number(id));
    if (!r) return;

    document.querySelector("#modalCuerpo").innerHTML = `
        <img src="${escapar(r.imagen)}" alt="${escapar(r.titulo)}">
        <h3>${escapar(r.titulo)}</h3>
        <p class="tipo">Tipo: ${escapar(r.tipo)}</p>
        <p class="prep">${escapar(r.preparacion)}</p>
        <div class="modal-botones">
            <button class="btn-eliminar" data-accion="eliminar" data-id="${r.id}">Eliminar</button>
            <button class="btn-cerrar" data-accion="cerrar">Cerrar</button>
        </div>`;
    document.querySelector("#modalReceta").classList.add("abierto");
}

function cerrarModal() {
    const modal = document.querySelector("#modalReceta");
    if (modal) modal.classList.remove("abierto");
}

// fetch DELETE a eliminar.php?id=X -> cierra el modal y recarga el catalogo
async function eliminarReceta(id) {
    if (!confirm("Seguro que desea eliminar esta receta?")) return;

    try {
        const resp = await fetch(`eliminar.php?id=${id}`, { method: "DELETE" });
        if (!resp.ok) {
            throw new Error(`Error HTTP: ${resp.status}`);
        }
        const resultado = await resp.json();
        if (!resultado.exito) {
            throw new Error(resultado.error || "No se pudo eliminar");
        }
        cerrarModal();
        pregunta4(); // recarga automatica del catalogo con otro fetch
    } catch (error) {
        alert("Error: " + error.message);
    }
}

// Boton "Pregunta 4"
document.querySelector("#btnPregunta4").addEventListener("click", pregunta4);

// Event delegation dentro de #contenido: tarjetas y botones del modal
contenido.addEventListener("click", (e) => {
    const tarjeta = e.target.closest(".tarjeta[data-id]");
    if (tarjeta) {
        abrirModal(tarjeta.dataset.id);
        return;
    }

    if (e.target.id === "modalReceta") { // clic en el fondo oscuro
        cerrarModal();
        return;
    }

    const boton = e.target.closest("button[data-accion]");
    if (!boton) return;
    if (boton.dataset.accion === "cerrar") cerrarModal();
    if (boton.dataset.accion === "eliminar") eliminarReceta(boton.dataset.id);
});

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") cerrarModal();
});
