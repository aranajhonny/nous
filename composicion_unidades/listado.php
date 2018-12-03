<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 43;
$_SESSION['acc']['form'] = 93;
include("../complementos/permisos.php");

Auditoria("Accedio Al Modulo de Composicion de Unidades Listado",0);

unset($_SESSION['composición']);

if(isset($_REQUEST['eli'])){ 
	$id = filtrar_campo('int',6,$_REQUEST['eli']);
	
	pg_query($link, filtrar_sql("update composiciones set id_dependencia = 0 where id_dependencia = $id "));
	$rs = pg_query($link, filtrar_sql("delete from composiciones where id_composicion=$id"));
	
	if($rs){ 
		Auditoria("ID = $id - Composición Eliminada",$id); 
		$_SESSION['mensaje3']="Composición Eliminada";
	} else { 
		$_SESSION['mensaje1']="No se logro eliminar la composición";
		Auditoria("Problema al eliminar la Composicion de Unidad Error: ".pg_last_error($link),$id);
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
table.table tbody tr td,
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
<li><a href="#">Unidades</a></li>
<li><a href="#">Composiciones</a></li>
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


<div class="header">Listado de Composiciones 
<a class="headerclose"><i class="fa fa-times pull-right"></i></a> 
<a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> 
<a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a>
</div>
<table class="table table-striped table-bordered" id="managed-table">
	<thead>
	<tr>
    <th>Acciones</th>
	<th>Tipo de Unidad</th>
        <th>Nombre</th>
        <th>Observaciones</th>
	
	</tr>
	</thead>
	<tbody>
<?php 
include("../composiciones/composiciones_CompUnid.php");

$rs = pg_query($link, filtrar_sql("select composiciones.id_composicion, composiciones.nombre, confunid.codigo_principal, id_dependencia, confunid.id_confunid, descripcion from composiciones, confunid where composiciones.id_confunid = confunid.id_confunid and id_dependencia=0 order by confunid.id_confunid, composiciones.id_composicion asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ $i=1; $tipo=0;
while($r = pg_fetch_array($rs)){ 
if($tipo==0){ $tipo = $r[4]; } else if($tipo!=$r[4]){ $i=1; $tipo = $r[4]; }

$qs=pg_query($link, filtrar_sql("select count(id_composicion) from composiciones where id_dependencia = ".$r[0])); $qs=pg_fetch_array($qs); 
if($qs[0]==0){?>    
<tr><td><div class=" info-tooltip">

<img src='../img/cross.png' width='15' height='15' title='Eliminar Composición' rel='tooltip' data-placement='right' onclick="pregunta(<?php echo $r[0];?>);" style="margin-right:15px;"/>

<?php if ( in_array(340,$_SESSION['acl']) ){ ?>
<img src="../img/search.png" width="15" height="15" title="Ver datos de la composición" rel="tooltip" data-placement="right" onclick="location.href='ver.php?comp=<?php echo $r[0];?>'"/>
<?php } ?>

<?php if ( in_array(234,$_SESSION['acl']) ){ ?>
<img src="../img/pencil.png" width="15" height="15" title="Editar Datos de la composición" style="margin-left:15px;"  rel="tooltip" data-placement="right" onclick="location.href='editar.php?comp=<?php echo $r[0];?>'" />
<?php } ?>

<?php if ( in_array(94,$_SESSION['acl']) ){ ?>
<img src="../img/plus.png" width="15" height="15" title="Agregar Nueva composición" style="margin-left:15px;"  rel="tooltip" data-placement="right" onclick="location.href='agregar.php'" />
</div></td>

<td><?php echo $r[2];?></td>
<td><?php echo $i.".- ".$r[1];?></td>
<td><?php echo $r[5];?></td>
</tr>
<?php } ?>

<?php } else { ?>
<tr><td><div class=" info-tooltip">

<img src='../img/cross.png' width='15' height='15' title='Eliminar Composición' rel='tooltip' data-placement='right' onclick="pregunta(<?php echo $r[0];?>);" style="margin-right:15px;"/>

<?php if ( in_array(340,$_SESSION['acl']) ){ ?>
<img src="../img/search.png" width="15" height="15" title="Ver datos de la composición" rel="tooltip" data-placement="right" onclick="location.href='ver.php?comp=<?php echo $r[0];?>'"/>
<?php } ?>

<?php if ( in_array(234,$_SESSION['acl']) ){ ?>
<img src="../img/pencil.png" width="15" height="15" title="Editar Datos de la composición" style="margin-left:15px;"  rel="tooltip" data-placement="right" onclick="location.href='editar.php?comp=<?php echo $r[0];?>'" />
<?php } ?>

<?php if ( in_array(94,$_SESSION['acl']) ){ ?>
<img src="../img/plus.png" width="15" height="15" title="Agregar Nueva composición" style="margin-left:15px;"  rel="tooltip" data-placement="right" onclick="location.href='agregar.php'" />
<?php } ?>

</div></td>
<td><?php echo $r[2];?></td>
<td><?php echo $i.".- ".$r[1];?></td>
<td><?php echo $r[5];?></td>
</tr>
<?php echo ComponerComboxCompUnid2($r[0], $i, "&emsp;"); 
} ?>

<?php $i++; } } ?>    
	</tbody>


<tfoot><tr>
<th><input type="text" name="acc" id="acc" style="border:none; background:none; width:0px; height:0px;" /></th>
<th><input type="text" name="unid" id="unid" placeholder="Buscar Tipo de Unidad" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="nom" id="nom" placeholder="Buscar Nombre" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="obs" id="obs" placeholder="Buscar Observaciones" class="search_init"  style="width:160px;" /></th>

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
<script src="../Legend/admin/assets/bootstrapdatatables2/jquery-2.0.3.min.js"></script>
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
{ "sExtends": "copy","mColumns": [1,2,3] },
{ "sExtends": "csv", "mColumns": [1,2,3] },
{ "sExtends": "pdf", "mColumns": [1,2,3] },
{ "sExtends": "xls", "mColumns": [1,2,3] }
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


<script src="../Legend/admin/assets/vex/js/vex.js"></script>
<script src="../Legend/admin/assets/vex/js/vex.dialog.js"></script>
<script>
function pregunta(id) {
         vex.defaultOptions.className = 'vex-theme-top';
         vex.dialog.open({
             message: '¿ Desea Eliminar La Composición ? ',
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