<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");


$_SESSION['acc']['mod'] = 41;
$_SESSION['acc']['form'] = 148;
include("../complementos/permisos.php");


if(isset($_REQUEST['id'])==false){ 
	Auditoria("En Seguimiento de Unidad Especifico Acceso Invalido Archivo Historial",0);
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
table.table tfoot tr .extra { width:120px; max-width:120px; }
</style>
        <!--[if lt IE 9]>
        <script src="../Legend/admin/assets/js/html5shiv.js"></script>
        <script src="../Legend/admin/assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body style="max-width:890px; background:#FFF;">
            	<div class="row">
            		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		                <div class="well">
		                	
<table class="table table-striped table-bordered" id="managed-table">
	<thead>
	<tr>
		<!-- <th class="extra">Cliente</th> -->
        <th class="extra">Estatus</th>
        <th>Fecha y Hora</th>
        <th>Descripción</th>
        <th class="extra">Valor</th>
	</tr>
	</thead>
	<tbody>
<?php 
$rs = pg_query($link, filtrar_sql("select id_control, unidmed.nombre from unimedcli, unidmed, controles where unimedcli.id_unidmed = unidmed.id_unidmed and unimedcli.id_unimedcli = controles.id_unimedcli "));
$r = pg_num_rows($rs);
if($r!=false && $r>0) { 
	while($r = pg_fetch_array($rs)){ 
		$unidmed[$r[0]] = $r[1];
	}
}


$rs = pg_query($link, filtrar_sql("select id_estatus, fecha_evento, sensores.descripcion, sensores.serial, dato, estatus.nombre, sensores.id_control from log_sensor, sensores, estatus where log_sensor.id_unidad = $id and sensores.id_unidad = $id and log_sensor.id_sensor = sensores.id_sensor and log_sensor.id_estatus = estatus.id_estatu and (sensores.nro_tsen <> 20 and sensores.nro_tsen <> 53 and sensores.nro_tsen <> 54 and  sensores.nro_tsen <> 55 ) order by fecha_evento desc limit 500")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){  

if($r[0]==22){$est="Por Debajo del Valor Crítico Mínimo"; 
} else if($r[0]==23){$est="Por Encima del Valor Crítico Máximo";
} else if($r[0]==20){$est="Por Debajo del Valor Mínimo";
} else if($r[0]==21){$est="Por Encima del Valor Máximo";
} else if($r[0]==24){$est="Estable"; } 

?>    
<tr>
<td class="extra"><?php echo $r[5];?></td>
<td><?php echo date3($r[1]);?></td>
<td><?php echo $r[2];?></td>
<td class="extra"><?php echo $r[4]." ".$unidmed[$r[6]];?></td>
</tr>
<?php } } else { ?>
<tr><td>NO HAY LECTURAS EN EL HISTORIAL PARA ESTA UNIDAD</td><td></td><td></td><td></td></tr>
<?php }  ?>   


<?php $rs = pg_query($link, filtrar_sql("select fecha_hora_gps, sensores.descripcion, sensores.serial, velocidad_gps, geo_posicion, valor_min, valor_max, valor_critico_min, valor_critico_max from log_gps, tipo_sensores, sensores where log_gps.id_unidad = ".$_REQUEST['id']." and sensores.id_unidad = ".$_REQUEST['id']." and log_gps.id_sensor = sensores.id_sensor and order by fecha_hora_gps desc  limit 500")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){  

if($r[3]<=$r[7]){$est="Por Debajo del Valor Crítico Mínimo"; 
} else if($r[3]>=$r[8]){$est="Por Encima del Valor Crítico Máximo";
} else if($r[3]<=$r[5]){$est="Por Debajo del Valor Mínimo";
} else if($r[3]>=$r[6]){$est="Por Encima del Valor Máximo";
} else { $est="Estable"; } 

?>    
<tr>
<td class="extra">Estable</td>
<td><?php echo date3($r[0]);?></td>
<td><?php echo $r[1]." Velocidad ";?></td>
<td class="extra"><?php echo $r[3]." Km/Hr";?></td>
</tr><tr>
<td class="extra"><?php echo $est;?></td>
<td><?php echo date3($r[0]);?></td>
<td><?php echo $r[1]." Geoposición ";?></td>
<td class="extra"><?php echo $r[4]." Lon/Lat";?></td></tr>
<?php } } else { ?>
<tr><td>NO HAY LECTURAS EN EL HISTORIAL PARA ESTA UNIDAD</td><td></td><td></td><td></td></tr>
<?php }  ?>   
	</tbody>
    
<tfoot>
		<tr>
<th class="extra"><input type="text" name="est" id="est" placeholder="Buscar Estatus" class="search_init"  style="width:100px;" /></th>
<th><input type="text" name="fecha" id="fecha" placeholder="Buscar Fecha" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="sens" id="sens" placeholder="Buscar Sensor" class="search_init"  style="width:160px;" /></th>
<th class="extra"><input type="text" name="valor" id="valor" placeholder="Buscar Valor" class="search_init"  style="width:160px;" /></th>

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
<script>$('[rel=tooltip]').tooltip();</script>
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
<script src="../Legend/admin/assets/js/theme.js"></script>
<?php include("../complementos/closdb.php"); ?>
</body>
</html><?php } ?>