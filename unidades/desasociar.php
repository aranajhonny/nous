<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$valor = "false";

if(isset($_REQUEST['pri']) && isset($_REQUEST['seg'])){ 
	$pri = filtrar_campo('int', 6, $_REQUEST['pri']);
	$seg = filtrar_campo('int', 6, $_REQUEST['seg']);

	if(pg_query($link, filtrar_sql("update unidades set id_unidpri = 0 where id_unidad = $seg and id_unidpri = $pri"))){ 
		$rs = pg_query($link, filtrar_sql("select confunid.codigo_principal, unidades.codigo_principal from unidades, confunid where unidades.id_confunid = confunid.id_confunid and unidades.id_unidad = $seg ")); 
		$rs = pg_fetch_array($rs);
		$unid = $rs[0]." ".$rs[1];
		Auditoria("Unidad: $unid  Desociada ",$seg);
		$valor = "true";
	}
}

include("../complementos/closdb.php");
echo $valor;
?>