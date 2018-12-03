<?php session_start();
include("../complementos/condb.php"); 
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$val = false;

$rs = pg_query($link, filtrar_sql("select id_unidad, fecha_hora_activacion, id_panico from panico where id_estatus = 31")); 
$r = pg_num_rows($rs);

if($r!=false && $r>0){ 
	$r = pg_fetch_array($rs);
$qs = pg_query($link, filtrar_sql("select confunid.codigo_principal, unidades.codigo_principal from unidades, confunid where confunid.id_confunid = unidades.id_confunid and unidades.id_unidad = ".$r[0]));
	$qs = pg_fetch_array($qs);
	$val = $r[2].":::"."La Unidad ".$qs[0]." - ".$qs[1]." se Encuentra en Estatus de Panico a las ".date3($r[1]);
}

echo $val;
?>