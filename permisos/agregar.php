<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 37;
$_SESSION['acc']['form'] = 90;
include("../complementos/permisos.php");



if(isset($_POST['guardar'])){ 
$tipo = filtrar_campo('int', 6, $_POST['tipo']); 

$cli = filtrar_campo('int', 6, $_POST['cli']); 
$unid = filtrar_campo('cadena', 120, $_POST['unid']);
$res = filtrar_campo('int', 6, $_POST['res']);   
$ser = filtrar_campo('todo', 120, $_POST['ser']); //$est = $_POST['est'];
$fe = filtrar_campo('date', 10, $_POST['fe']); 
$fv = filtrar_campo('date', 10, $_POST['fv']); 
$recargar=true; 
$cant_doc = filtrar_campo('int', 6, $_POST['cant_doc']);

if(isset( $_POST['imagen']) && strcmp( $_POST['imagen'],"on")==0){ 
$img = $_POST['imagen']; $tmp="true"; } else { $img = ""; $tmp="false"; } 

if(empty($fv)){ $tmp2 = "NULL"; } else { $tmp2 = date2($fv); }

if(empty($tipo)){ $_SESSION['mensaje1']="Debe seleccionar el tipo de permiso";
} else if(empty($cli)){ $_SESSION['mensaje1']="Debe seleccionar el cliente";
} else if(empty($unid)){ $_SESSION['mensaje1']="Debe seleccionar la unidad";
} else if(empty($res)){ $_SESSION['mensaje1']="Debe seleccionar el responsable";
} else if(empty($ser)){ $_SESSION['mensaje1']="Debe indicar el serial";
} else if(empty($fe)){ $_SESSION['mensaje1']="Debe seleccionar la fecha de expedición";
//} else if(empty($fv)){ $_SESSION['mensaje1']="Debe seleccionar la fecha de vencimiento";
//} else if(empty($est)){ $_SESSION['mensaje1']="Debe seleccionar el estatus del permiso";
} else if(in_array(93,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 

list($unid, $area, $zona) = explode(":::",$unid);
$unid = filtrar_campo('int', 6, $unid);
$area = filtrar_campo('int', 6, $area);
$zona = filtrar_campo('int', 6, $zona);

$fotos = array();
for ($i=0; $i<$cant_doc; $i++){ 
	$fotos[$i][0] = Preparar_Imagen($_FILES["foto$i"]);
	$fotos[$i][1] = filtrar_campo('todo', 120, $_POST["des$i"]);
} 
if(isset($_SESSION['mensaje1'])==false)


{ // si validar 2
if(strcmp($tmp2,"NULL")==0){ 
	$est=9;
} else { 
   $actual = date("Y-m-d");
$rs = pg_query($link, filtrar_sql("select '$tmp2'::date < '$actual'::date, '$actual'::date >= ('$tmp2'::date - (dias_gestion * interval '1 day'))::date from tipo_permisos where id_tipo_permiso = $tipo"));

   $r = pg_fetch_array($rs);
   if(strcmp($r[0],"f")==0){ //si fv menor a actual :: vigente o tramitando
	  if(strcmp($r[1],"t")==0) $est = 10;
	  else $est = 9;	
   } else { 
		$est=11;
   }
}
	// EXCEPCION EN ESTUDIO DE SI ES BENEFICIOSO 
	$rs = pg_query($link, filtrar_sql("select id_clasperm from tipo_permisos where id_tipo_permiso = $tipo"));
	$rs = pg_fetch_array($rs);
	$clas = $rs[0];
	
	$rs = pg_query($link, filtrar_sql("insert into permisos(id_tipo_permiso, id_cliente, id_unidad, id_responsable_especifico, serial, fecha_expedicion, fecha_vencimiento, is_imagen, id_area, id_zona, id_estatus, id_clasperm) values ($tipo, $cli, $unid, $res, '$ser', '".date2($fe)."', '$tmp2', $tmp, $area, $zona, $est, $clas)"));
	if($rs){ 
		$rs = pg_query($link, filtrar_sql("select max(id_permiso) from permisos")); 
		$rs = pg_fetch_array($rs); 
		$id=$rs[0];
		
Auditoria("Agrego Permiso: $ser",$rs[0]);
//================================ REQUISITOS =======================================
$n = count($_SESSION['tmp_req']); $sql2="";
for($i=0; $i<$n; $i++){ 
	//$_SESSION['tmp_req'][$i][2] = $_POST['ids_'.$_SESSION['tmp_req'][$i][0]];
if(isset($_POST['ids_'.$_SESSION['tmp_req'][$i][0]])){ 
$sql2.="($id, ".$_SESSION['tmp_req'][$i][0].",".selecciono($_SESSION['tmp_req'][$i][2])."),";
} else { 
$sql2.="($id, ".$_SESSION['tmp_req'][$i][0].",false),";
}
}
if(empty($sql2)==false){ 
$rs = pg_query($link, filtrar_sql("insert into reqperm(id_permiso, id_reqtipperm, is_doc) values ".substr($sql2,0,(strlen($sql2)-1)).";"));
if($rs==false) {
			$_SESSION['mensaje1']="No se Logro Registrar Los Requisitos";
			 Auditoria("Problema al registrar Los Requisitos del Permiso Error: ".pg_last_error($link),$id);
}
unset($sql2);
}
//===================================================================================	
//============================== ARCHIVOS ===========================================
for ($i=0; $i<$cant_doc; $i++){
	if(empty($fotos[$i][0])==false){ // SI ARCHIVO VACIO
		
		$fotos[$i][1] = filtrar_campo('todo', 120, $fotos[$i][1]);
		$fotos[$i][0]['name'] = filtrar_campo('todo', 250, $fotos[$i][0]['name']);
		$fotos[$i][0]['ext']  = filtrar_campo('todo', 250, $fotos[$i][0]['ext']);
		
		$rs = pg_query($link, "insert into permimg(id_permiso, descripcion, archivo, extension, nombre) values ($id, '".$fotos[$i][1]."', '".$fotos[$i][0]['archivo']."', '".$fotos[$i][0]['ext']."', '".$fotos[$i][0]['name']."')");
		if($rs==false) {
			$_SESSION['mensaje1']="No se Logro Registrar El Archivo";
			 Auditoria("Problema al registrar el Archivo del Permiso Error: ".pg_last_error($link),$id);
		}
	}
}
//===================================================================================
		$_SESSION['mensaje3']="Permiso Agregado";
		header("location: listado.php");
		exit();
		$ser = $cli = $unid = $res = $est = $fv = $fe = $tipo = $area = $zona = "";
		$clas = "";
		$img = "on";
		unset($_SESSION['tmp_req']);
	} else { 
		$_SESSION['mensaje1']="No se logro agregar el permiso";
		Auditoria("Problema al registrar El Permiso Error: ".pg_last_error($link),0);
	}


} // si validar 2
} // si validar
} else { 
	$clas = $ser = $est = $fv = $fe =  "";
	$area = $zona = $tipo = $cli = $res = $unid = 0;
	$img = "on";
	unset($_SESSION['tmp_req']);
	$recargar=false;
	
	Auditoria("Accedio Al Modulo Agregar Permiso",0);
}

function selecciono($op){ 
	if(strcmp($op,"off")==0) return 'true'; 
	else return 'false';
}

function Preparar_Imagen($file){ 
	if(empty($file['tmp_name'])){  // SI ARCHIVO VACIO
		$tmp = "";
	} else { 
//$tipos = array("image/gif","image/jpeg","image/bmp","image/png","image/tiff");
$maximo = 15728640; //15Mb
if (is_uploaded_file($file['tmp_name'])){ // Se ha cargado el archivo
//if (in_array($file['type'],$tipos)){ // si tipo de archivo valido
if ($file['size'] <= $maximo){ // si tamaño del archivo correcto
$fp = fopen($file['tmp_name'], 'r'); //Abrimos el archivo
$imagen = fread($fp, filesize($file['tmp_name'])); //Extraemos el contenido del archivo
//$imagen = addslashes($imagen); // NO FUNCIONA PARA POSTGRES ALTERA LA CADENA DE BYTES
fclose($fp); //Cerramos el archivo
$tmp['name'] = $file['name'];
$tmp['ext']  = $file['type'];
$tmp['archivo'] = pg_escape_bytea($imagen);
} else { $tmp="";
$_SESSION['mensaje1'] = "Tamaño del Archivo (".$file['name'].") No puede ser mayor a 15Mb"; }
//} else { $tmp="";
//$_SESSION['mensaje'] = "El Formato del Archivo no es Correcto"; }
} else { $tmp="";
$_SESSION['mensaje1'] = "El Archivo (".$file['name'].") No ha Sido Cargado"; }
	}
return $tmp;
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
<link href="../Legend/admin/assets/fuelux/css/fuelux.min.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/select2/css/select2.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/icheck/skins/square/_all.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/bootstrapui/css/jquery-ui-1.9.2.custom.css" rel="stylesheet"/>
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
<li><a href="#">Control de Permisos</a></li>
<li><a href="#">Permisos</a></li>
<li><a href="#">Agregar</a></li>
<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
<li class="pull-right"><?php echo date('d/m/Y');?></li>
<li class="pull-right"><a href="" class="text-muted"><?php echo $_SESSION['miss'][6];?></a></li>
</ol>
<!-- InstanceEndEditable -->
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">
<!-- InstanceBeginEditable name="formulario" -->

<div class="well">

<div class="header">Agregar Permiso<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="agregar.php" onsubmit="return validar();" enctype="multipart/form-data">
<fieldset>

<div class="fuelux">
<div id="MyWizard" class="wizard">
<ul class="steps">
<li data-target="#step1"  onclick="$('#MyWizard').wizard('selectedItem', { step: 1 });"  class="active">1.- Permiso<span class="chevron"></span></li>
<li data-target="#step2"  onclick="$('#MyWizard').wizard('selectedItem', { step: 2 });" >2.- Requisitos<span class="chevron"></span></li>
<li data-target="#step3"  onclick="$('#MyWizard').wizard('selectedItem', { step: 3 });" >3.- Archivos<span class="chevron"></span></li>
</ul>
</div>
<div class="step-content">
<div class="step-pane active" id="step1">

<div class="form-group"><label>Cliente</label>
<div id="f_cli"></div></div>



<div class="form-group"><label>Tipos de Permiso</label>
<div><select id="tipo" name="tipo" class="selectpicker" onchange="CargarRequisitos('&limpiar=true');CargarDocs();">
<option value="0" selected="selected">Seleccione un Tipo de Permiso</option>
<!-- LLENADO POR JAVASCRIPT --> 
</select></div>
</div>

<div class="form-group"><label>Unidades del Cliente</label>
<div><select id="unid" name="unid" class="selectpicker" onchange="rellenar();">
<option value="0" selected="selected">Seleccione una Unidad</option>
<!-- LLENADO POR JAVASCRIPT -->    
</select></div></div>

<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Área</label>
<input id="area" name="area" type="text" placeholder="Área a la que Pertenece la Unidad" class="form-control" value="" readonly="readonly" /></div>

<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Zona</label>
<input id="zona" name="zona" type="text" placeholder="Zona a la que Pertenece la Zona" class="form-control" value="" readonly="readonly" /></div>

<div class="form-group"><label>Serial</label>
<input id="ser" name="ser" type="text" placeholder="Serial, Nro ó Código del Permiso" class="form-control" maxlength="118" value="<?php echo $ser;?>" onkeypress="return permite(event,'todo')" /><p class="help-block">Ejemplo: N# A-001</p></div>

<div class="form-group"><label>Responsable</label>
<div><select id="res" name="res" class="selectpicker">
<option value="0" selected="selected">Seleccione un Responsable</option>
<!-- LLENADO POR JAVASCRIPT -->   
</select></div></div>

<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Fecha de Expedición</label>
<input id="fe" name="fe" type="text" placeholder="Fecha de Expedición" class="form-control" maxlength="12" value="<?php echo $fe;?>"  /><p class="help-block">Ejemplo: 01/01/2014</p>
</div>

<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Fecha de Vencimiento</label>
<input id="fv" name="fv" type="text" placeholder="Fecha de Vencimiento" class="form-control" maxlength="12" value="<?php echo $fv;?>"  /><p class="help-block">Ejemplo: 01/01/2014</p></div>

<div class="skin skin-square skin-section checkbox icheck form-group">
<label for="square-checkbox-2" class="icheck">
<input tabindex="6" type="checkbox" name="imagen" id="imagen" <?php if(strcmp($img,"on")==0) echo "checked";?> onchange="HabFoto()" /> ¿ Usa Imagenes ?
</label>
</div>

</div>
       
                                    
                                              
<div class="step-pane" id="step2"></div>



<div class="step-pane" id="step3">
<div class="row">

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="fotos"></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="dess"></div>  

<input type="hidden" name="cant_doc" id="cant_doc" value="0" />                         
</div>
</div>
<script>
var items = 0;
function agregar_doc(){ 

	$("#fotos").append("<div class='form-group'><label>"+(items+1)+".- Archivo</label><input id='foto"+(items)+"' name='foto"+(items)+"' accept='*' type='file' class='filestyle' data-classButton='btn btn-primary btn-lg' data-input='false'></div>");
	
	$("#dess").append("<div class='form-group'><label>Descripción</label><input id='des"+(items)+"' name='des"+(items)+"' type='text' placeholder='Breve Descripción de la Foto' class='form-control' maxlength='120' value='' onkeypress='return permite(event,\"todo\");' /></div>");
	
	items++;
	document.getElementById('cant_doc').value = items; 
	
	File_style();
}

</script>


</div>
<br>
<button type="button" class="btn btn-default" id="btnWizardPrev">Ant.</button>
<button type="button" class="btn btn-primary" id="btnWizardNext">Sig.</button>
</div> 

                            
</fieldset>
<p>&nbsp;</p><p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='listado.php'"/></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="submit" name="guardar" value="Guardar" class="btn btn-primary btn-block"/></div></div>
</form>
</div>

<script>function validar(){ 
val = false;
	if(document.getElementById('tipo').value=="0"){ 
		mensaje("Debe seleccionar un Tipo de Permiso",1);
	
	} else if(document.getElementById('cli').value=="0"){ 
		mensaje("Debe seleccionar un cliente",1);
	
	} else if(document.getElementById('unid').value=="0"){ 
		mensaje("Debe seleccionar una unidad",1);
	
	} else if(document.getElementById('res').value=="0"){ 
		mensaje("Debe seleccionar un responsable",1);
		
	} else if(document.getElementById('ser').value.length<1){ 
		mensaje("Debe indicar el serial",1);
		
	} else if(document.getElementById('fe').value.length<9){ 
		mensaje("Debe seleccionar la fecha de expedición",1);
		
	} else { 
		val = true;
	}
	
return val; } 

function rellenar(){ 
	var texto = document.getElementById('unid').value;
	if(texto.length<1 || texto=="0"){
		document.getElementById('area').value="";
		document.getElementById('zona').value="";
	} else {
		var data = texto.split(':::'); 
		document.getElementById('area').value=data[5];
		document.getElementById('zona').value=data[6];
	}
}
</script>
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
<script src="../Legend/admin/assets/bootstrapmaxlength/js/bootstrap-maxlength.min.js"></script>
<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>
<script>
$("#ser").maxlength({ alwaysShow: true });
$("#fv").maxlength({ alwaysShow: true });
$("#fe").maxlength({ alwaysShow: true });

$("#tipo").select2();
$("#unid").select2();
$("#res").select2();
</script>
<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="../jquery/development-bundle/themes/custom-theme/jquery.ui.datepicker.css"/>
<script> 
$(function() {
	$( "#fv" ).datepicker();
	$( "#fe" ).datepicker();
});
</script>

<script>

$(document).ready(function(){
<?php if(isset($_SESSION['miss'][3]) && $_SESSION['miss'][3]==-1) {?>
	$('#f_cli').empty().append('<select id="cli" name="cli" class="selectpicker"><option value="0" selected="selected">Seleccione un Cliente</option><!-- LLENADO POR JAVASCRIPT -->   </select>');
	cargar_clientes();
	$("#cli").select2();
	$("#cli").change(function(){ 
		dependencia_tipo();
		dependencia_unid(); 
		dependencia_resp();
	});
<?php } else { 
$rs = pg_query($link,"select rif, razon_social from clientes where id_cliente = ".$_SESSION['miss'][3]); $rs = pg_fetch_array($rs); ?>
	$('#f_cli').empty().append('<input id="cli" name="cli" type="hidden" value="<?php echo $_SESSION['miss'][3];?>" readonly="readonly"/><input id="dcli" name="dcli" type="text" placeholder="Cliente Actual" class="form-control" value="<?php echo $rs[0]." ".$rs[1];?>" readonly="readonly" />');
	dependencia_tipo();
	dependencia_unid(); 
	dependencia_resp();
<?php } ?>
	$("#tipo").attr("disabled",true);
	$("#unid").attr("disabled",true);
	$("#res").attr("disabled",true);
});

function cargar_clientes(){
	$.get("../combox/cargar_clientes.php", function(resultado){
		if(resultado == false){ alert("Error"); }
		else{ 
			$('#cli').append(resultado);	
			document.getElementById('cli').value = '<?php echo $cli;?>';
		}
	});	
}

function dependencia_unid(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_unidades3.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#unid").attr("disabled",false);
				document.getElementById("unid").options.length=0;
				$('#unid').append(resultado);
				document.getElementById('unid').value = '<?php echo $unid;?>';
			}
		}
	);
}
function dependencia_resp(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_personal.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#res").attr("disabled",false);
				document.getElementById("res").options.length=0;
				$('#res').append(resultado);	
				document.getElementById('res').value = '<?php echo $res;?>';		
			}
		}
	);
}
function dependencia_tipo(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_tipo_requerimientos.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#tipo").attr("disabled",false);
				document.getElementById("tipo").options.length=0;
				$('#tipo').append(resultado);	
				document.getElementById('tipo').value = '<?php echo $tipo;?>';		
			}
		}
	);
}
	dependencia_tipo();
