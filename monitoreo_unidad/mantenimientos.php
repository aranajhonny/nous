<?php 


$_SESSION['acc']['mod'] = 42;
$_SESSION['acc']['form'] = 149;
include_once("../complementos/permisos.php");
		                	
$tabla="<table class='table table-striped table-bordered class-managed-table'><thead><tr><th>Área</th><th>Zona Geografica</th><th>Tipo de Unidad</th><th>Unidad</th><th>Plan de Mantenimiento</th><th>Fecha Programada</th><th>Estatus del Mantenimiento</th></tr></thead><tbody>";
	
$rs = pg_query($link, filtrar_sql("select id_progmant, darea, dzona, dconfunid, unidades.codigo_principal, planmant.descripcion, progmant.fr, progmant.estatus from progmant, planmant_unidades, planmant, unidades where planmant_unidades.id_unidad = ".$_SESSION['monunidgen']." and unidades.id_unidad = ".$_SESSION['monunidgen']." and planmant_unidades.id_unidad = unidades.id_unidad and planmant_unidades.id_planmant = planmant.id_planmant and  id_planmantunid = id_planmant_unidad and progmant.fr >= '".date('Y-m-d')."' order by progmant.fr asc limit 100") ); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){  
    
$tabla.="<tr><td>".$r[1]."</td><td>".$r[2]."</td><td>".$r[3]."</td><td>".$r[4]."</td><td>".$r[5]."</td><td>".date1($r[6])."</td><td>".$r[7]."</td></tr>";

} } else { 

$tabla.="<tr><td>NO HAY MANTENIMIENTOS PARA ESTA UNIDAD</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

}       

$tabla.="</tbody><tfoot><tr><th><input type='text' name='area' placeholder='Buscar Areas' class='search_init'  style='width:160px;' /></th><th><input type='text' name='zona' placeholder='Buscar Zona Geografica' class='search_init'  style='width:160px;' /></th><th><input type='text' name='tunid' placeholder='Buscar Tipo de Unidad' class='search_init'  style='width:160px;' /></th><th><input type='text' name='cod' placeholder='Buscar Unidad' class='search_init' style='width:160px;' /></th><th><input type='text' name='plan' placeholder='Buscar Plan de Mantenimiento' class='search_init' style='width:160px;' /></th><th><input type='text' name='fecha' placeholder='Buscar Fecha Programada' class='search_init' style='width:160px;' /></th><th><input type='text' name='est' placeholder='Buscar Estatus del Mantenimiento' class='search_init' style='width:160px;' /></th></tr></tfoot> </table>";


?>