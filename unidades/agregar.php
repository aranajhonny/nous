<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 15;
$_SESSION['acc']['form'] = 18;
include("../complementos/permisos.php");

if(isset($_POST['guardar'])){ 
$cli =  filtrar_campo('int', 6, $_POST['cli']);   
$zona = filtrar_campo('int', 6, $_POST['zona']); 
$area = filtrar_campo('int', 6, $_POST['area']); 
$disp = filtrar_campo('int', 6, $_POST['disp']); 
$obs  = filtrar_campo('todo', 0, $_POST['obs']);   
$resp = filtrar_campo('int', 6, $_POST['resp']);
$kmi  = filtrar_campo('num', 10, $_POST['kmi']); 
$fi   = filtrar_campo('date', 10, $_POST['fi']);
$tmp  = "false"; 
$prop = "";
$unid = 0;  
$conf1 = filtrar_campo('todo', 60, $_POST['conf1']); 
$cod   = filtrar_campo('todo', 20, $_POST['cod']);
$conf2 = filtrar_campo('todo', 60, $_POST['conf2']);  
$conf3 = filtrar_campo('todo', 60, $_POST['conf3']);  
$conf4 = filtrar_campo('todo', 60, $_POST['conf4']);

$conf = explode(":::", filtrar_campo('cadena', 120, $_POST['conf'])); 

if(empty($disp)) $disp=0;
if(empty($kmi)) $kmi=0;

if(empty($cli)){ $_SESSION['mensaje1']="Debe seleccionar un cliente";
} else if(empty($zona)){ $_SESSION['mensaje1']="Debe seleccionar la zona";
} else if(empty($area)){ $_SESSION['mensaje1']="Debe seleccionar el área";
} else if(empty($conf)){$_SESSION['mensaje1']="Debe seleccionar la configración de la unidad";
} else if(empty($cod)){ $_SESSION['mensaje1']="Debe indicar el Código Principal";
} else if(empty($conf1)){ $_SESSION['mensaje1']="Debe indicar la Primera Caracteristica";
} else if(empty($conf2)){ $_SESSION['mensaje1']="Debe indicar la Segunda Caracteristica";
} else if(empty($fi)){ $_SESSION['mensaje1']="Debe seleccionar La Fecha de Instalaciòn";
} else if(empty($resp)){ $_SESSION['mensaje1']="Debe seleccionar un responsable";
} else if(in_array(75,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar
$conf = $conf[0];

$f1 = Preparar_Imagen($_FILES['foto1']); 
$f2 = Preparar_Imagen($_FILES['foto2']);
$f3 = Preparar_Imagen($_FILES['foto3']); 
$f4 = Preparar_Imagen($_FILES['foto4']);
$f5 = Preparar_Imagen($_FILES['foto5']); 
$f6 = Preparar_Imagen($_FILES['foto6']);

if(isset($_SESSION['mensaje1'])==false)


{ // si validar 2

	$rs = pg_query($link, filtrar_sql("insert into unidades(id_cliente, id_zona, id_area,  id_dispositivo, id_confunid, id_tipo_unidad, codigo_principal, n_configuracion1, n_configuracion2, n_configuracion3, n_configuracion4, propietario, is_principal, ult_posicion, estatus_control, obs, ult_act, id_responsable, id_unidpri, km_ini, km_acum, hr_acum, fecha_instalacion) values ($cli, $zona, $area, $disp, $conf, $unid, '$cod', '$conf1', '$conf2', '$conf3', '$conf4', '', $tmp, null, 'Estable', '$obs', '".date('Y-m-d')."', $resp, 0, $kmi, $kmi, 0, '".date2($fi)."')"));
	if($rs){ 
$rs = pg_query($link, filtrar_sql("select max(id_unidad) from unidades"));
$rs = pg_fetch_array($rs);
$id = $rs[0];
Auditoria("Agrego Unidades: $cod",$id);
//============================== FOTOS ==============================================
if(empty($f1)==false){ // SI ARCHIVO VACIO
$rs = pg_query($link, filtrar_sql($link,"insert into unidimg(id_unidad, descripcion, archivo, extension) values ($id, '".$_POST['des1']."', '".$f1['archivo']."', '".$f1['ext']."')"));
if($rs==false) { $_SESSION['mensaje1']="No se Logro Registrar La Foto"; } } 

if(empty($f2)==false){ // SI ARCHIVO VACIO
$rs = pg_query($link, filtrar_sql($link,"insert into unidimg(id_unidad, descripcion, archivo, extension) values ($id, '".$_POST['des2']."', '".$f2['archivo']."', '".$f2['ext']."')"));
if($rs==false) { $_SESSION['mensaje1']="No se Logro Registrar La Foto"; } } 

if(empty($f3)==false){ // SI ARCHIVO VACIO
$rs = pg_query($link, filtrar_sql($link,"insert into unidimg(id_unidad, descripcion, archivo, extension) values ($id, '".$_POST['des3']."', '".$f3['archivo']."', '".$f3['ext']."')"));
if($rs==false) { $_SESSION['mensaje1']="No se Logro Registrar La Foto"; } } 

if(empty($f4)==false){ // SI ARCHIVO VACIO
$rs = pg_query($link, filtrar_sql($link,"insert into unidimg(id_unidad, descripcion, archivo, extension) values ($id, '".$_POST['des4']."', '".$f4['archivo']."', '".$f4['ext']."')"));
if($rs==false) { $_SESSION['mensaje1']="No se Logro Registrar La Foto"; } } 

if(empty($f5)==false){ // SI ARCHIVO VACIO
$rs = pg_query($link, filtrar_sql($link,"insert into unidimg(id_unidad, descripcion, archivo, extension) values ($id, '".$_POST['des5']."', '".$f5['archivo']."', '".$f5['ext']."')"));
if($rs==false) { $_SESSION['mensaje1']="No se Logro Registrar La Foto"; } } 

if(empty($f6)==false){ // SI ARCHIVO VACIO
$rs = pg_query($link, filtrar_sql($link,"insert into unidimg(id_unidad, descripcion, archivo, extension) values ($id, '".$_POST['des6']."', '".$f6['archivo']."', '".$f6['ext']."')"));
if($rs==false) { $_SESSION['mensaje1']="No se Logro Registrar La Foto"; } } 
//===================================================================================
		$_SESSION['mensaje3']="Unidad Agregada";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro agregar la unidad";
		Auditoria("Problema al registrar La Unidad Error: ".pg_last_error($link),0);
	}

} // si validar 2
} // si validar
} else { 
	$cod = $prop = $conf1 = $conf2 = $conf3 = $conf4 = $obs = "";
	$des1 = $des2 = $des3 = $des4 = $des5 = $des6 = ""; 
	$prin = $cli = $area = $zona = $resp = $disp = $conf = 0;
	$fi = date('d/m/Y');
	$kmi = 0;
Auditoria("Accedio Al Modulo Agregar Unidades",0);
}




function Preparar_Imagen($file){ 
	if(empty($file['tmp_name'])){  // SI ARCHIVO VACIO
		$tmp = "";
	} else { 
$tipos = array("image/gif","image/jpeg","image/bmp","image/png","image/tiff");
$maximo = 15728640; //15Mb
if (is_uploaded_file($file['tmp_name'])){ // Se ha cargado el archivo
if (in_array($file['type'],$tipos)){ // si tipo de archivo valido
if ($file['size'] <= $maximo){ // si tamaño del archivo correcto
$fp = fopen($file['tmp_name'], 'r'); //Abrimos el archivo
$imagen = fread($fp, filesize($file['tmp_name'])); //Extraemos el contenido del archivo
//$imagen = addslashes($imagen); // NO FUNCIONA PARA POSTGRES ALTERA LA CADENA DE BYTES
fclose($fp); //Cerramos el archivo

$tmp['name'] = filtrar_campo('todo', 120, $file['name']);
$tmp['ext']  = filtrar_campo('todo', 250, $file['type']);
$tmp['archivo'] = pg_escape_bytea($imagen);

} else { $tmp=""; $_SESSION['mensaje1'] = "Tamaño del Archivo (".$file['name'].") No puede ser mayor a 15Mb"; }
} else { $tmp=""; $_SESSION['mensaje1'] = "El Formato del Archivo no es Correcto"; }
} else { $tmp=""; $_SESSION['mensaje1'] = "El Archivo (".$file['name'].") No ha Sido Cargado"; }
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
<link href="../Legend/admin/assets/icheck/skins/square/_all.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/select2/css/select2.css" rel="stylesheet"/>
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
<li><a href="#">Unidades</a></li>
<li><a href="#">Unidades</a></li>
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

<div class="header">Agregar Unidad<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="agregar.php" onsubmit="return validar();" enctype="multipart/form-data">
<fieldset>
<div class="fuelux">
<div id="MyWizard" class="wizard">
<ul class="steps">
<li data-target="#step1"  onclick="$('#MyWizard').wizard('selectedItem', { step: 1 });"  class="active">1.- Unidad<span class="chevron"></span></li>
<li data-target="#step2"  onclick="$('#MyWizard').wizard('selectedItem', { step: 2 });" >2.- Fotos<span class="chevron"></span></li>
</ul>
</div>
<div class="step-content">
<div class="step-pane active" id="step1">

<div class="form-group"><label>Cliente</label>
<div id="f_cli"></div>
</div>

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

<div class="form-group"><label>Dispositivo</label>
<div><select id="disp" name="disp" class="selectpicker">
<option value="0" selected="selected">Seleccione un Dispositivo</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div>
</div>

<div class="form-group"><label>Tipo de Unidad</label>
<div><select id="conf" name="conf" class="selectpicker" onchange="CargarConfUnid();">
<option value="0" selected="selected">Seleccione un Tipo de Unidad</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div>
</div>

<div class="form-group"><label id="eti5"></label>
<input id="cod" name="cod" type="text" placeholder="Código Principal" class="form-control" maxlength="60" value="<?php echo $cod;?>" onkeyup="mayu(this)" onkeypress="return permite(event, 'todo')"/></div>

<div class="form-group"><label id="eti1"></label>
<input id="conf1" name="conf1" type="text" placeholder="Primera Caracteristica" class="form-control" maxlength="60" value="<?php echo $conf1;?>" onkeyup="mayu(this)" onkeypress="return permite(event, 'todo')" /></div> 

<div class="form-group"><label id="eti2"></label>
<input id="conf2" name="conf2" type="text" placeholder="Segunda  Caracteristica" class="form-control" maxlength="60" value="<?php echo $conf2;?>" onkeyup="mayu(this)" onkeypress="return permite(event, 'todo')" /></div> 

<div class="form-group"><label id="eti3"></label>
<input id="conf3" name="conf3" type="text" placeholder="Tercera  Caracteristica" class="form-control" maxlength="60" value="<?php echo $conf3;?>" onkeyup="mayu(this)" onkeypress="return permite(event, 'todo')" /></div> 

<div class="form-group"><label id="eti4"></label>
<input id="conf4" name="conf4" type="text" placeholder="Cuarta  Caracteristica" class="form-control" maxlength="60" value="<?php echo $conf4;?>" onkeyup="mayu(this)" onkeypress="return permite(event, 'todo')" /></div>   

<div class='form-group'>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<label>Km Inicial</label>
<input id="kmi" name="kmi" type="text" placeholder="Kilometraje Inicial" class="form-control" maxlength="12" value="<?php echo $kmi;?>" onkeypress="return permite(event, 'float')"/>
</div>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<label>Fecha de Instalaciòn</label>
<input id='fi' name='fi' type='text' placeholder='Fecha de Instalaciòn' class='form-control' maxlength='12' value='<?php echo $fi;?>'/>
</div>  
<p>&nbsp;</p>    
</div>

<div class="form-group"><label>Responsable</label>
<div><select id="resp" name="resp" class="selectpicker">
<option value="0" selected="selected">Seleccione un Responsable</option>
    <!-- LLENADO POR JAVASCRIPT -->
</select></div>
</div>

<div class="form-group"><label>Observaciones</label>
<textarea rows="8" name="obs" id="obs" onkeypress="return permite(event, 'todo')" class="form-control"><?php echo $obs; ?></textarea>
</div>

</div>
       
                                    
                                              
<div class="step-pane" id="step2">
<div class="row">
                            
		                		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="form-group"><label>1.- Foto</label><input id="foto1" name="foto1" accept="image/*" type="file" class="filestyle" data-classButton="btn btn-default btn-lg" data-input="false"></div>

<div class="form-group"><label>2.- Foto</label><input id="foto2" name="foto2" accept="image/*"  type="file" class="filestyle" data-classButton="btn btn-info btn-lg" data-input="false"></div>

<div class="form-group"><label>3.- Foto</label><input id="foto3" name="foto3" accept="image/*"  type="file" class="filestyle" data-classButton="btn btn-primary btn-lg" data-input="false"></div>

<div class="form-group"><label>4.- Foto</label><input id="foto4" name="foto4" accept="image/*"  type="file" class="filestyle" data-classButton="btn btn-default btn-lg" data-input="false"></div>
                                    
<div class="form-group"><label>5.- Foto</label><input id="foto5" name="foto5" accept="image/*"  type="file" class="filestyle" data-classButton="btn btn-info btn-lg" data-input="false"></div>

<div class="form-group"><label>6.- Foto</label><input id="foto6" name="foto6" accept="image/*"  type="file" class="filestyle" data-classButton="btn btn-primary btn-lg" data-input="false"></div>

</div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">	      	

<div class="form-group"><label>Descripción</label><input id="des1" name="des1" type="text" placeholder="Breve Descripción de la Foto" class="form-control" maxlength="120" value="<?php echo $des1;?>" onkeypress="return permite(event,'todo')" /></div>

<div class="form-group"><label>Descripción</label><input id="des2" name="des2" type="text" placeholder="Breve Descripción de la Foto" class="form-control" maxlength="120" value="<?php echo $des2;?>" onkeypress="return permite(event,'todo')" /></div>

<div class="form-group"><label>Descripción</label><input id="des3" name="des3" type="text" placeholder="Breve Descripción de la Foto" class="form-control" maxlength="120" value="<?php echo $des3;?>" onkeypress="return permite(event,'todo')" /></div>

<div class="form-group"><label>Descripción</label><input id="des4" name="des4" type="text" placeholder="Breve Descripción de la Foto" class="form-control" maxlength="120" value="<?php echo $des4;?>" onkeypress="return permite(event,'todo')" /></div>

<div class="form-group"><label>Descripción</label><input id="des5" name="des5" type="text" placeholder="Breve Descripción de la Foto" class="form-control" maxlength="120" value="<?php echo $des5;?>" onkeypress="return permite(event,'todo')" /></div>

<div class="form-group"><label>Descripción</label><input id="des6" name="des6" type="text" placeholder="Breve Descripción de la Foto" class="form-control" maxlength="120" value="<?php echo $des6;?>" onkeypress="return permite(event,'todo')" /></div>
		                		</div>
                                
		                	</div>
</div>



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
	if(document.getElementById('cli').value=="0"){ 
		mensaje("Debe seleccionar el cliente",1);
		
	} else if(document.getElementById('zona').value=="0"){ 
		mensaje("Debe seleccionar la zona geográfica ",1);
		
	} else if(document.getElementById('area').value=="0"){ 
		mensaje("Debe seleccionar el área",1);

	} else if(document.getElementById('conf').value=="0"){ 
		mensaje("Debe seleccionar la configuración de la unidad",1);
		
	} else if(document.getElementById('cod').value.length<1){ 
		mensaje("Debe indicar la "+label5,1);
			
	} else if(document.getElementById('conf1').value.length<1){ 
		mensaje("Debe indicar la "+label1,1);
	
	} else if(document.getElementById('conf2').value.length<1){ 
		mensaje("Debe indicar la "+label2,1);
		
	} else if(document.getElementById('fi').value=="0"){ 
		mensaje("Debe seleccionar la fecha de instalaciòn ",1);
	
	} else if(document.getElementById('resp').value=="0"){ 
		mensaje("Debe seleccionar un responsable ",1);
		
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
<script src="../Legend/admin/assets/bootstrapmaxlength/js/bootstrap-maxlength.min.js"></script>
<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>
<script>

//$("#prop").maxlength({ alwaysShow: true });
$("#conf1").maxlength({ alwaysShow: true });
$("#conf2").maxlength({ alwaysShow: true });
$("#conf3").maxlength({ alwaysShow: true });
$("#conf4").maxlength({ alwaysShow: true });
$("#cod").maxlength({ alwaysShow: true });

$("#conf").select2();
$("#zona").select2();
$("#area").select2();
$("#disp").select2();
$("#resp").select2();</script>

<script>

$(document).ready(function(){
<?php if(isset($_SESSION['miss'][3]) && $_SESSION['miss'][3]==-1) {?>
	$('#f_cli').empty().append('<select id="cli" name="cli" class="selectpicker"><option value="0" selected="selected">Seleccione un Cliente</option><!-- LLENADO POR JAVASCRIPT --></select>');
	cargar_clientes();
	$("#cli").select2();
	$("#cli").change(function(){ 
		dependencia_zonas();
		dependencia_areas(); 
		dependencia_dispositivos();
		dependencia_confunid();
		dependencia_personal();
	});
<?php } else { 
$rs = pg_query($link,"select rif, razon_social from clientes where id_cliente = ".$_SESSION['miss'][3]); $rs = pg_fetch_array($rs); ?>
	$('#f_cli').empty().append('<input id="cli" name="cli" type="hidden" value="<?php echo $_SESSION['miss'][3];?>" readonly="readonly"/><input id="dcli" name="dcli" type="text" placeholder="Cliente Actual" class="form-control" value="<?php echo $rs[0]." ".$rs[1];?>" readonly="readonly" />');
	dependencia_zonas();
	dependencia_areas();
	dependencia_dispositivos();
	dependencia_confunid();
	dependencia_personal();
<?php } ?>
	$("#zona").attr("disabled",true);
	$("#area").attr("disabled",true);
	$("#disp").attr("disabled",true);
	$("#conf").attr("disabled",true);
	$("#resp").attr("disabled",true);
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
function dependencia_zonas(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_zonas.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#zona").attr("disabled",false);
				document.getElementById("zona").options.length=0;
				$('#zona').append(resultado);
				document.getElementById('zona').value = '<?php echo $zona;?>';		
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
				$('#area').append(resultado);
				document.getElementById('area').value = '<?php echo $area;?>';			
			}
		}
	);
}
function dependencia_dispositivos(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_dispositivos.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#disp").attr("disabled",false);
				document.getElementById("disp").options.length=0;
				$('#disp').append(resultado);
				document.getElementById('disp').value = '<?php echo $disp;?>';		
			}
		}
	);
}
function dependencia_confunid(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_confunid.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#conf").attr("disabled",false);
				document.getElementById("conf").options.length=0;
				$('#conf').append(resultado);
				document.getElementById('conf').value = '<?php echo $conf;?>';		
			}
		}
	);
}

