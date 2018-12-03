<?php  

$cnn_panico = pg_connect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014",PGSQL_CONNECT_FORCE_NEW);

$rs = pg_query($cnn_panico, filtrar_sql("select panico.id_panico, confunid.codigo_principal, unidades.codigo_principal from paniusu, panico, confunid, unidades where paniusu.id_panico = panico.id_panico and panico.id_unidad = unidades.id_unidad and unidades.id_confunid = confunid.id_confunid and paniusu.id_usuario = ".$_SESSION['miss'][8]." and visto = false  order by id_panico asc limit 1"));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ 
	$rs = pg_fetch_array($rs);
	$_SESSION['ptc']['id'] = $rs[0];
	$_SESSION['ptc']['unid'] = $rs[1]." ".$rs[2];
}

pg_close($cnn_panico);

?>