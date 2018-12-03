<?php session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");


$_SESSION['acc']['mod'] = 55;
$_SESSION['acc']['form'] = 150;
include("../complementos/permisos.php");

Auditoria("Accedio Al Modulo de Alarmas Listado",0);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="../Templates/marco.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../Legend/admin/assets/ico/apple-touch-icon-144-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../Legend/admin/assets/ico/apple-touch-icon-114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../Legend/admin/assets/ico/apple-touch-icon-72-precomposed.png" />
<link rel="apple-touch-icon-precomposed" href="../Legend/admin/assets/ico/apple-touch-icon-57-precomposed.png" />  
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<link href="../Legend/admin/assets/humane/css/jackedup.css" rel="stylesheet" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>.:: NousTrack ::.</title>
<!-- InstanceEndEditable -->

<?php 
include("../complementos/panico.php");
if(isset($_SESSION['ptc'])){ ?>
<link href="../Legend/admin/assets/vex/css/vex.css" rel="stylesheet" />
<link href="../Legend/admin/assets/vex/css/vex-theme-top.css" rel="stylesheet" />
<?php } ?>

<!-- InstanceBeginEditable name="head" -->
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>";?>
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/bootstrapdatatables2/dataTables.bootstrap.css" rel="stylesheet" />
<style>
table.table thead tr th,
table.table tbody tr td,
table.table tfoot tr th { min-width:220px; }

table.table thead tr .extra,
table.table tbody tr .extra,
table.table tfoot tr .extra { min-width:160px; }
</style>
<!-- InstanceEndEditable -->
</head>
<body>
<?php echo $_SESSION['miss'][4]; ?>          
<div class="overlay"></div>
<div class="controlshint" ><img src="../img/swipe.png" alt="Menu Help" /></div>
<section class="wrap">
<div class="container">
<img src="../img/logo.png" height="67" width="454" onclick="location.href='../inicio/principal.php'" /><br/>
<!-- InstanceBeginEditable name="panelsession" -->
<ol class="breadcrumb">
<li><a href="#">Actividades</a></li>
<li><a href="#">Alarmas</a></li>
<li><a href="#">Listado</a></li>
<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
<li class="pull-right"><?php echo date('d/m/Y');?></li>
<li class="pull-right"><a href="" class="text-muted"><?php echo $_SESSION['miss'][6];?></a></li>
</ol>
<!-- InstanceEndEditable -->
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">
<!-- InstanceBeginEditable name="formulario" -->


<div class="header">Listado de Alarmas
<a class="headerclose"><i class="fa fa-times pull-right"></i></a> 
<a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> 
<a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a>
</div>
<table class="table table-striped table-bordered" id="managed-table">
<thead>
	<tr>
		<!-- <th class="extra">Cliente</th> -->
        <th class="extra">Área</th>
        <th class="extra">Zona Geografica</th>
        <th class="extra">Tipo de Unidad</th>
        <th class="extra">Código Principal</th>
		<th>Control</th>
		<th>Fecha y Hora</th>
        <th>Valores de Referencia</th>
        <th>Valores</th>
        <th>Estatus de la Alarma</th>
        <th>Duración</th>
	</tr>
	</thead>
	<tbody>
<?php 
$a=filtrar_campo('int', 6, $_SESSION['miss'][0]);
$z=filtrar_campo('int', 6, $_SESSION['miss'][1]);
$t=filtrar_campo('int', 6, $_SESSION['miss'][2]);
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

$rs = pg_query($link, filtrar_sql("select id_alarma, alarmas.darea, alarmas.dzona, alarmas.dconfunid, unidades.codigo_principal, dcontrol, fecha_evento, min, max, crimin, crimax, dato, estatus.nombre, duracion_min from alarmas, unidades, sensores, estatus where (unidades.id_cliente=$c and sensores.id_cliente=$c and alarmas.id_cliente=$c) and  ((alarmas.id_area = $a or $a < 1) and (alarmas.id_zona = $z or $z < 1) and (alarmas.id_confunid = $t or $t < 1)) and alarmas.id_unidad = unidades.id_unidad and alarmas.id_sensor = sensores.id_sensor and unidades.id_unidad = sensores.id_unidad and alarmas.id_estatus = estatus.id_estatu order by fecha_evento desc limit 1000 ")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){  ?>    
<tr>
<td class="extra"><?php echo $r[1];?></td>
<td class="extra"><?php echo $r[2];?></td>
<td class="extra"><?php echo $r[3];?></td>
<td class="extra"><?php echo $r[4];?></td>
<td><?php echo $r[5];?></td>
<td><?php echo date3($r[6]);?></td>
<td><?php echo (1*$r[7])."-".(1*$r[8])." y ".(1*$r[9])."-".(1*$r[10]);?></td>
<td><?php echo (1*$r[11]);?></td>
<td><?php echo $r[12];?></td>
<td><?php echo $r[13]." Min";?></td>
</tr>
<?php } } else { ?>
<tr>
<td class="extra">Sin Alarmas</td>
<td class="extra"></td>
<td class="extra"></td>
<td class="extra"></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
<?php } ?>   
	</tbody>
    
<tfoot>
		<tr>
<th class="extra"><input type="text" name="area" id="area" placeholder="Buscar Areas" class="search_init"  style="width:160px;" /></th>
<th class="extra"><input type="text" name="zona" id="zona" placeholder="Buscar Zona Geografica" class="search_init"  style="width:160px;" /></th>
<th class="extra"><input type="text" name="tunid" id="tunid" placeholder="Buscar Tipo de Unidad" class="search_init"  style="width:160px;" /></th>
<th class="extra"><input type="text" name="cod" id="cod" placeholder="Buscar Código Principal" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="control" placeholder="Buscar Control" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="fecha" placeholder="Buscar Fecha y Hora" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="ref" placeholder="Buscar Valores de Referencia" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="valor" placeholder="Buscar Valores" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="est" placeholder="Buscar Estatus" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="dura" placeholder="Buscar Duración" class="search_init" style="width:160px;" /></th>
		</tr>
	</tfoot>