function dependencia_personal(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_personal.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#resp").attr("disabled",false);
				document.getElementById("resp").options.length=0;
				$('#resp').append(resultado);
				document.getElementById('resp').value = '<?php echo $resp;?>';	
			}
		}
	);
}
</script>

<script>
var label1="", label2="", label3="", label4="";
function CargarConfUnid(){ 
	var id = document.getElementById('conf').value;
	if(id=='0'){ 
		document.getElementById('eti1').innerHTML="";
		document.getElementById('eti2').innerHTML="";
		document.getElementById('eti3').innerHTML="";
		document.getElementById('eti4').innerHTML="";
		document.getElementById('eti5').innerHTML="";
	} else { 
		id = id.split(":::");
		label1 = id[1];
		label2 = id[2];
		label3 = id[3];
		label4 = id[4];
		label5 = id[5];
		document.getElementById('eti1').innerHTML=id[1];
		document.getElementById('eti2').innerHTML=id[2];
		document.getElementById('eti3').innerHTML=id[3];
		document.getElementById('eti4').innerHTML=id[4];
		document.getElementById('eti5').innerHTML=id[5];
	}
}
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
fueluxwizard();</script>

<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="../jquery/development-bundle/themes/custom-theme/jquery.ui.datepicker.css"/>
<script> 
	$( "#fi" ).datepicker({ 
		defaultDate: "0",
		minDate: "0",
		maxDate: "+36M +1D"
	});
</script>

<script src="../Legend/admin/assets/bootstrapfilestyle/js/bootstrap-filestyle.js"></script>
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