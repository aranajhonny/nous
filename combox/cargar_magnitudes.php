<?php
include("class.combos.php");

$selects = new selects();
$vends = $selects->cargarMagnitudes();
$cont=0; $lista = "";
foreach($vends as $key=>$value)
{
		$lista .= "<option value=\"$key\">$value</option>";
		$cont++;
}

// AGREGAR AL PRINCIPIO DE LA LISTA
//if(empty($lista)==false){ $lista = "<option value=\"0\" selected='selected'>Seleccione un Cliente</option>".$lista; }

// AGREGAR AL FINAL DE LA LISTA
if($cont>=1){  
} else { $lista .= "<option value=\"0\" selected='selected'>Lista de Magnitudes Vacia</option>"; } 

echo $lista;
?>