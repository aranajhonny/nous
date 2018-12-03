<?php 
session_start();
include("../complementos/condb.php");

$eventos="";

$rs = pg_query($link, filtrar_sql("select id_progmant, confunid.codigo_principal, unidades.codigo_principal, planmant.descripcion, fr, extract(days from (fr - timestamp '".date('Y-m-d')."')) from progmant, planmant_unidades, planmant, unidades, confunid  where unidades.id_unidad = ".$_SESSION['progmant_unidad']." and planmant_unidades.id_planmant_unidad = progmant.id_planmantunid and   planmant_unidades.id_unidad = unidades.id_unidad and planmant_unidades.id_planmant = planmant.id_planmant and unidades.id_confunid = confunid.id_confunid order by fr desc"));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$eventos="";
} else { 
if($r[5]>0){ $color="#C00"; } else { $color="#093";}
    while ($r = pg_fetch_array($rs)) {
$eventos .= "{id: ".$r[0].", title: '".$r[1]."-".$r[2]." ".$r[3]."', start: '".$r[4]."', backgroundColor: '$color', borderColor: '$color'},  ";
    } 
	$eventos = substr($eventos, 0, (strlen($eventos)-1));
}
echo $eventos;

include("../complementos/closdb.php");
?>