<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 47;
$_SESSION['acc']['form'] = 143;
include("../complementos/permisos.php");

Auditoria("Accedio Al Modulo de Asignar Plan de Mantenimiento a Unidades Listado",0);

if(isset($_REQUEST['remove'])){  
	$id = $_REQUEST['remove'];
	
	$rs = pg_query("select descripcion, confunid.codigo_principal, unidades.codigo_principal 
from unidades, confunid, planmant, planmant_unidades where planmant_unidades.id_planmant =  planmant.id_planmant and planmant_unidades.id_unidad = unidades.id_unidad and unidades.id_confunid = confunid.id_confunid and id_planmant_unidad =");
	$rs = pg_fetch_array($rs);
	$plan = $rs[0];
	$unid = $rs[1]." ".$rs[2];
	
	$rs = pg_query("delete from planmant_unidades where id_planmant_unidad = $id");
	if($rs){ 
		$_SESSION['mensaje3']="plan de mantenimiento removido de la unidad";
		Auditoria("Plan de Mantenimiento: $plan Removido de la Unidad: $unid",$id); 
	} else { 
		$_SESSION['mensaje1']="No se logro remover el plan de mantenimiento";
	}
} 

unset($_SESSION['asigplan']);

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
<link href="../Legend/admin/assets/bootstrapdatatables/css/DT_bootstrap.css" rel="stylesheet" />
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>";?>

<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/vex/css/vex.css" rel="stylesheet" />
<link href="../Legend/admin/assets/vex/css/vex-theme-top.css" rel="stylesheet" />
<style>
div.dataTables_scrollBody, 
div.dataTables_scrollHead, 
div.dataTables_scrollFoot { max-width:920px; }

table.table thead tr th,
table.table tbody tr td,
table.table tfoot tr th { min-width:180px; }

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
<li><a href="#">Mantenimiento</a></li>
<li><a href="#">Planes de Mantenimeinto y Unidades</a></li>
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


<div class="header">Listado Planes de Mantenimiento y unidades 
<a class="headerclose"><i class="fa fa-times pull-right"></i></a> 
<a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> 
<a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a>
</div>
<table class="table table-striped table-bordered" id="managed-table">
	<thead>
	<tr>
        <th>Acciones</th>
    	<th>Área</th>
        <th>Zona Geográfica</th>
        <th>Tipo de Unidad</th>
        
		<th>Unidad</th>
		<th>Plan de Mantenimiento</th>
        
        <th>Responsable</th>
        <th>Proveedor de Servicio</th>
		
	</tr>
	</thead>
	<tbody>
<?php 
$a=filtrar_campo('int', 6, $_SESSION['miss'][0]);
$z=filtrar_campo('int', 6, $_SESSION['miss'][1]);
$t=filtrar_campo('int', 6, $_SESSION['miss'][2]);
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

$rs = pg_query("select id_planmant_unidad, unidades.codigo_principal, planmant.descripcion, confunid.codigo_principal, areas.descripcion,
zongeo.nombre, ci, personal.nombre  
from planmant_unidades, unidades, planmant, confunid, areas, zongeo, personal 
where 
(unidades.id_cliente=$c and planmant.id_cliente=$c and confunid.id_cliente=$c and unidades.id_cliente=$c and areas.id_cliente=$c and zongeo.id_cliente=$c and personal.id_cliente=$c) and
((areas.id_area=$a or $a<1) and (zongeo.id_zongeo=$z or $z<1) and (confunid.id_confunid=$t or $t<1)) and  
unidades.id_area = areas.id_area and 
unidades.id_zona = zongeo.id_zongeo and 
planmant.id_responsable = personal.id_personal and 
planmant_unidades.id_unidad = unidades.id_unidad and 
planmant_unidades.id_planmant = planmant.id_planmant and 
unidades.id_confunid = confunid.id_confunid 
order by confunid.codigo_principal, unidades.codigo_principal, valor asc"); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){ ?>    
<tr><td><div class=" info-tooltip">


<img src="../img/remove.png" width="15" height="15" title="Quitar Plan de Mantenimiento de la Unidad" style="margin-left:15px;"  rel="tooltip" data-placement="right" onclick="vexalerts(<?php echo $r[0];?>)" />

<?php if ( in_array(423,$_SESSION['acl']) ){ ?>
<img src="../img/plus.png" width="15" height="15" title="Asignar Plan de Mantenimiento a la Unidad" style="margin-left:15px;"  rel="tooltip" data-placement="right" onclick="location.href='agregar.php'" /><?php } ?>

</div></td>
<td><?php echo $r[4];?></td>
<td><?php echo $r[5];?></td>
<td><?php echo $r[3];?></td>
<td><?php echo $r[1];?></td>
<td><?php echo $r[2];?></td>
<td><?php echo $r[6]." ".$r[7];?></td>
<td><?php echo "- -";?></td>
</tr>
<?php } } ?>    
	</tbody>

<tfoot>
		<tr>
<th><input type="text" name="acc" id="acc" placeholder="" class="search_init"  style="width:0px; height:0px; visibility:hidden;border:none;" /></th>
<th><input type="text" name="prov" placeholder="Buscar Proveedor de Servicio" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="area" placeholder="Buscar Areas" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="zona" placeholder="Buscar Zona Geografica" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="tunid" placeholder="Buscar Tipo de Unidad" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="unid" placeholder="Buscar Unidad" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="plan" placeholder="Buscar Plan de Mantenimiento" class="search_init" style="width:160px;" /></th>
<th><input type="text" name="resp" placeholder="Buscar Responsable" class="search_init" style="width:160px;" /></th>

		</tr>
	</tfoot> 
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
<script src="../Legend/admin/assets/bootstrapdatatables/js/jquery.dataTables.js"></script>
<script src="../Legend/admin/assets/bootstrapdatatables/js/DT_bootstrap.js"></script><script>
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


$('.selectpicker').selectBoxIt();
$('[rel=tooltip]').tooltip();
</script>

<script src="../Legend/admin/assets/vex/js/vex.js"></script>
<script src="../Legend/admin/assets/vex/js/vex.dialog.js"></script>
<script>

function vexalerts(id) { 
         vex.defaultOptions.className = 'vex-theme-top';
         vex.dialog.open({
             message: '¿ Desea Remover El Plan de Mantenimiento de la Unidad ?',
             buttons: [
                 $.extend({}, vex.dialog.buttons.YES, {
                     text: 'SI',
					 click: function SI(){ location.href='listado.php?remove='+id; }
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