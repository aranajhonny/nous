<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 15;
$_SESSION['acc']['form'] = 19;
include("../complementos/permisos.php");

if(isset($_REQUEST['unidad'])){ $_SESSION['unidad']=filtrar_campo('int', 6, $_REQUEST['unidad']); }

if(isset($_POST['guardar'])){ 
$cli =  filtrar_campo('int', 6, $_POST['cli']);   
$zona = filtrar_campo('int', 6, $_POST['zona']); 
$area = filtrar_campo('int', 6, $_POST['area']); 
$disp = filtrar_campo('int', 6, $_POST['disp']); 
$obs =  filtrar_campo('todo', 0, $_POST['obs']);   
$resp = filtrar_campo('int', 6, $_POST['resp']);
$kmi =  filtrar_campo('num', 10, $_POST['kmi']); 
$fi =   filtrar_campo('date', 10, $_POST['fi']);
$tmp  = "false"; 
$prop = ""; 
$conf1 = filtrar_campo('todo', 60, $_POST['conf1']); 
$cod =   filtrar_campo('todo', 20, $_POST['cod']);
$conf2 = filtrar_campo('todo', 60, $_POST['conf2']);  
$conf3 = filtrar_campo('todo', 60, $_POST['conf3']);  
$conf4 = filtrar_campo('todo', 60, $_POST['conf4']);
$conf = explode(":::", filtrar_campo('cadena', 120, $_POST['conf'])); 
$unid = filtrar_campo('int', 6, $_POST['unid']); 

if(isset( $_POST['prin']) && strcmp( $_POST['prin'],"on")==0){ 
$prin = filtrar_campo('onoff', 3, $_POST['prin']); $tmp="true"; 
} else { $prin = ""; $tmp="false"; } 

$cant_doc = filtrar_campo('int', 6, $_POST['cant_doc']);

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
} else if(in_array(215,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar
$conf = $conf[0];

$fotos = array();
for ($i=0; $i<$cant_doc; $i++){ 
	$fotos[$i][0] = Preparar_Imagen($_FILES["foto$i"]);
	$fotos[$i][1] = $_POST["des$i"];
} 


	$rs = pg_query($link, filtrar_sql("update unidades set id_zona=$zona, id_area=$area, id_dispositivo=$disp, id_confunid=$conf, codigo_principal='$cod', n_configuracion1='$conf1', n_configuracion2='$conf2', n_configuracion3='$conf3', n_configuracion4='$conf4', obs='$obs', id_responsable=$resp, km_ini=$kmi, fecha_instalacion = '".date2($fi)."' where id_unidad = ".$_SESSION['unidad']));
	if($rs){ 
	Auditoria("Actualizo La Unidad: $cod ",$_SESSION['unidad']);
//=============================  ARCHIVOS  ==========================================	
for ($i=0; $i<$cant_doc; $i++){
	if(empty($fotos[$i][0])==false){ // SI ARCHIVO VACIO
		$rs = pg_query($link, filtrar_sql($link,"insert into unidimg(id_unidad, descripcion, archivo, extension) values (".$_SESSION['unidad'].", '".$fotos[$i][1]."', '".$fotos[$i][0]['archivo']."', '".$fotos[$i][0]['ext']."')"));
		if($rs==false) {
			$_SESSION['mensaje1']="No se Logro Registrar Las Fotos";
			 Auditoria("Problema al registrar Foto de la Unidad Error: ".pg_last_error($link),$_SESSION['unidad']);
		}
	}
}
//===================================================================================
		$_SESSION['mensaje3']="Unidad Editada";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro editar la unidad";
		Auditoria("Problema al actualizar La Unidad Error: ".pg_last_error($link),$_SESSION['unidad']);
	}

} // si validar

} else if(isset($_SESSION['unidad'])){
$rs = pg_query($link, filtrar_sql("select * from unidades where id_unidad = ".$_SESSION['unidad']));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico La Unidad";
	Auditoria("Unidad No Identificado ",$_SESSION['unidad']);
	unset($_SESSION['unidad']);
	header("location: listado.php");
	exit();
} else { 
	$rs = pg_fetch_array($rs); 
	$disp = $rs[1]; 
	$unid = $rs[2]; 
	$_SESSION['unidad_cliente'] = $rs[3];
	$conf = $rs[4]; 
	$zona = $rs[5]; 
	$area = $rs[6]; 
	$cod = $rs[7]; 
	$conf1 = $rs[8];
	$conf2 = $rs[9]; 
	$conf3 = $rs[10]; 
	$conf4 = $rs[13]; 
	$prop = $rs[11]; 
	$prin = $rs[12];
	$obs = $rs[16];
	$resp = $rs[18];
	$kmi = $rs[20];
	$kma = $rs[21];
	$hra = $rs[22];
	$fi = date1($rs[23]);
	Auditoria("Accedio Al Modulo Editar Unidade: $cod",$_SESSION['unidad']);
	if($prin==true){ $prin="on"; } else { $prin="off"; }
}

} else { 
	$_SESSION['mensaje1']="No se identifico la Unidad";
	header("location: listado.php");
	exit();
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
$tmp['name'] = filtrar_campo('todo', 120, $file['name']);
$tmp['ext']  = filtrar_campo('todo', 250, $file['type']);
$tmp['archivo'] = pg_escape_bytea($imagen);
} else { $tmp="";
$_SESSION['mensaje1'] = "Tamaño del Archivo (".$file['name'].") No puede ser mayor a 15Mb"; }
//} else { $tmp="";
//$_SESSION['mensaje1'] = "El Formato del Archivo no es Correcto"; }
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
<link href="../Legend/admin/assets/boxer/css/jquery.fs.boxer.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/fuelux/css/fuelux.min.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/select2/css/select2.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/vex/css/vex.css" rel="stylesheet" />
<link href="../Legend/admin/assets/vex/css/vex-theme-top.css" rel="stylesheet" />
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="../Legend/admin/assets/icheck/skins/square/_all.css" rel="stylesheet"/>
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

