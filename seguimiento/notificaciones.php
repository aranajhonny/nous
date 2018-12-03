<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");



$_SESSION['acc']['mod'] = 41;
$_SESSION['acc']['form'] = 148;
include("../complementos/permisos.php");



if(isset($_REQUEST['id'])==false){ 
	Auditoria("En Seguimiento de Unidad Especifico Acceso Invalido Archivo Notificaciones",0);
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
table.table tfoot tr .extra { min-width:100px; }

table.table thead tr .extra3,
table.table tbody tr .extra3,
table.table tfoot tr .extra3 { min-width:250px; }

table.table thead tr .extra2,
table.table tbody tr .extra2,
table.table tfoot tr .extra2 { min-width:1000px; }
</style>
        <!--[if lt IE 9]>
        <script src="../Legend/admin/assets/js/html5shiv.js"></script>
        <script src="../Legend/admin/assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body style="max-width:890px; background:#FFF;">
<ol class="breadcrumb">
<li><a href="#">Listado de Notificaciones de la Unidad</a></li>
</ol>    
            	<div class="row">
            		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		                <div class="well">
		                	
<table class="table table-striped table-bordered" id="managed-table">
	<thead>
	<tr>
		<!-- <th class="extra">Cliente</th> -->
        <th class="extra">ID de Alarma</th>
        <th>Unidad</th>
        <th class="extra3">Responsable</th>
        <th>Fecha y Hora de Envio</th>
        <th>Fecha y Hora de Confirmación</th>
		<th>Duración Min.</th>
		<th class="extra2">Mensaje</th>
	</tr>
	</thead>
	<tbody>
<?php $rs = pg_query($link, filtrar_sql("select id_noti, notificaciones.id_alarma, alarmas.dresp, fecha_hora_enviado, fecha_hora_confirmacion, notificaciones.duracion_min, mensaje, confirmacion, unidades.codigo_principal, alarmas.dconfunid from notificaciones, alarmas, unidades where notificaciones.id_alarma = alarmas.id_alarma and alarmas.id_unidad = unidades.id_unidad and alarmas.id_unidad = $id order by fecha_hora_enviado desc limit 1000"));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){  
if($r[8]=='t'){ $dir="../img/vigente.jpg"; $est="CONFIRMADA"; } else { $dir="../img/vencido.jpg"; $est="SIN CONFIRMAR"; } ?>    
<tr>
<td class="extra"><div class=" info-tooltip"><img src="<?php echo $dir;?>" width="20" height="43" rel="tooltip" title="<?php echo $est;?>"  style="margin-right:10px;"/><?php echo $r[1];?></div></td>
<td><?php echo $r[8]." ".$r[7];?></td>
<td class="extra3"><?php echo $r[2];?></td>
<td><?php echo date3($r[3]);?></td>
<td><?php echo date3($r[4]);?></td>
<td><?php echo $r[5];?></td>
<td class="extra2"><?php echo $r[6];?></td>
</tr>
<?php } } else { ?>

<tr><td class="extra"></td><td></td><td class="extra3">NO HAY NOTIFICACIONES PARA ESTA UNIDAD</td><td></td><td></td><td></td><td class="extra2"></td></tr>
<?php }  ?>   
	</tbody>
    
<tfoot>
		<tr>
<th class="extra"><input type="text" name="area" id="area" placeholder="Buscar ID Alarma" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="unidad" id="unidad" placeholder="Buscar Unidad" class="search_init"  style="width:160px;" /></th>
<th class="extra3"><input type="text" name="zona" id="zona" placeholder="Buscar Responsable" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="tunid" id="tunid" placeholder="Buscar Fecha de Envio" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="cod" id="cod" placeholder="Buscar Fecha de Confirmación" class="search_init" style="width:160px;"/></th>
<th><input type="text" name="control" placeholder="Buscar Duración Min" class="search_init" style="width:160px;" /></th>
<th class="extra2"><input type="text" name="msj" placeholder="Buscar Mensaje" class="search_init" style="width:160px;" /></th>
		</tr>
	</tfoot> 
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