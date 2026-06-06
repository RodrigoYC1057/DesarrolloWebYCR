<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f0f4f8">

<nav class="navbar navbar-dark px-4 py-3" style="background:#0d6efd">
    <span class="navbar-brand fw-bold">🏥 Citas Médicas</span>
    <div>
        <a href="indexprincipal.html" class="btn btn-light btn-sm me-2">Inicio</a>
        <a href="medicos.php"         class="btn btn-light btn-sm me-2">Médicos</a>
        <a href="pacientes.php"       class="btn btn-light btn-sm me-2">Pacientes</a>
        <a href="citas.php"           class="btn btn-light btn-sm">Citas</a>
    </div>
</nav>

<div class="container mt-4">
    <h3>🧑‍🤝‍🧑 Gestión de Pacientes</h3>
    <button class="btn btn-success mb-3" onclick="abrirModalNuevo()">+ Nuevo Paciente</button>
    <div id="tabla-pacientes"></div>
</div>

<!-- MODAL FORMULARIO -->
<div class="modal fade" id="modalPaciente" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModal">Nuevo Paciente</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="pacienteId">
        <div class="mb-3">
            <label>Nombre completo</label>
            <input type="text" id="nombre" class="form-control">
        </div>
        <div class="mb-3">
            <label>Documento (CI)</label>
            <input type="text" id="documento" class="form-control">
        </div>
        <div class="mb-3">
            <label>Teléfono</label>
            <input type="text" id="telefono" class="form-control">
        </div>
        <div class="mb-3">
            <label>Correo</label>
            <input type="email" id="correo" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-success" onclick="guardarPaciente()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL ELIMINAR -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">¿Eliminar?</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>¿Seguro que deseas eliminar este paciente?</p>
        <input type="hidden" id="idEliminar">
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" onclick="confirmarEliminar()">Sí, eliminar</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL MENSAJE -->
<div class="modal fade" id="modalMensaje" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body text-center py-4">
        <p id="textoMensaje" class="fs-5"></p>
        <button class="btn btn-success mt-2" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>

window.onload = function() { cargarPacientes(); };

function cargarPacientes() {
    fetch('ajax/pacientes_ajax.php?accion=listar')
        .then(r => r.text())
        .then(html => { document.getElementById('tabla-pacientes').innerHTML = html; });
}

function abrirModalNuevo() {
    document.getElementById('tituloModal').innerText  = 'Nuevo Paciente';
    document.getElementById('pacienteId').value = '';
    document.getElementById('nombre').value     = '';
    document.getElementById('documento').value  = '';
    document.getElementById('telefono').value   = '';
    document.getElementById('correo').value     = '';
    new bootstrap.Modal(document.getElementById('modalPaciente')).show();
}

function editarPaciente(id, nombre, documento, telefono, correo) {
    document.getElementById('tituloModal').innerText  = 'Editar Paciente';
    document.getElementById('pacienteId').value = id;
    document.getElementById('nombre').value     = nombre;
    document.getElementById('documento').value  = documento;
    document.getElementById('telefono').value   = telefono;
    document.getElementById('correo').value     = correo;
    new bootstrap.Modal(document.getElementById('modalPaciente')).show();
}

function guardarPaciente() {
    const id        = document.getElementById('pacienteId').value;
    const nombre    = document.getElementById('nombre').value.trim();
    const documento = document.getElementById('documento').value.trim();
    const telefono  = document.getElementById('telefono').value.trim();
    const correo    = document.getElementById('correo').value.trim();

    if (!nombre || !documento) { mostrarMensaje('⚠️ Nombre y documento son obligatorios.'); return; }

    const datos = new FormData();
    datos.append('accion', id ? 'editar' : 'insertar');
    datos.append('id', id);
    datos.append('nombre', nombre);
    datos.append('documento', documento);
    datos.append('telefono', telefono);
    datos.append('correo', correo);

    fetch('ajax/pacientes_ajax.php', { method: 'POST', body: datos })
        .then(r => r.text())
        .then(msg => {
            bootstrap.Modal.getInstance(document.getElementById('modalPaciente')).hide();
            mostrarMensaje(msg);
            cargarPacientes();
        });
}

function eliminarPaciente(id) {
    document.getElementById('idEliminar').value = id;
    new bootstrap.Modal(document.getElementById('modalEliminar')).show();
}

function confirmarEliminar() {
    const id = document.getElementById('idEliminar').value;
    const datos = new FormData();
    datos.append('accion', 'eliminar');
    datos.append('id', id);
    fetch('ajax/pacientes_ajax.php', { method: 'POST', body: datos })
        .then(r => r.text())
        .then(msg => {
            bootstrap.Modal.getInstance(document.getElementById('modalEliminar')).hide();
            mostrarMensaje(msg);
            cargarPacientes();
        });
}

function mostrarMensaje(texto) {
    document.getElementById('textoMensaje').innerText = texto;
    new bootstrap.Modal(document.getElementById('modalMensaje')).show();
}
</script>
</body>
</html>
