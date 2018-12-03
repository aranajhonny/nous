<?php 

// --------------------- campo vacio ---------------------------------
function vacio($campo,$valor){ 
if(empty($valor)){ $_SESSION['mensaje']='El Campo '.$campo.' es Obligatorio \n Debe Indicarlo'; return true;
} else { return false; } }



// --------------------- campo CI ------------------------------------
function CI($campo,$valor){ 
if(empty($valor)){ $_SESSION['mensaje']='El Campo '.$campo.' es Obligatorio \n Debe Indicarlo'; return true;
} else if (($valor*1)<6000000 || ($valor*1)>30000000){ 
$_SESSION['mensaje']='El Campo '.$campo.' Esta Fuera de Rango \n [Desde 6.000.000 Hasta 30.000.000] \n Debe Verificar e Intentar de Nuevo'; return true;
} else { return false; } }



// ---------------------- lista sin seleccion -------------------------
function select($campo,$valor){ 
if(empty($valor)){ $_SESSION['mensaje']='La Lista '.$campo.' es Obligatorio \n Debe Seleccionar una Opción';return true;
} else { return false; } }



# funcion para pasar todas las letras a MAYUSCULAS 
function fullupper($string){ 
  return strtr(strtoupper($string), array(      
      "è" => "È", 
      "ì" => "Ì", 
      "ò" => "Ò", 
      "ù" => "Ù", 
          "à" => "À", 
      "é" => "É", 
      "í" => "Í", 
      "ó" => "Ó", 
      "ú" => "Ú", 
          "â" => "Â", 
      "ê" => "Ê", 
      "î" => "Î", 
      "ô" => "Ô", 
      "û" => "Û", 
          "ç" => "Ç",
		  "ñ" => "Ñ", 
    )); 
}


?>