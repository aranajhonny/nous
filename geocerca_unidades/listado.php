<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");


$_SESSION['acc']['mod'] = 64;
$_SESSION['acc']['form'] = 168;
include("../complementos/permisos.php");


if(isset($_REQUEST['rem'])){ 
	$unid = filtrar_campo('int', 6, $_REQUEST['unid']); 
	$geo = filtrar_campo('int', 6, $_REQUEST['geo']);
	
	if(pg_query($link, filtrar_sql("delete from geounid where id_unidad = $unid and id_geocerca = $geo"))){ 
		$_SESSION['mensaje3']="Geocerca Removida de la Unidad";
		Auditoria("Geocerca Removida de la Unidad ",$unid);
		
	} else { 
		$_SESSION['mensaje1']="No se logro remover la geocerca de la unidad";
Auditoria("NO se logro remover la geocerca de la unidad Error: ".pg_last_error($link),$unid);
		
	}

} else if(isset($_REQUEST['act'])){
	$unid = filtrar_campo('int', 6, $_REQUEST['unid']); 
	$geo = filtrar_campo('int', 6, $_REQUEST['geo']);
	
	if(pg_query($link, filtrar_sql("update geounid set alarma = TRUE where id_unidad = $unid and id_geocerca = $geo"))){ 
		$_SESSION['mensaje3']="Alarma Activada";
		Auditoria("Alarma Activada ",$unid);
		
	} else { 
		$_SESSION['mensaje1']="No se logro Activar las Alarmas";
		Auditoria("No se logro Activar las Alarmas Error: ".pg_last_error($link),$unid);
		
	}
	
} else if(isset($_REQUEST['des'])){
	$unid = filtrar_campo('int', 6, $_REQUEST['unid']); 
	$geo = filtrar_campo('int', 6, $_REQUEST['geo']);
	
	if(pg_query($link, filtrar_sql("update geounid set alarma = FALSE where id_unidad = $unid and id_geocerca = $geo"))){ 
		$_SESSION['mensaje3']="Alarmas Desactivada";
		Auditoria("Alarmas Desactivada ",$unid);
		
	} else { 
		$_SESSION['mensaje1']="No se logro Desactivar las Alarmas";
		Auditoria("No se logro Desactivar las Alarmas Error: ".pg_last_error($link),$unid);
		
	}
	
}




Auditoria("Accedio Al Modulo de Geocerca - Unidades Listado",0);

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
<li><a href="#">Controles</a></li>
<li><a href="#">Geocerca - Unidades</a></li>
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


<div class="header">Listado de Geocerca - Unidades
<a class="headerclose"><i class="fa fa-times pull-right"></i></a> 
<a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> 
<a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a>
</div>

<table class="table table-striped table-bordered" id="managed-table">
	<thead>
	<tr>
        <th>Acciones</th>
        <th>Unidad</th>
		<th>Geocerca</th>
    	<th>Área</th>
        <th>Zona Geográfica</th>
	</tr>
	</thead>
	<tbody>
<?php 
$a=filtrar_campo('int', 6, $_SESSION['miss'][0]);
$z=filtrar_campo('int', 6, $_SESSION['miss'][1]);
$t=filtrar_campo('int', 6, $_SESSION['miss'][2]);
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

$rs = pg_query($link, filtrar_sql("select unidades.id_unidad, unidades.codigo_principal, confunid.codigo_principal, areas.descripcion, zongeo.nombre, ult_posicion, nom, geocercas.id_geocerca, alarma 
from unidades, confunid, areas, zongeo, geounid, geocercas 
where geocercas.id_geocerca = geounid.id_geocerca and unidades.id_unidad = geounid.id_unidad and unidades.id_confunid = confunid.id_confunid and areas.id_area = unidades.id_area and zongeo.id_zongeo = unidades.id_zona
order by confunid.nombre, unidades.codigo_principal asc")); 
//old
// $rs = pg_query($link, filtrar_sql("select unidades.id_unidad, unidades.codigo_principal, confunid.codigo_principal, areas.descripcion, zongeo.nombre, ult_posicion, nom, geocercas.id_geocerca, alarma 
// from unidades, confunid, areas, zongeo, geounid, geocercas 
// where geocercas.id_geocerca = geounid.id_geocerca and unidades.id_unidad = geounid.id_unidad and unidades.id_confunid = confunid.id_confunid and areas.id_area = unidades.id_area and zongeo.id_zongeo = unidades.id_zona and (areas.id_cliente = $c and confunid.id_cliente = $c and unidades.id_cliente = $c and zongeo.id_cliente = $c) and ((areas.id_area = $a or $a < 1) and (zongeo.id_zongeo = $z or $z < 1) and (confunid.id_confunid = $t or $t < 1))
// order by confunid.nombre, unidades.codigo_principal asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ 
	while($r = pg_fetch_array($rs)){  if(strlen($r[5])>16) { 
?>    
<tr><td><div class=" info-tooltip" style="width:100px;">

<?php if ( in_array(439,$_SESSION['acl']) ){ ?>
<img src="../img/plus.png" width="15" height="15" title="Asignar Unidad a Geocerca" rel="tooltip" data-placement="right" onclick="location.href='agregar.php'" />
<?php } ?>

<img src="../img/remove.png" width="15" height="15" title="Remover Geocerca de la Unidad" rel="tooltip" data-placement="right" onclick=" pregunta('?rem=true&unid=<?php echo $r[0];?>&geo=<?php echo $r[7];?>');" style="margin:0px 15px 0px 15px;" />

<?php if($r[8]=='f') { ?>
<img src="../img/cambios.png" width="15" height="15" title="Activar Alarmas" rel="tooltip" data-placement="right" onclick="location.href='listado.php?act=true&unid=<?php echo $r[0];?>&geo=<?php echo $r[7];?>'" />
<?php } else { ?>
<img src="../img/cambios2.png" width="15" height="15" title="Desactivar Alarmas" rel="tooltip" data-placement="right" onclick="location.href='listado.php?des=true&unid=<?php echo $r[0];?>&geo=<?php echo $r[7];?>'" />
<?php } ?>

</div></td>
<td><?php echo $r[2]." ".$r[1];?></td>

<td><?php echo $r[6];?></td>
<td><?php echo $r[3];?></td>
<td><?php echo $r[4];?></td>
</tr>
<?php } } } ?>   
</tbody>
    
    
<tfoot><tr>
<th><input type="text" name="acc" id="acc" placeholder="" class="search_init"  style="width:0px; height:0px; visibility:hidden;border:none;" /></th>

<th><input type="text" name="cod" id="cod" placeholder="Buscar Unidad" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="conf1" id="conf1" placeholder="Buscar Geocerca" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="area" id="area" placeholder="Buscar Área" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="zona" id="zona" placeholder="Buscar Zona Geográfica" class="search_init"  style="width:160px;" /></th>

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
{ "sExtends": "copy","mColumns": [1,2,3,4,5] },
{ "sExtends": "csv", "mColumns": [1,2,3,4,5] },
{ "sExtends": "pdf", "mColumns": [1,2,3,4,5] },
{ "sExtends": "xls", "mColumns": [1,2,3,4,5] }
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
             message: '¿ Desea Remover La Geocerca de la Unidad ? ',
             buttons: [
                 $.extend({}, vex.dialog.buttons.YES, {
                     text: 'SI',
					 click: function(){ location.href='listado.php'+id; }
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