</table>
</div></div>


<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><div class="well">
<iframe src="ultimas_alarmas.php" width="420" height="397" frameborder="0" scrolling="no"></iframe></div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><div class="well">
<iframe src="porcentaje_diarios.php" width="420" height="397" frameborder="0" scrolling="no"></iframe></div></div>
<!-- InstanceEndEditable -->
</div>
</div>
</div>
</div>
</section>
<p>&nbsp;</p> <p>&nbsp;</p>  
<script src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script type="text/javascript">
$(document).bind("mobileinit", function(){
		$.extend($.mobile, {autoInitializePage:false} );
	}
);</script>
<script src="../jquerymobile/jquery.mobile.custom.js"></script>
<script src="../Legend/admin/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="../Legend/admin/assets/js/leftmenu.js"></script>
<script src="../Legend/admin/assets/js/theme.js"></script>
<script src="../Legend/admin/assets/humane/js/humane.min.js"></script> 
<script> 
function mensaje(texto, tipo) { 
		 var notify = 0;
		 if(tipo==1){ 
		 notify = humane.create({
             timeout: 6000,
             baseCls: 'humane-jackedup',
             addnCls: 'humane-jackedup-error'
         });
		 } else if(tipo==2){ 
		 notify = humane.create({
             timeout: 6000,
             baseCls: 'humane-jackedup',
			 addnCls: 'humane-jackedup-info'
         });
		 } else if(tipo==3){ 
		 notify = humane.create({
             timeout: 6000,
             baseCls: 'humane-jackedup',
			 addnCls: 'humane-jackedup-success'
         }); 
		 } 
         notify.log(''+texto);
}</script>
<!-- InstanceBeginEditable name="lib" -->
<script src="../Legend/admin/assets/bootstrapdatatables2/jquery.dataTables.min.js"></script>
<script src="../Legend/admin/assets/bootstrapdatatables2/ZeroClipboard.js"></script>
<script src="../Legend/admin/assets/bootstrapdatatables2/dataTables.tableTools.min.js"></script>
<script src="../Legend/admin/assets/bootstrapdatatables2/dataTables.bootstrap.min.js"></script>
<script>
var InitiateSearchableDataTable = function () {
    return {
        init: function () {
            var oTable = $('#managed-table').dataTable({
                "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
				"scrollX": true,
                "aaSorting": [[1, 'asc']],
                "aLengthMenu": [
                   [5, 10, 20, 50, 100, -1],
                   [5, 10, 20, 50, 100, "All"]
                ],
                "iDisplayLength": 10,
				"oTableTools": {
                    "aButtons": [
{ "sExtends": "copy","mColumns": [0,1,2,3,4,5,6,7,8,9] },
{ "sExtends": "csv", "mColumns": [0,1,2,3,4,5,6,7,8,9] },
{ "sExtends": "pdf", "mColumns": [0,1,2,3,4,5,6,7,8,9] },
{ "sExtends": "xls", "mColumns": [0,1,2,3,4,5,6,7,8,9] }
                    ],
                    "sSwfPath": "../Legend/admin/assets/swf/copy_csv_xls_pdf.swf"
                },
                "language": {
                    "search": "",
                    "sLengthMenu": "_MENU_",
                    "oPaginate": {
                        "sPrevious": "Sig.", "sNext": "Ant."
                    }
                }
            });

            $("tfoot input").keyup(function () {
                /* Filter on the column (the index) of this element */
                oTable.fnFilter(this.value, $("tfoot input").index(this));
            });

        }
    }
}();
 InitiateSearchableDataTable.init();

$('.selectpicker').selectBoxIt();
$('[rel=tooltip]').tooltip();</script>
<!-- InstanceEndEditable -->

<?php 
if(isset($_SESSION['mensaje1'])){ 
	echo "<script>mensaje('".$_SESSION['mensaje1']."',1);</script>"; 
	unset($_SESSION['mensaje1']);
}

if(isset($_SESSION['mensaje2'])){ 
	echo "<script>mensaje('".$_SESSION['mensaje2']."',2);</script>"; 
	unset($_SESSION['mensaje2']);
}

if(isset($_SESSION['mensaje3'])){ 
	echo "<script>mensaje('".$_SESSION['mensaje3']."',3);</script>"; 
	unset($_SESSION['mensaje3']);
} ?>

<?php if(isset($_SESSION['ptc'])){ ?>
<script src="../Legend/admin/assets/vex/js/vex.js"></script>
<script src="../Legend/admin/assets/vex/js/vex.dialog.js"></script>
<script>
function mostrar_ptc() {
         vex.defaultOptions.className = 'vex-theme-top';
         vex.dialog.open({
message: 'Panico Activado para La Unidad <?php echo $_SESSION['ptc']['unid'];?>',
             buttons: [
                 $.extend({}, vex.dialog.buttons.YES, {
                     text: 'Atender',
click: function(){ location.href='../panico/atender.php?pan=<?php echo $_SESSION['ptc']['id'];?>'; }
                 }),
                 $.extend({}, vex.dialog.buttons.NO, { text: 'Ignorar' })
             ]
         });
}
setTimeout('mostrar_ptc();',5);
</script>
<?php unset($_SESSION['ptc']); } ?>

<?php include("../complementos/closdb.php"); ?>
</body>
<!-- InstanceEnd --></html>