<?php
include("class.combos.php");

$clientes = new selects();
$clientes->code = $_GET["code"];
$clientes = $clientes->cargarDispositivos2();
$cont=0; $lista="";
foreach($clientes as $key=>$value)
{
		$lista .= "<option value=\"$key\">$value</option>";
		$cont++;
}

// AGREGAR AL PRINCIPIO DE LA LISTA
if(empty($lista)==false){ $lista = "<option value='0' selected='selected'>Seleccione un Dispositivo</option>".$lista; }

// AGREGAR AL FINAL DE LA LISTA
if($cont<1){ $lista .= "<option value=\"0\" selected='selected'>Lista de Dispositivos Vacia</option>"; } 

echo $lista;
?>