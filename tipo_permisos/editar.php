<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 36;
$_SESSION['acc']['form'] = 87;
include("../complementos/permisos.php");

if(isset($_REQUEST['tipoperm'])){ $_SESSION['tipo_perm']=filtrar_campo('int',6,$_REQUEST['tipoperm']); }




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

if(strcmp($aviso,"Alarma")==0) $esc = filtrar_campo('int', 6, $_POST['esc']); 
else $esc = "0";



if(empty($cli)){ $_SESSION['mensaje1']="Debe seleccionar un Cliente";
} else if(empty($resp)){ $_SESSION['mensaje1']="Debe seleccionar un Responsable General";
} else if(empty($nom)){ $_SESSION['mensaje1']="Debe indicar el nombre del tipo de permiso";
} else if(empty($dias)){ $_SESSION['mensaje1']="Debe indicar los días de gestión";
} else if(empty($cant_doc)){ $_SESSION['mensaje1']="Debe seleccionar La Cantidad de Documentos Por Permiso";
} else if(empty($aviso)){ $_SESSION['mensaje1']="Debe seleccionar El Tipo de Aviso";
} else if(empty($msj)){ $_SESSION['mensaje1']="Debe seleccionar El Tipo de Mensaje";
} else if(empty($esc) && strcmp($aviso,"Alarma")==0){ $_SESSION['mensaje1']="Debe Indicar El Tiempo de Escalabilidad";
} else if(in_array(232,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";

} else { // si validar 

for($i=1; $i<=$CantItems; $i++){
$_SESSION['treq']['req'][$i][0]=filtrar_campo('int',6,$_POST["id_det_".$i]);//ID del requisito
$_SESSION['treq']['req'][$i][1]=filtrar_campo('todo',250,$_POST["det_".$i]);//Detalle 
$_SESSION['treq']['req'][$i][2]=filtrar_campo('string',30,$_POST["est_".$i]); // Estatus 
}

	$qs = pg_query($link, filtrar_sql("update tipo_permisos set id_responsable_general = $resp, dias_gestion = $dias, nombre = '$nom', cant_doc = $cant_doc, tipo_aviso='$aviso', tipo_msj='$msj', esc = $esc, id_clasperm = $clas where id_tipo_permiso = ".$_SESSION['tipo_perm']));
	if($qs){
	Auditoria("Actualizo Tipo Permiso: $nom ",$_SESSION['tipo_perm']);

// -----------------------------------------------------------------------------
for($i=1; $i<=$CantItems; $i++){ 
	if(empty($_SESSION['treq']['req'][$i][1])==false){ 
		if($_SESSION['treq']['req'][$i][0]==0){ 
			pg_query($link, filtrar_sql("insert into req_tipperm(id_tipo_permiso, descripcion, estatus) values (".$_SESSION['tipo_perm'].", '".$_SESSION['treq']['req'][$i][1]."', 'Activo')"));
		} else { 
			pg_query($link, filtrar_sql("update req_tipperm set descripcion='".$_SESSION['treq']['req'][$i][1]."', estatus='".$_SESSION['treq']['req'][$i][2]."' where id_reqtipperm = ".$_SESSION['treq']['req'][$i][0]));
		}
	}
}
// -----------------------------------------------------------------------------
		$_SESSION['mensaje3']="Tipo de Permiso Editado";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro editar el Tipo de Permiso";
		Auditoria("Problema al actualizar el Tipo de Permiso Error: ".pg_last_error($link),0);
	}


} // si validar

} else if(isset($_SESSION['tipo_perm'])){
$rs = pg_query($link, filtrar_sql("select * from tipo_permisos where id_tipo_permiso=".$_SESSION['tipo_perm']));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico El Tipo de Permiso";
	Auditoria("Tipo de Permiso No Identificado ",$_SESSION['tipo_perm']);
	unset($_SESSION['tipo_perm']);
	header("location: listado.php");
	exit();
} else {
	$rs = pg_fetch_array($rs);
	$_SESSION['tipoperm_cli']=$rs[1]; 
	$resp = $rs[2]; 
	$nom = $rs[3]; 
	$dias=$rs[4];  
	$cant_doc = $rs[5]; 
	$aviso = $rs[6]; 
	$msj = $rs[7]; 
	$esc = $rs[8];
	$clas = $rs[9];
	Auditoria("Accedio Al Modulo Editar Tipo de Permiso: $nom",$_SESSION['tipo_perm']);
	
	$req = array();
	$rs = pg_query($link, filtrar_sql("select id_reqtipperm, descripcion, estatus from req_tipperm where id_tipo_permiso = ".$_SESSION['tipo_perm']." order by descripcion asc "));
	$r = pg_num_rows($rs);
	if($r!=false && $r>0){ $i=0; 
		while($r = pg_fetch_array($rs)){ 
			$req[$i][0] = $r[0];
			$req[$i][1] = $r[1];
			$req[$i][2] = $r[2];
			$_SESSION['treq']['req'][$i][0] = $r[0]; // ID del requisito
			$_SESSION['treq']['req'][$i][1] = $r[1]; // detalle del requisito
			$_SESSION['treq']['req'][$i][2] = $r[2]; // estatus del requisito
			$i++; 
		} 
	}
}


} else { 
	$_SESSION['mensaje1']="No se identifico el Tipo de Permiso";
	Auditoria("Tipo de Permiso No Identificado ",$_SESSION['tipo_perm']);
	unset($_SESSION['tipo_perm']);
	header("location: listado.php");
	exit();
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
<li><a href="#">Editar</a></li>
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

<div class="header">Editar Tipo de Permiso<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="editar.php" onsubmit="return validar();">
<fieldset>
<div class="fuelux">
<div id="MyWizard" class="wizard">
<ul class="steps">
<li data-target="#step1"  onclick="$('#MyWizard').wizard('selectedItem', { step: 1 });"  class="active">1.- Tipo de Permiso<span class="chevron"></span></li>
<li data-target="#step2"  onclick="$('#MyWizard').wizard('selectedItem', { step: 2 });" >2.- Requisitos<span class="chevron"></span></li>
</ul>
</div>
<div class="step-content">
<div class="step-pane active" id="step1">

<?php $cli = "";
$rs = pg_query($link, filtrar_sql("select rif, razon_social from clientes where id_cliente = ".$_SESSION['tipoperm_cli'])); $rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1]; ?>
<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Clasificación</label>
<div><select id="clas" name="clas" class="selectpicker">
<option value="0" selected="selected">Seleccione una Clasificación</option>
<?php $rs = pg_query($link, filtrar_sql("select id, nom from clasperm where id_cliente = ".$_SESSION['tipoperm_cli']." order by nom asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($clas==$r[0]) echo "selected";?> ><?php echo $r[1];?></option> 
<?php } } ?> 
</select></div>
</div>

