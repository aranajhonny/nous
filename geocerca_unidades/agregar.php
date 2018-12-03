<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 64;
$_SESSION['acc']['form'] = 168;
include("../complementos/permisos.php");

Auditoria("Accedio Al Modulo de Asignar Geocerca a Unidades",0);




if(isset($_POST['guardar'])){   
	
$geo = filtrar_campo('int', 6,$_POST['geo']);
$c = filtrar_campo('int', 6,$_POST['cli']);
$t = explode(":::",filtrar_campo('cadena',120,$_POST['unid']));
$t = filtrar_campo('int', 6,$t[0]); 
$z = filtrar_campo('int', 6,$_POST['zona']); 
$a = filtrar_campo('int', 6,$_POST['area']); 
$hecho = false; 

$rs = pg_query($link, filtrar_sql("select id_unidad, ult_posicion, confunid.codigo_principal, unidades.codigo_principal from unidades, confunid, areas, zongeo where unidades.id_confunid = confunid.id_confunid and areas.id_area = unidades.id_area and zongeo.id_zongeo = unidades.id_zona and (areas.id_cliente = $c and confunid.id_cliente = $c and unidades.id_cliente = $c and zongeo.id_cliente = $c) and ((areas.id_area = $a or $a < 1) and (zongeo.id_zongeo = $z or $z < 1) and (confunid.id_confunid = $t or $t < 1)) order by confunid.nombre, unidades.codigo_principal asc"));

$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	
} else { 
	
$qs = pg_query($link, filtrar_sql("select cuando_alarma, hi, hf, dom, lun, mar, mie, jue, vie, sab, nom from geocercas where id_geocerca = $geo"));
$qs = pg_fetch_array($qs);
$qs[3] = str_replace("f","false",str_replace("t","true",$qs[3]));
$qs[4] = str_replace("f","false",str_replace("t","true",$qs[4]));
$qs[5] = str_replace("f","false",str_replace("t","true",$qs[5]));
$qs[6] = str_replace("f","false",str_replace("t","true",$qs[6]));
$qs[7] = str_replace("f","false",str_replace("t","true",$qs[7]));
$qs[8] = str_replace("f","false",str_replace("t","true",$qs[8]));
$qs[9] = str_replace("f","false",str_replace("t","true",$qs[9]));


	while($r = pg_fetch_array($rs)){  
		$chk = "chk_".$r[0];
		if(isset($_POST[$chk])){ 
			$rs = pg_query($link, filtrar_sql("select count(id_geocerca) from geounid where id_geocerca = $geo and id_unidad = ".$r[0]." "));
			$rs = pg_fetch_array($rs);
			if($rs[0]>0){ 
				$hecho = true; 
			} else { 
				if(pg_query($link, filtrar_sql("insert into geounid(id_geocerca, id_unidad, cuando_alarma, hi, hf, dom, lun, mar, mie, jue, vie, sab, alarma) values ($geo, ".$r[0].", ".$qs[0].", '".$qs[1]."', '".$qs[2]."', ".$qs[3].", ".$qs[4].", ".$qs[5].", ".$qs[6].", ".$qs[7].", ".$qs[8].", ".$qs[9].", true)"))){ 
					$hecho = true; 
					Auditoria("Se asigno La Unidad ".$r[2]." ".$r[3]." a la Geocerca ".$qs[10],$geo);
			  	}
			}
		   
		} 
	} 
} 

if($hecho==true){ 
	$_SESSION['mensaje3']="Geocerca Asignada a la(s) Unidades";
	header("location: listado.php");
	exit();
} else { 
	$_SESSION['mensaje1']="Debe Seleccionar Al Menos Una Unidad para Asignar La Geocerca";
	Auditoria("Problema no se logro asignar la Geocerca a la(s) Unidades",0);
}
	
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
<link href="../Legend/admin/assets/select2/css/select2.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet"/>

<link href="../Legend/admin/assets/bootstrapdatatables2/dataTables.bootstrap.css" rel="stylesheet" />
<style>
table.table thead tr th,
table.table tbody tr th,
table.table tfoot tr th { min-width:220px; }
</style>
<script src="../complementos/utilidades.js"></script>
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
<li><a href="#">Asignar</a></li>
<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
<li class="pull-right"><?php echo date('d/m/Y');?></li>
<li class="pull-right"><a href="" class="text-muted"><?php echo $_SESSION['miss'][6];?></a></li>
</ol>
<!-- InstanceEndEditable -->
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">
<!-- InstanceBeginEditable name="formulario" -->


<form name="agregar" method="post" action="agregar.php" onsubmit="return validar();">
<div class="header">Asignar Geocerca <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>

<fieldset>

<div class="form-group"><label>Cliente</label>
<div><select id="cli" name="cli" class="selectpicker">
<option value="0" selected="selected">Seleccione un Cliente</option>
<!-- LLENADO POR JAVASCRIPT -->    
</select></div></div>

         
<div class="form-group"><label>Zona Geográfica</label>
<div><select id="zona" name="zona" class="selectpicker">
<option value="0" selected="selected">Seleccione una Zona</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div>
</div>

