<?php 
session_start();
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

Auditoria("En Geoposición se Abrio la Vista de Ubicaciones de Todas las Unidades para la Unidad: $titulo",$id);

$html = "";

$html="<div class='well'>
<div class='header'>Ultima Posición Reportada<a class='headerclose'><i class='fa fa-times pull-right'></i></a> <a class='headerrefresh'><i class='fa fa-refresh pull-right'></i></a> <a class='headershrink'><i class='fa fa-chevron-down pull-right'></i></a></div>

<iframe src='mapa_general.php' name='posicion' id='posicion' height='600' width='915' scrolling='no' style='border:none;background:none;'></iframe>

<p>&nbsp;</p><p>&nbsp;</p>
<div class='row'>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<input type='button' name='volver' value='Limpiar' class='btn btn-info btn-block' onclick='limpiar()'/></div></div>
</div>";


echo $html;?>