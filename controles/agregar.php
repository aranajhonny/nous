<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 21;
$_SESSION['acc']['form'] = 38;
include("../complementos/permisos.php");

if(isset($_POST['guardar'])){ 
$nom =  filtrar_campo('todo', 100,$_POST['nom']);  
$cli =  filtrar_campo('int', 6,$_POST['cli']); 
$unid = filtrar_campo('int', 6,$_POST['unid']);
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
if(isset( $_POST['lun']) && strcmp( $_POST['lun'],"on")==0){ 
$lun = $_POST['lun']; $dias[0]="true"; } else { $lun = ""; $dias[0]="false"; }
if(isset( $_POST['mar']) && strcmp( $_POST['mar'],"on")==0){ 
$mar = $_POST['mar']; $dias[1]="true"; } else { $mar = ""; $dias[1]="false"; }
if(isset( $_POST['mie']) && strcmp( $_POST['mie'],"on")==0){ 
$mie = $_POST['mie']; $dias[2]="true"; } else { $mie = ""; $dias[2]="false"; }
if(isset( $_POST['jue']) && strcmp( $_POST['jue'],"on")==0){ 
$jue = $_POST['jue']; $dias[3]="true"; } else { $jue = ""; $dias[3]="false"; }
if(isset( $_POST['vie']) && strcmp( $_POST['vie'],"on")==0){ 
$vie = $_POST['vie']; $dias[4]="true"; } else { $vie = ""; $dias[4]="false"; }
if(isset( $_POST['sab']) && strcmp( $_POST['sab'],"on")==0){ 
$sab = $_POST['sab']; $dias[5]="true"; } else { $sab = ""; $dias[5]="false"; }
if(isset( $_POST['dom']) && strcmp( $_POST['dom'],"on")==0){ 
$dom = $_POST['dom']; $dias[6]="true"; } else { $dom = ""; $dias[6]="false"; }

$lun = filtrar_campo('onoff',3,$lun);
$mar = filtrar_campo('onoff',3,$mar);
$mie = filtrar_campo('onoff',3,$mie);
$jue = filtrar_campo('onoff',3,$jue);
$vie = filtrar_campo('onoff',3,$vie);
$sab = filtrar_campo('onoff',3,$sab);
$dom = filtrar_campo('onoff',3,$dom);

if(empty($nom)){ $_SESSION['mensaje1']="Debe indicar el Nombre del Control";
} else if(empty($cli)){ $_SESSION['mensaje1']="Debe seleccionar un cliente";
} else if(empty($unid)){ $_SESSION['mensaje1']="Debe seleccionar una Unidad de Medida";
} else if(strlen($tamax)<1){ $_SESSION['mensaje1']="Debe indicar el Tiempo de Activación Máximo";
} else if(strlen($tamin)<1){ $_SESSION['mensaje1']="Debe indicar el Tiempo de Activación Mínimo";
} else if(strlen($vemax)<1){ $_SESSION['mensaje1']="Debe indicar el Valor Estable Máximo";
} else if(strlen($vemin)<1){ $_SESSION['mensaje1']="Debe indicar el Valor Estable Mínimo";
} else if(strlen($vcmax)<1){ $_SESSION['mensaje1']="Debe indicar el Valor Crítico Máximo";
} else if(strlen($vcmin)<1){ $_SESSION['mensaje1']="Debe indicar el Valor Crítico Mínimo";
} else if(empty($hi)){ $_SESSION['mensaje1']="Debe seleccionar la hora de inicio en el Horario de Alarma";
} else if(empty($hf)){ $_SESSION['mensaje1']="Debe seleccionar la hora de finalización en el Horario de Alarma";
} else if(strlen($tiempo_esc)<1){ $_SESSION['mensaje1']="Debe indicar el Tiempo de Escalabilidad en Horario de Alarma";
} else if(empty($tipo_aviso)){ $_SESSION['mensaje1']="Debe seleccionar el Tipo de Aviso en Horario de Alarma";
} else if(in_array(80,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 

$rs = pg_query($link, filtrar_sql("select id_vista from unimedcli, magnitudes where id_unimedcli = $unid and unimedcli.id_magnitud = magnitudes.id_magnitud "));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ $rs=pg_fetch_array($rs); $vista = $rs[0]; } else { $vista = 0; }

$rs = pg_query($link, filtrar_sql("insert into controles(id_cliente, id_unimedcli, nombre, tiempo_activacion_min, tiempo_activacion_max, val_minimo, val_maximo, valor_critico_min, valor_critico_max, tiempo_activacion_on_off, on_off, tipo_aviso, tiempo_esc, reenvios, id_vista) values ($cli, $unid, '$nom', $tamin, $tamax, $vemin, $vemax, $vcmin, $vcmax, 0.0, true, '$tipo_aviso', $tiempo_esc, 0, $vista)"));
	if($rs){ 
		$rs = pg_query($link, filtrar_sql("select max(id_control) from controles "));
		$rs = pg_fetch_array($rs);
		$id = $rs[0];
	 	Auditoria("Agrego controles: $nom",$rs[0]);
//===============================================================================
$aux = ConvertirHora($hi);
$aux2 = ConvertirHora($hf);
$rs=pg_query($link, filtrar_sql("insert into horalarm(id_cliente, id_control, hi, hf, estatus, lun, mar, mie, jue, vie, sab, dom) values ($cli, $id, '$aux', '$aux2', 'Activo', ".$dias[0].", ".$dias[1].", ".$dias[2].", ".$dias[3].", ".$dias[4].", ".$dias[5].", ".$dias[6].")"));
//===============================================================================
		$_SESSION['mensaje3']="Control de Sensor Agregado";
		$cli = $unid = $nom = $tamax = $tamin = $vemax = $vemin = $vcmax = $vcmin = "";
		$hi = $hf = $lun = $mar = $mie = $jue = $vie = $sab = $dom = "";
		$tiempo_esc = $tipo_aviso = $todos = "";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro agregar el Control del Sensor";
		Auditoria("Problema al registrar el Control de Sensor Error: ".pg_last_error($link),0);
	}

} // si validar
} else { 
	$nom = $tamax = $tamin = $vemax = $vemin = $vcmax = $vcmin = "";
	$hi = $hf = $lun = $mar = $mie = $jue = $vie = $sab = $dom = "";
	$tiempo_esc = $tipo_aviso = $todos = "";
	$mag = $cli = $unid = 0;
Auditoria("Accedio Al Modulo Agregar controles",0);

}