</script>

<script src="../Legend/admin/assets/icheck/js/jquery.icheck.min.js"></script>
<script> 
function icheck() {
     $('.colors li').click(function () {
         var self = $(this);

         if (!self.hasClass('active')) {
             self.siblings().removeClass('active');
			 
             var skin = self.closest('.skin'),
                 color = self.attr('class') ? '-' + self.attr('class') : '',
                 checkbox = skin.data('icheckbox'),
                 checkbox_default = 'icheckbox_minimal';

             if (skin.hasClass('skin-square')) {
                 checkbox_default = 'icheckbox_square';
                 checkbox == undefined && (checkbox = 'icheckbox_square');
             };

             checkbox == undefined && (checkbox = checkbox_default);

             skin.find('input, .skin-states .state').each(function () {
                 var element = $(this).hasClass('state') ? $(this) : $(this).parent(),
                     element_class = element.attr('class').replace(checkbox, checkbox_default + color);

                 element.attr('class', element_class);
             });

             skin.data('icheckbox', checkbox_default + color);
         
             self.addClass('active');
         };
     });
     $('.skin-square input').iCheck({
         checkboxClass: 'icheckbox_square-blue',
         
         increaseArea: '20%'
     });
 }icheck();
</script>

<script src="../Legend/admin/assets/fuelux/js/all.min.js"></script>
<script src="../Legend/admin/assets/fuelux/js/loader.min.js"></script>
<script>

 function fueluxwizard() {
     $('#MyWizard').on('change', function (e, data) {
         console.log('change');
         if (data.step === 3 && data.direction === 'next') {
              //return e.preventDefault();
         }
     });
     $('#MyWizard').on('changed', function (e, data) {
         console.log('changed');
     });
     $('#MyWizard').on('finished', function (e, data) {
         console.log('finished');
     });
     $('#btnWizardPrev').on('click', function () {
         $('#MyWizard').wizard('previous');
     });
     $('#btnWizardNext').on('click', function () {
         $('#MyWizard').wizard('next', 'foo');
     });
     $('#btnWizardStep').on('click', function () {
         var item = $('#MyWizard').wizard('selectedItem');
         console.log(item.step);
     });
     $('#MyWizard').on('stepclick', function (e, data) {
         console.log('step' + data.step + ' clicked');
         if (data.step === 1) {
              //return e.preventDefault();
         }
     });

     // optionally navigate back to 2nd step
     $('#btnStep2').on('click', function (e, data) {
         $('[data-target=#step2]').trigger("click");
     });
 }
fueluxwizard();

function CargarRequisitos(cmd){ 	
	var id = document.getElementById('tipo').value; 
	$.get("requisitos.php?id="+id+cmd, function(resultado){ 
		if(resultado == false){ alert("Error"); 
		} else { 
			$("#step2").empty();
			$('#step2').append(resultado); 
			icheck();
		} 
	});
}

function CargarDocs(){ 
	var id = document.getElementById('tipo').value;
	$.get("docs.php?id="+id, function(resultado){ 
		id = Number(resultado);
		items = 0;
		$("#fotos").empty();
		$("#dess").empty();
		for(i=0; i<id; i++){ 
			agregar_doc();
		}
	});
}
</script>

<?php if(isset($recargar) && $recargar==true){ 
	echo "<script>CargarRequisitos('');</script>";
}?>

<script src="../Legend/admin/assets/bootstrapfilestyle/js/bootstrap-filestyle2.js"></script>
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
