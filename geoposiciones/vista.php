<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 38;
$_SESSION['acc']['form'] = 145;
include("../complementos/permisos.php");



if(isset($_REQUEST['limpiar'])){ 
	Auditoria("Accedio al Modulo de Geoposición",0);
	unset($_SESSION['seg_unidad']);
	
} else if(isset($_REQUEST['apa'])){ 
	$id = filtrar_campo('int', 6, $_REQUEST['apa']);
	$uni = filtrar_campo('int', 6, $_REQUEST['uni']);
	$duni = filtrar_campo('todo', 120, $_REQUEST['duni']);
	$rs = pg_query($link, filtrar_sql("insert into querys(id_dispositivo, orden, enviado) values ($id, 'Apagar', false)"));
	if($rs){ 
		Auditoria("Orden de Apagado Enviada - Unidad: $duni",$uni); 
		$_SESSION['mensaje3']="Orden de Apagado Enviada";
	} else { 
		$_SESSION['mensaje1']="No se logro Enviar La Orden de Apagado";
Auditoria("Problema al registrar la Orden de Apagado Error: ".pg_last_error($link),$uni);
	}
	
} else if(isset($_REQUEST['enc'])){ 
	$id = filtrar_campo('int', 6, $_REQUEST['enc']);
	$uni = filtrar_campo('int', 6, $_REQUEST['uni']);
	$duni = filtrar_campo('todo', 120, $_REQUEST['duni']);
	$rs = pg_query($link, filtrar_sql("insert into querys(id_dispositivo, orden, enviado) values ($id, 'Encender', false)"));
	if($rs){ 
		Auditoria("Orden de Encendido Enviada - Unidad: $duni",$uni); 
		$_SESSION['mensaje3']="Orden de Encendido Enviada";
	} else { 
		$_SESSION['mensaje1']="No se logro Enviar La Orden de Encendido";
Auditoria("Problema al registrar la Orden de Encendido Error: ".pg_last_error($link),$uni);
	}

} else { 
	Auditoria("Accedio al Modulo de Geoposición",0);
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/marco.dwt" codeOutsideHTMLIsLocked="false" -->
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
<link href="../Legend/admin/assets/bootstrapdatatables/css/DT_bootstrap.css" rel="stylesheet" />
<link href="../Legend/admin/assets/stepswizard/css/jquery.steps.css" rel="stylesheet"/>

<link href="../Legend/admin/assets/vex/css/vex.css" rel="stylesheet" />
<link href="../Legend/admin/assets/vex/css/vex-theme-top.css" rel="stylesheet" />

<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/bootstrapui/css/jquery-ui-1.9.2.custom.css" rel="stylesheet"/>
<style>
div.dataTables_scrollBody, 
div.dataTables_scrollHead, 
div.dataTables_scrollFoot { max-width:920px; }

table.table thead tr th,
table.table tbody tr th,
table.table tfoot tr th { min-width:180px; }

table.table thead tr .extra,
table.table tbody tr .extra,
table.table tfoot tr .extra { min-width:220px; }

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
<li><a href="#">Monitoreo</a></li>
<li><a href="#">Geoposición</a></li>
<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
<li class="pull-right"><?php echo date('d/m/Y');?></li>
<li class="pull-right"><a href="" class="text-muted"><?php echo $_SESSION['miss'][6];?></a></li>
</ol>
<!-- InstanceEndEditable -->
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">
<!-- InstanceBeginEditable name="formulario" -->					

<div class="header">Listado de Unidades
<a class="headerclose"><i class="fa fa-times pull-right"></i></a> 
<a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> 
<a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a>
</div>
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
<?php
$a=filtrar_campo('int', 6, $_SESSION['miss'][0]);
$z=filtrar_campo('int', 6, $_SESSION['miss'][1]);
$t=filtrar_campo('int', 6, $_SESSION['miss'][2]);
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

$rs = pg_query($link, filtrar_sql("select id_unidad, unidades.codigo_principal, n_configuracion1, n_configuracion2, n_configuracion3, n_configuracion4, dconfunid, rif, clientes.razon_social, darea, dzona, ult_posicion, unidades.id_dispositivo from unidades, clientes where ((unidades.id_cliente = $c and clientes.id_cliente = $c) or (unidades.id_cliente = clientes.id_cliente and $c < 1)) and ((unidades.id_area = $a or $a < 1) and (unidades.id_zona = $z or $z < 1) and (unidades.id_confunid = $t or $t < 1)) order by rif, clientes.razon_social, dconfunid, unidades.codigo_principal asc")); 
$r = pg_num_rows($rs);
if($r==false || $r<1){ ?>
<tr><td class="extra"></td><td>No Hay Unidades</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>

<?php } else { 
	while($r = pg_fetch_array($rs)){ 
		if(empty($r[11])==false){ ?>    
<tr>
<td class="extra"><div class=" info-tooltip" >
<img src="../img/waypoint_map-512.png" width="32" height="32" title="Ver Recorrido de la Unidad" rel="tooltip" data-placement="right"onclick="ver_seguimiento(<?php echo $r[0];?>);"/>

<img src="../img/map_marker-512.png" width="32" height="32"  title="Ver Ubicación de la Unidad" rel="tooltip" data-placement="right" onclick="ver_posicion(<?php echo $r[0];?>);" style="margin-left:10px;"/>

<img src="../img/dashboard.png" width="32" height="32"  title="Ver Velocidad de la Unidad" rel="tooltip" data-placement="right" onclick="ver_velocidad(<?php echo $r[0];?>);" style="margin-left:10px;"/>

<img src="../img/kilometraje.png" width="32" height="32"  title="Ver Distancias Recorridas" rel="tooltip" data-placement="right" onclick="ver_distancia(<?php echo $r[0];?>);" style="margin-left:10px;"/>



<img src="../img/apagar.png" width="32" height="32"  title="Apagar La Unidad" rel="tooltip" data-placement="right" onclick="apagar(<?php echo $r[12];?>);" style="margin-left:10px;"/>

<img src="../img/encender.png" width="32" height="32"  title="Encender La Unidad" rel="tooltip" data-placement="right" onclick="encender(<?php echo $r[12];?>);" style="margin-left:10px;"/>


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
<?php } } } ?>   
</tbody>
    
<tfoot>
	<tr>
<th class="extra"><input type="text" name="acc" id="acc" class="search_init"  style="width:0px; height:0px; border:none;" /></th>
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


<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="ver" value="Ubicación Todas Las Unidades" class="btn btn-info btn-block" onclick="ver_mapag();"/></div>
</div>

						


<div id="vistas"></div>



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
$('.selectpicker').selectBoxIt();
</script>


<script src="../Legend/admin/assets/stepswizard/js/jquery.steps.min.js"></script>
<script> function stepswizard() {
     $("#wizard3").steps({
         headerTag: "h2",
         bodyTag: "section",
         transitionEffect: "none",
         enableFinishButton: false,
         enablePagination: false,
         enableAllSteps: true,
         titleTemplate: "#title#",
         cssClass: "tabcontrol"
     });
 }
</script>


<script> 
function ver_seguimiento(id){ 
	$.get('vista_seguimiento.php?id='+id, function(resultado){ 
		if(resultado.length>0){ 
			$('#vistas').empty();
			$('#vistas').append(resultado);
			stepswizard();
			calendarios();
		} else { 
			mensaje('No Se Logro Cargar El Seguimiento',3);
		}
	});
}

function ver_posicion(id){ 
	$.get('vista_posicion.php?id='+id, function(resultado){ 
		if(resultado.length>0){ 
			$('#vistas').empty();
			$('#vistas').append(resultado);
		} else { 
			mensaje('No Se Logro Cargar La Posición',3);
		}
	});
}

function ver_mapag(){ 
	$.get('vista_mapag.php', function(resultado){ 
		if(resultado.length>0){ 
			$('#vistas').empty();
			$('#vistas').append(resultado);
		} else { 
			mensaje('No Se Logro Cargar La Posición de las Unidades',3);
		}
	});
}

function ver_velocidad(id){
	$.get('vista_velocidad.php?id='+id, function(resultado){ 
		if(resultado.length>0){
			$('#vistas').empty();
			$('#vistas').append(resultado);
		} else {
			mensaje('No Se Logro Cargar Las Velocidades',3);	
		}
	});	
}

function ver_distancia(id){
	$.get('vista_distancia.php?id='+id, function(resultado){ 
		if(resultado.length>0){
			$('#vistas').empty();
			$('#vistas').append(resultado);
		} else {
			mensaje('No Se Logro Cargar El Acumulado de las Distancias',3);	
		}
	});	
}

function limpiar(){ 
	$('#vistas').empty();
}
</script>


<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="../jquery/development-bundle/themes/custom-theme/jquery.ui.datepicker.css"/>
<script> 
function calendarios() {
	$( "#fi" ).datepicker({ 
		defaultDate: "0",
		maxDate: "+36M +1D",
		onClose: function( selectedDate ) {
			$( "#ff" ).datepicker( "option", "minDate", selectedDate );
		},
		onSelect: function( selectedDate ){ fechas(); }
	});
	$( "#ff" ).datepicker({
		defaultDate: "0",
		maxDate: "+36M +1D",
		onClose: function( selectedDate ) {
			$( "#fi" ).datepicker( "option", "maxDate", selectedDate );
		},
		onSelect: function( selectedDate ){ fechas(); }
	});
}

function fechas(){ 
	var ini = $('#fi').val();
	var fin = $('#ff').val();
	if(ini.length>0 && fin.length>0){ 
		document.getElementById('fechas').src= "fechas.php?fi="+ini+"&ff="+fin;
	}
}
</script>


<script src="../Legend/admin/assets/vex/js/vex.js"></script>
<script src="../Legend/admin/assets/vex/js/vex.dialog.js"></script>


<script>
function encender(id, uni, duni) {
         vex.defaultOptions.className = 'vex-theme-top';
         vex.dialog.open({
             message: '¿ Desea Encender La Unidad ?',
             buttons: [
                 $.extend({}, vex.dialog.buttons.YES, {
                     text: 'SI',
click: function(){ location.href='vista.php?enc='+id+'&uni='+uni+'&duni'+duni; }
                 }),
                 $.extend({}, vex.dialog.buttons.NO, { text: 'NO' })
             ]
         });
}

function apagar(id, uni, duni) {
         vex.defaultOptions.className = 'vex-theme-top';
         vex.dialog.open({
             message: '¿ Desea Apagar La Unidad ? ',
             buttons: [
                 $.extend({}, vex.dialog.buttons.YES, {
                     text: 'SI',
click: function(){ location.href='vista.php?apa='+id+'&uni='+uni+'&duni'+duni; }
                 }),
                 $.extend({}, vex.dialog.buttons.NO, { text: 'NO' })
             ]
         });
}
</script><!-- InstanceEndEditable -->

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