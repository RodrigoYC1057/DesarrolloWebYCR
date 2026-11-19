
// 1. DATOS: arreglo de 9 objetos 

const productos = [
    { id: 1, nombre: "Empanadas de queso",  categoria: "Salados", precio: 6,  imagen: "empanadas.jpg" },
    { id: 2, nombre: "Papas fritas",        categoria: "Salados", precio: 8,  imagen: "papas.jpg" },
    { id: 3, nombre: "Hamburguesa clásica", categoria: "Salados", precio: 18, imagen: "hamburguesa.jpg" },
    { id: 4, nombre: "Sopa de pollo",       categoria: "Platos",  precio: 12, imagen: "sopa.jpg" },
    { id: 5, nombre: "Pollo al horno",      categoria: "Platos",  precio: 22, imagen: "pollo.jpg" },
    { id: 6, nombre: "Spaghetti carbonara", categoria: "Platos",  precio: 20, imagen: "espagueti.jpg" },
    { id: 7, nombre: "Flan casero",         categoria: "Postres", precio: 7,  imagen: "flan.jpg" },
    { id: 8, nombre: "Tiramisú",            categoria: "Postres", precio: 12, imagen: "tiramisu.jpg" },
    { id: 9, nombre: "Helado de vainilla",  categoria: "Postres", precio: 8,  imagen: "vainilla.jpg" }
];

const CLAVE_PEDIDO = "pedidoCafeteria";
let pedido = []; // { id, nombre, precio, cantidad }

// Elementos del DOM 

const catalogo     = document.querySelector("#catalogo");
const resumen      = document.querySelector("#resumen");
const inputBuscar  = document.querySelector("#buscar");
const selCategoria = document.querySelector("#categoria");
const detalle      = document.querySelector("#detalle");
const totalCelda   = document.querySelector("#total");
const contador     = document.querySelector("#contador");
const btnVaciar    = document.querySelector("#vaciar");
const formulario   = document.querySelector("#formEntrega");
const selEntrega   = document.querySelector("#entrega");
const campoAula    = document.querySelector("#campoAula");
const inputAula    = document.querySelector("#aula");
const erroresBox   = document.querySelector("#errores");
const confirmacion = document.querySelector("#confirmacion");

// 2. CATALOGO: tarjetas con createElement 

function mostrarProductos(lista) {
    catalogo.textContent = ""; 

    lista.forEach((p) => {
        const tarjeta = document.createElement("article");
        tarjeta.classList.add("producto");

        const img = document.createElement("img");
        img.src = `../images/${p.imagen}`;
        img.alt = p.nombre;

        const cuerpo = document.createElement("div");
        cuerpo.classList.add("cuerpo");

        const etiqueta = document.createElement("span");
        etiqueta.classList.add("etiqueta", `et-${p.categoria.toLowerCase()}`);
        etiqueta.textContent = p.categoria;

        const titulo = document.createElement("h4");
        titulo.textContent = p.nombre;

        const precio = document.createElement("div");
        precio.classList.add("precio");
        precio.textContent = `Bs ${p.precio.toFixed(2)}`;

        const acciones = document.createElement("div");
        acciones.classList.add("acciones");

        const boton = document.createElement("button");
        boton.type = "button";
        boton.classList.add("boton", "mini", "btn-agregar");
        boton.textContent = "Agregar";
        boton.dataset.id = p.id; 

        acciones.appendChild(boton);
        cuerpo.appendChild(etiqueta);
        cuerpo.appendChild(titulo);
        cuerpo.appendChild(precio);
        cuerpo.appendChild(acciones);
        tarjeta.appendChild(img);
        tarjeta.appendChild(cuerpo);
        catalogo.appendChild(tarjeta);
    });

    resumen.textContent = `Mostrando ${lista.length} de ${productos.length} productos`;

    if (lista.length === 0) {
        const aviso = document.createElement("p");
        aviso.classList.add("vacio");
        aviso.textContent = "No hay productos que coincidan con la búsqueda.";
        catalogo.appendChild(aviso);
    }
}

// 3. FILTROS: una sola funcion combina texto y categoria con filter()

function filtrar() {
    const texto = inputBuscar.value.trim().toLowerCase();
    const categoria = selCategoria.value;

    const resultado = productos.filter((p) =>
        p.nombre.toLowerCase().includes(texto) &&
        (categoria === "Todas" || p.categoria === categoria)
    );
    mostrarProductos(resultado);
}

inputBuscar.addEventListener("input", filtrar);     
selCategoria.addEventListener("change", filtrar);   

// 4. PEDIDO

function agregar(id) {
    const producto = productos.find((p) => p.id === id);
    if (!producto) return;

    const enPedido = pedido.find((item) => item.id === id);
    if (enPedido) {
        enPedido.cantidad++;
    } else {
        pedido.push({
            id: producto.id,
            nombre: producto.nombre,
            precio: producto.precio,
            cantidad: 1
        });
    }
    cambiarPedido();
}

function quitar(id) {
    const indice = pedido.findIndex((item) => item.id === id);
    if (indice === -1) return;

    if (pedido[indice].cantidad > 1) {
        pedido[indice].cantidad--;
    } else {
        pedido.splice(indice, 1);
    }
    cambiarPedido();
}

