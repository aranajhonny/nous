<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 41;
$_SESSION['acc']['form'] = 148;
include("../complementos/permisos.php");


if(isset($_REQUEST['id'])==false){ 
	Auditoria("En Seguimiento de Unidad Especifico Acceso Invalido Archivo Alarmas",0);
	header("location: vacio.php");
	exit();
} else { 
	$id = filtrar_campo('int', 6, $_REQUEST['id']);
}



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
</style>
        <!--[if lt IE 9]>
        <script src="../Legend/admin/assets/js/html5shiv.js"></script>
        <script src="../Legend/admin/assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body style="max-width:890px; background:#FFF;">
<ol class="breadcrumb">
<li><a href="#">Listado de Alarmas de la Unidad</a></li>
</ol>    
            	<div class="row">
            		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		                <div class="well">
		                	
<table class="table table-striped table-bordered" id="managed-table">
	<thead>
	<tr>
		<!-- <th class="extra">Cliente</th> -->
        <th>Área</th>
        <th>Zona Geografica</th>
        <th>Tipo de Unidad</th>
        <th>Código Principal</th>
		<th>Control</th>
		<th>Fecha y Hora</th>
        <th>Valores de Referencia</th>
        <th>Valores</th>
        <th>Estatus de la Alarma</th>
        <th>Duración</th>
	</tr>
	</thead>
	<tbody>
<?php $rs = pg_query($link, filtrar_sql("select id_alarma, alarmas.darea, alarmas.dzona, alarmas.dconfunid, unidades.codigo_principal, dcontrol, fecha_evento, val_min, val_max, val_cri_min, val_cri_max, dato, estatus.nombre, duracion_min, sensores.dunidmed from alarmas, unidades, sensores, estatus where alarmas.id_unidad = ".$_REQUEST['id']." and unidades.id_unidad = ".$_REQUEST['id']." and alarmas.id_unidad = unidades.id_unidad and alarmas.id_sensor = sensores.id_sensor and unidades.id_unidad = sensores.id_unidad and alarmas.id_estatus = estatus.id_estatu order by fecha_evento desc limit 1000 ")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){   ?>    
<tr>
<td><?php echo $r[1];?></td>
<td><?php echo $r[2];?></td>
<td><?php echo $r[3];?></td>
<td><?php echo $r[4];?></td>
<td><?php echo $r[5];?></td>
<td><?php echo date3($r[6]);?></td>
<td><?php echo (1*$r[7])."$unid  / ".(1*$r[8])."$unid <br/> ".(1*$r[9])."$unid  /  ".(1*$r[10])."$unid";?></td>
<td><?php echo (1*$r[11]).$r[14];?></td>
<td><?php echo $r[12];?></td>
<td><?php echo $r[13]." Min";?></td>
</tr>
<?php } }else { ?>

<tr><td>NO HAY ALARMAS PARA ESTA UNIDAD</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<?php }  ?>      
	</tbody>
    
<tfoot>
		<tr>
<!-- <th class="extra"><input type="text" name="cli" placeholder="Buscar Clientes" class="search_init"  style="width:160px;" /></th> -->

<th><input type="text" name="area" id="area" placeholder="Buscar Areas" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="zona" id="zona" placeholder="Buscar Zona Geografica" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="tunid" id="tunid" placeholder="Buscar Tipo de Unidad" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="cod" id="cod" placeholder="Buscar Código Principal" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="control" placeholder="Buscar Control" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="fecha" placeholder="Buscar Fecha y Hora" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="ref" placeholder="Buscar Valores de Referencia" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="valor" placeholder="Buscar Valores" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="est" placeholder="Buscar Estatus" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="dura" placeholder="Buscar Duración" class="search_init" style="width:160px;" /></th>
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