<?php 

$_SESSION['acc']['mod'] = 42;
$_SESSION['acc']['form'] = 149;
include_once("../complementos/permisos.php");




$tabla="<table class='table table-striped table-bordered class-managed-table'><thead><tr><th class='extra'>ID de Alarma</th><th>Unidad</th><th class='extra3'>Responsable</th><th>Fecha y Hora de Envio</th><th>Fecha y Hora de Confirmación</th><th>Duración Min.</th><th class='extra2'>Mensaje</th></tr></thead><tbody>";
	
$rs = pg_query($link, filtrar_sql("select id_noti, notificaciones.id_alarma, alarmas.dresp, fecha_hora_enviado, fecha_hora_confirmacion, notificaciones.duracion_min, mensaje, confirmacion, unidades.codigo_principal, alarmas.dconfunid from notificaciones, alarmas, unidades where notificaciones.id_alarma = alarmas.id_alarma and alarmas.id_unidad = unidades.id_unidad and alarmas.id_unidad = ".$_SESSION['monunidgen']." order by fecha_hora_enviado desc limit 100")); 

$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){  
if($r[8]=='t'){ $dir="../img/vigente.jpg"; $est="CONFIRMADA"; } else { $dir="../img/vencido.jpg"; $est="SIN CONFIRMAR"; } 

$qs = pg_query($link, filtrar_sql("select confunid.codigo_principal, unidades.codigo_principal from alarmas, unidades, confunid where confunid.id_confunid = unidades.id_confunid and alarmas.id_unidad = unidades.id_unidad and id_alarma = ".$r[1])); 
$qs = pg_fetch_array($qs); 
$unid = $qs[0]." ".$qs[1];
   
$tabla.="<tr><td class='extra'><div class=' info-tooltip'><img src='$dir' width='20' height='43' rel='tooltip' title='$est'  style='margin-right:10px;'/>".$r[1]."</div></td><td>".$unid."</td><td class='extra3'>".$r[2]." ".$r[3]."</td><td>".date3($r[4])."</td><td>".date3($r[5])."</td><td>".$r[6]."</td><td class='extra2'>".$r[7]."</td></tr>";

} } else { 

$tabla.="<tr><td class='extra'></td><td></td><td class='extra3'>NO HAY NOTIFICACIONES PARA ESTA UNIDAD</td><td></td><td></td><td></td><td class='extra2'></td></tr>";

}  
   
$tabla.="</tbody><tfoot><tr><th class='extra'><input type='text' name='area' id='area' placeholder='Buscar ID Alarma' class='search_init'  style='width:160px;' /></th><th><input type='text' name='unidad' id='unidad' placeholder='Buscar Unidad' class='search_init'  style='width:160px;' /></th><th class='extra3'><input type='text' name='zona' id='zona' placeholder='Buscar Responsable' class='search_init'  style='width:160px;' /></th><th><input type='text' name='tunid' id='tunid' placeholder='Buscar Fecha de Envio' class='search_init'  style='width:160px;' /></th><th><input type='text' name='cod' id='cod' placeholder='Buscar Fecha de Confirmación' class='search_init' style='width:160px;'/></th><th><input type='text' name='control' placeholder='Buscar Duración Min' class='search_init' style='width:160px;' /></th><th class='extra2'><input type='text' name='msj' placeholder='Buscar Mensaje' class='search_init' style='width:160px;' /></th></tr></tfoot></table>";


?>