<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 37;
$_SESSION['acc']['form'] = 91;
include("../complementos/permisos.php");


unset($_SESSION['mensaje']);
if(isset($_REQUEST['perm'])){ $_SESSION['permiso']=$_REQUEST['perm']; }

if(isset($_POST['guardar'])){ 
$unid = filtrar_campo('cadena', 120, $_POST['unid']);
$res = filtrar_campo('int', 6, $_POST['res']);   
$ser = filtrar_campo('todo', 120, $_POST['ser']); 
$fe = filtrar_campo('date', 10, $_POST['fe']); 
$fv = filtrar_campo('date', 10, $_POST['fv']);
$img = selecciono2($_POST['imagen']); 
$tmp = selecciono($_POST['imagen']); 
$cant_doc = filtrar_campo('int', 6, $_POST['cant_doc']);

if(empty($fv)){ $tmp2 = "NULL"; } else { $tmp2 = "'".date2($fv)."'"; }

$n = count($_SESSION['tmp_req']);
for($i=0; $i<$n; $i++){ 
	$_SESSION['tmp_req'][$i][2] = $_POST['ids_'.$_SESSION['tmp_req'][$i][0]];
}

if(empty($unid)){ $_SESSION['mensaje1']="Debe seleccionar la unidad";
} else if(empty($res)){ $_SESSION['mensaje1']="Debe seleccionar el responsable";
} else if(empty($ser)){ $_SESSION['mensaje1']="Debe indicar el serial";
} else if(empty($fe)){ $_SESSION['mensaje1']="Debe seleccionar la fecha de expedición";
} else if(in_array(233,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 
list($unid, $area, $zona) = explode(":::",$unid);

$fotos = array();
for ($i=0; $i<$cant_doc; $i++){ 
	$fotos[$i][0] = Preparar_Imagen($_FILES["foto$i"]);
	$fotos[$i][1] = $_POST["des$i"];
} 
if(isset($_SESSION['mensaje'])==false)


{ // si validar 2

if(strcmp($tmp2,"NULL")==0){ 
	$est=9;
} else { 
  $actual = date("Y-m-d");
  $rs = pg_query($link, filtrar_sql("select $tmp2::date < '$actual'::date, '$actual'::date >= ($tmp2::date - (dias_gestion * interval '1 day'))::date, id_permiso from permisos, tipo_permisos where permisos.id_tipo_permiso = tipo_permisos.id_tipo_permiso and id_permiso  = ".$_SESSION['permiso']) );
  $r = pg_fetch_array($rs);
  if(strcmp($r[0],"f")==0){ //si fv menor a actual :: vigente 
	  if(strcmp($r[0],"t")==0) $est = 10; else $est = 9;	
  }
}

	$rs = pg_query($link, filtrar_sql("update permisos set id_unidad=$unid, id_responsable_especifico=$res, serial='$ser', fecha_expedicion='".date2($fe)."', fecha_vencimiento=$tmp2, is_imagen=$tmp, id_area = $area, id_zona = $zona where id_permiso = ".$_SESSION['permiso']));
	if($rs){ 

Auditoria("Actualizo Permiso: $ser",$_SESSION['permiso']);
//================================ REQUISITOS =======================================
for($i=0; $i<$n; $i++){ 
	if($_SESSION['tmp_req'][$i][3]!=0){ 
		$sql = "update reqperm set is_doc = ".selecciono($_SESSION['tmp_req'][$i][2])." where id_reqperm = ".$_SESSION['tmp_req'][$i][3];
	} else { 
		$sql= "insert into reqperm(id_permiso, id_reqtipperm, is_doc) values (".$_SESSION['permiso'].", ".$_SESSION['tmp_req'][$i][0].", ".selecciono($_SESSION['tmp_req'][$i][2]).")"; 
	}
	$rs = pg_query($link, filtrar_sql($sql));
	if($rs==false){
		Auditoria("Problema con Los Requerimientos del Permiso Error: ".pg_last_error($link),$_SESSION['permiso']);
	}
	unset($sql);
}
//===================================================================================	
//=============================  ARCHIVOS  ==========================================	
for ($i=0; $i<$cant_doc; $i++){
	if(empty($fotos[$i][0])==false){ // SI ARCHIVO VACIO
		$rs = pg_query($link, filtrar_sql("insert into permimg(id_permiso, descripcion, archivo, extension, nombre) values (".$_SESSION['permiso'].", '".$fotos[$i][1]."', '".$fotos[$i][0]['archivo']."', '".$fotos[$i][0]['ext']."', '".$fotos[$i][0]['name']."')"));
		if($rs==false) {
			$_SESSION['mensaje1']="No se Logro Registrar El Archivo del Permiso";
			Auditoria("Problema al registrar Archivo del Permiso Error: ".pg_last_error($link), $_SESSION['permiso']);
		}
	}
}
//===================================================================================	
		$_SESSION['mensaje3']="Permiso Editado";
		unset($_SESSION['tmp_req']);
		unset($_SESSION['permiso_tipo']);
		unset($_SESSION['permiso']);
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro editar el permiso";
		Auditoria("Problema al actualizar El Permiso Error: ".pg_last_error($link),$_SESSION['permiso']);
	} 
} // si validar 2
} // si validar

} else if(isset($_SESSION['permiso'])){ 
unset($_SESSION['tmp_req']);
$rs = pg_query($link, filtrar_sql("select * from permisos where id_permiso = ".$_SESSION['permiso']));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico El Permiso";
	Auditoria("Permiso No Identificado ",$_SESSION['permiso']);
	unset($_SESSION['permiso']);
	header("location: listado.php");
	exit();
} else {
	$rs = pg_fetch_array($rs);
	$_SESSION['permiso_tipo'] = $rs[1];  
	$unid = $rs[2];  
	$res = $rs[3];  
	$ser = $rs[4];  
	$img = selecciono2($rs[5]);
	$fe = date1($rs[6]);  
	if($rs[7]!=NULL) $fv = date1($rs[7]); else $fv="";   
	$_SESSION['permiso_cliente'] = $rs[8];
	$clas = $rs[12];
	Auditoria("Accedio al Modulo Editar Permiso: $ser",$_SESSION['permiso']);
	
	// CARGANDO REQUISITOS 
	$rs = pg_query($link, filtrar_sql("select id_reqtipperm, descripcion from req_tipperm where id_tipo_permiso = ".$_SESSION['permiso_tipo']." and estatus='Activo' order by descripcion asc "));
	$r = pg_num_rows($rs);
	if($r!=false && $r>0){ $i=0; 
	while($r = pg_fetch_array($rs)){
	$_SESSION['tmp_req'][$i][0]=$r[0];
	$_SESSION['tmp_req'][$i][1]=$r[1];
	$qs = pg_query($link, filtrar_sql("select is_doc, id_reqperm from reqperm where id_permiso = ".$_SESSION['permiso']." and id_reqtipperm = ".$r[0])); 
	$q=pg_num_rows($qs); 
	if($q==false || $q<1){ 
		$_SESSION['tmp_req'][$i][2]="off"; 
		$_SESSION['tmp_req'][$i][3]=0; 
	} else { 
		$q=pg_fetch_array($qs); 
		if(strcmp($q[0],"t")==0){  
			$_SESSION['tmp_req'][$i][2]="on"; 
			$_SESSION['tmp_req'][$i][3]=$q[1];
		} else { 
			$_SESSION['tmp_req'][$i][2]="off";
			$_SESSION['tmp_req'][$i][3]=$q[1];
		}
	}
	$i++; } }
}


} else { 
	$_SESSION['mensaje1']="No se identifico el permiso";
	Auditoria("Permiso No Identificado ",$_SESSION['permiso']);
	unset($_SESSION['permiso']);
	header("location: listado.php");
	exit();
}








