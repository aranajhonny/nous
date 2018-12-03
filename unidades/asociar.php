<?php session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
$valor = "false";

if(isset($_REQUEST['pri']) && isset($_REQUEST['seg'])){ 
	$pri = filtrar_campo('int', 6, $_REQUEST['pri']);
	$seg = filtrar_campo('int', 6, $_REQUEST['seg']);
	
$rs = pg_query(filtrar_sql("select count(id_unidad) from unidades where id_unidpri = $seg or ( id_unidad = $seg and id_unidpri <> 0 )"));
$rs = pg_fetch_array($rs);	
		if($rs[0]>0){ // si unidad segundaria ya se encuentra ligada a una principal o segundaria
			$valor = "Unidad Segundaria ya se Encuentra Asociada a otra Unidad";
		} else {
			if(pg_query(filtrar_sql("update unidades set id_unidpri = $pri where id_unidad = ".$_REQUEST['seg']))){ 
				$rs = pg_query(filtrar_sql("select confunid.codigo_principal, unidades.codigo_principal from unidades, confunid where unidades.id_confunid = confunid.id_confunid and unidades.id_unidad = $pri")); 
				$rs = pg_fetch_array($rs);
				$pri = $rs[0]." ".$rs[1];
				
				$rs = pg_query(filtrar_sql("select confunid.codigo_principal, unidades.codigo_principal from unidades, confunid where unidades.id_confunid = confunid.id_confunid and unidades.id_unidad = $seg ")); 
				$rs = pg_fetch_array($rs);
				$seg2 = $rs[0]." ".$rs[1];
		
				Auditoria("Unidad: $pri Ahora se Encuentra Asociada a la Unidad: $seg2",$seg);
				$valor = "true";
			}
		}
	
	
}

include("../complementos/closdb.php");
echo $valor;?>