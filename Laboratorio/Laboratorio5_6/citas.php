<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Citas</title>
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
    <h3>📅 Gestión de Citas</h3>
    <button class="btn btn-primary mb-3" onclick="abrirModalNuevaCita()">+ Nueva Cita</button>

    <!-- FILTROS -->
    <div class="card mb-3 p-3 shadow-sm">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label>Estado</label>
                <select id="filtroEstado" class="form-select" onchange="cargarCitas()">
                    <option value="">-- Todos --</option>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Atendida">Atendida</option>
                    <option value="Cancelada">Cancelada</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Médico</label>
                <select id="filtroMedico" class="form-select" onchange="cargarCitas()">
                    <option value="">-- Todos --</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Paciente</label>
                <select id="filtroPaciente" class="form-select" onchange="cargarCitas()">
                    <option value="">-- Todos --</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-secondary w-100" onclick="limpiarFiltros()">Limpiar</button>
            </div>
        </div>
    </div>

    <div id="tabla-citas"></div>
</div>

<!-- MODAL NUEVA CITA -->
<div class="modal fade" id="modalCita" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nueva Cita</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label>Médico</label>
            <select id="selectMedico" class="form-select">
                <option value="">-- Selecciona --</option>
            </select>
          </div>
          <div class="col-md-6">
            <label>Paciente</label>
            <select id="selectPaciente" class="form-select">
                <option value="">-- Selecciona --</option>
            </select>
          </div>
          <div class="col-md-6">
            <label>Fecha</label>
            <input type="date" id="fechaCita" class="form-control">
          </div>
          <div class="col-md-6">
            <label>Hora</label>
            <input type="time" id="horaCita" class="form-control">
          </div>
          <div class="col-12">
            <label>Motivo</label>
            <textarea id="motivo" class="form-control" rows="3"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" onclick="guardarCita()">Registrar</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL CAMBIAR ESTADO -->
<div class="modal fade" id="modalEstado" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cambiar Estado</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="citaIdEstado">
        <label>Nuevo estado:</label>
        <select id="nuevoEstado" class="form-select mt-2">
            <option value="Pendiente">Pendiente</option>
            <option value="Atendida">Atendida</option>
            <option value="Cancelada">Cancelada</option>
        </select>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" onclick="confirmarCambioEstado()">Guardar</button>
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
        <button class="btn btn-primary mt-2" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>

window.onload = function() {
    cargarSelects();
    cargarCitas();
};

function cargarSelects() {
    fetch('ajax/citas_ajax.php?accion=selects')
        .then(r => r.json())
        .then(data => {
            llenarSelect('selectMedico',   data.medicos,   'id', 'nombre');
            llenarSelect('selectPaciente', data.pacientes, 'id', 'nombre');
            llenarSelect('filtroMedico',   data.medicos,   'id', 'nombre');
            llenarSelect('filtroPaciente', data.pacientes, 'id', 'nombre');
        });
}

function llenarSelect(idSelect, lista, valorCampo, textoCampo) {
    const sel = document.getElementById(idSelect);
    const primera = sel.options[0].outerHTML;
    sel.innerHTML = primera;
    lista.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item[valorCampo];
        opt.text  = item[textoCampo];
        sel.appendChild(opt);
    });
}

function cargarCitas() {
    const estado   = document.getElementById('filtroEstado').value;
    const medico   = document.getElementById('filtroMedico').value;
    const paciente = document.getElementById('filtroPaciente').value;
    fetch(`ajax/citas_ajax.php?accion=listar&estado=${estado}&medico=${medico}&paciente=${paciente}`)
        .then(r => r.text())
        .then(html => { document.getElementById('tabla-citas').innerHTML = html; });
}

function limpiarFiltros() {
    document.getElementById('filtroEstado').value   = '';
    document.getElementById('filtroMedico').value   = '';
    document.getElementById('filtroPaciente').value = '';
    cargarCitas();
}

function abrirModalNuevaCita() {
    document.getElementById('selectMedico').value   = '';
    document.getElementById('selectPaciente').value = '';
    document.getElementById('fechaCita').value      = '';
    document.getElementById('horaCita').value       = '';
    document.getElementById('motivo').value         = '';
    new bootstrap.Modal(document.getElementById('modalCita')).show();
}

function guardarCita() {
    const idMedico   = document.getElementById('selectMedico').value;
    const idPaciente = document.getElementById('selectPaciente').value;
    const fecha      = document.getElementById('fechaCita').value;
    const hora       = document.getElementById('horaCita').value;
    const motivo     = document.getElementById('motivo').value.trim();

    if (!idMedico || !idPaciente || !fecha || !hora) {
        mostrarMensaje('⚠️ Todos los campos son obligatorios.');
        return;
    }

    const datos = new FormData();
    datos.append('accion',      'insertar');
    datos.append('id_medico',   idMedico);
    datos.append('id_paciente', idPaciente);
    datos.append('fecha_cita',  fecha);
    datos.append('hora_cita',   hora);
    datos.append('motivo',      motivo);

    fetch('ajax/citas_ajax.php', { method: 'POST', body: datos })
        .then(r => r.text())
        .then(msg => {
            bootstrap.Modal.getInstance(document.getElementById('modalCita')).hide();
            mostrarMensaje(msg);
            cargarCitas();
        });
}

function cambiarEstado(id, estadoActual) {
    document.getElementById('citaIdEstado').value = id;
    document.getElementById('nuevoEstado').value  = estadoActual;
    new bootstrap.Modal(document.getElementById('modalEstado')).show();
}

function confirmarCambioEstado() {
    const id     = document.getElementById('citaIdEstado').value;
    const estado = document.getElementById('nuevoEstado').value;
    const datos  = new FormData();
    datos.append('accion', 'cambiarEstado');
    datos.append('id',     id);
    datos.append('estado', estado);
    fetch('ajax/citas_ajax.php', { method: 'POST', body: datos })
        .then(r => r.text())
        .then(msg => {
            bootstrap.Modal.getInstance(document.getElementById('modalEstado')).hide();
            mostrarMensaje(msg);
            cargarCitas();
        });
}

function mostrarMensaje(texto) {
    document.getElementById('textoMensaje').innerText = texto;
    new bootstrap.Modal(document.getElementById('modalMensaje')).show();
}
</script>
</body>
</html>
