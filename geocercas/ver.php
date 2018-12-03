<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 63;
$_SESSION['acc']['form'] = 167;
include("../complementos/permisos.php");

if(isset($_REQUEST['geo'])){ $_SESSION['geo']=$_REQUEST['geo']; }

if(isset($_SESSION['geo'])){
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
	$cli = $rs[1];
	$area = $rs[2];
	$zona = $rs[3];
	$conf = $rs[4];
	$nom = $rs[5];
	$tipo1 = tipo1($rs[6]); $aux=$rs[6];
	$tipo2 = tipo2($rs[7]);
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
	$rs = pg_query($link, filtrar_sql("select rif, razon_social from clientes where id_cliente = $cli")); 
	$rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1]; 
	$rs = pg_query($link, filtrar_sql("select nombre from zongeo where id_zongeo = $zona"));
	$rs = pg_fetch_array($rs); $zona = $rs[0];
	$rs = pg_query($link, filtrar_sql("select descripcion from areas where id_area = $area"));
	$rs = pg_fetch_array($rs); $area = $rs[0];
	$rs = pg_query($link, filtrar_sql("select codigo_principal from confunid where id_confunid = $conf"));
	$rs = pg_fetch_array($rs); $conf = $rs[0];
	if(empty($resp)){ $resp="- -"; } else { 
	$rs = pg_query($link, filtrar_sql("select ci, nombre from personal where id_personal = $resp")); 
	$rs = pg_fetch_array($rs); $resp = $rs[0]." ".$rs[1]; } 
	if(empty($circulo)==false){ 
		$circulo = str_replace("<","",str_replace(">","",$circulo));
		$circulo = str_replace("(","",str_replace(")","",$circulo));
		list($mi_lat, $mi_lng, $radio) = explode(",",$circulo);
		$punto="($mi_lat,$mi_lng)";
		$poligono="[0,0]";
		$puntos=array();
	} else if(empty($poligono)==false){ 
		$puntos = str_replace("((","",str_replace("))","",$poligono));
		$tmp = explode("),(",$puntos);
		$puntos = array();
		for($i=0; $i<count($tmp); $i++){ 
			$puntos[$i] = explode(",",$tmp[$i]);
		}
		$poligono = str_replace("(","[",str_replace(")","]",$poligono));
		$mi_lat = 0; $mi_lng = 0; $punto = $radio="";
	}
}


Auditoria("Accedio Al Modulo Ver Geocerca: $nom",$_SESSION['geo']);

} else { 
	$_SESSION['mensaje1']="No se identifico La Geocerca";
	Auditoria("Geocerca No Identificada ",$_SESSION['geo']);
	unset($_SESSION['geo']);
	header("location: listado.php");
	exit();
}

function tipo2($op){
  switch($op){ 
	  case 1: $op="Alarma Cuando Salga de la Geocerca"; break;
	  case 2: $op="Alarma Cuando Entre a la Geocerca"; break;
	  case 3: $op="Si esta Fuera de la Geocerca y Fuera del Horario"; break;
  }	
return $op;
}

