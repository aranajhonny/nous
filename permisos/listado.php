<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 37;
$_SESSION['acc']['form'] = 89;
include("../complementos/permisos.php");

Auditoria("Accedio Al Modulo permiso listado",0);

unset($_SESSION['permiso']);
unset($_SESSION['permiso_cliente']);
unset($_SESSION['permiso_tipo']);
unset($_SESSION['tmp_req']);

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
<link href="../Legend/admin/assets/bootstrapui/css/jquery-ui-1.9.2.custom.css" rel="stylesheet" />
<link href="../Legend/admin/assets/fullcalendar/css/fullcalendar.css" rel="stylesheet" />
<link href="../Legend/admin/assets/fullcalendar/css/fullcalendar.print.css" rel="stylesheet" media="print" />
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/bootstrapdatatables2/dataTables.bootstrap.css" rel="stylesheet" />
<style>
table.table thead tr th,
table.table tbody tr th,
table.table tfoot tr th { min-width:220px; }
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
<li><a href="#">Usuarios</a></li>
<li><a href="#">Permisos</a></li>
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


<div class="header">Listado de Permisos
<a class="headerclose"><i class="fa fa-times pull-right"></i></a> 
<a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> 
<a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a>
</div>
<table class="table table-striped table-bordered" id="managed-table">
	<thead>
	<tr>
    	<th class="extra">Acciones</th>
		<th class="extra">Estatus</th>
        <th>Tipo de Permiso</th>
        <th>Serial</th>
        <th>Unidad</th>
		<th>Responsable</th>
		<th>Fecha Venc.</th>
        <th>Fecha Inicio Tram.</th>
	</tr>
	</thead>
	<tbody>
<?php 
$a=filtrar_campo('int', 6, $_SESSION['miss'][0]);
$z=filtrar_campo('int', 6, $_SESSION['miss'][1]);
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

$rs = pg_query($link, filtrar_sql("select permisos.id_permiso, tipo_permisos.nombre, dconfunid, unidades.codigo_principal, ci, personal.nombre, fecha_vencimiento, dias_gestion, permisos.id_estatus, serial, confunid.nombre from permisos, personal, unidades, confunid, tipo_permisos where permisos.id_tipo_permiso = tipo_permisos.id_tipo_permiso and confunid.id_confunid = unidades.id_confunid and id_personal = id_responsable_especifico and unidades.id_unidad = permisos.id_unidad and (permisos.id_cliente=$c and unidades.id_cliente=$c and confunid.id_cliente=$c) and ((permisos.id_area=$a or $a < 1) and (permisos.id_zona=$z or $z < 1)) order by serial desc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ 
while($r = pg_fetch_array($rs)){ 

if($r[6]!=NULL){ 
$qs = pg_query($link, filtrar_sql("select date '".$r[6]."' - interval '".$r[7]." day' as fecha"));  
$qs = pg_fetch_array($qs);
$ft = date1($qs[0]);
$fv = date1($r[6]);
} else { $fv = $ft = "- -"; }

if($r[8]==9){ $dir="../img/vigente.jpg"; $est="VIGENTE";
} else if($r[8]==10){ $dir="../img/tramitando.jpg"; $est="TRAMITANDO";
} else if($r[8]==11){ $dir="../img/vencido.jpg"; $est="VENCIDO";
}?>    
<tr>
<td class="extra"><div class=" info-tooltip">
<?php if ( in_array(339,$_SESSION['acl']) ){ ?>
<img src="../img/search.png" width="15" height="15"  title="Ver datos del Permiso"  rel="tooltip" data-placement="right" onclick="location.href='ver.php?perm=<?php echo $r[0];?>'"/>
<?php } ?>
<?php if ( in_array(233,$_SESSION['acl']) ){ ?>
<img src="../img/pencil.png" width="15" height="15" title="Editar Datos del Permiso" style="margin-left:15px;"  rel="tooltip" data-placement="right" onclick="location.href='editar.php?perm=<?php echo $r[0];?>'" />
<?php } ?>
<?php if ( in_array(93,$_SESSION['acl']) ){ ?>
<img src="../img/plus.png" width="15" height="15" title="Agregar Nuevo Permiso" style="margin-left:15px;"  rel="tooltip" data-placement="right" onclick="location.href='agregar.php'" />
<?php } ?>
</div></td>
<td class="extra"><div class=" info-tooltip">
<img src="<?php echo $dir;?>" width="25" height="45" rel="tooltip" title="<?php echo $est;?>" /> <?php echo $est;?>
</div></td>
<td><?php echo $r[1];?></td>
<td><?php echo $r[9];?></td>
<td><?php echo $r[10]." <br/>".$r[2]." - ".$r[3];?></td>
<td><?php echo $r[4]." ".$r[5];?></td>
<td><?php echo $fv;?></td>
<td><?php echo $ft;?></td></tr>
<?php } } ?>    
</tbody>
    
<tfoot><tr>
<th class="extra"><input type="text" name="acc" id="acc" style="border:none; background:none; width:0px; height:0px;" /></th>
<th class="extra"><input type="text" name="est" id="est" placeholder="Buscar Estatus" class="search_init"  style="width:100px;" /></th>
<th><input type="text" name="tipo" id="tipo" placeholder="Buscar Tipo de Permiso" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="serial" id="serial" placeholder="Buscar Serial" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="unid" id="unid" placeholder="Buscar Unidad" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="resp" id="resp" placeholder="Buscar Responsable" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="fv" id="fv" placeholder="Buscar Fecha de Vencimiento" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="ft" id="ft" placeholder="Buscar Fecha de Inicio de Tramite" class="search_init"  style="width:160px;" /></th>
</tr></tfoot>  
</table>
</div>


<div class="well">
<div class="header">Calendario de Permisos<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div id='calendar'></div>
	</div>
</div>
</div>

</div>

<div class="col-xs-12">
<div class="well">
<iframe src="carga_de_trabajo.php" frameborder="0" scrolling="no" width="915" height="420" ></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="porc_permisos_estatus.php" frameborder="0" scrolling="no" width="420" height="440"></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="permisos_vencidos.php" frameborder="0" scrolling="no" width="420" height="440"></iframe>
</div></div>

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
</script>

<script src="../Legend/admin/assets/fullcalendar/js/fullcalendar.js" type="text/javascript"></script>
<script>
 function fullcalendar() {
     $('#calendar').fullCalendar({
         header: {
             left: 'prev,next today',
             center: 'title',
             right: 'month,agendaWeek,agendaDay'
         },
		 events: [<?php include("Cargar_Permisos.php"); ?>],
         editable: true,
         droppable: true, 
         drop: function (date, allDay) { 
             var originalEventObject = $(this).data('eventObject');
             var copiedEventObject = $.extend({}, originalEventObject);
             copiedEventObject.start = date;
             copiedEventObject.allDay = allDay;
             $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
             if ($('#drop-remove').is(':checked')) { $(this).remove(); }
         },
		 dayClick: function() {
        	alert('a day has been clicked!');
    	 }
     });
 }		
fullcalendar();</script>
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