function calcularTotal() {
    return pedido.reduce((acc, item) => acc + item.precio * item.cantidad, 0);
}

function dibujarPedido() {
    if (pedido.length === 0) {
        detalle.innerHTML =
            '<tr><td colspan="4" class="vacio">Aún no agregó productos.</td></tr>';
    } else {
        
        detalle.innerHTML = pedido.map((item) => `
            <tr>
                <td>${item.nombre}</td>
                <td class="num">${item.cantidad}</td>
                <td class="num">Bs ${(item.precio * item.cantidad).toFixed(2)}</td>
                <td><button type="button" class="boton boton-no mini btn-quitar" data-id="${item.id}">−</button></td>
            </tr>`).join("");
    }

    totalCelda.textContent = `Bs ${calcularTotal().toFixed(2)}`;
    contador.textContent = pedido.reduce((acc, item) => acc + item.cantidad, 0);
}

function guardarPedido() {
    localStorage.setItem(CLAVE_PEDIDO, JSON.stringify(pedido));
}

function cambiarPedido() {
    dibujarPedido();
    guardarPedido();
}

catalogo.addEventListener("click", (e) => {
    if (e.target.matches(".btn-agregar")) {
        agregar(Number(e.target.dataset.id));
    }
});

detalle.addEventListener("click", (e) => {
    if (e.target.matches(".btn-quitar")) {
        quitar(Number(e.target.dataset.id));
    }
});

btnVaciar.addEventListener("click", () => {
    pedido = [];
    cambiarPedido();
});

// 5. VALIDACION del formulario de entrega

const reglas = {
    nombre:  { regex: /^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{3,50}$/, mensaje: "Solo letras y espacios (3 a 50)." },
    celular: { regex: /^[67]\d{7}$/,                    mensaje: "8 dígitos, empieza con 6 o 7." },
    aula:    { regex: /^[A-Z]-\d{1,3}$/,                mensaje: "Formato letra-número, ej. A-12." }
};

function validarCampo(campo) {
    const input = document.querySelector(`#${campo}`);
    const mensaje = document.querySelector(`#error-${campo}`);
    const correcto = reglas[campo].regex.test(input.value.trim());

    input.classList.toggle("invalido", !correcto);
    input.classList.toggle("valido", correcto);
    mensaje.textContent = correcto ? "" : reglas[campo].mensaje;
    return correcto;
}

function limpiarCampo(campo) {
    const input = document.querySelector(`#${campo}`);
    input.classList.remove("invalido", "valido");
    document.querySelector(`#error-${campo}`).textContent = "";
}

selEntrega.addEventListener("change", () => {
    const alAula = selEntrega.value === "Llevar al aula";
    campoAula.classList.toggle("oculto", !alAula);
    if (!alAula) {
        inputAula.value = "";
        limpiarCampo("aula");
    }
});

function mostrarErrores(problemas) {
    erroresBox.textContent = "";

    const aviso = document.createElement("div");
    aviso.classList.add("aviso", "aviso-error");

    const titulo = document.createElement("div");
    titulo.textContent = "No se puede confirmar:";

    const lista = document.createElement("ul");
    lista.classList.add("lista-errores");
    problemas.forEach((texto) => {
        const li = document.createElement("li");
        li.textContent = texto;
        lista.appendChild(li);
    });

    aviso.appendChild(titulo);
    aviso.appendChild(lista);
    erroresBox.appendChild(aviso);
}

formulario.addEventListener("submit", (e) => {
    e.preventDefault();

    const problemas = [];
    if (pedido.length === 0) problemas.push("El pedido está vacío.");
    if (!validarCampo("nombre")) problemas.push("Revise el nombre.");
    if (!validarCampo("celular")) problemas.push("Revise el celular.");

    const alAula = selEntrega.value === "Llevar al aula";
    if (alAula && !validarCampo("aula")) problemas.push("Revise el aula.");

    if (problemas.length > 0) {
        mostrarErrores(problemas);
        return; 
    }

    erroresBox.textContent = "";
    const datos = Object.fromEntries(new FormData(formulario));
    const total = calcularTotal();
    const numero = Math.floor(10000 + Math.random() * 90000);
    const destino = alAula ? `Entrega en aula ${datos.aula}` : "Recoger en caja";

    const aviso = document.createElement("div");
    aviso.classList.add("aviso");
    aviso.textContent =
        `Pedido N.º ${numero} confirmado para ${datos.nombre} (${datos.celular}). ` +
        `Total Bs ${total.toFixed(2)}. ${destino}.`;
    confirmacion.appendChild(aviso);

    console.log("Pedido registrado:", JSON.stringify({ numero, ...datos, total, pedido }));

    pedido = [];
    cambiarPedido();
    formulario.reset();
    ["nombre", "celular", "aula"].forEach(limpiarCampo);
    campoAula.classList.add("oculto");
});

// 6. INICIO: recuperar el pedido guardado 

document.addEventListener("DOMContentLoaded", () => {
    try {
        const guardado = JSON.parse(localStorage.getItem(CLAVE_PEDIDO));
        if (Array.isArray(guardado)) pedido = guardado;
    } catch (error) {
        pedido = [];
    }
    mostrarProductos(productos);
    dibujarPedido();
});