function tipo1($op){
  switch($op){ 
	  case 1: $op="Circular"; break;
	  case 2: $op="Poligono"; break;
  }	
return $op;
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

<div class="header">Ver Geocerca<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
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
<input id="nom" name="nom" type="text" placeholder="Nombre del Control" class="form-control" maxlength="100" value="<?php echo $nom;?>" readonly="readonly" /></div>

<div class="form-group"><label>Tipo de Geocerca</label>
<input id="tipo1" name="tipo1" type="text" placeholder="Tipo de Geocerca" class="form-control" maxlength="100" value="<?php echo $tipo1;?>" readonly="readonly" />
</div>

<div id="mi_contenedor"></div>

</div>
                                    
                                    
                                    
                                    
                                    
                                    
<div class="step-pane" id="step2">

<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Zona Geográfica</label>
<input id="zona" name="zona" type="text" placeholder="Zona Geográfica" class="form-control" value="<?php echo $zona;?>" readonly="readonly" /></div>

<div class="form-group"><label>Área</label>
<input id="area" name="area" type="text" placeholder="Área" class="form-control" value="<?php echo $area;?>" readonly="readonly" /></div>

<div class="form-group"><label>Tipo de Unidad</label>
<input id="conf" name="conf" type="text" placeholder="Tipo de Unidad" class="form-control" value="<?php echo $conf;?>" readonly="readonly" /></div>

<div class="form-group"><label>Responsable</label>
<input id="resp" name="resp" type="text" placeholder="Responsable de la Unidad" class="form-control" value="<?php echo $resp;?>" readonly="readonly" /></div>

<div class="form-group"><label>Tipo de Alarma</label>
<input id="tipo2" name="tipo2" type="text" placeholder="Tipo de Alarma" class="form-control" maxlength="100" value="<?php echo $tipo2;?>" readonly="readonly" />
</div>
</div>
		                            

<div class="step-pane" id="step3">

<div class="form-group"><label>Seleccione Los Días de Activación de las Alarmas</label><br/></div>

<div class="skin skin-square skin-section checkbox icheck form-group">
<label for="square-checkbox-2" class="icheck" style="margin-right:70px;">
<input tabindex="6" type="checkbox" name="lun" id="lun" <?php if(strcmp($lun,"on")==0) echo "checked";?>  class="dias" disabled="disabled" /> Lunes</label>

<label for="square-checkbox-2" class="icheck" style="margin-right:65px;">
<input tabindex="6" type="checkbox" name="mar" id="mar" <?php if(strcmp($mar,"on")==0) echo "checked";?>  class="dias" disabled="disabled" /> Martes</label>

<label for="square-checkbox-2" class="icheck" style="margin-right:60px;">
<input tabindex="6" type="checkbox" name="mie" id="mie" <?php if(strcmp($mie,"on")==0) echo "checked";?>  class="dias" disabled="disabled" /> Miercoles</label>

<label for="square-checkbox-2" class="icheck">
<input tabindex="6" type="checkbox" name="jue" id="jue" <?php if(strcmp($jue,"on")==0) echo "checked";?>  class="dias" disabled="disabled" /> Jueves</label>
</div>

<div class="skin skin-square skin-section checkbox icheck form-group">
<label for="square-checkbox-2" class="icheck" style="margin-right:60px;">
<input tabindex="6" type="checkbox" name="vie" id="vie" <?php if(strcmp($vie,"on")==0) echo "checked";?>  class="dias" disabled="disabled" /> Viernes</label>

<label for="square-checkbox-2" class="icheck" style="margin-right:60px;">
<input tabindex="6" type="checkbox" name="sab" id="sab" <?php if(strcmp($sab,"on")==0) echo "checked";?>  class="dias" disabled="disabled" /> Sabado </label>

<label for="square-checkbox-2" class="icheck" style="margin-right:60px;">
<input tabindex="6" type="checkbox" name="dom" id="dom" <?php if(strcmp($dom,"on")==0) echo "checked";?> class="dias" disabled="disabled" /> Domingo</label>
</div>


<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Hora de Inicio</label>
<div class="input-group">
<input type="text" id="hi" name="hi" value="<?php echo $hi;?>" data-format="hh:mm A" readonly="" class="form-control">
</div></div>
	        
<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Hora de Finalización</label>
<div class="input-group">
<input type="text" id="hf" name="hf" value="<?php echo $hf;?>" data-format="hh:mm A" readonly="" class="form-control">
</div></div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tipo de Alarma</label>
<input id="tipo_aviso" name="tipo_aviso" type="text" placeholder="Tipo de Alarma" class="form-control" maxlength="10" value="<?php echo $tipo_alarma;?>" readonly="readonly" />
</div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tipo de Mensaje</label>
<input id="tipo_msj" name="tipo_msj" type="text" placeholder="Tipo de Mensaje" class="form-control" maxlength="10" value="<?php echo $tipo_msj;?>" readonly="readonly" />
</div>
</div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tiempo de Escalabilidad (Min.)</label><input id="tiempo_esc" name="tiempo_esc" type="text" placeholder="Tiempo de Escalabilidad" class="form-control" maxlength="10" value="<?php echo $tiempo_esc;?>" readonly="readonly" />
</div>
</div>  
<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
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
</div>

</form>
</div>


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
var map;

var punto = {url: '../img/004.png',origin: new google.maps.Point(0,0),anchor: new google.maps.Point(0,0),size: new google.maps.Size(9,9)};

function tipo_geocerca(tipo_geo){ 
	if(tipo_geo==1){ 
		$('#mi_contenedor').empty();
		$('#mi_contenedor').append('<div class="form-group"><div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Radio de Alcance de la Geocerca</label><input id="radio" name="radio" type="text" placeholder="Kilometros de Alcance de la Geocerca" class="form-control" maxlength="10" value="<?php echo $radio;?>" readonly="readonly" /></div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Punto de Referencia</label><input id="punto" name="punto" type="text" placeholder="Coordenadas GPS del Punto de Referencia" class="form-control" maxlength="60" value="<?php echo $punto;?>" readonly="readonly" /></div></div><p style="min-height:50px;"></p><div id="mi_mapa" style="min-width:825px; min-height:500px; margin-bottom:50px; margin-top:50px;"></div>');
		map = new GMaps({
			el: '#mi_mapa',
			lat: <?php echo $mi_lat;?>,
		  	lng: <?php echo $mi_lng;?>,
			zoom: 16
		  }); 
		map.drawCircle({
		  id: 'mi_radio',
		  lat: <?php echo $mi_lat;?>,
		  lng: <?php echo $mi_lng;?>,
		  radius: <?php echo ($radio*1000);?>,
		  strokeColor: '#96F',
		  strokeOpacity: 1,
		  strokeWeight: 3,
		  fillColor: '#96F',
		  fillOpacity: 0.6
	    });
		map.addMarker({
			icon: punto,
			lat: <?php echo $mi_lat;?>,
			lng: <?php echo $mi_lng;?>,
			title: 'Punto de Referencia'
		});
		
	} else if(tipo_geo==2){ 
		$('#mi_contenedor').empty();
		$('#mi_contenedor').append('<div class="form-group"><div class="form-group"><label>Puntos de la Geocerca</label><textarea rows="6" name="puntos_gps" id="puntos_gps"  readonly="readonly" class="form-control"><?php echo $poligono; ?></textarea></div><div id="mi_mapa" style="min-width:825px; min-height:500px; margin-bottom:25px; margin-top:25px;"></div>');
		map = new GMaps({
			el: '#mi_mapa',
			lat: 8.776510716052352,    
        	lng: -66.697998046875,
			zoom: 6
		}); 
		map.drawPolygon({
		  paths: <?php echo $poligono;?>,
		  strokeColor: "#96F",
		  strokeOpacity: 0.8,
		  strokeWeight: 2,
		  fillColor: "#96F",
		  fillOpacity: 0.35,
		});
		<?php for($i=0; $i<count($puntos); $i++){ ?>
			map.addMarker({
				icon: punto,
				lat: <?php echo $puntos[$i][0];?>,
				lng: <?php echo $puntos[$i][1];?>,
				title: 'Punto de Referencia'
			});
		<?php } ?>
	} 
	
}

tipo_geocerca(<?php echo $aux;?>);
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