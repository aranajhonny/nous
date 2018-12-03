<?php 


$_SESSION['acc']['mod'] = 42;
$_SESSION['acc']['form'] = 149;
include_once("../complementos/permisos.php");


$tabla = "Este si Esta Vacio";

$rs = pg_query($link, filtrar_sql("select permisos.id_permiso, tipo_permisos.nombre, dconfunid, unidades.codigo_principal, ci, personal.nombre, fecha_vencimiento, dias_gestion, permisos.id_estatus, serial from permisos, personal, unidades, tipo_permisos where permisos.id_unidad = ".$_SESSION['monunidgen']." and unidades.id_unidad = ".$_SESSION['monunidgen']." and permisos.id_tipo_permiso = tipo_permisos.id_tipo_permiso and id_personal = id_responsable_especifico and unidades.id_unidad = permisos.id_unidad order by serial desc limit 100")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ 


$tabla = "<table class='table table-striped table-bordered class-managed-table' ><thead><tr><th>Estatus</th><th>Tipo de Permiso</th><th>Serial</th><th>Unidad</th><th>Responsable</th><th>Fecha Venc.</th><th>Fecha Inicio Tram.</th></tr></thead><tbody>";

while($r = pg_fetch_array($rs)){ 
$qs=pg_query($link, filtrar_sql("select date '".$r[6]."' - interval '".$r[7]." day' as fecha"));  $qs = pg_fetch_array($qs);
if($r[8]==9){ $dir="../img/vigente.jpg"; $est="VIGENTE";
} else if($r[8]==10){ $dir="../img/tramitando.jpg"; $est="TRAMITANDO";
} else if($r[8]==11){ $dir="../img/vencido.jpg"; $est="VENCIDO"; }    

$tabla .= "<tr><td><div class=' info-tooltip'><img src='$dir' width='25' height='45' rel='tooltip' title='$est' /> $est</div></td><td>".$r[1]."</td><td>".$r[9]."</td><td>".$r[2]." - ".$r[3]."</td><td>".$r[4]." ".$r[5]."</td><td>".date1($r[6])."</td><td>".date1($qs[0])."</td></tr>";

} 

$tabla .= "</tbody><tfoot><tr><th><input type='text' name='est' id='est' placeholder='Buscar Estatus' class='search_init'  style='width:160px;' /></th><th><input type='text' name='tipo' id='tipo' placeholder='Buscar Tipo de Permiso' class='search_init'  style='width:160px;' /></th><th><input type='text' name='serial' id='serial' placeholder='Buscar Serial' class='search_init'  style='width:160px;' /></th><th><input type='text' name='unid' id='unid' placeholder='Buscar Unidad' class='search_init'  style='width:160px;' /></th><th><input type='text' name='resp' id='resp' placeholder='Buscar Responsable' class='search_init'  style='width:160px;' /></th><th><input type='text' name='fv' id='fv' placeholder='Buscar Fecha de Vencimiento' class='search_init'  style='width:160px;' /></th><th><input type='text' name='ft' id='ft' placeholder='Buscar Fecha de Inicio de Tramite' class='search_init'  style='width:160px;' /></th></tr></tfoot>  </table>";


}  else { 

$tabla .= "<table class='table table-striped table-bordered class-managed-table' ><thead><tr><th>Estatus</th><th>Tipo de Permiso</th><th>Serial</th><th>Unidad</th><th>Responsable</th><th>Fecha Venc.</th><th>Fecha Inicio Tram.</th></tr></thead><tbody><tr><td>NO HAY PERMISOS PARA ESTA UNIDAD</td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody><tfoot><tr><th><input type='text' name='est' id='est' placeholder='Buscar Estatus' class='search_init'  style='width:160px;' /></th><th><input type='text' name='tipo' id='tipo' placeholder='Buscar Tipo de Permiso' class='search_init'  style='width:160px;' /></th><th><input type='text' name='serial' id='serial' placeholder='Buscar Serial' class='search_init'  style='width:160px;' /></th><th><input type='text' name='unid' id='unid' placeholder='Buscar Unidad' class='search_init'  style='width:160px;' /></th><th><input type='text' name='resp' id='resp' placeholder='Buscar Responsable' class='search_init'  style='width:160px;' /></th><th><input type='text' name='fv' id='fv' placeholder='Buscar Fecha de Vencimiento' class='search_init'  style='width:160px;' /></th><th><input type='text' name='ft' id='ft' placeholder='Buscar Fecha de Inicio de Tramite' class='search_init'  style='width:160px;' /></th></tr></tfoot>  </table>";

}     

   


?>