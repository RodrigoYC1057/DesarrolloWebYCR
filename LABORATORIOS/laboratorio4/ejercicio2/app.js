// SIS-256 - Laboratorio 4 - Ejercicio 2: Panel de la cafeteria con AJAX
// Fetch API + async/await.


const contenido = document.querySelector("#contenido");
const avisoBox = document.querySelector("#aviso");
const modal = document.querySelector("#modal");
const modalTitulo = document.querySelector("#modalTitulo");
const modalCuerpo = document.querySelector("#modalCuerpo");
const modalPie = document.querySelector("#modalPie");

let temporizador;

// Utilidades

function escapar(texto) {
    return String(texto)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;");
}

function mostrarAviso(mensaje, tipo = "ok") {
    const clase = tipo === "error" ? "aviso aviso-error" : "aviso";
    avisoBox.innerHTML = `<div class="${clase}">${escapar(mensaje)}</div>`;
}

function limpiarAviso() {
    avisoBox.innerHTML = "";
}

async function errorDeRespuesta(resp) {
    let mensaje = `HTTP ${resp.status}`;
    try {
        const datos = await resp.json();
        if (datos.mensaje) mensaje = datos.mensaje;
    } catch (e) {
    }
    return new Error(mensaje);
}

// 1. NAVEGACION DE UNA SOLA PAGINA con response.text()

async function cargarVista(url) {
    contenido.innerHTML = '<p class="cargando">Cargando...</p>';
    try {
        const resp = await fetch(url);
        if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
        contenido.innerHTML = await resp.text();
        return true;
    } catch (error) {
        contenido.innerHTML = `<div class="aviso aviso-error">${escapar(error.message)}</div>`;
        return false;
    }
}

const botonesMenu = document.querySelectorAll("[data-vista]");

botonesMenu.forEach((boton) => {
    boton.addEventListener("click", () => {
        botonesMenu.forEach((b) => b.classList.remove("activo"));
        boton.classList.add("activo");
        limpiarAviso();

        const vista = boton.dataset.vista;
        if (vista === "inicio") cargarVista("inicio.html");
        if (vista === "menu") mostrarMenu();
        if (vista === "nuevo") mostrarNuevo();
    });
});

// 2. VISTA MENU: datos en JSON + busqueda en vivo

async function llenarCategorias(select) {
    try {
        const resp = await fetch("categorias.php");
        if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
        const categorias = await resp.json();

        categorias.forEach((c) => {
            const opcion = document.createElement("option");
            opcion.value = c.id;
            opcion.textContent = c.nombre;
            select.appendChild(opcion);
        });
    } catch (error) {
        mostrarAviso(`No se pudieron cargar las categorías: ${error.message}`, "error");
    }
}

function tarjetaProducto(p) {
    const imagen = p.imagen
        ? `<img src="../images/${escapar(p.imagen)}" alt="${escapar(p.nombre)}">`
        : '<div class="sin-imagen">SIN FOTO</div>';
    const agotado = !p.disponible;

    return `
        <article class="producto${agotado ? " agotado" : ""}" data-id="${p.id}">
            ${imagen}
            <div class="cuerpo">
                <span class="etiqueta et-${escapar(p.categoria.toLowerCase())}">${escapar(p.categoria)}</span>
                <h4>${escapar(p.nombre)}</h4>
                <div class="precio">Bs ${p.precio.toFixed(2)}</div>
                ${agotado ? '<span class="etiqueta et-agotado">Agotado</span>' : ""}
                <div class="acciones">
                    <button type="button" class="boton mini" data-accion="detalle" data-id="${p.id}">Detalle</button>
                    <button type="button" class="boton boton-no mini" data-accion="eliminar" data-id="${p.id}">Eliminar</button>
                </div>
            </div>
        </article>`;
}

