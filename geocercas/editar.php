<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 63;
$_SESSION['acc']['form'] = 166;
include("../complementos/permisos.php");

if(isset($_REQUEST['geo'])){ $_SESSION['geo']=filtrar_campo('int', 6, $_REQUEST['geo']); }

if(isset($_POST['guardar'])){ 
$nom =  filtrar_campo('todo', 120,$_POST['nom']); 
$cli =  filtrar_campo('int', 6,$_POST['cli']); 
$zona = filtrar_campo('int', 6,$_POST['zona']); 
$area = filtrar_campo('int', 6,$_POST['area']);
$resp = filtrar_campo('int', 6,$_POST['resp']); 
$conf = explode(":::",filtrar_campo('cadena', 120,$_POST['conf'])); 
$conf = filtrar_campo('int', 6,$conf[0]);
$tipo1 = filtrar_campo('int', 6,$_POST['tipo1']); 
$tipo2 = filtrar_campo('string', 30,$_POST['tipo2']); 
if(isset($_POST['radio'])){  
	$radio=filtrar_campo('num', 12,$_POST['radio']); 
	$punto=filtrar_campo('posicion', 40, $_POST['punto']); 
	$puntos="";
} else { 
	$radio = $punto = ""; 
	$puntos=filtrar_campo('posicion', 0, $_POST['puntos_gps']); 
}
$hi = filtrar_campo('time', 12, $_POST['hi']); 
$hf = filtrar_campo('time', 12, $_POST['hf']);

if(isset($_POST['tiempo_esc'])) $tiempo_esc = filtrar_campo('int', 6,$_POST['tiempo_esc']); 
else $tiempo_esc=0; 

$tipo_alarma = filtrar_campo('string', 30, $_POST['tipo_alarma']); 
$tipo_msj = filtrar_campo('string', 30, $_POST['tipo_msj']);

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


if(empty($nom)){ $_SESSION['mensaje1']="Debe indicar el Nombre de la Geocerca";
} else if(empty($tipo1)){ $_SESSION['mensaje1']="Debe seleccionar un Tipo de Geocerca";
} else if($tipo1==1 && (empty($radio) || empty($punto))){ $_SESSION['mensaje1']="Debe 
Indicar El Radio o Alcance de la Geocerca y El Punto de Referencia";
} else if($tipo1==2 && empty($puntos)){ $_SESSION['mensaje1']="Debe Seleccionar una serie de puntos para generar la geocerca";
} else if(empty($cli)){ $_SESSION['mensaje1']="Debe seleccionar un cliente";
} else if(empty($zona)){ $_SESSION['mensaje1']="Debe seleccionar la zona";
} else if(empty($area)){ $_SESSION['mensaje1']="Debe seleccionar el área";
} else if(empty($conf)){ $_SESSION['mensaje1']="Debe seleccionar un tipo de unidad";
} else if(empty($resp)){ $_SESSION['mensaje1']="Debe seleccionar un responsable";
} else if(empty($hi)){ $_SESSION['mensaje1']="Debe seleccionar la hora de inicio en el Horario de Alarma";
} else if(empty($hf)){ $_SESSION['mensaje1']="Debe seleccionar la hora de finalización en el Horario de Alarma";
} else if(strlen($tiempo_esc)<1){ $_SESSION['mensaje1']="Debe indicar el Tiempo de Escalabilidad en Horario de Alarma";
} else if(empty($tipo_alarma)){ $_SESSION['mensaje1']="Debe seleccionar el Tipo de Alarma en Horario de Alarma";
} else if(empty($tipo_msj)){ $_SESSION['mensaje1']="Debe seleccionar el Tipo de Mensaje en Horario de Alarma";
} else if(in_array(436,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 

if($tipo1==1){ $circulo="circle (point $punto,$radio)"; $poligono="NULL"; 
} else { $poligono="polygon ( path '".substr($puntos,0,strlen($puntos)-1)."')"; $circulo="NULL"; } 

$rs = pg_query($link, filtrar_sql("update geocercas set id_area=$area, id_zona=$zona, id_confunid=$conf, nom='$nom', tipo_geocerca=$tipo1, cuando_alarma=$tipo2, hi='".ConvertirHora($hi)."', hf='".ConvertirHora($hf)."', lun=".$dias[0].", mar=".$dias[1].", mie=".$dias[2].", jue=".$dias[3].", vie=".$dias[4].", sab=".$dias[5].", dom=".$dias[6].", id_responsable=$resp, tipo_msj='$tipo_msj', tiempo_esc=$tiempo_esc, circulo=$circulo, poligono=$poligono, tipo_alarma='$tipo_alarma' where id_geocerca = ".$_SESSION['geo']));
	if($rs){ 
	 	Auditoria("Edito La Geocerca: $nom",$_SESSION['geo']);
		$_SESSION['mensaje3']="Geocerca Editada";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro editar La Geocerca";
		Auditoria("Problema al actualizar geocerca Error: ".pg_last_error($link),$_SESSION['geo']);
	}

} // si validar


} else if(isset($_SESSION['geo'])){
$rs = pg_query($link, filtrar_sql("select * from geocercas where id_geocerca = ".$_SESSION['geo']));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico La Geocerca";
	Auditoria("Geocerca No Identificada ",$_SESSION['geo']);
	unset($_SESSION['geo']);
	header("location: listado.php");
	exit();
} else {
	$rs = pg_fetch_array($rs);
	$_SESSION['geo_cliente'] = $rs[1];
	$area = $rs[2];
	$zona = $rs[3];
	$conf = $rs[4];
	$nom = $rs[5];
	$tipo1 = $rs[6];
	$tipo2 = $rs[7];
	$hi = ExtraerHora($rs[8]); 
	$hf = ExtraerHora($rs[9]);
	if($rs[10]=='t'){ $dom="on"; } else { $dom="off"; }
	if($rs[11]=='t'){ $lun="on"; } else { $lun="off"; }
	if($rs[12]=='t'){ $mar="on"; } else { $mar="off"; }
	if($rs[13]=='t'){ $mie="on"; } else { $mie="off"; }
	if($rs[14]=='t'){ $jue="on"; } else { $jue="off"; }
	if($rs[15]=='t'){ $vie="on"; } else { $vie="off"; }
	if($rs[16]=='t'){ $sab="on"; } else { $sab="off"; } 
	$resp = $rs[17];
	$tipo_msj = $rs[18]; 
	$tiempo_esc = $rs[19];
	$circulo = $rs[20]; 
	$poligono = $rs[21]; 
	$tipo_alarma = $rs[22]; 
	if(empty($circulo)==false){ 
		$circulo = str_replace("<","",str_replace(">","",$circulo));
		$circulo = str_replace("(","",str_replace(")","",$circulo));
		list($mi_lat, $mi_lng, $radio) = explode(",",$circulo);
		$puntos="";
	} else if(empty($poligono)==false){ 
		$puntos = array();
		$poligono = str_replace("((","",str_replace("))","",$poligono));
		$tmp = explode("),(",$poligono);
		for($i=0; $i<count($tmp); $i++){ 
			$puntos[$i] = explode(",",$tmp[$i]);
		}
		$mi_lat = 0; $mi_lng = 0; $punto = ""; $radio = 0;
	}
}

Auditoria("Accedio Al Modulo Editar Geocerca: $nom",$_SESSION['geo']);

} else { 
	$_SESSION['mensaje1']="No se identifico La Geocerca";
	Auditoria("Geocerca No Identificada ",$_SESSION['geo']);
	unset($_SESSION['geo']);
	header("location: listado.php");
	exit();
}

