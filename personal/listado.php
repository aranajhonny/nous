<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 31;
$_SESSION['acc']['form'] = 69;
include("../complementos/permisos.php");

Auditoria("Accedio Al Modulo de Personal Listado",0);

unset($_SESSION['personal']);

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
<li><a href="#">Personal</a></li>
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


<div class="header">Listado de Personal
<a class="headerclose"><i class="fa fa-times pull-right"></i></a> 
<a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> 
<a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a>
</div>
<table class="table table-striped table-bordered" id="managed-table">
	<thead>
	<tr>
		<th>Acciones</th>
		<th>C.I.</th>
		<th>Nombre</th>
        <th>Cargo</th>
		<th>Télefono</th>
        <th>Área</th>
        <th>Zona Geografica</th>
        <th>Tipo de Unidad</th>
	</tr>
	</thead>
	<tbody>
<?php 
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

$rs = pg_query($link, filtrar_sql("select id_personal, ci, nombre, rif, razon_social, cargos.descripcion, personal.telefono, id_area, id_zona, id_confunid from personal, clientes, cargos where personal.id_cargo = cargos.id_cargo and personal.id_cliente = clientes.id_cliente and ( personal.id_cliente = $c or $c = -1 ) order by ci desc ")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){ 

if($r[7]==0){ $area = "- -"; 
} else if($r[7]==-1){ $area="TODAS LAS AREAS"; 
} else { 
$qs = pg_query($link, filtrar_sql("select descripcion from areas where id_area = ".$r[7]));
$qs = pg_fetch_array($qs); $area = $qs[0]; }

if($r[8]==0){ $zona = "- -"; 
} else if($r[8]==-1){ $zona="TODAS LAS ZONAS GEOGRAFICAS"; 
} else { 
$qs = pg_query($link, filtrar_sql("select nombre from zongeo where id_zongeo = ".$r[8]));
$qs = pg_fetch_array($qs); $zona = $qs[0]; }

if($r[9]==0){ $tipo = "- -"; 
} else if($r[9]==-1){ $tipo="TODOS LOS TIPO DE UNIDAD"; 
} else { 
$qs = pg_query($link, filtrar_sql("select codigo_principal from confunid where id_confunid = ".$r[9]));
$qs = pg_fetch_array($qs); $tipo = $qs[0]; } ?>    
<tr>
<td><div class=" info-tooltip">
<?php if ( in_array(334,$_SESSION['acl']) ){ ?>
<img src="../img/search.png" width="15" height="15"  title="Ver datos del Personal"  rel="tooltip" data-placement="right" onclick="location.href='ver.php?pers=<?php echo $r[0];?>'"/><?php } ?>

<?php if ( in_array(228,$_SESSION['acl']) ){ ?>
<img src="../img/pencil.png" width="15" height="15" title="Editar Datos del Personal" style="margin-left:15px;"  rel="tooltip" data-placement="right" onclick="location.href='editar.php?pers=<?php echo $r[0];?>'" /><?php } ?>

<?php if ( in_array(88,$_SESSION['acl']) ){ ?>
<img src="../img/plus.png" width="15" height="15" title="Agregar Nuevo Personal" style="margin-left:15px;"  rel="tooltip" data-placement="right" onclick="location.href='agregar.php'" /><?php } ?>
</div></td>
<td><?php echo $r[1];?></td>
<td><?php echo $r[2];?></td>
<td><?php echo $r[5];?></td>
<td><?php echo $r[6];?></td>
<td><?php echo $area; ?></td>
<td><?php echo $zona; ?></td>
<td><?php echo $tipo; ?></td>
</tr>
<?php } } ?>    
	</tbody>
    
<tfoot><tr>
<th><input type="text" name="acc" id="acc" placeholder="" class="search_init"  style="width:0px; height:0px; visibility:hidden; border:none;" /></th>
<th><input type="text" name="ci" id="ci" placeholder="Buscar Cédula de Identidad" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="nom" id="nom" placeholder="Buscar Nombre del Personal" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="cargo" id="cargo" placeholder="Buscar por Cargo" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="tlf" id="tlf" placeholder="Buscar Télefono" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="area" id="area" placeholder="Buscar Área" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="zona" id="zona" placeholder="Buscar Zona Geográfica" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="tipo" id="tipo" placeholder="Buscar Tipo de Unidad" class="search_init"  style="width:160px;" /></th>
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