function ConvertirHora($h){ 
	$m = stripos($h," AM");
	if($m!=false && $m>-1){ 
		$h = str_replace(" AM","",$h);
	} else { 
		$h = str_replace(" PM","",$h);
		list($h,$m) = explode(":",$h);
		$h += 12;
		$h = $h.":".$m;
	}
	return $m;
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
<link href="../Legend/admin/assets/clockface/css/clockface.css" rel="stylesheet"/>
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
<li><a href="#">Controles</a></li>
<li><a href="#">Controles de Sensor</a></li>
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

<div class="header">Agregar Control del Sensor<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="agregar.php" onsubmit="return validar();">
<fieldset>
		                    <div class="fuelux">
		                        <div id="MyWizard" class="wizard">
		                            <ul class="steps">
<li data-target="#step1"  onclick="$('#MyWizard').wizard('selectedItem', { step: 1 });"  class="active">1.- Control<span class="chevron"></span></li>
<li data-target="#step2"  onclick="$('#MyWizard').wizard('selectedItem', { step: 2 });" >2.- Horario de Alarmas<span class="chevron"></span></li>
		                            </ul>
		                        </div>
		                        <div class="step-content">
		                            <div class="step-pane active" id="step1">
                                    
                                    
                                    
                                                                    
<div class="form-group"><label>Nombre</label>
<input id="nom" name="nom" type="text" placeholder="Nombre del Control" class="form-control" maxlength="100" value="<?php echo $nom;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /></div>

<div class="form-group"><label>Cliente</label>
<div id="f_cli"></div></div>

<div class="form-group"><label>Magnitudes del Cliente</label>
<div><select id="mag" name="mag" class="selectpicker">
<!-- LLENADO POR JAVASCRIPT -->  
</select></div>
</div>

<div class="form-group"><label>Unidades de Medida del Cliente</label>
<div><select id="unid" name="unid" class="selectpicker">
<!-- LLENADO POR JAVASCRIPT -->  
</select></div>
</div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Valor Estable Mínimo</label>
<input id="vemin" name="vemin" type="text" placeholder="Valor Estable Mínimo para el Sensor" class="form-control" maxlength="14" value="<?php echo $vemin;?>" onkeypress="return permite(event,'float')" /><p class="help-block">Ejemplo: X.x °C </p>
</div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Valor Estable Máximo</label>
<input id="vemax" name="vemax" type="text" placeholder="Valor Estable Máximo para el Sensor" class="form-control" maxlength="14" value="<?php echo $vemax;?>" onkeypress="return permite(event,'float')" /><p class="help-block">Ejemplo: X.x °C </p></div>
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
<input id="vcmin" name="vcmin" type="text" placeholder="Valor Crítico Mínimo para el Sensor" class="form-control" maxlength="14" value="<?php echo $vcmin;?>" onkeypress="return permite(event,'float')" /><p class="help-block">Ejemplo: X.x °C </p>
</div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Valor Crítico Máximo</label>
<input id="vcmax" name="vcmax" type="text" placeholder="Valor Crítico Máximo para el Sensor" class="form-control" maxlength="14" value="<?php echo $vcmax;?>" onkeypress="return permite(event,'float')"/><p class="help-block">Ejemplo: X.x °C </p>
</div>
</div>
</div>
                                    
                                    
                                    
                                    
                                    
                                    
<div class="step-pane" id="step2">

<div class="form-group"><label>Seleccione Los Días de Activación de las Alarmas</label><br/></div>

<div class="skin skin-square skin-section checkbox icheck form-group">
<label for="square-checkbox-2" class="icheck dias" style="margin-right:70px;">
<input tabindex="6" type="checkbox" name="lun" id="lun" <?php if(strcmp($lun,"on")==0) echo "checked";?>  /> Lunes</label>

<label for="square-checkbox-2" class="icheck dias" style="margin-right:65px;">
<input tabindex="6" type="checkbox" name="mar" id="mar" <?php if(strcmp($mar,"on")==0) echo "checked";?>  /> Martes</label>

<label for="square-checkbox-2" class="icheck dias" style="margin-right:60px;">
<input tabindex="6" type="checkbox" name="mie" id="mie" <?php if(strcmp($mie,"on")==0) echo "checked";?>  /> Miercoles</label>

<label for="square-checkbox-2" class="icheck dias">
<input tabindex="6" type="checkbox" name="jue" id="jue" <?php if(strcmp($jue,"on")==0) echo "checked";?>  /> Jueves</label>
</div>

<div class="skin skin-square skin-section checkbox icheck form-group">
<label for="square-checkbox-2" class="icheck dias" style="margin-right:60px;">
<input tabindex="6" type="checkbox" name="vie" id="vie" <?php if(strcmp($vie,"on")==0) echo "checked";?>  /> Viernes</label>

<label for="square-checkbox-2" class="icheck dias" style="margin-right:60px;">
<input tabindex="6" type="checkbox" name="sab" id="sab" <?php if(strcmp($sab,"on")==0) echo "checked";?>  /> Sabado </label>

<label for="square-checkbox-2" class="icheck dias" style="margin-right:60px;">
<input tabindex="6" type="checkbox" name="dom" id="dom" <?php if(strcmp($dom,"on")==0) echo "checked";?> /> Domingo</label>


<label for="square-checkbox-2" class="icheck" style="margin-left:60px;">
<input tabindex="6" type="checkbox" name="todos" id="todos" <?php if(strcmp($todos,"on")==0) echo "checked";?> /> Todos</label>
</div>


<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Hora de Inicio</label>
<div class="input-group">
<input type="text" id="hi" name="hi" value="<?php echo $hi;?>" data-format="hh:mm A" readonly="" class="form-control"><span class="input-group-btn"><button class="btn btn-default btn-lg" type="button" id="toggle-btn2"><i class="fa fa-clock-o"></i></button></span>
</div></div>
	        
<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Hora de Finalización</label>
<div class="input-group">
<input type="text" id="hf" name="hf" value="<?php echo $hf;?>" data-format="hh:mm A" readonly="" class="form-control"><span class="input-group-btn"><button class="btn btn-default btn-lg" type="button" id="toggle-btn"><i class="fa fa-clock-o"></i></button></span>
</div></div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tiempo de Escalabilidad (Min.)</label><input id="tiempo_esc" name="tiempo_esc" type="text" placeholder="Tiempo de Escalabilidad" class="form-control" maxlength="10" value="<?php echo $tiempo_esc;?>" onkeypress="return permite(event,'num')" />
</div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tipo de Aviso</label>
<div><select id="tipo_aviso" name="tipo_aviso" class="selectpicker">
<option selected >Seleccione</option>
<option <?php if(strcmp($tipo_aviso,"Correo")==0) echo "selected";?> >Correo Electrónico</option>
<option <?php if(strcmp($tipo_aviso,"SMS")==0) echo "selected";?> >Mensajeria de Texto SMS</option>
<option <?php if(strcmp($tipo_aviso,"Ambos")==0) echo "selected";?> >Ambos</option>
</select>
</div><p>&nbsp;</p>
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

<script>
function validar(){ 
val = false;
	if(document.getElementById('nom').value.length<1){ 
		mensaje("Debe indicar el nombre del control",1);

	} else if(document.getElementById('cli').value=="0"){ 
		mensaje("Debe seleccionar el cliente",1);
		
	} else if(document.getElementById('unid').value=="0"){ 
		mensaje("Debe seleccionar la unidad de medida",1);
	
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
	
return val; }

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

$("#nom").maxlength({ alwaysShow: true });
$("#tamin").maxlength({ alwaysShow: true });
$("#tamax").maxlength({ alwaysShow: true });
$("#vemin").maxlength({ alwaysShow: true });
$("#vemax").maxlength({ alwaysShow: true });
$("#vcmin").maxlength({ alwaysShow: true });
$("#vcmax").maxlength({ alwaysShow: true });
$("#tiempo_esc").maxlength({ alwaysShow: true });

$("#tipo_aviso").select2();

$("#mag").select2();
$("#unid").select2();

$(document).ready(function(){
<?php if(isset($_SESSION['miss'][3]) && $_SESSION['miss'][3]==-1) {?>
	$('#f_cli').empty().append('<select id="cli" name="cli" class="selectpicker"><option value="0" selected="selected">Seleccione un Cliente</option><!-- LLENADO POR JAVASCRIPT --></select>');
	cargar_clientes();
	$("#cli").change(function(){ 
		dependencia_magnitudes(); 	
	});
<?php } else { 
$rs = pg_query($link,"select rif, razon_social from clientes where id_cliente = ".$_SESSION['miss'][3]); $rs = pg_fetch_array($rs); ?>
	$('#f_cli').empty().append('<input id="cli" name="cli" type="hidden" value="<?php echo $_SESSION['miss'][3];?>" readonly="readonly"/><input id="dcli" name="dcli" type="text" placeholder="Cliente Actual" class="form-control" value="<?php echo $rs[0]." ".$rs[1];?>" readonly="readonly" />');
	dependencia_magnitudes(); 
<?php } ?>
	$("#mag").change(function(){ 
		dependencia_unimedcli(); 
	});
	$("#mag").attr("disabled",true);
	$("#unid").attr("disabled",true);
	$("#todos").on('ifChecked', function(){ marca_todo(); });
	$("#todos").on('ifUnchecked', function(){ marca_todo(); });
});
function cargar_clientes(){
	$.get("../combox/cargar_clientes.php", function(resultado){
		if(resultado == false){ alert("Error"); }
		else{ 
			document.getElementById('cli').options.length = 0; 
			$('#cli').append(resultado);
			document.getElementById('cli').value = '0';	
			$("#cli").select2();
			document.getElementById('cli').value = '<?php echo $cli;?>';
		}
	});	
	
}

function dependencia_magnitudes(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_magnitudes.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#mag").attr("disabled",false);
				document.getElementById("mag").options.length=0;
				$('#mag').append(resultado);
				document.getElementById('mag').value = '0';	
				$("#mag").select2();
				document.getElementById('mag').value = '<?php echo $mag;?>';		
			}
		}
	);
}

function dependencia_unimedcli(){
	var code = $("#cli").val()+"::"+$("#mag").val();
	$.get("../combox/dependencia_unimedcli.php", { code:code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#unid").attr("disabled",false);
				document.getElementById("unid").options.length=0;
				$('#unid').append(resultado);	
				document.getElementById('unid').value = '0';
				$("#unid").select2();
				document.getElementById('unid').value = '<?php echo $unid;?>';
			}
		}
	);
}
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
}</script>

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