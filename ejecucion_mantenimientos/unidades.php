<?php 
session_start();
include("../complementos/condb.php");
include("../complementos/util.php");

unset($_SESSION['progmant']);

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
    <body style="max-width:915px;">
            	<div class="row">
            		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		                <div class="well">
		                	
<table class="table table-striped table-bordered" id="managed-table">
	<thead>
	<tr>
        <th class="extra">Acciones</th>
        <th>Área</th>
        <th>Zona Geografica</th>
        <th>Tipo de Unidad</th>
        <th>Código Principal</th>
		<th>Caracteristica 1°</th>
		<th>Caracteristica 2°</th>
        <th>Caracteristica 3°</th>
        <th>Caracteristica 4°</th>
	</tr>
	</thead>
<tbody>
<?php $rs = pg_query("select id_unidad, unidades.codigo_principal, n_configuracion1, n_configuracion2, n_configuracion3, n_configuracion4, confunid.codigo_principal, rif, clientes.razon_social, areas.descripcion, zongeo.nombre from unidades, confunid, clientes, areas, zongeo where unidades.id_confunid = confunid.id_confunid and unidades.id_cliente = clientes.id_cliente and areas.id_cliente = clientes.id_cliente and unidades.id_area = areas.id_area and unidades.id_zona = zongeo.id_zongeo order by rif, clientes.razon_social, confunid.nombre, unidades.codigo_principal asc"); 
$r = pg_num_rows($rs);
if($r==false || $r<1){ ?>
<tr><th class="extra"></th><th>No Hay Unidades</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>

<?php } else { while($r = pg_fetch_array($rs)){  ?>    
<tr>
<td class="extra"><div class=" info-tooltip" >
<img src="../img/search.png" width="15" height="15" title="Seleccionar Unidad" rel="tooltip" data-placement="right"onclick="location.href='ver_unidad2.php?id=<?php echo $r[0];?>';"/>
</div></td>
<td><?php echo $r[9];?></td>
<td><?php echo $r[10];?></td>
<td><?php echo $r[6];?></td>
<td><?php echo $r[1];?></td>
<td><?php echo $r[2];?></td>
<td><?php echo $r[3];?></td>
<td><?php echo $r[4];?></td>
<td><?php echo $r[5];?></td>
</tr>
<?php } } ?>   
</tbody>
    
<tfoot>
	<tr>
<th class="extra"><input type="text" name="accion" class="search_init"  style="width:0px; height:0px; border:none;" /></th>
<th><input type="text" name="area" placeholder="Buscar Areas" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="zona" placeholder="Buscar Zona Geografica" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="tunid" placeholder="Buscar Tipo de Unidad" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="cod" placeholder="Buscar Código Principal" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="conf1" placeholder="Buscar Caracteristica 1°" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="conf2" placeholder="Buscar Caracteristica 2°" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="conf3" placeholder="Buscar Caracteristica 3°" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="conf4" placeholder="Buscar Caracteristica 4°" class="search_init" style="width:160px;" /></th>
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