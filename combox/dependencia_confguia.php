<?php
include("class.combos.php");

$clientes = new selects();
$clientes->code = $_GET["code"];
$clientes = $clientes->cargarConfGuia();
$cont=0; $lista="";
foreach($clientes as $key=>$value)
{
		$lista .= "<option value=\"$key\">$value</option>";
		$cont++;
}

// AGREGAR AL PRINCIPIO DE LA LISTA
if(empty($lista)==false){ $lista = "<option value='0' selected='selected'>Seleccione una Configuración dela Guía de Despacho</option>".$lista; }

// AGREGAR AL FINAL DE LA LISTA
if($cont<1){ $lista .= "<option value=\"0\" selected='selected'>Lista de Configuraciones de la Guía de Despacho Vacia</option>"; } 

echo $lista;
?>