<div class="form-group"><label>Área</label>
<div><select id="area" name="area" class="selectpicker">
<option value="0" selected="selected">Seleccione un Área</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div>
</div>          
         
<div class="form-group"><label>Tipo de Unidad</label>
<div><select id="unid" name="unid" class="selectpicker">
<option value="0" selected="selected">Seleccione una Tipo de Unidad</option>
<!-- LLENADO POR JAVASCRIPT --> 
</select></div>
</div>   

<div class="form-group">
<label>Geocerca</label>
<div><select id="geo" name="geo" class="selectpicker">
<option value="0" selected="selected">Seleccione una Geocerca</option>
<!-- LLENADO POR JAVASCRIPT --> 
</select></div>
</div>                       
                                
</fieldset>
<p>&nbsp;</p><p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='listado.php'"/></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="buscar" value="Buscar Unidades" class="btn btn-primary btn-block" onclick="CargarUnidades();"/></div></div>



<div class="well">
<div class="header">Lista de Unidades<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
	
<table class="table table-striped table-bordered" id="managed-table">
	<thead>
	<tr>
        <th>Acciones</th>
        <th>Tipo de Unidad</th>
        <th>Código Principal</th>
	</tr>
	</thead>
<tbody id="filas">
 <tr>
 <td align="center">Lista de Unidades Vacia</td>
 <td></td>
 <td></td></tr>
</tbody>
</table>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="submit" name="guardar" value="Guardar" class="btn btn-primary btn-block"/></div></div>
</div>
</form>


<script>function validar(){ 
val = false;

	if(document.getElementById('geo').value=="0"){ 
		mensaje("Debe seleccionar una Geocerca",1);
	
	} else { 
		val = true;
	}
	
return val; }</script>
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
<script src="../Legend/admin/assets/bootstrapdatatables2/ZeroClipboard.js"></script></script>
<script src="../Legend/admin/assets/bootstrapdatatables2/dataTables.bootstrap.min.js"></script>
<script>
var InitiateSearchableDataTable = function () {
    return {
        init: function () {
            var oTable = $('#managed-table').dataTable({
				"scrollX": true,
                "aaSorting": [[1, 'asc']],
                "aLengthMenu": [
                   [5, 10, 20, 50, 100, -1],
                   [5, 10, 20, 50, 100, "All"]
                ],
                "iDisplayLength": 10,
                "language": {
                    "search": "",
                    "sLengthMenu": "_MENU_",
                    "oPaginate": {
                        "sPrevious": "Sig.", "sNext": "Ant."
                    }
                }
            });

           

        }
    }
}();
 //InitiateSearchableDataTable.init();
 </script>


<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>
<script>
$("#cli").select2();
$("#geo").select2();
$("#zona").select2();
$("#area").select2();
$("#unid").select2();
</script>
<script>

$(document).ready(function(){
	cargar_clientes();
	$("#cli").change(function(){ 
		dependencia_geocerca();
		dependencia_zonas();
		dependencia_areas();
		dependencia_tipounidad(); 
	});

	$("#geo").attr("disabled",true);
	$("#zona").attr("disabled",true);
	$("#area").attr("disabled",true);
	$("#unid").attr("disabled",true);
});
function cargar_clientes(){
	$.get("../combox/cargar_clientes.php", function(resultado){
		if(resultado == false){ alert("Error"); }
		else{ $('#cli').append(resultado);	}
	});	
}
function dependencia_tipounidad(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_confunid.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#unid").attr("disabled",false);
				document.getElementById("unid").options.length=0;
			$('#unid').append(resultado+"<option value='-1'>Todas Las Unidades</option>");			
			}
		}
	);
}

function dependencia_zonas(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_zonas.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#zona").attr("disabled",false);
				document.getElementById("zona").options.length=0;
				$('#zona').append(resultado+"<option value='-1'>Todas Las Zonas</option>");			
			}
		}
	);
}

function dependencia_areas(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_areas.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#area").attr("disabled",false);
				document.getElementById("area").options.length=0;
				$('#area').append(resultado+"<option value='-1'>Todas Las Áreas</option>");			
			}
		}
	);
}

function dependencia_geocerca(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_geocercas.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#geo").attr("disabled",false);
				document.getElementById("geo").options.length=0;
				$('#geo').append(resultado);			
			}
		}
	);
}
</script>

<script>
function CargarUnidades(){ 
var cl = document.getElementById('cli').value;
var pl = document.getElementById('geo').value;
var tu = document.getElementById('unid').value;
if(tu!='-1'){  tu = tu.split(":::"); tu = tu[0]; }
var zo = document.getElementById('zona').value;
var ae = document.getElementById('area').value;

if(cl!="0" && pl!="0"){ 	

$.get("cargar_unidades.php?data="+cl+":::"+pl+":::"+tu+":::"+zo+":::"+ae+":::"+"&limpiar=true", function(resultado){ 
		$('#filas').empty();
		$('#filas').append(resultado);
		InitiateSearchableDataTable.init();
});

	
	
}
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