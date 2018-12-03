<?php 
 


$_SESSION['acc']['mod'] = 42;
$_SESSION['acc']['form'] = 149;
include_once("../complementos/permisos.php");


$tabla = "<table class='table table-striped table-bordered class-managed-table'><thead><tr><th>Área</th><th>Zona Geografica</th><th>Tipo de Unidad</th><th>Código Principal</th><th>Control</th><th>Fecha y Hora</th><th>Valores de Referencia</th><th>Valores</th><th>Estatus de la Alarma</th><th>Duración</th></tr></thead><tbody>";
	
$rs = pg_query($link, filtrar_sql("select id_alarma, alarmas.darea, alarmas.dzona, alarmas.dconfunid, unidades.codigo_principal, dcontrol, fecha_evento, val_min, val_max, val_cri_min, val_cri_max, dato, estatus.nombre, duracion_min, sensores.dunidmed from alarmas, unidades, sensores, estatus where alarmas.id_unidad = ".$_SESSION['monunidgen']." and unidades.id_unidad = ".$_SESSION['monunidgen']." and alarmas.id_unidad = unidades.id_unidad and alarmas.id_sensor = sensores.id_sensor and unidades.id_unidad = sensores.id_unidad and alarmas.id_estatus = estatus.id_estatu order by fecha_evento desc limit 100 ")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){  

$qs = pg_query($link, filtrar_sql("select unidmed.nombre from unimedcli, unidmed where unimedcli.id_unidmed = unidmed.id_unidmed and id_unimedcli = ".$r[14])); 
$qs = pg_fetch_array($qs); 
$unid = $qs[0]; 
    
$tabla.="<tr><td>".$r[1]."</td><td>".$r[2]."</td><td>".$r[3]."</td><td>".$r[4]."</td><td>".$r[5]."</td><td>".date3($r[6])."</td><td>".(1*$r[7])."$unid  / ".(1*$r[8])."$unid <br/> ".(1*$r[9])."$unid  /  ".(1*$r[10])."$unid"."</td><td>".(1*$r[11]).$unid."</td><td>".$r[12]."</td><td>".$r[13]." Min</td></tr>";

} }else {

$tabla.="<tr><td>NO HAY ALARMAS PARA ESTA UNIDAD</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

}    

$tabla.="</tbody><tfoot><tr><th><input type='text' name='area' id='area' placeholder='Buscar Areas' class='search_init'  style='width:160px;' /></th><th><input type='text' name='zona' id='zona' placeholder='Buscar Zona Geografica' class='search_init'  style='width:160px;' /></th><th><input type='text' name='tunid' id='tunid' placeholder='Buscar Tipo de Unidad' class='search_init'  style='width:160px;' /></th><th><input type='text' name='cod' id='cod' placeholder='Buscar Código Principal' class='search_init' style='width:160px;' /></th><th><input type='text' name='control' placeholder='Buscar Control' class='search_init' style='width:160px;' /></th><th><input type='text' name='fecha' placeholder='Buscar Fecha y Hora' class='search_init' style='width:160px;' /></th><th><input type='text' name='ref' placeholder='Buscar Valores de Referencia' class='search_init' style='width:160px;' /></th><th><input type='text' name='valor' placeholder='Buscar Valores' class='search_init' style='width:160px;' /></th><th><input type='text' name='est' placeholder='Buscar Estatus' class='search_init' style='width:160px;' /></th><th><input type='text' name='dura' placeholder='Buscar Duración' class='search_init' style='width:160px;' /></th></tr></tfoot> </table>";


?>