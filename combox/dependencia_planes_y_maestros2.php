<?php
include("class.combos.php");

$clientes = new selects();
$clientes->code = $_GET["code"];
$clientes = $clientes->cargarPlanes_y_Maestros2();
$cont=0; $lista="";
foreach($clientes as $key=>$value)
{
		$lista .= "<option value=\"$key\">$value</option>";
		$cont++;
}

// AGREGAR AL PRINCIPIO DE LA LISTA
if(empty($lista)==false){ $lista = "<option value=\"0\" selected='selected'>Seleccione un Plan de Mantenimiento</option>".$lista; }

// AGREGAR AL FINAL DE LA LISTA
if($cont<1){ $lista .= "<option value=\"0\" selected='selected'>Lista de Planes de Mantenimiento Vacia</option>"; } 

echo $lista;
?>