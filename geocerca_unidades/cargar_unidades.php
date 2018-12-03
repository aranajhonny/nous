<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

if(isset($_REQUEST['data'])){ 
	list($c, $geo, $t, $z, $a) = explode(":::",filtrar_campo('cadena',120,$_REQUEST['data']));

$html = "";	
$rs = pg_query($link, filtrar_sql("select id_unidad, unidades.codigo_principal, confunid.codigo_principal, ult_posicion from unidades, confunid, areas, zongeo where unidades.id_confunid = confunid.id_confunid and areas.id_area = unidades.id_area and zongeo.id_zongeo = unidades.id_zona and (areas.id_cliente = $c and confunid.id_cliente = $c and unidades.id_cliente = $c and zongeo.id_cliente = $c) and ((areas.id_area = $a or $a < 1) and (zongeo.id_zongeo = $z or $z < 1) and (confunid.id_confunid = $t or $t < 1))
order by confunid.nombre, unidades.codigo_principal asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ 
	while($r = pg_fetch_array($rs)){  
		if(strlen($r[3])>16){ 
			$html.="<tr><td><input type='checkbox' name='chk_".$r[0]."' id='chk_".$r[0]."' /></td><td>".$r[2]."</td><td>".$r[1]."</td></tr>";
		}
 	} 
} else { 
	$html = "<tr><td colspan='3' align='center'>Lista de Unidades Vacia</td></tr>";
} 

echo $html;
} ?>