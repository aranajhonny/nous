<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 36;
$_SESSION['acc']['form'] = 86;
include("../complementos/permisos.php");


if(isset($_POST['guardar'])){ 
$cli =  filtrar_campo('int', 6, $_POST['cli']); 
$clas = filtrar_campo('int', 6, $_POST['clas']); if(empty($clas))$clas=0;
$resp = filtrar_campo('int', 6, $_POST['resp']);  
$dias = filtrar_campo('int', 6, $_POST['dias']);
$nom = filtrar_campo('todo', 120, $_POST['nom']); 
$CantItems = filtrar_campo('int', 6, $_POST['CantItems']); 
$cant_doc =  filtrar_campo('int', 6, $_POST['cant_doc']);
$aviso = filtrar_campo('string', 20, $_POST['aviso']); 
$msj = filtrar_campo('string', 12, $_POST['msj']); 

if(strcmp($aviso,"Alarma")==0) $esc = filtrar_campo('int', 6, $_POST['esc']); else $esc = "0";

for($i=1; $i<=$CantItems; $i++){
$_SESSION['treq']['req'][$i][0]=filtrar_campo('int',6,$_POST["id_det_".$i]);//ID del requisito
$_SESSION['treq']['req'][$i][1]=filtrar_campo('todo',250,$_POST["det_".$i]);//Detalle del requisito
}

if(empty($cli)){ $_SESSION['mensaje1']="Debe seleccionar un Cliente";
} else if(empty($resp)){ $_SESSION['mensaje1']="Debe seleccionar un Responsable General";
} else if(empty($nom)){ $_SESSION['mensaje1']="Debe indicar el nombre del tipo de permiso";
} else if(empty($dias)){ $_SESSION['mensaje1']="Debe indicar los días de gestión";
} else if(empty($cant_doc)){ $_SESSION['mensaje1']="Debe seleccionar La Cantidad de Documentos Por Permiso";
} else if(empty($aviso)){ $_SESSION['mensaje1']="Debe seleccionar El Tipo de Aviso";
} else if(empty($msj)){ $_SESSION['mensaje1']="Debe seleccionar El Tipo de Mensaje";
} else if(empty($esc) && strcmp($aviso,"Alarma")==0){ $_SESSION['mensaje1']="Debe Indicar El Tiempo de Escalabilidad";
} else if(in_array(92,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 

$f1 = Preparar_Imagen($_FILES['foto1']);
if(isset($_SESSION['mensaje1'])==false){ // si validar 2

	$rs = pg_query($link, filtrar_sql("insert into tipo_permisos(id_cliente, id_responsable_general, dias_gestion, nombre, cant_doc, tipo_aviso, tipo_msj, esc, id_clasperm) values ($cli, $resp, $dias, '$nom', $cant_doc, '$aviso', '$msj', $esc, $clas)"));
	if($rs){ 
$rs = pg_query($link, filtrar_sql("select max(id_tipo_permiso) from tipo_permisos"));
$rs = pg_fetch_array($rs); 
$id = $rs[0];
Auditoria("Agrego Tipo de Permiso: $nom",$id);

/* ============================================================================== */
$sql2="";
for($i=1; $i<=$CantItems; $i++){	
	if(empty($_SESSION['treq']['req'][$i][1])==false){  
$_SESSION['treq']['req'][$i][1] = filtrar_campo('todo',250,$_SESSION['treq']['req'][$i][1]);
			$sql2 .="($id, '".$_SESSION['treq']['req'][$i][1]."', 'Activo'),";
	}
}
if(empty($sql2)==false){ 
	$sql2 = "insert into req_tipperm(id_tipo_permiso, descripcion, estatus) values ".$sql2;
	$sql2 = substr($sql2,0,(strlen($sql2)-1)).";";
	$rs = pg_query($link, filtrar_sql($sql2));
	if($rs){ 
		Auditoria("En Agregar Tipo de Permiso Se Agregaron Los Requisitos del Tipo de Permiso: $nom",$id);
	} else { 
		Auditoria("Problema al registrar Requisitos del Tipo de Permiso Error: ".pg_last_error($link),$id);
	}
	unset($sql2);
}/* ============================================================================== */
/* ============================================================================== */
if(empty($f1)==false){ // SI ARCHIVO VACIO
	$des1 = filtrar_campo('todo', 250, $_POST['des1']);
	$ext = filtrar_campo('todo', 120, $f1['ext']);
	$name = filtrar_campo('todo', 120, $f1['name']);
	$rs = pg_query($link, filtrar_sql($link,"insert into reqimg(id_tipo_permiso, descripcion, archivo, extension, nombre) values ($id, '$des1', '".$f1['archivo']."', '$ext', '$name')")); 
	if($rs) { 
		Auditoria("En Agregar Tipo de Permiso Se Agrego La Foto con los Requisitos del Tipo de Permiso: $nom",$id);
	} else { 
		$_SESSION['mensaje1']="No se Logro Registrar La Foto";
		Auditoria("Problema al registrar El Archivo del Tipo de Permiso",$id); 
	} 
} 
/* ============================================================================== */

		$_SESSION['mensaje3']="Tipo de Permiso Agregado";
		unset($_SESSION['treq']['req']);
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro agregar el Tipo de Permiso";
		Auditoria("Problema al registrar el Tipo de Permiso Error: ".pg_last_error($link),0);
	}

} // si validar 2
} // si validar
} else { 
	$clas = $msj = $aviso = $dias = $nom = "";
	$cant_doc = $CantItems = 0;
	$cli = $resp = 0;
	Auditoria("Accedio al Modulo de Agregar Tipo de Permiso",0);
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
$tmp['name'] = $file['name'];
$tmp['ext']  = $file['type'];
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
<link href="../Legend/admin/assets/select2/css/select2.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet"/>
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
<li><a href="#">Configuración</a></li>
<li><a href="#">Usuarios</a></li>
<li><a href="#">Tipo de Permisos</a></li>
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

<div class="header">Agregar Tipo de Permiso<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="agregar.php" onsubmit="return validar();" enctype="multipart/form-data">
<fieldset>
<div class="fuelux">
<div id="MyWizard" class="wizard">
<ul class="steps">
<li data-target="#step1"  onclick="$('#MyWizard').wizard('selectedItem', { step: 1 });"  class="active">1.- Tipo de Permiso<span class="chevron"></span></li>
<li data-target="#step2"  onclick="$('#MyWizard').wizard('selectedItem', { step: 2 });" >2.- Requisitos<span class="chevron"></span></li>
<li data-target="#step3"  onclick="$('#MyWizard').wizard('selectedItem', { step: 3 });" >3.- Foto<span class="chevron"></span></li>
</ul>
</div>
<div class="step-content">
<div class="step-pane active" id="step1">


<div class="form-group"><label>Cliente</label>
<div id="f_cli"></div></div>

<div class="form-group"><label>Clasificación</label>
<div><select id="clas" name="clas" class="selectpicker">
<option value="0" selected="selected">Seleccione una Clasificación</option>
<!-- LLENADO POR JAVASCRIPT --> 
</select></div>
</div>

<div class="form-group"><label>Responsable General</label>
<div><select id="resp" name="resp" class="selectpicker">
<option value="0" selected="selected">Seleccione un Responsable General</option>
<!-- LLENADO POR JAVASCRIPT -->  
</select></div>
</div>
                                
<div class="form-group"><label>Nombre</label>
<input id="nom" name="nom" type="text" placeholder="Nombre del Tipo de Permiso" class="form-control" maxlength="120" value="<?php echo $nom;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /></div>



<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Días de Gestión</label>
<input id="dias" name="dias" type="text" placeholder="Días de Gestión" class="form-control" maxlength="12" value="<?php echo $dias;?>" onkeypress="return permite(event,'num')" />
</div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Cantidad de Documentos Por Permiso</label>
<div><select id="cant_doc" name="cant_doc" class="selectpicker">
<option value="0" selected="selected">Seleccione</option>
<option <?php if($cant_doc==1) echo "selected";?>>1</option>
<option <?php if($cant_doc==2) echo "selected";?>>2</option>
<option <?php if($cant_doc==3) echo "selected";?>>3</option>
<option <?php if($cant_doc==4) echo "selected";?>>4</option>
<option <?php if($cant_doc==5) echo "selected";?>>5</option>
<option <?php if($cant_doc==6) echo "selected";?>>6</option>
</select></div><p>&nbsp;</p>
</div>
</div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Tipo de Aviso</label>
<div><select id="aviso" name="aviso" class="selectpicker" onchange="activa();">
<option value="0" selected="selected">Seleccione</option>
<option <?php if(strcmp($aviso,"Alarma")==0) echo "selected";?>>Alarma</option>
<option <?php if(strcmp($aviso,"Notificación")==0) echo "selected";?>>Notificación</option>
</select></div><p>&nbsp;</p></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Tipo de Mensaje</label>
<div><select id="msj" name="msj" class="selectpicker">
<option value="0" selected="selected">Seleccione</option>
<option <?php if(strcmp($msj,"SMS")==0) echo "selected";?>>SMS</option>
<option <?php if(strcmp($msj,"Correo")==0) echo "selected";?>>Correo</option>
<option <?php if(strcmp($msj,"Ambos")==0) echo "selected";?>>Ambos</option>
</select></div><p>&nbsp;</p></div>
</div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Tiempo de Escalabilidad</label>
<input id="esc" name="esc" type="text" placeholder="Tiempo de Escalabilidad en Días" class="form-control" maxlength="12" value="<?php echo $tiempo;?>" onkeypress="return permite(event,'num')" disabled="disabled" /><p class="help-block">Ejemplo: 2 Días </p>
</div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div></div>

</div>
                                    
                                    
                                    
                                    
                                    
<div class="step-pane" id="step2">  
<table class="table">
<thead>
    <tr>
<td colspan="2" align="right">Agregar Requisito <img src="../img/plus.png" height="15" width="15" onclick="agregar_detalle(0,'');" /></td>
    </tr>
</thead>
<thead>
	<tr>
		<th>Nro</th>
		<th>Requisito</th>
	</tr>
</thead>
<tbody id="cuerpo"></tbody>
</table>
<script>
var items = 0;
function agregar_detalle(id, valor){ 
	items++;
	$('#cuerpo').append("<tr><td>#"+items+"</td><td><input type='hidden' name='id_det_"+items+"' id='id_det_"+items+"' value='"+id+"' /><input type='text' name='det_"+items+"' id='det_"+items+"' maxlength='120' size='100' value='"+valor+"' onkeypress='return permite(event,\"todo\");' placeholder='Indique El Requisito' /></td></tr>");
	document.getElementById('CantItems').value = items;
}</script>
<input type="hidden" name="CantItems" id="CantItems" value="0" />
</div>




<div class="step-pane" id="step3"> 
<div class="row">      
                   
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="form-group"><label>Archivo</label><input id="foto1" name="foto1" accept="*" type="file" class="filestyle" data-classButton="btn btn-default btn-lg" data-input="false"></div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">	      	
<div class="form-group"><label>Descripción</label><input id="des1" name="des1" type="text" placeholder="Breve Descripción de la Foto" class="form-control" maxlength="120" value="<?php echo $des1;?>" onkeypress="return permite(event,'todo')" /></div></div>

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

<script>
function validar(){ 
val = false;
	if(document.getElementById('cli').value=="0"){ 
		mensaje("Debe seleccionar un Cliente",1);
		
	} else if(document.getElementById('resp').value=="0"){ 
		mensaje("Debe seleccionar un Responsable General",1);
		
	} else if(document.getElementById('nom').value.length<1){ 
		mensaje("Debe indicar el nombre",1);
		
	} else if(document.getElementById('dias').value.length<1){ 
		mensaje("Debe indicar los dias de gestión",1);
	
	} else if(document.getElementById('cant_doc').value=="0"){ 
		mensaje("Debe seleccionar La Cantidad de Documentos Por Permiso",1);
	
	} else if(document.getElementById('aviso').value=="0"){ 
		mensaje("Debe seleccionar El Tipo de Aviso",1);
		
	} else if(document.getElementById('msj').value=="0"){ 
		mensaje("Debe seleccionar El Tipo de Mensaje",1);
		
	} else if(document.getElementById('aviso').value=="Alarma" && 
		( document.getElementById('esc').value.length<1 || document.getElementById('esc').value=="0")){ 
		mensaje("Debe Indicar El Tiempo de Escalabilidad",1);
		
	} else { 
		val = true;
	}
	
return val; 
}
 
function activa(){ 
	if(document.getElementById('aviso').value=="Alarma"){ 
		document.getElementById('esc').disabled = false;
	} else { 
		document.getElementById('esc').disabled = false;
		document.getElementById('esc').value = 0;
		document.getElementById('esc').disabled = true;
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

$("#dias").maxlength({ alwaysShow: true });
$("#nom").maxlength({ alwaysShow: true });
$("#des1").maxlength({ alwaysShow: true });
$("#esc").maxlength({ alwaysShow: true });

$("#resp").select2();
$("#clas").select2();
$("#aviso").select2();
$("#msj").select2();
$("#cant_doc").select2();</script>

<script>

$(document).ready(function(){
<?php if(isset($_SESSION['miss'][3]) && $_SESSION['miss'][3]==-1) {?>
	$('#f_cli').empty().append('<select id="cli" name="cli" class="selectpicker"><option value="0" selected="selected">Seleccione un Cliente</option><!-- LLENADO POR JAVASCRIPT -->   </select>');
	cargar_clientes();
	$("#cli").select2();
	$("#cli").change(function(){ dependencia_personal(); dependencia_clasperm(); });
<?php } else { 
$rs = pg_query($link,"select rif, razon_social from clientes where id_cliente = ".$_SESSION['miss'][3]); $rs = pg_fetch_array($rs); ?>
	$('#f_cli').empty().append('<input id="cli" name="cli" type="hidden" value="<?php echo $_SESSION['miss'][3];?>" readonly="readonly"/><input id="dcli" name="dcli" type="text" placeholder="Cliente Actual" class="form-control" value="<?php echo $rs[0]." ".$rs[1];?>" readonly="readonly" />');
	dependencia_personal();
	dependencia_clasperm();
<?php } ?>
	$("#resp").attr("disabled",true);
	$("#clas").attr("disabled",true);
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

function dependencia_clasperm(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_clasperm.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#clas").attr("disabled",false);
				document.getElementById("clas").options.length=0;
				$('#clas').append(resultado);
				document.getElementById('clas').value = '<?php echo $clas;?>';
			}
		}
	);
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


<?php  if(isset($_SESSION['treq']['req'])){ 
$CantItems = count($_SESSION['treq']['req']);
for($i=1; $i<=$CantItems; $i++){
echo "<script>agregar_detalle(".$_SESSION['treq']['req'][$i][0].",'".$_SESSION['treq']['req'][$i][1]."');</script>";
} } ?>

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