function ConvertirHora($h){ 
	$m = stripos($h," AM");
	if($m!=false && $m>-1){ 
		$h = str_replace(" AM","",$h);
		if(strlen($h)<3){ $h.":00"; }
	} else { 
		$h = str_replace(" PM","",$h);
		if(strlen($h)<3){ $h.":00"; }
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
<script type="text/javascript" src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script type="text/javascript"src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="../gmaps/gmaps.js"></script>
<script type="text/javascript">var map;</script>

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
<li><a href="#">Geocerca</a></li>
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

<div class="header">Editar Geocerca<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="editar.php" onsubmit="return validar();">
<fieldset>
		                    <div class="fuelux">
		                        <div id="MyWizard" class="wizard">
		                            <ul class="steps">
<li data-target="#step1"  onclick="$('#MyWizard').wizard('selectedItem', { step: 1 });"  class="active">1.- Geocerca<span class="chevron"></span></li>
<li data-target="#step2"  onclick="$('#MyWizard').wizard('selectedItem', { step: 2 });" >2.- Configuración<span class="chevron"></span></li>
<li data-target="#step3"  onclick="$('#MyWizard').wizard('selectedItem', { step: 3 });" >3.- Horario de Alarmas<span class="chevron"></span></li>
		                            </ul>
		                        </div>
		                        <div class="step-content">
                                
                                
                                
<div class="step-pane active" id="step1">
<div class="form-group"><label>Nombre</label>
<input id="nom" name="nom" type="text" placeholder="Nombre de la Geocerca" class="form-control" maxlength="100" value="<?php echo $nom;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /></div>

<div class="form-group"><label>Tipo de Geocerca</label>
<div><select id="tipo1" name="tipo1" class="selectpicker" onchange="tipo_geocerca();">
<option value="0" selected="selected">Seleccione un Tipo de Geocerca</option>
<option value="1" <?php if($tipo1==1) echo "selected";?>>Circular</option>
<option value="2" <?php if($tipo1==2) echo "selected";?>>Poligono</option>
</select></div>
</div>

<div id="mi_contenedor"></div>

</div>
                                    
                                    
                                    
                                    
                                    
                                    
<div class="step-pane" id="step2">

<?php $cli = "";
$rs = pg_query($link, filtrar_sql("select rif, razon_social from clientes where id_cliente = ".$_SESSION['geo_cliente'])); $rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1]; ?>
<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>


<?php  
$a=filtrar_campo('int', 6, $_SESSION['miss'][0]);
$z=filtrar_campo('int', 6, $_SESSION['miss'][1]);
$t=filtrar_campo('int', 6, $_SESSION['miss'][2]);
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);
?>