<div class="header">Editar Unidad<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="editar.php" onsubmit="return validar();" enctype="multipart/form-data">
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


<?php $cli = "";
$rs = pg_query($link, filtrar_sql("select rif, razon_social from clientes where id_cliente = ".$_SESSION['unidad_cliente'])); $rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1]; ?>
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
<?php 
$rs = pg_query($link, filtrar_sql("select zongeo.id_zongeo, nombre from zongeo, unidades where ( unidades.id_cliente = $c or -1 = $c ) and (zongeo.id_zongeo = $z or $z < 1) order by nombre asc "));
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
$rs=pg_query($link, filtrar_sql("select areas.id_area, descripcion from areas, unidades where id_dependencia = 0 and ( unidades.id_cliente = $c or -1 = $c ) and (areas.id_area = $a or $a < 1) order by descripcion asc ")); 
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



<div class="form-group"><label>Dispositivo</label>
<div><select id="disp" name="disp" class="selectpicker">
<option value="0" selected="selected">Seleccione un Dispositivo</option>
<?php $rs = pg_query($link, filtrar_sql("select id_dispositivo, descripcion, serial from dispositivos, tipo_disp where dispositivos.id_tipo_disp = tipo_disp.id_tipo_disp and id_cliente = ".$_SESSION['unidad_cliente']." order by descripcion, serial asc "));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($disp==$r[0]) echo "selected";?> ><?php echo $r[1]." ".$r[2];?></option> 
<?php } } ?>
</select></div>
</div>


<div class="form-group"><label>Tipo de Unidad</label>
<div><select id="conf" name="conf" class="selectpicker" onchange="CargarConfUnid();">
<option value="0" selected="selected">Seleccione un Tipo Unidad</option>
<?php $rs = pg_query($link, filtrar_sql("select confunid.id_confunid, nombre, n_configuracion_01, n_configuracion_02, n_configuracion_03, 
n_configuracion_04, confunid.codigo_principal from confunid, unidades where ( unidades.id_cliente = $c or -1 = $c ) and (confunid.id_confunid = $t or $t < 1) order by nombre asc "));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){ 
$id = $r[0].":::".$r[2].":::".$r[3].":::".$r[4].":::".$r[5].":::".$r[1];?>    
<option value="<?php echo $id;?>" <?php if($conf==$r[0]) echo "selected";?> ><?php echo $r[6];?></option> 
<?php } } ?>
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
<label>Km Acumulado</label>
<input id='kma' name='kma' type='text' placeholder='Kilometraje Acumulado' class='form-control' value='<?php echo $kma;?>' readonly="readonly"/>
</div>  
<p>&nbsp;</p>    
</div>

<div class='form-group'>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<label>Fecha de Instalaciòn</label>
<input id='fi' name='fi' type='text' placeholder='Fecha de Instalaciòn' class='form-control' maxlength='12' value='<?php echo $fi;?>'/>
</div> 
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<label>Hrs Acumuladas</label>
<input id="hra" name="hra" type="text" placeholder="Horas Acumuladas" class="form-control" value="<?php echo $hra;?>" readonly="readonly"/>
</div>
<p>&nbsp;</p>    
</div>


