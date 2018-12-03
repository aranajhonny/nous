<?php  
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$val = "false";
if(isset($_REQUEST['id'])){ 
$id = filtrar_campo('int', 6, $_REQUEST['id']);

	$rs = pg_query($link, filtrar_sql("select confunid.codigo_principal, unidades.codigo_principal, descripcion from unidades, confunid, unidimg where unidimg.id_unidad = unidades.id_unidad and confunid.id_confunid = unidades.id_confunid and id_unidimg =$id"));
	$unid = $rs[0]." ".$rs[1];
	$doc = $rs[2];
	if(pg_query($link, filtrar_sql("delete from unidimg where id_unidimg = $id"))){ 
		$val="true";
		Auditoria("Documento: $doc de la Unidad: $unid Fue Eliminado", $id);
	}
}

include("../complementos/closdb.php");
echo $val;
?>