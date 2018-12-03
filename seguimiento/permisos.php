<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");



$_SESSION['acc']['mod'] = 41;
$_SESSION['acc']['form'] = 148;
include("../complementos/permisos.php");



if(isset($_REQUEST['id'])==false){ 
	Auditoria("En Seguimiento de Unidad Especifico Acceso Invalido Archivo Mantenimientos",0);
	header("location: vacio.php");
	exit();
} else { 
	$id = filtrar_campo('int', 6, $_REQUEST['id']);

?><!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../Legend/admin/assets/ico/apple-touch-icon-144-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../Legend/admin/assets/ico/apple-touch-icon-114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../Legend/admin/assets/ico/apple-touch-icon-72-precomposed.png" />
<link rel="apple-touch-icon-precomposed" href="../Legend/admin/assets/ico/apple-touch-icon-57-precomposed.png" />  
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<title>.:: NousTrack ::.</title>
<link href="../Legend/admin/assets/humane/css/jackedup.css" rel="stylesheet" />
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'>"; ?>        
<link href="../Legend/admin/assets/bootstrapdatatables/css/DT_bootstrap.css" rel="stylesheet" />
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'> "; ?>
<style>
div.dataTables_scrollBody, 
div.dataTables_scrollHead, 
div.dataTables_scrollFoot { max-width:920px; }

table.table thead tr th,
table.table tbody tr th,
table.table tfoot tr th { min-width:180px; }

table.table thead tr .extra,
table.table tbody tr .extra,
table.table tfoot tr .extra { min-width:60px; }
</style>
        <!--[if lt IE 9]>
        <script src="../Legend/admin/assets/js/html5shiv.js"></script>
        <script src="../Legend/admin/assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body style="max-width:890px; background:#FFF;">
<ol class="breadcrumb">
<li><a href="#">Listado de Permisos de la Unidad</a></li>
</ol>    
    
            	<div class="row">
            		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		                <div class="well">
		                	
<table class="table table-striped table-bordered" id="managed-table">
	<thead>
	<tr>
		<th>Estatus</th>
        <th>Tipo de Permiso</th>
        <th>Serial</th>
        <th>Unidad</th>
		<th>Responsable</th>
		<th>Fecha Venc.</th>
        <th>Fecha Inicio Tram.</th>
	</tr>
	</thead>
	<tbody>
<?php $rs = pg_query($link, filtrar_sql("select permisos.id_permiso, tipo_permisos.nombre, confunid.codigo_principal, unidades.codigo_principal, ci, personal.nombre, fecha_vencimiento, dias_gestion, permisos.id_estatus, serial from permisos, personal, unidades, confunid, tipo_permisos where permisos.id_unidad = $id and unidades.id_unidad = ".$_REQUEST['id']." and permisos.id_tipo_permiso = tipo_permisos.id_tipo_permiso and confunid.id_confunid = unidades.id_confunid and id_personal = id_responsable_especifico and unidades.id_unidad = permisos.id_unidad order by serial desc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ 
while($r = pg_fetch_array($rs)){ 
$qs=pg_query("select date '".$r[6]."' - interval '".$r[7]." day' as fecha");  $qs = pg_fetch_array($qs);
if($r[8]==9){ $dir="../img/vigente.jpg"; $est="VIGENTE";
} else if($r[8]==10){ $dir="../img/tramitando.jpg"; $est="TRAMITANDO";
} else if($r[8]==11){ $dir="../img/vencido.jpg"; $est="VENCIDO";
}?>    
<tr>
<td><div class=" info-tooltip">
<img src="<?php echo $dir;?>" width="25" height="45" rel="tooltip" title="<?php echo $est;?>" /> <?php echo $est;?>
</div></td>
<td><?php echo $r[1];?></td>
<td><?php echo $r[9];?></td>
<td><?php echo $r[2]." - ".$r[3];?></td>
<td><?php echo $r[4]." ".$r[5];?></td>
<td><?php echo date1($r[6]);?></td>
<td><?php echo date1($qs[0]);?></td>
</tr>
<?php } }  else { ?>

<tr><td>NO HAY PERMISOS PARA ESTA UNIDAD</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<?php }  ?>   
	</tbody>
    
<tfoot><tr>
<th><input type="text" name="est" id="est" placeholder="Buscar Estatus" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="tipo" id="tipo" placeholder="Buscar Tipo de Permiso" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="serial" id="serial" placeholder="Buscar Serial" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="unid" id="unid" placeholder="Buscar Unidad" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="resp" id="resp" placeholder="Buscar Responsable" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="fv" id="fv" placeholder="Buscar Fecha de Vencimiento" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="ft" id="ft" placeholder="Buscar Fecha de Inicio de Tramite" class="search_init"  style="width:160px;" /></th>
</tr></tfoot>  

</table>
		</div>                
	</div>                         
</div>

<script src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script type="text/javascript">$(document).bind("mobileinit", function(){$.extend(  $.mobile , {autoInitializePage: false})});</script>
<script src="../jquerymobile/jquery.mobile.custom.js"></script>
<script src="../Legend/admin/assets/bootstrap/js/bootstrap.min.js"></script>

<script src="../Legend/admin/assets/bootstrapdatatables/js/jquery.dataTables.js"></script>
<script src="../Legend/admin/assets/bootstrapdatatables/js/DT_bootstrap.js"></script>
<script>
var asInitVals = new Array();
var lastIdx = null;

$(document).ready(function() {
	
    var oTable = $('#managed-table').dataTable( {
		"sScrollX": "100%",
		"sScrollXInner": "110%",
		"bScrollCollapse": true
    } );
     
    $("tfoot input").keyup( function () {
        oTable.fnFilter( this.value, $("tfoot input").index(this) );
    } );

    $("tfoot input").each( function (i) {
        asInitVals[i] = this.value;
    } );
     
    $("tfoot input").focus( function () {
        if ( this.className == "search_init" )
        {
            this.className = "";
            this.value = "";
        }
    } );
     
    $("tfoot input").blur( function (i) {
        if ( this.value == "" )
        {
            this.className = "search_init";
            this.value = asInitVals[$("tfoot input").index(this)];
        }
    } );

} );

$('.selectpicker').selectBoxIt();</script>
<script>$('[rel=tooltip]').tooltip();</script>
<script src="../Legend/admin/assets/js/theme.js"></script>
<?php include("../complementos/closdb.php"); ?>
</body>
</html>
<?php } ?>