function selecciono($op){ 
	if(strcmp($op,"on")==0) return 'true'; 
	else return 'false';
}
function selecciono2($op){
	if($op=='t' || strcmp($op,"on")==0) return 'checked'; 
	else return '';
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
$_SESSION['mensaje'] = "Tamaño del Archivo (".$file['name'].") No puede ser mayor a 15Mb"; }
//} else { $tmp="";
//$_SESSION['mensaje'] = "El Formato del Archivo no es Correcto"; }
} else { $tmp="";
$_SESSION['mensaje'] = "El Archivo (".$file['name'].") No ha Sido Cargado"; }
	}
return $tmp;
}?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<link href="../Legend/admin/assets/boxer/css/jquery.fs.boxer.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/vex/css/vex.css" rel="stylesheet" />
<link href="../Legend/admin/assets/vex/css/vex-theme-top.css" rel="stylesheet" />
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
<li><a href="#">Configuración</a></li>
<li><a href="#">Usuarios</a></li>
<li><a href="#">Permisos</a></li>
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

<div class="header">Editar Permiso<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="editar.php" onsubmit="return validar();" enctype="multipart/form-data">
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

<div class="form-group"><label>Tipos de Permiso</label>
<?php $rs = pg_query($link, filtrar_sql("select nombre, cant_doc from tipo_permisos where id_tipo_permiso = ".$_SESSION['permiso_tipo'])); $r = pg_fetch_array($rs); 
$tipo = $r[0]; $_SESSION['permiso_cant_doc'] = $r[1];  ?>    
<input id="tipo" name="tipo" type="text" placeholder="Tipo de Permiso" class="form-control" value="<?php echo $tipo;?>" readonly="readonly" /></div>

