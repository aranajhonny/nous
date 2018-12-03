<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 66;
$_SESSION['acc']['form'] = 172;
include("../complementos/permisos.php");

if(isset($_REQUEST['control'])){ $_SESSION['control']=filtrar_campo('int', 6,$_REQUEST['control']); }

if(isset($_POST['guardar'])){ 
$mag = filtrar_campo('int', 6,$_POST['mag']);
$nom = filtrar_campo('todo', 100,$_POST['nom']);  
$tamin = filtrar_campo('int', 6,$_POST['tamin']);  
$tamax = filtrar_campo('int', 6,$_POST['tamax']);
$vemin = filtrar_campo('num', 12,$_POST['vemin']);  
$vemax = filtrar_campo('num', 12,$_POST['vemax']);
$vcmin = filtrar_campo('num', 12,$_POST['vcmin']);  
$vcmax = filtrar_campo('num', 12,$_POST['vcmax']);
$hi = filtrar_campo('time', 12,$_POST['hi']); 
$hf = filtrar_campo('time', 12,$_POST['hf']);
$tiempo_esc = filtrar_campo('int', 6, $_POST['tiempo_esc']); 
$tipo_aviso = filtrar_campo('string', 30,$_POST['tipo_aviso']);

$dias = array();
if(isset( $_POST['lun']) && strcmp( $_POST['lun'],"on")==0){ $lun = $_POST['lun']; $dias[0]="true"; } else { $lun = ""; $dias[0]="false"; }
if(isset( $_POST['mar']) && strcmp( $_POST['mar'],"on")==0){ $mar = $_POST['mar']; $dias[1]="true"; } else { $mar = ""; $dias[1]="false"; }
if(isset( $_POST['mie']) && strcmp( $_POST['mie'],"on")==0){ $mie = $_POST['mie']; $dias[2]="true"; } else { $mie = ""; $dias[2]="false"; }
if(isset( $_POST['jue']) && strcmp( $_POST['jue'],"on")==0){ $jue = $_POST['jue']; $dias[3]="true"; } else { $jue = ""; $dias[3]="false"; }
if(isset( $_POST['vie']) && strcmp( $_POST['vie'],"on")==0){ $vie = $_POST['vie']; $dias[4]="true"; } else { $vie = ""; $dias[4]="false"; }
if(isset( $_POST['sab']) && strcmp( $_POST['sab'],"on")==0){ $sab = $_POST['sab']; $dias[5]="true"; } else { $sab = ""; $dias[5]="false"; }
if(isset( $_POST['dom']) && strcmp( $_POST['dom'],"on")==0){ $dom = $_POST['dom']; $dias[6]="true"; } else { $dom = ""; $dias[6]="false"; }

$lun = filtrar_campo('onoff',3,$lun);
$mar = filtrar_campo('onoff',3,$mar);
$mie = filtrar_campo('onoff',3,$mie);
$jue = filtrar_campo('onoff',3,$jue);
$vie = filtrar_campo('onoff',3,$vie);
$sab = filtrar_campo('onoff',3,$sab);
$dom = filtrar_campo('onoff',3,$dom);

if(empty($nom)){ $_SESSION['mensaje1']="Debe indicar el Nombre del Control";
} else if(strlen($tamax) < 1){ $_SESSION['mensaje1']="Debe indicar el Tiempo de Activación Máximo";
} else if(strlen($tamin) < 1){ $_SESSION['mensaje1']="Debe indicar el Tiempo de Activación Mínimo";
} else if(strlen($vemax) < 1){ $_SESSION['mensaje1']="Debe indicar el Valor Estable Máximo";
} else if(strlen($vemin) < 1){ $_SESSION['mensaje1']="Debe indicar el Valor Estable Mínimo";
} else if(strlen($vcmax) < 1){ $_SESSION['mensaje1']="Debe indicar el Valor Crítico Máximo";
} else if(strlen($vcmin) < 1){ $_SESSION['mensaje1']="Debe indicar el Valor Crítico Mínimo";
} else if(empty($hi)){ $_SESSION['mensaje1']="Debe seleccionar la hora de inicio en el Horario de Alarma";
} else if(empty($hf)){ $_SESSION['mensaje1']="Debe seleccionar la hora de finalización en el Horario de Alarma";
} else if(strlen($tiempo_esc) < 1){ $_SESSION['mensaje1']="Debe indicar el Tiempo de Escalabilidad en Horario de Alarma";
} else if(empty($tipo_aviso)){ $_SESSION['mensaje1']="Debe seleccionar el Tipo de Aviso en Horario de Alarma";
} else if(in_array(447,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 



$rs = pg_query($link, filtrar_sql("update controles set nombre='$nom', tiempo_activacion_min=$tamin, tiempo_activacion_max=$tamax, val_minimo=$vemin, val_maximo=$vemax, valor_critico_min=$vcmin, valor_critico_max=$vcmax, tipo_aviso='$tipo_aviso', tiempo_esc=$tiempo_esc where id_control = ".$_SESSION['control']));
	if($rs){ 
		$id = $_SESSION['control'];
	 	Auditoria("En Instalación Actualizo Los Datos del Controles: $nom",$id);
//===============================================================================
$aux = ConvertirHora($hi);
$aux2 = ConvertirHora($hf);
$rs=pg_query($link, filtrar_sql("update horalarm set  hi='$aux', hf='$aux2', lun=".$dias[0].", mar=".$dias[1].", mie=".$dias[2].", jue=".$dias[3].", vie=".$dias[4].", sab=".$dias[5].", dom=".$dias[6]." where id_horalarm = ".$_SESSION['control_horalarm']));
//===============================================================================
		$_SESSION['mensaje3']="Datos del Control Actualizados";
		header("location: controles_listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro actualizar Los Datos del Control";
		Auditoria("Problema al actualizar los datos del Control ",0);
	}

} // si validar

} else if(isset($_SESSION['control'])){
	$rs = pg_query($link, filtrar_sql("select * from controles where id_control = ".$_SESSION['control']));
	$r = pg_num_rows($rs);
	if($r==false || $r<1){ 
		$_SESSION['mensaje1']="No se identifico el Control del Sensor";
		Auditoria("En Instalacion Control del Sensor No Identificado ",$_SESSION['control']);
		unset($_SESSION['control']);
		header("location: controles_listado.php");
		exit();
	} else {
		$rs = pg_fetch_array($rs);
		$nom = $rs[1]; $unid = $rs[8]; 
		$tamin = $rs[2]; $tamax = $rs[3]; $tiempo_esc = $rs[13]; $tipo_aviso = $rs[12];
		$vemin = 1*$rs[5]; $vemax = 1*$rs[4]; $vcmin = 1*$rs[10]; $vcmax = 1*$rs[9];
		 
		$rs = pg_query($link, filtrar_sql("select unimedcli.id_magnitud, magnitudes.nombre, unidmed.nombre from unimedcli, magnitudes, unidmed where id_unimedcli=$unid and unimedcli.id_magnitud = magnitudes.id_magnitud and unimedcli.id_unidmed = unidmed.id_unidmed and unidmed.id_magnitud = magnitudes.id_magnitud "));
		$rs = pg_fetch_array($rs); 
		$_SESSION['control_magnitud'] = $rs[0];
		$_SESSION['control_desmag'] = $rs[1];
		$_SESSION['control_desunidmed'] = $rs[2];
		
		$rs = pg_query($link, filtrar_sql("select * from horalarm where id_control = ".$_SESSION['control']." and id_cliente = ".$_SESSION['instalacion']['cli']));
		$rs = pg_fetch_array($rs);
		$_SESSION['control_horalarm'] = $rs[0];
		$hi = ExtraerHora($rs[1]); $hf = ExtraerHora($rs[2]); 
		if($rs[6]=='t'){ $dom="on"; } else { $dom="off"; }
		if($rs[7]=='t'){ $lun="on"; } else { $lun="off"; }
		if($rs[8]=='t'){ $mar="on"; } else { $mar="off"; }
		if($rs[9]=='t'){ $mie="on"; } else { $mie="off"; }
		if($rs[10]=='t'){ $jue="on"; } else { $jue="off"; }
		if($rs[11]=='t'){ $vie="on"; } else { $vie="off"; }
		if($rs[12]=='t'){ $sab="on"; } else { $sab="off"; } 
		Auditoria("En Instalacion Accedio a Editar Control: $nom",$_SESSION['control']);
	}

} else { 
	$_SESSION['mensaje1']="No se identifico el control del sensor";
	Auditoria("En Instalacion Control del Sensor No Identificado ",0);
	unset($_SESSION['control']);
	header("location: controles_listado.php");
	exit();
}




function ConvertirHora($h){ 
	$m = stripos($h," AM");
	$m = stripos($h,"AM");
	
	if($m!=false && $m>-1){ 
		$h = str_replace(" AM","",$h);
		$h = str_replace("AM","",$h);
	} else { 
		$h = str_replace(" PM","",$h);
		$h = str_replace("PM","",$h);
		list($h,$m) = explode(":",$h);
		$h += 12;
		$h = $h.":".$m;
	}
	return $h.":00";
}

function ExtraerHora($h){ 
	list($h, $m, $s) = explode(":",$h);
	if( $h > 12 ) { $h -=12; $s="PM"; } else { $h += 0; $s="AM"; }
	return $h.":".$m." ".$s;
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../Legend/admin/assets/ico/apple-touch-icon-144-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../Legend/admin/assets/ico/apple-touch-icon-114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../Legend/admin/assets/ico/apple-touch-icon-72-precomposed.png" />
<link rel="apple-touch-icon-precomposed" href="../Legend/admin/assets/ico/apple-touch-icon-57-precomposed.png" />  
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<link href="../Legend/admin/assets/humane/css/jackedup.css" rel="stylesheet" />

<title>.:: NousTrack ::.</title>

<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>";?>
<link href="../Legend/admin/assets/select2/css/select2.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/icheck/skins/square/_all.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/clockface/css/clockface.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<script src="../complementos/utilidades.js"></script>
<style>
	.wrap { margin:0px;padding:0px; }
	.wrap .container { padding:0px; }
	body { background-color:#FFF; }
</style>
</head>
<body>
         
<section class="wrap">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">

<div class="header">Editar Datos del Control para Sensor </div>
<form name="editar" method="post" action="controles_editar.php" onsubmit="return validar();">
<fieldset>
		                                                           
<div class="form-group"><label>Nombre</label>
<input id="nom" name="nom" type="text" placeholder="Nombre del Control" class="form-control" maxlength="100" value="<?php echo $nom;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /></div>

<div class="form-group"><label>Magnitud y Medida</label>
<input id="mag" name="mag" type="text" placeholder="Magnitud" class="form-control" value="<?php echo $_SESSION['control_desmag']." - ".$_SESSION['control_desunidmed'];?>" readonly="readonly" /></div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Valor Estable Mínimo</label>
<input id="vemin" name="vemin" type="text" placeholder="Valor Estable Mínimo para el Sensor" class="form-control" maxlength="14" value="<?php echo $vemin;?>" onkeypress="return permite(event,'float')" /><p class="help-block">Ejemplo: X.x <?php echo $_SESSION['control_desunidmed'];?> </p></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Valor Estable Máximo</label>
<input id="vemax" name="vemax" type="text" placeholder="Valor Estable Máximo para el Sensor" class="form-control" maxlength="14" value="<?php echo $vemax;?>" onkeypress="return permite(event,'float')" /><p class="help-block">Ejemplo: X.x <?php echo $_SESSION['control_desunidmed'];?> </p></div>
</div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Tiempo de Activación en Mínimo</label>
<input id="tamin" name="tamin" type="text" placeholder="Tiempo de Activación Mínimo para el Sensor" class="form-control" maxlength="10" value="<?php echo $tamin;?>" onkeypress="return permite(event,'num')" /><p class="help-block">Ejemplo: 1 Min.</p></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Tiempo de Activación en Máximo</label>
<input id="tamax" name="tamax" type="text" placeholder="Tiempo de Activación Máximo para el Sensor" class="form-control" maxlength="10" value="<?php echo $tamax;?>" onkeypress="return permite(event,'num')" /><p class="help-block">Ejemplo: 80 Min.</p></div>
</div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Valor Crítico Mínimo</label>
<input id="vcmin" name="vcmin" type="text" placeholder="Valor Crítico Mínimo para el Sensor" class="form-control" maxlength="14" value="<?php echo $vcmin;?>" onkeypress="return permite(event,'float')" /><p class="help-block">Ejemplo: X.x <?php echo $_SESSION['control_desunidmed'];?> </p></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Valor Crítico Máximo</label>
<input id="vcmax" name="vcmax" type="text" placeholder="Valor Crítico Máximo para el Sensor" class="form-control" maxlength="14" value="<?php echo $vcmax;?>" onkeypress="return permite(event,'float')" /><p class="help-block">Ejemplo: X.x <?php echo $_SESSION['control_desunidmed'];?> </p></div>


<div class="form-group"><label><br/><br/>Seleccione Los Días de Activación de las Alarmas</label><br/></div>

<div class="skin skin-square skin-section checkbox icheck form-group">
<label for="square-checkbox-2" class="icheck" style="margin-right:70px;">
<input tabindex="6" type="checkbox" name="lun" id="lun" <?php if(strcmp($lun,"on")==0) echo "checked";?>  class="dias" /> Lunes</label>

<label for="square-checkbox-2" class="icheck" style="margin-right:65px;">
<input tabindex="6" type="checkbox" name="mar" id="mar" <?php if(strcmp($mar,"on")==0) echo "checked";?>  class="dias" /> Martes</label>

<label for="square-checkbox-2" class="icheck" style="margin-right:60px;">
<input tabindex="6" type="checkbox" name="mie" id="mie" <?php if(strcmp($mie,"on")==0) echo "checked";?>  class="dias" /> Miercoles</label>

<label for="square-checkbox-2" class="icheck">
<input tabindex="6" type="checkbox" name="jue" id="jue" <?php if(strcmp($jue,"on")==0) echo "checked";?>  class="dias" /> Jueves</label>
</div>

<div class="skin skin-square skin-section checkbox icheck form-group">
<label for="square-checkbox-2" class="icheck" style="margin-right:60px;">
<input tabindex="6" type="checkbox" name="vie" id="vie" <?php if(strcmp($vie,"on")==0) echo "checked";?>  class="dias" /> Viernes</label>

<label for="square-checkbox-2" class="icheck" style="margin-right:60px;">
<input tabindex="6" type="checkbox" name="sab" id="sab" <?php if(strcmp($sab,"on")==0) echo "checked";?>  class="dias" /> Sabado </label>

<label for="square-checkbox-2" class="icheck" style="margin-right:60px;">
<input tabindex="6" type="checkbox" name="dom" id="dom" <?php if(strcmp($dom,"on")==0) echo "checked";?> class="dias" /> Domingo</label>

<label for="square-checkbox-2" class="icheck" style="margin-left:60px;">
<input tabindex="6" type="checkbox" name="todos" id="todos" <?php if(strcmp($todos,"on")==0) echo "checked";?> /> Todos</label>
</div>


<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Hora de Inicio</label>
<div class="input-group">
<input type="text" id="hi" name="hi" value="<?php echo $hi;?>" data-format="hh:mm A" readonly="" class="form-control"><span class="input-group-btn"><button class="btn btn-default btn-lg" type="button" id="toggle-btn2"><i class="fa fa-clock-o"></i></button></span>
</div><br/></div>
	        
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Hora de Finalización</label>
<div class="input-group">
<input type="text" id="hf" name="hf" value="<?php echo $hf;?>" data-format="hh:mm A" readonly="" class="form-control"><span class="input-group-btn"><button class="btn btn-default btn-lg" type="button" id="toggle-btn"><i class="fa fa-clock-o"></i></button></span>
</div><br/></div>


<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tiempo de Escalabilidad (Min.)</label><input id="tiempo_esc" name="tiempo_esc" type="text" placeholder="Tiempo de Escalabilidad" class="form-control" maxlength="10" value="<?php echo $tiempo_esc;?>" onkeypress="return permite(event,'num')" /></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tipo de Aviso</label>
<div><select id="tipo_aviso" name="tipo_aviso" class="selectpicker">
<option selected >Seleccione</option>
<option <?php if(strcmp($tipo_aviso,"Correo")==0) echo "selected";?> >Correo Electrónico</option>
<option <?php if(strcmp($tipo_aviso,"SMS")==0) echo "selected";?> >Mensajeria de Texto SMS</option>
<option <?php if(strcmp($tipo_aviso,"Ambos")==0) echo "selected";?> >Ambos</option>
</select>
</div><p>&nbsp;</p></div>
                                 
</fieldset>
<p>&nbsp;</p><p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='controles_listado.php'"/></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="submit" name="guardar" id="guardar" value="Guardar" class="btn btn-primary btn-block"/></div></div>

</form>
</div>

<script>
function validar(){ 
val = false;
	
	if(document.getElementById('nom').value.length<1){ 
		mensaje("Debe indicar el numero de rif y debe contener al menos 10 digitos",1);

	} else if(document.getElementById('cli').value=="0"){ 
		mensaje("Debe seleccionar el tipo de cliente",1);
		
	} else if(document.getElementById('unid').value=="0"){ 
		mensaje("Debe seleccionar el tipo de cliente",1);
	
	} else if(document.getElementById('tamax').value.length<1){ 
		mensaje("Debe indicar el Tiempo de Activación Máximo",1);
		
	} else if(document.getElementById('tamin').value.length<1){ 
		mensaje("Debe indicar el Tiempo de Activación Mínimo",1);
		
	} else if(document.getElementById('vemax').value.length<1){ 
		mensaje("Debe indicar el Valor Estable Máximo",1);
		
	} else if(document.getElementById('vemin').value.length<1){ 
		mensaje("Debe indicar el Valor Estable Mínimo",1);
		
	} else if(document.getElementById('vcmax').value.length<1){ 
		mensaje("Debe indicar el Valor Crítico Máximo",1);
		
	} else if(document.getElementById('vcmin').value.length<1){ 
		mensaje("Debe indicar el Valor Crítico Mínimo",1);
	
	} else if(document.getElementById('lun').checked==false && 
	          document.getElementById('mar').checked==false && 
			  document.getElementById('mie').checked==false && 
			  document.getElementById('jue').checked==false && 
			  document.getElementById('vie').checked==false && 
			  document.getElementById('sab').checked==false && 
			  document.getElementById('dom').checked==false ){
		mensaje("Debe Selecionar Al Menos 1 Día en el Horario de Alarma",1);
	
	} else if(document.getElementById('hi').value.length<2){ 
		mensaje("Debe seleccionar la hora de inicio en el Horario de Alarma",1);
	
	} else if(document.getElementById('hf').value.length<2){ 
		mensaje("Debe seleccionar la hora de finalización en el Horario de Alarma",1);
		
	} else if(document.getElementById('tiempo_esc').value.length<1){ 
		mensaje("Debe indicar el Tiempo de Escalabilidad en Horario de Alarma",1);
	
	} else if(document.getElementById('tipo_aviso').value=="0"){ 
		mensaje("Debe seleccionar el tipo de aviso en Horario de Alarma",1);	
		
	} else { 
		val = true;
	}
	
	if( val == true ){ 
		val = Valor_Estable();
	} 
	
	if( val == true ){ 
		val = Valor_Critico();
	}
	
	if( val== true ){ 
		mensaje("Registrando...",3);
		$('#guardar').css('display','none');
	}
	
return val; }</script>
<script>

function Valor_Estable(){ 
	var est = true;
	var mini = document.getElementById('vemin').value;
	var maxi = document.getElementById('vemax').value;
	if(mini.length>0 && maxi.length>0){ 
		mini = Number(mini);  maxi = Number(maxi);
		if(mini>maxi){ 
			mensaje("Valor Estable Mínimo no debe ser Mayor al Valor Estable Máximo",1);
			est = false;
		} 
	}
	return est;
}

function Valor_Critico(){ 
	var est = true;
	var mini = document.getElementById('vcmin').value;
	var maxi = document.getElementById('vcmax').value;
	if(mini.length>0 && maxi.length>0){ 
		mini = Number(mini);  maxi = Number(maxi);
		if(mini>maxi){ 
			mensaje("Valor Crítico Mínimo no debe ser Mayor al Valor Crítico Máximo",1);
			est = false;
		} 
	}
	return est;
}
</script>

</div>
</div>
</div>
</section>
 
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

<script src="../Legend/admin/assets/bootstrapmaxlength/js/bootstrap-maxlength.min.js"></script>
<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>
<script>

$("#nom").maxlength({ alwaysShow: true });
$("#tamin").maxlength({ alwaysShow: true });
$("#tamax").maxlength({ alwaysShow: true });
$("#vemin").maxlength({ alwaysShow: true });
$("#vemax").maxlength({ alwaysShow: true });
$("#vcmin").maxlength({ alwaysShow: true });
$("#vcmax").maxlength({ alwaysShow: true });
$("#tiempo_esc").maxlength({ alwaysShow: true });
$("#tipo_aviso").select2();</script>

<script>
$(document).ready(function(){
	$("#todos").on('ifChecked', function(){ marca_todo(); });
	$("#todos").on('ifUnchecked', function(){ marca_todo(); });
});
</script>

<script src="../Legend/admin/assets/icheck/js/jquery.icheck.js"></script>
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
 }
icheck();

function marca_todo(){ 
	var op = document.getElementById('todos').checked;
	if ( op==true ){ 
		$('#lun').iCheck('check');
		$('#mar').iCheck('check');
		$('#mie').iCheck('check');
		$('#jue').iCheck('check');
		$('#vie').iCheck('check');
		$('#sab').iCheck('check');
		$('#dom').iCheck('check');
		
	} else if( op==false ){ 
		$('#lun').iCheck('uncheck');
		$('#mar').iCheck('uncheck');
		$('#mie').iCheck('uncheck');
		$('#jue').iCheck('uncheck');
		$('#vie').iCheck('uncheck');
		$('#sab').iCheck('uncheck');
		$('#dom').iCheck('uncheck');
		
	} 
}
</script>

<script src="../Legend/admin/assets/clockface/js/clockface.js"></script>
<script>
     $('#hi').clockface({
         format: 'HH:mm',
         trigger: 'manual'
     });
	 $('#toggle-btn2').click(function (e) {
         e.stopPropagation();
         $('#hi').clockface('toggle');
     });
     $('#hf').clockface({
         format: 'HH:mm',
         trigger: 'manual'
     });
     $('#toggle-btn').click(function (e) {
         e.stopPropagation();
         $('#hf').clockface('toggle');
     });
</script>


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
<?php include("../complementos/closdb.php"); ?>
</body>
</html>