<div class="form-group"><label>Responsable General</label>
<div><select id="resp" name="resp" class="selectpicker">
<option value="0" selected="selected">Seleccione un Responsable General</option>
<?php $rs = pg_query($link, filtrar_sql("select id_personal, ci, nombre from personal where id_cliente = ".$_SESSION['tipoperm_cli']." order by ci asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($resp==$r[0]) echo "selected";?> ><?php echo $r[1]." ".$r[2];?></option> 
<?php } } ?> 
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
<input id="esc" name="esc" type="text" placeholder="Tiempo de Escalabilidad en Días" class="form-control" maxlength="12" value="<?php echo $esc;?>" onkeypress="return permite(event,'num')" disabled="disabled" /><p class="help-block">Ejemplo: 2 Días </p>
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
<td colspan="3" align="right">Agregar Requisito <img src="../img/plus.png" height="15" width="15" onclick="agregar_detalle(0,'');" /></td>
    </tr>
</thead>
<thead>
	<tr>
		<th>Nro</th>
		<th>Requisito</th>
        <th>Estatus</th>
	</tr>
</thead>
<tbody id="cuerpo"></tbody>
</table>
<script>
var items = 0;
function agregar_detalle(id, valor, valor2){ 
	items++;
	$('#cuerpo').append("<tr><td>#"+items+"</td><td><input type='hidden' name='id_det_"+items+"' id='id_det_"+items+"' value='"+id+"' /><input type='text' name='det_"+items+"' id='det_"+items+"' maxlength='120' size='80' value='"+valor+"' onkeypress='return permite(event,"+"'"+"todo"+"'"+")' placeholder='Indique El Requisito' /></td><td><select name='est_"+items+"' id='est_"+items+"'><option>Activo</option><option>Inactivo</option></select> </td></tr>");
	document.getElementById('CantItems').value = items;
}</script>
<input type="hidden" name="CantItems" id="CantItems" value="0" />
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
	
return val; }</script>
<script> 
function activa(){ 
	if(document.getElementById('aviso').value=="Alarma"){ 
		document.getElementById('esc').disabled = false;
	} else { 
		document.getElementById('esc').disabled = false;
		document.getElementById('esc').value = 0;
		document.getElementById('esc').disabled = true;
	}
}
activa();
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
$("#esc").maxlength({ alwaysShow: true });
<?php for($i=0; $i<$n; $i++){ echo "\n $('#des_".$req[$i][0]."').maxlength({ alwaysShow: true });"; } ?>

$("#clas").select2();
$("#resp").select2();
$("#cant_doc").select2();
$("#aviso").select2();
$("#msj").select2();
<?php for($i=0; $i<$n; $i++){ echo "\n $('#est_".$req[$i][0]."').select2();"; } ?></script>


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
echo "<script>agregar_detalle(".$_SESSION['treq']['req'][$i][0].",'".$_SESSION['treq']['req'][$i][1]."','".$_SESSION['treq']['req'][$i][2]."');</script>";
} } ?>
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