<?php $cli = "";
$rs = pg_query($link, filtrar_sql("select rif, razon_social from clientes where id_cliente = ".$_SESSION['permiso_cliente'])); $rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1]; ?>
<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Unidades del Cliente</label>
<div><select id="unid" name="unid" class="selectpicker" onchange="rellenar();">
<option value="0" selected="selected">Seleccione una Unidad</option>
<?php 
$a=filtrar_campo('int', 6, $_SESSION['miss'][0]);
$z=filtrar_campo('int', 6, $_SESSION['miss'][1]);
$t=filtrar_campo('int', 6, $_SESSION['miss'][2]);
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

$rs = pg_query($link, filtrar_sql("select id_unidad, areas.id_area, zongeo.id_zongeo, confunid.codigo_principal, unidades.codigo_principal, areas.descripcion, zongeo.nombre from unidades, areas, zongeo, confunid where unidades.id_area = areas.id_area and unidades.id_zona = zongeo.id_zongeo and unidades.id_confunid = confunid.id_confunid and ( unidades.id_cliente=$c and areas.id_cliente=$c and zongeo.id_cliente=$c and confunid.id_cliente=$c ) and ((areas.id_area = $a or $a < 1) and (zongeo.id_zongeo = $z or $z < 1) and (confunid.id_confunid = $t or $t < 1)) order by confunid.codigo_principal, unidades.codigo_principal asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0].":::".$r[1].":::".$r[2].":::".$r[3].":::".$r[4].":::".$r[5].":::".$r[6];?>" <?php if($unid==$r[0]) echo "selected";?> ><?php echo $r[3]." - ".$r[4];?></option> 
<?php } } ?>    
</select></div></div>

<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Área</label>
<input id="area" name="area" type="text" placeholder="Área a la que Pertenece la Unidad" class="form-control" value="" readonly="readonly" /></div>

<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Zona</label>
<input id="zona" name="zona" type="text" placeholder="Zona a la que Pertenece la Zona" class="form-control" value="" readonly="readonly" /></div>

<div class="form-group"><label>Responsable</label>
<div><select id="res" name="res" class="selectpicker">
<option value="0" selected="selected">Seleccione un Responsable</option>
<?php $rs = pg_query($link, filtrar_sql("select id_personal, ci, nombre from personal where id_cliente = ".$_SESSION['permiso_cliente']." order by ci asc ")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($res==$r[0]) echo "selected";?> ><?php echo $r[1]." ".$r[2];?></option> 
<?php } } ?>    
</select></div></div>

<div class="form-group"><label>Serial</label>
<input id="ser" name="ser" type="text" placeholder="Serial, Nro ó Código del Permiso" class="form-control" maxlength="118" value="<?php echo $ser;?>" onkeypress="return permite(event,'todo');" /><p class="help-block">Ejemplo: N# A-001</p></div>

<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Fecha de Expedición</label>
<input id="fe" name="fe" type="text" placeholder="Fecha de Expedición" class="form-control" maxlength="12" value="<?php echo $fe;?>"  /><p class="help-block">Ejemplo: 01/01/2014</p></div>

<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Fecha de Vencimiento</label><input id="fv" name="fv" type="text" placeholder="Fecha de Vencimiento" class="form-control" maxlength="12" value="<?php echo $fv;?>"  /><p class="help-block">Ejemplo: 01/01/2014</p></div>

<div class="skin skin-square skin-section checkbox icheck form-group">
<label for="square-checkbox-2" class="icheck">
<input tabindex="6" type="checkbox" name="imagen" id="imagen" <?php echo $img;?> /> ¿ Usa Imagenes ?
</label>
</div>
</div>
       
                                    
                                              
<div class="step-pane" id="step2">
<?php 
$cuerpo1 = "";
$cuerpo2 = "";

