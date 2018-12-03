<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 23;
$_SESSION['acc']['form'] = 134;
include("../complementos/permisos.php");




if(isset($_REQUEST['sensor2'])){ 
	$sensor = filtrar_campo('int', 6, $_REQUEST['sensor2']);
	$est = filtrar_campo('bool', 5, $_REQUEST['est']);
	
	if( pg_query($link, filtrar_sql("update sensores set act_alarma = $est where id_sensor = $sensor")) ){ 
		$_SESSION['mensaje3']="Alarmas del Sensor Desactivada";
		Auditoria("Alarmas Desactivadas para El Sensor ",$sensor);
	} else { 
		Auditoria("Problema al desactivar alarmas del sensor ", $sensor);
		$_SESSION['mensaje1']="No se logro desactivar las alarmas del sensor";
	}
	
} else if(isset($_REQUEST['sensor'])){ 
	$sensor = filtrar_campo('int', 6, $_REQUEST['sensor']);
	$est = filtrar_campo('bool', 5, $_REQUEST['est']);
	
	if( pg_query($link, filtrar_sql("update sensores set act_alarma = $est where id_sensor = $sensor")) ){ 
		$_SESSION['mensaje3']="Alarmas del Sensor Activada";
		Auditoria("Alarmas Activadas para El Sensor ",$sensor);
	} else { 
		Auditoria("Problema al activar alarmas del sensor ", $sensor);
		$_SESSION['mensaje1']="No se logro Activar las alarmas del sensor";
	}
	
} else { 
	Auditoria("Accedio Al Modulo Sensor - Control: $serial",0);
}


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
<link href="../Legend/admin/assets/vex/css/vex.css" rel="stylesheet" />
<link href="../Legend/admin/assets/vex/css/vex-theme-top.css" rel="stylesheet" />
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/bootstrapdatatables2/dataTables.bootstrap.css" rel="stylesheet" />
<style>
table.table thead tr th,
table.table tbody tr td,
table.table tfoot tr th { min-width:220px; }

table.table thead tr .extra,
table.table tbody tr .extra,
table.table tfoot tr .extra { max-width:140px; }
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
<li><a href="#">Configuración</a></li>
<li><a href="#">Dispositivos</a></li>
<li><a href="#">Sensores</a></li>
<li><a href="#">Sensores - Controles</a></li>
<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
<li class="pull-right"><?php echo date('d/m/Y');?></li>
<li class="pull-right"><a href="" class="text-muted"><?php echo $_SESSION['miss'][6];?></a></li>
</ol>
<!-- InstanceEndEditable -->
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">
<!-- InstanceBeginEditable name="formulario" -->


<div class="header">Sensores - Controles
<a class="headerclose"><i class="fa fa-times pull-right"></i></a> 
<a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> 
<a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a>
</div>
<table class="table table-striped table-bordered" id="managed-table">
<thead>
  <tr>
    <th class='extra'>Acciones</th>
    <th>Unidad</th>
    <th>Sensor</th>
    <th>Control</th>
  </tr>
</thead>
	<tbody>
<?php $i=0;

$rs = pg_query($link, filtrar_sql("select sensores.id_sensor, sensores.serial, tipo_sensores.descripcion, dcontrol, dconfunid, unidades.codigo_principal, act_alarma, unidades.id_unidad from sensores, tipo_sensores, unidades where tipo_sensores.id_tipo_sensor = sensores.id_tipo_sensor and unidades.id_unidad = sensores.id_unidad order by dconfunid, unidades.codigo_principal, sensores.serial asc ")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){ ?>    
<tr>
<td class='extra'><div class=" info-tooltip">

<img src="../img/pencil.png" width="15" height="15" title="Cambiar El Control" rel="tooltip" data-placement="right" onclick="location.href='cambio.php<?php echo "?sensor=".$r[0];?>'" style="margin-right:15px;" />

<?php if($r[6]=='t'){ ?>
<img src="../img/cambios.png" width="22" height="22"  title="Desactivar Notificaciones"  rel="tooltip" data-placement="right" onclick="pregunta2(<?php echo $r[0];?>);" />
<?php } else { ?>
<img src="../img/cambios2.png" width="22" height="22"  title="Activar Notificaciones"  rel="tooltip" data-placement="right" onclick="pregunta(<?php echo $r[0];?>);" />
<?php } ?>

</div></td>
<td><?php echo $r[4]." ".$r[5];?></td>
<td><?php echo $r[2]." ".$r[1];?></td>
<td><?php echo $r[3];?></td>
</tr>
<?php $i++; } } ?>    
	</tbody>
    
<tfoot><tr>
<th class='extra'><input type='text' name='acc' id='acc' value='' style='width:0px; height:0px; border:none;' /></th>
<th><input type="text" name="cod" id="cod" placeholder="Buscar Unidad" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="sens" id="sens" placeholder="Buscar Sensor" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="cont" id="cont" placeholder="Buscar Control del Sensor" class="search_init"  style="width:160px;" /></th>
</tr></tfoot>  
</table>

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
{ "sExtends": "copy","mColumns": [1,2,3,4,5,6,7] },
{ "sExtends": "csv", "mColumns": [1,2,3,4,5,6,7] },
{ "sExtends": "pdf", "mColumns": [1,2,3,4,5,6,7] },
{ "sExtends": "xls", "mColumns": [1,2,3,4,5,6,7] }
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

$('.selectpicker').selectBoxIt();</script>
<script>$('[rel=tooltip]').tooltip();</script>


<script src="../Legend/admin/assets/vex/js/vex.js"></script>
<script src="../Legend/admin/assets/vex/js/vex.dialog.js"></script>
<script>
function pregunta(sensor) {
         vex.defaultOptions.className = 'vex-theme-top';
         vex.dialog.open({
             message: '¿ Desea Activar Las Alarmas del Sensor ? ',
             buttons: [
                 $.extend({}, vex.dialog.buttons.YES, {
                     text: 'SI',
					 click: function(){ 
					 	location.href='sensor_control.php?sensor='+sensor+'&est=TRUE'; 
					 }
                 }),
                 $.extend({}, vex.dialog.buttons.NO, {
                     text: 'NO'
                 })
             ]
         });
     }
</script>

<script>
function pregunta2(sensor) {
         vex.defaultOptions.className = 'vex-theme-top';
         vex.dialog.open({
             message: '¿ Desea Desactivar Las Alarmas del Sensor ? ',
             buttons: [
                 $.extend({}, vex.dialog.buttons.YES, {
                     text: 'SI',
					 click: function(){ 
					 	location.href='sensor_control.php?sensor2='+sensor+'&est=FALSE'; 
					 }
                 }),
                 $.extend({}, vex.dialog.buttons.NO, {
                     text: 'NO'
                 })
             ]
         });
     }
</script>



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