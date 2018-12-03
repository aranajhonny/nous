<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 27;
$_SESSION['acc']['form'] = 57;
include("../complementos/permisos.php");

Auditoria("Accedio Al Modulo de Áreas Listado",0);

unset($_SESSION['area']);
unset($_SESSION['area_cliente']);

if(isset($_REQUEST['eli'])){ 
	$id = filtrar_campo('int', 6, $_REQUEST['eli']);
	pg_query($link, filtrar_sql("update areas set id_dependencia=0 where id_dependencia=$id"));
	$rs = pg_query($link, filtrar_sql("delete from areas where id_area = $id"));
	if($rs){ 
		Auditoria("ID = $id - Área Eliminada",$id); 
		$_SESSION['mensaje3']="Área Eliminada";
	} else { 
		$_SESSION['mensaje1']="No se logro eliminar el área";
		Auditoria("Problema al eliminar el Área Error: ".pg_last_error($link),$_SESSION['area']);
	}
}?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
table.table tbody tr th,
table.table tfoot tr th { min-width:380px; }
table.table thead tr .extra,
table.table tbody tr .extra,
table.table tfoot tr .extra { min-width:180px; }
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
<li><a href="#">Áreas</a></li>
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


<div class="header">Listado de Áreas
<a class="headerclose"><i class="fa fa-times pull-right"></i></a> 
<a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> 
<a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a>
</div>
<table class="table table-striped table-bordered" id="managed-table">
	<thead>
	<tr>
    	<th class='extra'>Acciones</th>
    	<th>Cliente</th>
    	<th>Decripción</th>
        <th>Responsable</th>
	</tr>
	</thead>
	<tbody>
<?php 
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

include("../composiciones/composiciones_areas.php");
$rs = pg_query($link, filtrar_sql("select areas.id_area, areas.descripcion, id_dependencia, rif, razon_social, clientes.id_cliente, areas.id_responsable from clientes, areas where areas.id_cliente = clientes.id_cliente and id_dependencia=0  and ( areas.id_cliente = $c or $c = -1 ) order by clientes.id_cliente, areas.id_area asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ $i=1; $cli=0;
while($r = pg_fetch_array($rs)){ 
if($cli==0){ $cli = $r[5]; } else if($cli!=$r[5]){ $i=1; $cli = $r[5]; }

if($r[6]==0){ $resp = " - - "; } else { 
$qs = pg_query($link, filtrar_sql("select ci, nombre from personal where id_personal = ".$r[6]));
$qs = pg_fetch_array($qs); $resp = $qs[0]." ".$qs[1]; }

$qs=pg_query($link, filtrar_sql("select count(id_area) from areas where id_dependencia = ".$r[0])); 
$qs=pg_fetch_array($qs); 
if($qs[0]==0){ ?>    
<tr>
<td class='extra'><div class=" info-tooltip">

<img src='../img/cross.png' width='15' height='15'  title='Eliminar El Área' rel='tooltip' data-placement='right' onclick="pregunta(<?php echo $r[0];?>);"/>

<?php if ( in_array(331,$_SESSION['acl']) ){ ?>
<img src="../img/search.png" width="15" height="15"  title="Ver datos del Área"  rel="tooltip" data-placement="right" onclick="location.href='ver.php?area=<?php echo $r[0];?>'"  style="margin-left:15px;"/>
<?php } ?>

<?php if ( in_array(225,$_SESSION['acl']) ){ ?>
<img src="../img/pencil.png" width="15" height="15" title="Editar Datos del Área" style="margin-left:15px;"  rel="tooltip" data-placement="right" onclick="location.href='editar.php?area=<?php echo $r[0];?>'" />
<?php } ?>

<?php if ( in_array(85,$_SESSION['acl']) ){ ?>
<img src="../img/plus.png" width="15" height="15" title="Agregar Nueva Área" style="margin-left:15px;"  rel="tooltip" data-placement="right" onclick="location.href='agregar.php'" />
<?php } ?>

</div></td>
<td><?php echo $r[3]." ".$r[4];?></td>
<td><?php echo $i.".- ".$r[1];?></td>
<td><?php echo $resp;?></td>
</tr>

<?php } else { ?>
<tr>
<td class='extra'><div class=" info-tooltip">
<img src="../img/cross.png" width="15" height="15"  title="Eliminar El Área"  rel="tooltip" data-placement="right" onclick="pregunta(<?php echo $r[0];?>);"/>

<?php if ( in_array(331,$_SESSION['acl']) ){ ?>
<img src="../img/search.png" width="15" height="15" title="Ver datos del Área" rel="tooltip" data-placement="right" onclick="location.href='ver.php?area=<?php echo $r[0];?>'" style="margin-left:15px;"/>
<?php } ?>

<?php if ( in_array(225,$_SESSION['acl']) ){ ?>
<img src="../img/pencil.png" width="15" height="15" title="Editar Datos del Área" style="margin-left:15px;"  rel="tooltip" data-placement="right" onclick="location.href='editar.php?area=<?php echo $r[0];?>'" />
<?php } ?>

<?php if ( in_array(85,$_SESSION['acl']) ){ ?>
<img src="../img/plus.png" width="15" height="15" title="Agregar Nueva Área" style="margin-left:15px;"  rel="tooltip" data-placement="right" onclick="location.href='agregar.php'" />
<?php } ?>
</div></td>
<td><?php echo $r[3]." ".$r[4];?></td>
<td><?php echo $i.".- ".$r[1];?></td>
<td><?php echo $resp;?></td>
</tr>
<?php echo ComponerComboxAreas3($r[0], $i, "&emsp;"); 
}  $i++; } } ?>    
	</tbody>
    
<tfoot><tr>
<th class="extra"><input type="text" name="acc" id="acc" style="border:none; background:none; width:0px; height:0px;" /></th>
<th class="extra"><input type="text" name="cli" id="cli" placeholder="Buscar Cliente" class="search_init"  style="width:200px;" /></th>
<th><input type="text" name="des" id="des" placeholder="Buscar Areas" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="resp" id="resp" placeholder="Buscar Responsable" class="search_init"  style="width:160px;" /></th>
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
{ "sExtends": "copy","mColumns": [1,2] },
{ "sExtends": "csv", "mColumns": [1,2] },
{ "sExtends": "pdf", "mColumns": [1,2] },
{ "sExtends": "xls", "mColumns": [1,2] }
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
function pregunta(id) {
         vex.defaultOptions.className = 'vex-theme-top';
         vex.dialog.open({
             message: '¿ Desea Eliminar El Área ? ',
             buttons: [
                 $.extend({}, vex.dialog.buttons.YES, {
                     text: 'SI',
					 click: function(){ location.href='listado.php?eli='+id; }
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