$n = count($_SESSION['tmp_req']); $i=0;
while($i<$n){ 
	$cuerpo1 .= "<div class='skin skin-square skin-section checkbox icheck form-group'><label for='square-checkbox-2' class='icheck'><input tabindex='6' type='checkbox' name='ids_".$_SESSION['tmp_req'][$i][0]."' id='ids_".$_SESSION['tmp_req'][$i][0]."' ".selecciono2($_SESSION['tmp_req'][$i][2])." />".$_SESSION['tmp_req'][$i][1]."</label></div><br/>";
	$i++;

if($i<$n){
	$cuerpo2 .= "<div class='skin skin-square skin-section checkbox icheck form-group'><label for='square-checkbox-2' class='icheck'><input tabindex='6' type='checkbox' name='ids_".$_SESSION['tmp_req'][$i][0]."' id='ids_".$_SESSION['tmp_req'][$i][0]."' ".selecciono2($_SESSION['tmp_req'][$i][2])." />".$_SESSION['tmp_req'][$i][1]."</label></div><br/>";
	$i++; 
}	
}

if(empty($cuerpo1)){ 
	$cuerpo1="<div class='form-group'><label>No Hay Requisitos</label></div>"; 
}

echo "<div class='row'>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>$cuerpo1</div>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>$cuerpo2</div>
</div>";
?>
</div>




<div class="step-pane" id="step3">
<div class="row">
<?php $rs = pg_query($link, filtrar_sql("select id_permimg, descripcion, extension from permimg where id_permiso = ".$_SESSION['permiso']." order by descripcion asc") ); 
$r = pg_num_rows($rs);
if($r==false || $r<1){ ?>
<div class="form-group"><label>No Hay Fotos</label></div>
<?php } else { $i=1; while($r=pg_fetch_array($rs)){ 
$dir = "vista.php?id=".$r[0]; 

if(strpos($r[2],"image/")>-1){ ?>
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="foto_<?php echo $r[0];?>"><label><?php echo $i.".- ".$r[1]; ?></label>
<img src='../img/cross.png' width='15' height='15'  title='Eliminar El Archivo' rel='tooltip' data-placement='right' onclick="pregunta(<?php echo $r[0];?>);"/>
<a href="<?php echo $dir;?>" class="boxer thumbnail" title="<?php echo $r[1]; ?>" rel="gallery"><img src="<?php echo $dir;?>" alt="Thumbnail One" /></a></div>

<?php } else { ?>
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="foto_<?php echo $r[0];?>"><label><?php echo $i.".- ".$r[1]; ?></label>
<img src='../img/cross.png' width='15' height='15'  title='Eliminar El Archivo' rel='tooltip' data-placement='right' onclick="pregunta(<?php echo $r[0];?>);"/>
<a href="" target="new" class="boxer thumbnail" title="<?php echo $r[1]; ?>" rel="gallery"><img src="../img/arch_descargar.png" width="250" height="250" alt="Thumbnail One" onclick="window.open('descargar_archivo.php?id=<?php echo $r[0];?>','_blank')" /></a></div>

<?php } $i++; } } 
if($i>0) $i--;
$tope = $_SESSION['permiso_cant_doc'] - $i;?>
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
	if(document.getElementById('unid').value=="0"){ 
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

rellenar();</script>

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
<script src="../Legend/admin/assets/boxer/js/jquery.fs.boxer.min.js"></script>
<script>function lightbox() { $(".boxer").boxer(); } lightbox();</script>

<script src="../Legend/admin/assets/bootstrapmaxlength/js/bootstrap-maxlength.min.js"></script>
<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>
<script>
$("#ser").maxlength({ alwaysShow: true });
$("#fv").maxlength({ alwaysShow: true });
$("#fe").maxlength({ alwaysShow: true });

$("#unid").select2();
$("#res").select2();
</script>
<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="../../jquery/development-bundle/themes/custom-theme/jquery.ui.datepicker.css"/>
<script> 
$(function() {
	$( "#fv" ).datepicker();
	$( "#fe" ).datepicker();
});</script>


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
 }
icheck();</script>

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
             message: '¿ Desea Eliminar El Archivo ? ',
             buttons: [
                 $.extend({}, vex.dialog.buttons.YES, {
                     text: 'SI',
					 click: function(){ 
					 	$.get('eliminar_doc.php?id='+id , function(resultado){ 
							if(resultado=="true"){ 
								$("#foto_"+id).remove();
								mensaje('Archivo Eliminado',3);
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