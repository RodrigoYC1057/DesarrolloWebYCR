// Variables globales de control
var colores = ['#FFEAA7', '#DFE6E9', '#B2BEFF']; 
var colorIdx = 0, contador = 0; 


function cambiarColor() { 
  // Rota el índice del color
  colorIdx = (colorIdx + 1) % colores.length; 
  
  // Modifica el estilo del panel
  document.getElementById('panel').style.backgroundColor = colores[colorIdx]; 
  
  contador++; 
  document.getElementById('cont').innerHTML = 'Clics: ' + contador; 
} 



function agregarParrafo() { 
  var txt = document.getElementById('txtInput').value; 
  
  // Validación de campo vacío
  if (txt.trim() === '') { 
      alert('Escribe algo'); 
      return; 
  } 
  
  // Creación dinámica de elementos
  var p = document.createElement('p'); 
  p.textContent = txt; 
  p.setAttribute('class', 'item-dinamico'); 
  
  // Inserción en el DOM
  document.getElementById('lista').appendChild(p); 
  
  
  document.getElementById('txtInput').value = ''; 
} 

function eliminarUltimo() { 
  var lista = document.getElementById('lista'); 
  // Uso de querySelectorAll 
  var items = lista.querySelectorAll('.item-dinamico'); 
  
  if (items.length > 0) {
    lista.removeChild(items[items.length - 1]); 
  }
} 

// Asignación de funciones a los botones 
document.getElementById('btnAgregar').onclick = agregarParrafo;
document.getElementById('btnEliminar').onclick = eliminarUltimo;



window.onload = function() { 
    
    var imagen = document.getElementById('miImagen');

    // Evento mouseover: resalta la imagen con un borde
    imagen.addEventListener('mouseover', function() { 
        this.style.border = '3px solid #C8420A'; 
    }); 

   
    imagen.addEventListener('mouseout', function() { 
        this.style.border = 'none'; 
    }); 
};