async function cargarProductos() {
    const txtBuscar = document.querySelector("#buscar");
    const selCategoria = document.querySelector("#categoria");
    const grilla = document.querySelector("#grilla");
    const contador = document.querySelector("#contadorProductos");
    if (!grilla) return;

    const params = new URLSearchParams({
        buscar: txtBuscar.value.trim(),
        categoria: selCategoria.value
    });

    grilla.innerHTML = '<p class="cargando">Cargando productos...</p>'; 
    try {
        const resp = await fetch(`productos.php?${params}`);
        if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
        const productos = await resp.json();

        if (!grilla.isConnected) return; 
        contador.textContent = productos.length;
        grilla.innerHTML = productos.length === 0
            ? '<p class="vacio">No se encontraron productos.</p>'
            : productos.map(tarjetaProducto).join("");
    } catch (error) {
        if (!grilla.isConnected) return;
        contador.textContent = "0";
        grilla.innerHTML = `<div class="aviso aviso-error">${escapar(error.message)}</div>`;
    }
}

function mostrarMenu() {
    contenido.innerHTML = `
        <h2>Menú <span class="contador" id="contadorProductos">0</span></h2>
        <div class="barra">
            <div class="campo">
                <label for="buscar">Buscar (mientras escribe)</label>
                <input type="search" id="buscar" placeholder="Ej.: pollo">
            </div>
            <div class="campo">
                <label for="categoria">Categoría</label>
                <select id="categoria">
                    <option value="0">Todas</option>
                </select>
            </div>
        </div>
        <div class="grilla" id="grilla"></div>`;

    const txtBuscar = document.querySelector("#buscar");
    const selCategoria = document.querySelector("#categoria");

    txtBuscar.addEventListener("input", () => {
        clearTimeout(temporizador);
        temporizador = setTimeout(cargarProductos, 300);
    });
    selCategoria.addEventListener("change", cargarProductos);

    llenarCategorias(selCategoria);
    cargarProductos();
}

// 3. VENTANA MODAL 

function abrirModal(titulo, cuerpoHtml, pieHtml) {
    modalTitulo.textContent = titulo;
    modalCuerpo.innerHTML = cuerpoHtml;
    modalPie.innerHTML = pieHtml;
    modal.classList.add("abierto");
}

function cerrarModal() {
    modal.classList.remove("abierto");
}

modal.addEventListener("click", (e) => {
    if (e.target === modal || e.target.matches('[data-modal="cerrar"]')) {
        cerrarModal();
    } else if (e.target.matches('[data-modal="eliminar"]')) {
        eliminarProducto(e.target.dataset.id);
    }
});

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") cerrarModal();
});

async function verDetalle(id) {
    try {
        const resp = await fetch("producto.php?id=" + id);
        if (!resp.ok) throw await errorDeRespuesta(resp);
        const p = await resp.json();

        const imagen = p.imagen
            ? `<img src="../images/${escapar(p.imagen)}" alt="${escapar(p.nombre)}">`
            : '<div class="sin-imagen">SIN FOTO</div>';

        abrirModal(
            p.nombre,
            `${imagen}
             <p><span class="etiqueta et-${escapar(p.categoria.toLowerCase())}">${escapar(p.categoria)}</span></p>
             <p>${escapar(p.descripcion)}</p>
             <p>Precio: Bs ${p.precio.toFixed(2)}</p>`,
            '<button type="button" class="boton boton-sec mini" data-modal="cerrar">Cerrar</button>'
        );
    } catch (error) {
        mostrarAviso(error.message, "error");
    }
}

function pedirConfirmacion(id, nombre) {
    abrirModal(
        "Confirmar eliminación",
        `<p>¿Está seguro de eliminar <strong>${escapar(nombre)}</strong>? Esta acción no se puede deshacer.</p>`,
        `<button type="button" class="boton boton-sec mini" data-modal="cerrar">Cancelar</button>
         <button type="button" class="boton boton-no mini" data-modal="eliminar" data-id="${id}">Eliminar</button>`
    );
}