<div class="form-group"><label>Zona Geográfica</label>
<div><select id="zona" name="zona" class="selectpicker">
<option value="0" selected="selected">Seleccione una Zona</option>
<?php $rs = pg_query($link, filtrar_sql("select id_zongeo, nombre from zongeo where id_cliente = $c and (id_zongeo = $z or $z < 1) order by nombre asc "));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($zona==$r[0]) echo "selected";?> ><?php echo $r[1]." ".$r[2];?></option> 
<?php } } ?>
</select></div>
</div>

<div class="form-group"><label>Área</label>
<div><select id="area" name="area" class="selectpicker">
<option value="0" selected="selected">Seleccione un Área</option>

<?php include("../composiciones/composiciones_areas.php");
$rs=pg_query($link, filtrar_sql("select id_area, descripcion from areas where id_dependencia = 0 and  id_cliente = $c and (id_area = $a or $a < 1) order by descripcion asc ")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<?php $qs=pg_query($link, filtrar_sql("select count(id_area) from areas where id_dependencia = ".$r[0])); $qs=pg_fetch_array($qs); if($qs[0]==0){ ?>
<option value="<?php echo $r[0];?>" <?php if($area==$r[0]) echo "selected";?> ><?php echo $r[1];?></option>
<?php } else { ?>
<option value="<?php echo $r[0];?>" <?php if($area==$r[0]) echo "selected";?> ><?php echo $r[1];?></option>	
<?php echo ComponerComboxAreas2($r[0], $r[1], $area, "&emsp;"); } ?>

<?php } } ?>
</select></div>
</div>

<div class="form-group"><label>Tipo de Unidad</label>
<div><select id="conf" name="conf" class="selectpicker" onchange="CargarConfUnid();">
<option value="0" selected="selected">Seleccione un Tipo Unidad</option>
<?php $rs = pg_query($link, filtrar_sql("select id_confunid, nombre, n_configuracion_01, n_configuracion_02, n_configuracion_03, n_configuracion_04, codigo_principal from confunid where id_cliente = $c and (id_confunid = $t or $t < 1) order by nombre asc "));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){ 
$id = $r[0].":::".$r[2].":::".$r[3].":::".$r[4].":::".$r[5].":::".$r[1];?>    
<option value="<?php echo $id;?>" <?php if($conf==$r[0]) echo "selected";?> ><?php echo $r[6];?></option> 
<?php } } ?>
</select></div>
</div>