<div class="form-group"><label>Responsable</label>
<div><select id="resp" name="resp" class="selectpicker">
<option value="0" selected="selected">Seleccione un Responsable</option>
<?php $rs = pg_query($link, filtrar_sql("select personal.id_personal, ci, nombre from personal, unidades where ( unidades.id_cliente = $c or -1 = $c ) and ((personal.id_area=$a or $a<1) and (personal.id_zona=$z or $z<1) and (personal.id_confunid=$t or $t<1)) order by ci asc ")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($resp==$r[0]) echo "selected";?> ><?php echo $r[1]." ".$r[2];?></option> 
<?php } } ?>    
</select></div></div>

<div class="form-group"><label>Observaciones</label>
<textarea rows="8" name="obs" id="obs" onkeypress="return permite(event, 'todo')" class="form-control"><?php echo $obs; ?></textarea>
</div>

</div>
       
                                    

<div class="step-pane" id="step2">
<div class="row">
<?php $rs = pg_query($link, filtrar_sql("select id_unidimg, descripcion from unidimg where id_unidad = ".$_SESSION['unidad']." order by descripcion asc")); 
$r = pg_num_rows($rs);
if($r==false || $r<1){ ?>
<div class="form-group"><label>No Hay Fotos</label></div>
<?php } else { $i=1; while($r=pg_fetch_array($rs)){ $dir = "vista.php?id=".$r[0]; ?>
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"  id="foto_<?php echo $r[0];?>">
<label><?php echo $i.".- ".$r[1]; ?></label>
<img src='../img/cross.png' width='15' height='15'  title='Eliminar El Archivo' rel='tooltip' data-placement='right' onclick="pregunta(<?php echo $r[0];?>);"/>
<a href="<?php echo $dir;?>" class="boxer thumbnail" title="<?php echo $r[1]; ?>" rel="gallery"><img src="<?php echo $dir;?>" alt="Thumbnail One" /></a></div>
<?php $i++; } } 
if($i>0) $i--;
$tope = 6 - $i;?>
</div>

<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="fotos"></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="dess"></div>  
<input type="hidden" name="cant_doc" id="cant_doc" value="0" />                         
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
	if(document.getElementById('zona').value=="0"){ 
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
		
	/*} else if(document.getElementById('conf3').value.length<1){ 
		mensaje("Debe indicar la "+label3,1);
		
	} else if(document.getElementById('conf4').value.length<1){ 
		mensaje("Debe indicar la "+label4,1);*/
	
	} else if(document.getElementById('kmi').value.length<1){ 
		mensaje("Debe indicar El Kilometraje Inicial",1);
		
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
$("#resp").select2();
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


CargarConfUnid();
</script>


<script src="../Legend/admin/assets/boxer/js/jquery.fs.boxer.min.js"></script>
<script>function lightbox() { 
	$(".boxer").boxer(); 
} lightbox();</script>


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
</script>


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


<script>
var items = 0;
function agregar_doc(){ 

	$("#fotos").append("<div class='form-group'><label>"+(items+1)+".- Archivo</label><input id='foto"+(items)+"' name='foto"+(items)+"' accept='*' type='file' class='filestyle' data-classButton='btn btn-primary btn-lg' data-input='false'></div>");
	
	$("#dess").append("<div class='form-group'><label>Descripción</label><input id='des"+(items)+"' name='des"+(items)+"' type='text' placeholder='Breve Descripción del Archivo' class='form-control' maxlength='120' value='' onkeypress='return permite(event,todo)' /></div>");
	
	items++;
	document.getElementById('cant_doc').value = items;
}

</script>

<script src="../Legend/admin/assets/vex/js/vex.js"></script>
<script src="../Legend/admin/assets/vex/js/vex.dialog.js"></script>
<script>
function pregunta(id) {
         vex.defaultOptions.className = 'vex-theme-top';
         vex.dialog.open({
             message: '¿ Desea Eliminar La Foto ? ',
             buttons: [
                 $.extend({}, vex.dialog.buttons.YES, {
                     text: 'SI',
					 click: function(){ 
					 	$.get('eliminar_doc.php?id='+id , function(resultado){ 
							if(resultado=="true"){ 
								$("#foto_"+id).remove();
								mensaje('Foto Eliminada',3);
								agregar_doc();
								File_style();
							} 
						});
					 }
                 }),
                 $.extend({}, vex.dialog.buttons.NO, {
                     text: 'NO'
                 })
             ]
         });
     }
</script>


<?php for($i=0; $i<$tope; $i++){ echo "<script>agregar_doc();</script>"; }?>
<script src="../Legend/admin/assets/bootstrapfilestyle/js/bootstrap-filestyle2.js"></script>
<script>File_style();</script>
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