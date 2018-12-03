<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 57;
$_SESSION['acc']['form'] = 124;
include("../complementos/permisos.php");

if(isset($_REQUEST['pers'])){ $_SESSION['usuario_personal'] = filtrar_campo('int', 6, $_REQUEST['pers']); }
	
if(isset($_SESSION['usuario_personal'])){ 
$rs = pg_query($link, filtrar_sql("select * from usuarios where id_usuario = ".$_SESSION['usuario_personal'])); 
	$r = pg_num_rows($rs);
	if($r!=false && $r>0){ 
		$rs = pg_fetch_array($rs);
		$nom = $rs[1];
		$tipou = $rs[6];
		if($rs[7]=='t') $est="Activo"; else $est = "Inactivo";
		
		$rs = pg_query($link, filtrar_sql("select nombre from tipo_usuarios where id_tipou = $tipou"));
		$rs = pg_fetch_array($rs);
		$tipou = $rs[0];
		
if($r[8]==0){ $area = "- -"; 
} else if($r[8]==-1){ $area="TODAS LAS AREAS"; 
} else { 
$qs = pg_query($link, filtrar_sql("select descripcion from areas where id_area = ".$r[8]));
$qs = pg_fetch_array($qs); $area = $qs[0]; }

if($r[9]==0){ $zona = "- -"; 
} else if($r[9]==-1){ $zona="TODAS LAS ZONAS GEOGRAFICAS"; 
} else { 
$qs = pg_query($link, filtrar_sql("select nombre from zongeo where id_zongeo = ".$r[9]));
$qs = pg_fetch_array($qs); $zona = $qs[0]; }

if($r[10]==0){ $conf = "- -"; 
} else if($r[10]==-1){ $conf="TODOS LOS TIPO DE UNIDAD"; 
} else { 
$qs = pg_query($link, filtrar_sql("select codigo_principal from confunid where id_confunid = ".$r[10]));
$qs = pg_fetch_array($qs); $conf = $qs[0]; } 
		Auditoria("Accedio Al Modulo Ver Usuario: $nom",$_SESSION['usuario_personal']);

		
	} else { 
		$_SESSION['mensaje1']="No se identifico el usuario";
		Auditoria("Usuario No Identificado",$_SESSION['usuario_personal']);
		unset($_SESSION['usuario_personal']);
		header("location: listado.php");
		exit();
	}
	
} else { 
	$_SESSION['mensaje1']="No se identifico el usuario";
	Auditoria("Usuario No Identificado",$_SESSION['usuario_personal']);
	unset($_SESSION['usuario_personal']);
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
<link href="../Legend/admin/assets/select2/css/select2.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/fuelux/css/fuelux.min.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/icheck/skins/square/_all.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="../stickytableheaders/css/component.css" />
		<!--[if IE]>
  		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
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
<li><a href="#">Administración de Sistema</a></li>
<li><a href="#">Usuarios</a></li>
<li><a href="#">Ver</a></li>
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

<div class="header">Ver Usuario<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="ver" method="post" action="ver.php">
<fieldset>
<div class="fuelux">
<div id="MyWizard" class="wizard">
<ul class="steps">
<li data-target="#step1"  onclick="$('#MyWizard').wizard('selectedItem', { step: 1 });"  class="active">1.- Datos del Usuario<span class="chevron"></span></li>
<li data-target="#step2"  onclick="$('#MyWizard').wizard('selectedItem', { step: 2 });" >2.- Control de Acceso<span class="chevron"></span></li>

</ul>
</div>
<div class="step-content">


<div class="step-pane active" id="step1">   
                         
<div class="form-group"><label>Nombre de Usuario</label>
<input id="nom" name="nom" type="text" placeholder="Nombre de Usuario" class="form-control" maxlength="250" value="<?php echo $nom;?>" readonly="readonly" /></div>

<div class="form-group"><label>Tipo de Usuario</label>
<input id="tipou" name="tipou" type="text" placeholder="Tipo de Usuario" class="form-control" maxlength="250" value="<?php echo $tipou;?>" readonly="readonly" /></div>

<div class="form-group"><label>Área</label>
<input id="area" name="area" type="text" placeholder="Área" class="form-control" value="<?php echo $area;?>" readonly="readonly" /></div>

<div class="form-group"><label>Zona Geográfica</label>
<input id="zona" name="zona" type="text" placeholder="Zona Geográfica" class="form-control" value="<?php echo $zona;?>" readonly="readonly" /></div>

<div class="form-group"><label>Tipo de Unidad</label>
<input id="conf" name="conf" type="text" placeholder="Tipo de Unidad" class="form-control" value="<?php echo $conf;?>" readonly="readonly" /></div>

<div class="form-group"><label>Estatus</label>
<input id="est" name="est" type="text" placeholder="Estatus de Usuario" class="form-control" maxlength="250" value="<?php echo $est;?>" readonly="readonly" /></div>
</div>
                                    
                                    
                                    
                                    
                                    
<div class="step-pane" id="step2">  

<div class="component" style="max-width:850px; max-height:600px; width:850px; height:560px; overflow:auto; margin:0px; padding:0px;">
<table class="overflow-y">
					<thead>                    
<tr><th style="min-width:210px;">Formularios / Botones</th>
<?php $bot = array(); $i=0;
$rs = pg_query($link, filtrar_sql("select id_bot, nom from sys_botones where id_bot<10 and id_bot<>7 and id_bot<>8 and id_bot<>6 and id_bot>2 order by nom asc"));
while( $r = pg_fetch_array($rs)){ $bot[$i]=$r[0];?>
<th align="center"><?php echo $r[1];?></th>
<?php $i++; } $bot[$i]=-1;?>
<th align="center"><?php echo "Opciones Extras";?></th>
</tr>
					</thead>
					<tbody>
<?php $rs = pg_query($link, filtrar_sql("select sys_modulos.id_mod, sys_modulos.nom from sys_modulos where sys_modulos.est='Activo' and id_dependencia <> 1 and id_dependencia <> 0 order by sys_modulos.nom asc ")); 
$t=0;
while( $r = pg_fetch_array($rs) ){ ?>
<tr><th><?php echo $r[1]; ?></th>
<?php $tmp = array(0,0,0,0,-1); 
for($i=0; $i<count($bot); $i++){  
$qs = pg_query($link, filtrar_sql("select id_acc from sys_acciones where id_mod = ".$r[0]." and id_bot = 9 order by id_form asc"));
	$q = pg_num_rows($qs);
	if($q!=false && $q>0){ 
		$j = 0;
		while( $q = pg_fetch_array($qs)) { 
			$tmp[$j] = $q[0];
			$j++;
		}
	} else { $tmp[$i] = 0; }
} 
for($i=0; $i<count($bot); $i++){ if($tmp[$i]!=0 && $tmp[$i]!=-1){ 

$ws = pg_query($link, filtrar_sql("select count(id_acl) from sys_acl where id_usu = ".$_SESSION['usuario_personal']." and id_acc = ".$tmp[$i])); $ws = pg_fetch_array($ws); ?><td align="center"><input type="checkbox" name="chk_<?php echo $t.$i;?>" class="select_<?php echo $i;?>" value="<?php echo $tmp[$i];?>" <?php if($ws[0]>0) echo "checked";?> disabled="disabled" /></td>
<?php } else { ?> 
<td align="center"> - - </td> <?php } } ?>
</tr><?php $t++; } ?>

</tbody>
</table>

</div>
</div>

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
	if(document.getElementById('nom').value.length<8){ 
		mensaje("Debe indicar el nombre de usuario y debe contener al menos 8 digitos",1);
		
	
	} else if(document.getElementById('tipou').value=="0"){ 
		mensaje("Debe seleccionar el Tipo de Usuarios",1);
		
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

$("#nom").maxlength({ alwaysShow: true });
$("#est").select2();
$("#tipou").select2();</script>

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


<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-throttle-debounce/1.1/jquery.ba-throttle-debounce.min.js"></script>
<script src="../stickytableheaders/js/jquery.stickyheader.js"></script>
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