<div class="form-group"><label>Responsable</label>
<div><select id="resp" name="resp" class="selectpicker">
<option value="0" selected="selected">Seleccione un Responsable</option>
<?php $rs = pg_query($link, filtrar_sql("select id_personal, ci, nombre from personal where id_cliente = $c and ((id_area=$a or $a < 1) and (id_zona=$z or $z < 1) and (id_confunid=$t or $t < 1)) order by ci asc ")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($resp==$r[0]) echo "selected";?> ><?php echo $r[1]." ".$r[2];?></option> 
<?php } } ?>    
</select></div></div>

<div class="form-group"><label>Tipo de Alarma</label>
<div><select id="tipo2" name="tipo2" class="selectpicker">
<option value="0" selected="selected">Seleccione un Tipo de Alarma</option>
<option value="1" <?php if($tipo2==1) echo "selected";?>>Alarma Cuando Salga de la Geocerca</option>
<option value="2" <?php if($tipo2==2) echo "selected";?>>Alarma Cuando Entre a la Geocerca</option>
<option value="3" <?php if($tipo2==3) echo "selected";?>>Si esta Fuera de la Geocerca y Fuera del Horario</option>
</select></div>
</div>
</div>


<div class="step-pane" id="step3">

<div class="form-group"><label>Seleccione Los Días de Activación de las Alarmas</label><br/></div>

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

<label for="square-checkbox-2" class="icheck" style="margin-right:60px;">
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
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Tipo de Alarma</label>
<div><select id="tipo_alarma" name="tipo_alarma" class="selectpicker" onchange="activa();">
<option value="0" selected="selected">Seleccione</option>
<option <?php if(strcmp($tipo_alarma,"Alarma")==0) echo "selected";?>>Alarma</option>
<option <?php if(strcmp($tipo_alarma,"Notificación")==0) echo "selected";?>>Notificación</option>
</select></div><p>&nbsp;</p></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Tipo de Mensaje</label>
<div><select id="tipo_msj" name="tipo_msj" class="selectpicker">
<option value="0" selected="selected">Seleccione</option>
<option <?php if(strcmp($tipo_msj,"SMS")==0) echo "selected";?>>SMS</option>
<option <?php if(strcmp($tipo_msj,"Correo")==0) echo "selected";?>>Correo</option>
<option <?php if(strcmp($tipo_msj,"Ambos")==0) echo "selected";?>>Ambos</option>
</select></div><p>&nbsp;</p></div>
</div>


<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tiempo de Escalabilidad (Min.)</label><input id="tiempo_esc" name="tiempo_esc" type="text" placeholder="Tiempo de Escalabilidad" class="form-control" maxlength="10" value="<?php echo $tiempo_esc;?>" onkeypress="return permite(event,'num')" disabled="disabled" />
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
var tipo = Number(document.getElementById('tipo1').value);

	if(document.getElementById('nom').value.length<1){ 
		mensaje("Debe indicar El Nombre de la Geocerca ",1);
		
	} else if(tipo==0){ 
		mensaje("Debe seleccionar el Tipo de Geocerca en la Pestaña 1",1);
	
	} else if(tipo==1 && document.getElementById('radio').value.length<1){
		mensaje("Debe Indicar El Radio o Alcance de la Geocerca en la Pestaña 1",1);
	
	} else if(tipo==1 && creado==false){ 
		mensaje("Debe Construir La Geocerca en la Pestaña 1",1);
		
	} else if(tipo==2 && creado2==false){ 
		mensaje("Debe Construir La Geocerca en la Pestaña 1",1);

	} else if(document.getElementById('cli').value=="0"){ 
		mensaje("Debe seleccionar un cliente",1);
	
	} else if(document.getElementById('zona').value=="0"){ 
		mensaje("Debe seleccionar la zona geográfica ",1);
		
	} else if(document.getElementById('area').value=="0"){ 
		mensaje("Debe seleccionar el área",1);
		
	} else if(document.getElementById('conf').value=="0"){ 
		mensaje("Debe seleccionar un tipo de unidad",1);
		
	} else if(document.getElementById('resp').value=="0"){ 
		mensaje("Debe seleccionar un responsable",1);
		
	} else if(document.getElementById('tipo2').value=="0"){ 
		mensaje("Debe seleccionar El Tipo de Alarma",1);
	
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
	
return val; }</script>

