/*  
 * 1. Nombre: Válido: 'Ana Maria', Inválido: '123', Límite: 'Ab' (2 chars) 
 * 2. Email: Válido: 'test@mail.com', Inválido: 'test@mail', Límite: 'a@b.co' 
 * 3. Teléfono: Válido: '71234567', Inválido: '51234567', Límite: '7123456' (7 dígitos) 
 */

var reglas = { 
  nombre:   /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{3,50}$/, 
  email:    /[\w-.]{3,}@([\w-]{2,}\.)+[\w-]{2,4}/, 
  pass:     /(?!^[0-9]*$)(?!^[a-zA-Z]*$)^([a-zA-Z0-9]{8,10})$/, 
  telefono: /^[67]\d{7}$/,
  fecha:    /^\d{1,2}\/\d{1,2}\/\d{2,4}$/
}; 


function validarCampo(id, regex, msg) { 
  var campo = document.getElementById(id); 
  var error = document.getElementById('err_' + id); 
  
  if (!regex.test(campo.value.trim())) { 
    campo.style.borderColor = '#DC2626'; 
    error.textContent = msg; 
    error.style.display = 'block'; 
    return false; 
  } 
  
  campo.style.borderColor = '#16A34A'; 
  error.style.display = 'none'; 
  return true; 
} 

//Validacion en blur (campo por campo)

document.getElementById('nombre').addEventListener('blur', function() { 
  validarCampo('nombre', reglas.nombre, 'Mínimo 3 caracteres, solo letras'); 
}); 

document.getElementById('email').addEventListener('blur', function() { 
  validarCampo('email', reglas.email, 'Formato de email inválido'); 
}); 

document.getElementById('pass').addEventListener('blur', function() { 
  validarCampo('pass', reglas.pass, '8-10 caracteres, letras y números'); 
}); 

document.getElementById('telefono').addEventListener('blur', function() { 
  validarCampo('telefono', reglas.telefono, 'Debe iniciar con 6 o 7 (8 dígitos)'); 
}); 

document.getElementById('fecha').addEventListener('blur', function() { 
  validarCampo('fecha', reglas.fecha, 'Formato requerido: DD/MM/AAAA'); 
}); 


//Validación al enviar el formulario 
document.getElementById('miForm').addEventListener('submit', function(e) { 
  e.preventDefault(); 
  
  var esValido = 
    validarCampo('nombre', reglas.nombre, 'Nombre inválido') && 
    validarCampo('email', reglas.email, 'Email inválido') && 
    validarCampo('pass', reglas.pass, 'Contraseña débil') && 
    validarCampo('telefono', reglas.telefono, 'Teléfono inválido') &&
    validarCampo('fecha', reglas.fecha, 'Fecha inválida'); 

  if (esValido) {
    alert('Formulario válido. Enviando datos...'); 
  } else {
    alert('Por favor, corrija los errores resaltados en rojo.');
  }
});