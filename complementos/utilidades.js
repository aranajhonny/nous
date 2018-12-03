// JavaScript Document mostrar
/* ------------------------------ SOLO MINUSCULAS ---------------------------------------------------------------- */
function minu(obj){ obj.value=obj.value.toLowerCase(); return true; }

/* ------------------------------ SOLO MAYUSCULAS ---------------------------------------------------------------- */
function mayu(obj){ obj.value=obj.value.toUpperCase(); return true; }


/* ------------------------------ TIPOS DE TECLAS PERMITIDAS ----------------------------------------------------- */
function permite(elEvento, permitidos) {
  var num   = "0123456789";
  var minus = "abcdefghijklmnñopqrstuvwxyzáéíúó";
  var mayus = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÚÓ";
  var car   = minus+mayus;
  var esp   = "(),.-_/#°@";

  switch(permitidos) {
    case 'num':   permitidos = num; break;
	case 'float': permitidos = num+".-"; break;
	case 'telef': permitidos = num+"-"; break;
    case 'car':   permitidos = " "+car; break;
	case 'espe':  permitidos = " "+car+esp; break;
	case 'todo':  permitidos = " "+car+num+esp; break;
	case 'rif':   permitidos = num+"-JjVvNnGg"; break;
	case 'ci':    permitidos = num+"-VvEe"; break;
	case 'user':  permitidos = car+num+"_@."; break;
	case 'clav':  permitidos = car+num+"_@."; break;
  }
 
  var evento = elEvento || window.event;
  var codigoCaracter = evento.charCode || evento.keyCode;
  var key = evento.keyCode;
  var car = String.fromCharCode(codigoCaracter);

  // si backspace = 8 si tabulador = 9  si feclas arriba abajo izquierda derecha 37-40
  if(key==8 || key==9 || (key>=37&&key<=40)) return 1 != -1;
  
  if(car==127 || car==0) return 1!= -1
 
  return permitidos.indexOf(car) != -1 ;
}