<script> 
function activa(){ 
	if(document.getElementById('tipo_alarma').value=="Alarma"){ 
		document.getElementById('tiempo_esc').disabled = false;
	} else { 
		document.getElementById('tiempo_esc').disabled = false;
		document.getElementById('tiempo_esc').value = 0;
		document.getElementById('tiempo_esc').disabled = true;
	}
}
</script>

<script>
var punto = {url: '../img/004.png',origin: new google.maps.Point(0,0),anchor: new google.maps.Point(0,0),size: new google.maps.Size(9,9)};
var tipo_geo = 0;
var circulo;
var mi_lat = 0, mi_lng = 0;
var creado = false, marcado = false;
var marka;

var cerca;
var i=0;
var creado2 = false;
var marcas = Array();
var puntos = Array();

function tipo_geocerca(){ 
	tipo_geo = Number(document.getElementById('tipo1').value);
	if(tipo_geo==1){ 
		$('#mi_contenedor').empty();
		$('#mi_contenedor').append('<div class="form-group"><div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Radio de Alcance de la Geocerca</label><input id="radio" name="radio" type="text" placeholder="Kilometros de Alcance de la Geocerca" class="form-control" maxlength="10" value="<?php echo $radio;?>" onkeypress="return permite(event,\'float\')" /></div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Punto de Referencia</label><input id="punto" name="punto" type="text" placeholder="Coordenadas GPS del Punto de Referencia" class="form-control" maxlength="60" value="<?php echo $punto;?>" readonly="readonly" /></div></div><p style="min-height:50px;"></p><div id="mi_mapa" style="min-width:825px; min-height:500px; margin-bottom:50px; margin-top:50px;"></div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><input type="button" name="crear" id="crear" value="Construir Geocerca" class="btn btn-info btn-block" onclick="crear_cerca();"/></div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><input type="button" name="bquitar" id="bquitar" value="Limpiar Mapa" class="btn btn-primary btn-block" onclick="quitar();"/></div><p>&nbsp;</p><p>&nbsp;</p>');
		map = new GMaps({
			el: '#mi_mapa',
			lat: 8.776510716052352,    
        	lng: -66.697998046875,
			zoom: 6,
			click: function(e){  
			  mi_lat = Number(e.latLng.lat());
			  mi_lng = Number(e.latLng.lng());
			  punto_referencia();
			}
		  }); 
		quitar();
		
		
	} else if(tipo_geo==2){ 
		$('#mi_contenedor').empty();
		$('#mi_contenedor').append('<div class="form-group"><div class="form-group"><label>Puntos de la Geocerca</label><textarea rows="6" name="puntos_gps" id="puntos_gps"  readonly="readonly" class="form-control"><?php echo str_ireplace("Array","","".$puntos); ?></textarea></div><div id="mi_mapa" style="min-width:825px; min-height:500px; margin-bottom:25px; margin-top:25px;"></div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><input type="button" name="crear" id="crear" value="Construir Geocerca" class="btn btn-info btn-block" onclick="crear_cerca2();"/></div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><input type="button" name="bquitar" id="bquitar" value="Limpiar Mapa" class="btn btn-primary btn-block" onclick="quitar3();"/></div><p>&nbsp;</p><p>&nbsp;</p>');
		map = new GMaps({
			el: '#mi_mapa',
			lat: 8.776510716052352,    
        	lng: -66.697998046875,
			zoom: 6,
			click: function(e){  
			  mi_lat = Number(e.latLng.lat());
			  mi_lng = Number(e.latLng.lng());
			  punto_referencia2();
			}
		  }); 
		quitar3();
		
	} else { 
		$('#mi_contenedor').empty();
		quitar();
		quitar3();
	}
	
	alert("Carga 1");
}

function punto_referencia(){ 
	if(marcado == true){ 
		marka.setMap(null);
	}	
	marka = map.addMarker({
		icon: punto,
        lat: mi_lat,
        lng: mi_lng,
        title: 'Punto de Referencia'
    });
	document.getElementById('punto').value = "("+mi_lat+","+mi_lng+")";
	marcado = true;
}

function quitar(){ 
	circulo.setMap(null);
	marka.setMap(null);
	creado = false;
	marcado = false;
	mi_lat = 0;
	mi_lng = 0;
	document.getElementById('punto').value="";
	
	return true;
}