async function eliminarProducto(id) {
    const datos = new FormData();
    datos.append("id", id);

    try {
        const resp = await fetch("eliminar.php", { method: "POST", body: datos });
        if (!resp.ok) throw await errorDeRespuesta(resp);
        const resultado = await resp.json();

        cerrarModal();
        const tarjeta = document.querySelector(`.producto[data-id="${id}"]`);
        if (tarjeta) tarjeta.remove();

        const contador = document.querySelector("#contadorProductos");
        if (contador) contador.textContent = Math.max(0, Number(contador.textContent) - 1);

        mostrarAviso(resultado.mensaje);
    } catch (error) {
        cerrarModal();
        mostrarAviso(error.message, "error");
    }
}

// 4. DELEGACION DE EVENTOS sobre #contenido (click y submit, una sola vez)

contenido.addEventListener("click", (e) => {
    if (!e.target.matches("button[data-accion]")) return;

    const id = e.target.dataset.id;
    if (e.target.dataset.accion === "detalle") {
        verDetalle(id);
    }
    if (e.target.dataset.accion === "eliminar") {
        const nombre = e.target.closest(".producto").querySelector("h4").textContent;
        pedirConfirmacion(id, nombre);
    }
});

// 5. ALTA DE PRODUCTO con FormData 

async function mostrarNuevo() {
    const cargada = await cargarVista("form_producto.html");
    if (cargada) {
        llenarCategorias(document.querySelector("#categoria_id"));
    }
}

function mostrarErroresForm(lista) {
    const caja = document.querySelector("#erroresForm");
    caja.innerHTML = `
        <div class="aviso aviso-error">Corrija lo siguiente:
            <ul class="lista-errores">
                ${lista.map((m) => `<li>${escapar(m)}</li>`).join("")}
            </ul>
        </div>`;
}

function validarProducto(form) {
    const errores = [];
    const campos = form.elements;

    const nombreOk = campos.nombre.value.trim().length >= 3;
    const categoriaOk = campos.categoria_id.value !== "";
    const precioOk = parseFloat(campos.precio.value) > 0;

    [[campos.nombre, nombreOk], [campos.categoria_id, categoriaOk], [campos.precio, precioOk]]
        .forEach(([campo, correcto]) => {
            campo.classList.toggle("invalido", !correcto);
            campo.classList.toggle("valido", correcto);
        });

    if (!nombreOk) errores.push("El nombre debe tener al menos 3 caracteres.");
    if (!categoriaOk) errores.push("Seleccione una categoría.");
    if (!precioOk) errores.push("El precio debe ser mayor a cero.");
    return errores;
}

contenido.addEventListener("submit", async (e) => {
    if (!e.target.matches("#formProducto")) return;
    e.preventDefault();

    const form = e.target;
    const errores = validarProducto(form);
    if (errores.length > 0) {
        mostrarErroresForm(errores);
        return;
    }

    const boton = form.querySelector('button[type="submit"]');
    boton.disabled = true;
    boton.textContent = "Guardando...";

    try {
        
        const resp = await fetch("insertar.php", { method: "POST", body: new FormData(form) });
        const resultado = await resp.json();

        if (resp.status === 201) {
            document.querySelector("#erroresForm").innerHTML = "";
            form.reset();
            form.querySelectorAll(".valido, .invalido")
                .forEach((campo) => campo.classList.remove("valido", "invalido"));
            mostrarAviso(resultado.mensaje);
        } else if (resp.status === 422) {
            mostrarErroresForm(resultado.errores); 
        } else {
            throw new Error(resultado.mensaje || `HTTP ${resp.status}`);
        }
    } catch (error) {
        mostrarErroresForm([error.message]);
    } finally {
        boton.disabled = false;
        boton.textContent = "Guardar producto";
    }
});

// Inicio: se abre la vista Inicio

document.addEventListener("DOMContentLoaded", () => {
    document.querySelector('[data-vista="inicio"]').click();
});
