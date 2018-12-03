<?php
include("class.combos.php");

$clientes = new selects();
$clientes->code = $_GET["code"];
$clientes = $clientes->cargarUnidMed();
$cont=0; $lista="";
foreach($clientes as $key=>$value)
{
		$lista .= "<option value=\"$key\">$value</option>";
		$cont++;
}

// AGREGAR AL PRINCIPIO DE LA LISTA
if(empty($lista)==false){ $lista = "<option value='0' selected='selected'>Seleccione una Unidad de Medida</option>".$lista; }

// AGREGAR AL FINAL DE LA LISTA
if($cont<1){ $lista .= "<option value=\"0\" selected='selected'>Lista de Unidades de Medida Vacia</option>"; } 

echo $lista;
?>