function crear_cerca(){ 
	var rd = 1000*Number(document.getElementById('radio').value);
	if(mi_lat!=0 && mi_lng !=0 && creado==false && rd>0){  
	  circulo = map.drawCircle({
		  id: 'mi_radio',
		  lat: mi_lat,
		  lng: mi_lng,
		  radius: rd,
		  strokeColor: '#432070',
		  strokeOpacity: 1,
		  strokeWeight: 3,
		  fillColor: '#432070',
		  fillOpacity: 0.6
	  });
	  creado = true;
	} else if(rd<1){
		mensaje("Debe Indicar El Radio de Alcance de la Geocerca",1);
	} else if(creado==true){ 
		mensaje("GeoCerca Ya Ha Sido Construida", 1);
	} else { 
		mensaje("Debe Marcar un Punto en el Mapa para Construir La Geocerca",1);
	}
	
	return true;
}

function punto_referencia2(){ 
	if(creado2==true){ 
		mensaje("La Geocerca ya ha sido construida",1);
	} else {
	  puntos[i] = map.addMarker({
		  id: i,
		  icon: punto,
		  lat: mi_lat,
		  lng: mi_lng,
		  title: 'Punto de Referencia',
		  click: function(e){
			  quitar2(e.id);
		  }
	  });
  
	  marcas[i] = Array(mi_lat, mi_lng);
	  i++;
	  document.getElementById('puntos_gps').textContent = document.getElementById('puntos_gps').textContent+'('+mi_lat+','+mi_lng+'),';
	}
}

function quitar2(id){ 
	if(creado2==true){ 
		mensaje("La Geocerca ya ha sido construida",1);
	} else { 
		puntos[id].setMap(null);
		marcas[id] = 0;
	}
}

function quitar3(){ 
	for(j=0; j<i; j++){ puntos[j].setMap(null); }
	puntos = Array();
	marcas = Array();
	mi_lat = 0, mi_lng = 0, i=0;
	creado2 = false;
	cerca.setMap(null);
	document.getElementById('puntos_gps').value = "";
}

function crear_cerca2(){ 
	if(i>=3){ 
	  cerca = map.drawPolygon({
		paths: marcas,
		strokeColor: "#96F",
		strokeOpacity: 0.8,
		strokeWeight: 2,
		fillColor: "#96F",
		fillOpacity: 0.35,
	  });
	  creado2 = true;
	} else if(creado2==true){ 
		mensaje("GeoCerca Ya Ha Sido Creada",1);
	} else { 
		mensaje("La GeoCerca Debe Tener Al Menos 3 Puntos",1);
	}
}

function Cargar_Circulo(){ 
	mi_lat = <?php echo $mi_lat; ?>;
	mi_lng = <?php echo $mi_lng; ?>;
	rd = <?php echo $radio;?>;
	
	circulo = map.drawCircle({
		  id: 'mi_radio',
		  lat: mi_lat,
		  lng: mi_lng,
		  radius: <?php echo ($radio*1000);?>,
		  strokeColor: '#96F',
		  strokeOpacity: 1,
		  strokeWeight: 3,
		  fillColor: '#96F',
		  fillOpacity: 0.6
	 });
	 creado = true; 
	 
	 marka = map.addMarker({
		icon: punto,
        lat: mi_lat,
        lng: mi_lng,
        title: 'Punto de Referencia'
     });
	 document.getElementById('punto').value = "("+mi_lat+","+mi_lng+")";
	 marcado = true;
}

function Cargar_Poligono(){ 
<?php for($i=0; $i<count($puntos) && empty($puntos)==false; $i++){ echo "mi_lat = ".$puntos[$i][0]."; mi_lng = ".$puntos[$i][1]."; punto_referencia2();\n"; }?>
crear_cerca2();
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
$("#tiempo_esc").maxlength({ alwaysShow: true });

$("#conf").select2();
$("#zona").select2();
$("#area").select2();
$("#resp").select2();
$("#tipo1").select2();
$("#tipo2").select2();
$("#tipo_alarma").select2();
$("#tipo_msj").select2();



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



<script>
document.getElementById('tipo1').value = <?php echo $tipo1;?>;	
tipo_geocerca();	
</script>
<?php if($tipo1==1 && empty($circulo)==false) echo "<script>Cargar_Circulo();</script>";?>
<?php if($tipo1==2 && empty($poligono)==false) echo "<script>Cargar_Poligono();</script>";?>
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