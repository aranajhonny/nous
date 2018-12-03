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
</style>
        <!--[if lt IE 9]>
        <script src="../Legend/admin/assets/js/html5shiv.js"></script>
        <script src="../Legend/admin/assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body style="max-width:890px; background:#FFF;">
<ol class="breadcrumb">
<li><a href="#">Listado de Mantenimientos de la Unidad</a></li>
</ol>    

            	<div class="row">
            		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		                <div class="well">
		                	
<table class="table table-striped table-bordered" id="managed-table">
	<thead>
	<tr>
        <th>Área</th>
        <th>Zona Geografica</th>
        <th>Tipo de Unidad</th>
        <th>Unidad</th>
		<th>Plan de Mantenimiento</th>
        <th>Fecha Programada</th>
        <th>Estatus del Mantenimiento</th>
	</tr>
	</thead>
	<tbody>
<?php $rs = pg_query($link, filtrar_sql("select id_progmant, areas.descripcion, zongeo.nombre, confunid.codigo_principal, unidades.codigo_principal, planmant.descripcion, progmant.fr, progmant.estatus from progmant, planmant_unidades, planmant, unidades, areas, zongeo, confunid where planmant_unidades.id_unidad = $id and unidades.id_unidad = $id and unidades.id_confunid = confunid.id_confunid and unidades.id_area = areas.id_area and unidades.id_zona = zongeo.id_zongeo and planmant_unidades.id_unidad = unidades.id_unidad and planmant_unidades.id_planmant = planmant.id_planmant and id_planmantunid = id_planmant_unidad and progmant.fr >= '".date('Y-m-d')."' order by progmant.fr asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){  ?>    
<tr>
<td><?php echo $r[1];?></td>
<td><?php echo $r[2];?></td>
<td><?php echo $r[3];?></td>
<td><?php echo $r[4];?></td>
<td><?php echo $r[5];?></td>
<td><?php echo date1($r[6]);?></td>
<td><?php echo $r[7];?></td>
</tr>
<?php } } else { ?>

<tr><td>NO HAY MANTENIMIENTOS PARA ESTA UNIDAD</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<?php }  ?>     
	</tbody>
    
<tfoot>
		<tr>
<th><input type="text" name="area" placeholder="Buscar Areas" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="zona" placeholder="Buscar Zona Geografica" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="tunid" placeholder="Buscar Tipo de Unidad" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="cod" placeholder="Buscar Unidad" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="plan" placeholder="Buscar Plan de Mantenimiento" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="fecha" placeholder="Buscar Fecha Programada" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="est" placeholder="Buscar Estatus del Mantenimiento" class="search_init" style="width:160px;" /></th>
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