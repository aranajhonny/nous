<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 50;
$_SESSION['acc']['form'] = 159;
include("../complementos/permisos.php");

if(isset($_POST['guardar'])){  //============================================
$cant_t = $_POST['cant_t']; $cant_tr = $_POST['cant_tr']; $cant_tsr = $_POST['cant_tsr'];
$cant_prov = $_POST['cant_prov']; $cant_fact = $_POST['cant_fact']; $obs = $_POST['obs'];
$porc_tr = $_POST['porc_tr']; $porc_tsr = $_POST['porc_tsr']; $costo = $_POST['costo'];
$fe = date("Y-m-d H:i:s");  $tiempo="1 Día"; $fi = $ff = date("Y-m-d");
$total_fact = $_POST['total_fact'];
	
//********************************* CARGANDO DETALLES ************************************
$detalles = "insert into det_ejemant(id_ejemant, id_composicion, hecho, descripcion, obs) values ";
for($i=1; $i<=$cant_t; $i++){ 
	if(isset($_POST["chk_$i"])) $hecho = "true"; else $hecho = "false";
	if(empty($_POST["det_$i"])==false) $detalles .= "(:::, ".$_POST["comp_$i"].", $hecho, '".$_POST["det_$i"]."', '".$_POST["obs_$i"]."'),"; 
}
$detalles = substr($detalles,0,strlen($detalles)-1);
//****************************************************************************************
//********************************* CARGANDO FACTURAS ************************************

echo "Total Fact: ".$total_fact."<br/>";
$facturas = "insert into factmant(id_ejemant, id_provserv, nro, ff, fr, monto, archivo, tipo) values ";
for($i=1; $i<=$total_fact; $i++){
	if(empty($_POST["nro_$i"])==false) { 
		$facturas .= "(:::, ".$_POST["prov_$i"].", '".$_POST["nro_$i"]."', '".date2($_POST["ff_$i"])."', '".date2($_POST["fr_$i"])."', ".$_POST["monto_$i"].", NULL, ''),";
	}
}
$facturas = substr($facturas,0,strlen($facturas)-1);
//****************************************************************************************


if(isset($_SESSION['progmant'])==false){ $_SESSION['mensaje1']="Debe Seleccionar Una Unidad";
} else if($cant_fact==0){  $_SESSION['mensaje1']="Debe Agregar Al Menos Una Factura"; 
} else if($total_fact==0){ $_SESSION['mensaje1']="Debe Agregar Al Menos Una Factura"; 
} else if($cant_t==0){ $_SESSION['mensaje1']="Debe Agregar Detalles al Mantenimiento"; 
} else if(in_array(427,$_SESSION['acl'])==false){$_SESSION['mensaje']= "no posee permiso para guardar este registro";
} else { // si validar


if(pg_query("insert into ejemant(id_progmant, id_planmant, id_unidad, id_confunid, total, fe, obs) values (0, 0, ".$_SESSION['progmant']['unidad'].", ".$_SESSION['progmant']['confunid'].", ".($costo*1).", '$fe', '$obs')")){ 
	
	$rs = pg_query("select max(id_ejemant) from ejemant ");
	$rs = pg_fetch_array($rs);
	$id = $rs[0];
	
	$rs = pg_query("select confunid.codigo_principal, unidades.codigo_principal from confunid, unidades where unidades.id_confunid = confunid.id_confunid and unidades.id_unidad = ".$_SESSION['progmant']['unidad']); $rs = pg_fetch_array($rs); $unidad = $rs[0]." ".$rs[1];
	
	Auditoria("Agrego Ejecución de Mantenimiento a la Unidad: $unidad en Fecha ".date1($fe), $id);
	
	$detalles = str_replace(":::", $id, $detalles);
	if( pg_query($detalles) == true){ 
		Auditoria("En Agregar Ejecución de Mantenimiento Se Registraron Los Detalles del Mantenimiento", $id);
	}
	
	$facturas = str_replace(":::", $id, $facturas);
	if( pg_query($facturas) == true ){ 
		Auditoria("En Agregar Ejecución de Mantenimiento Se Registraron Las Facturas del Mantenimiento", $id);
	}
	
	if( pg_query("insert into evaluaciones(id_ejemant, tiempo, fi, ff, cant_t, cant_tr, porc_tr, cant_prov, cant_fact) values ($id, '$tiempo', '$fi 00:00:00', '$ff 00:00:00', $cant_t, $cant_tr, ".($porc_tr*1).", $cant_prov, $cant_fact)") == true){
		Auditoria("En Agregar Ejecución de Mantenimiento Se Registro La Evaluación del Mantenimiento", $id);
	}
	
	
	$_SESSION['mensaje3']="Ejecución del Mantenimiento Registrada"; 
	unset($_SESSION['detplan']);
	unset($_SESSION['progmant']);
	unset($facturas);
	unset($detalles);
	$salir = true;
	
}

} // si validar

	

} else { 
	$cant_t = $cant_tr =  $cant_tsr =  $cant_prov =  $cant_fact = "";
	$costo = $porc_tr = $porc_tsr = "";
}




if(isset($salir)){ 
echo "<html><head></head><body><script>window.open(' ', '_parent',' '); 
window.close(); </script></body></html>";

} else { 
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
<link href="../Legend/admin/assets/stepswizard/css/jquery.steps.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>";?>
<link href="../Legend/admin/assets/icheck/skins/square/_all.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>

<link href="../Legend/admin/assets/bootstrapui/css/jquery-ui-1.9.2.custom.css" rel="stylesheet"/>
<link rel="stylesheet" href="../jquery/development-bundle/themes/custom-theme/jquery.ui.datepicker.css"/>
<script src="../complementos/utilidades.js"></script>

</head>
<body>          
<div class="overlay"></div>
<div class="controlshint" ><img src="../img/swipe.png" alt="Menu Help" /></div>
<section class="wrap">
<div class="container">
<img src="../img/logo.png" height="67" width="454" onclick="location.href='../inicio/principal.php'" /><br/>

<ol class="breadcrumb">
<li><a href="#">Mantenimiento</a></li>
<li><a href="#">Ejecución de Mantenimiento</a></li>
<li><a href="#">Agregar</a></li>
<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
<li class="pull-right"><?php echo date('d/m/Y');?></li>
<li class="pull-right"><a href="" class="text-muted"><?php echo $_SESSION['miss'][6];?></a></li>
</ol>

<div class="row">
<form name="agregar" method="post" action="agregar.php" onsubmit="return validar();">


<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">
<div class="header">Agregar Ejecución de Mantenimiento<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>


<div id="wizard3">
		                        <h2>Unidad</h2>
		                        <section>
<iframe src="unidades.php" name="unid" id="unid" height="700" width="915" scrolling="no" style="border:none;background:none;"></iframe>
		                        </section>

		                        <h2>Detalle del Mant.</h2>
		                        <section style="width:915px;">
<table class="table">
<thead>
		<tr>
<th colspan="4" align="right"><label style="padding-left:750px;">Agregar Detalle</label> <img src="../img/plus.png" width="20" height="20" style="margin-left:20px;" onclick="agregar_detalle2('','','',0);" /></th>
		</tr>
	</thead>
	<thead>
		<tr>
		    <th>Nro</th>
		    <th>Hecho</th>
		    <th>Detalle</th>
            <th>Observaciones</th>
		</tr>
	</thead>
	<tbody id="detalles">
<tr><td colspan="4">Debe Agregar Detalles</td></tr>   
	</tbody>
</table>

<script>
var cantdet = 0;
function agregar_detalle2(chk, etiqueta, obs, comp){  
	if(cantdet == 0) $('#detalles').empty();
	
	cantdet++;
	
	$('#detalles').append("<tr><td>"+cantdet+"</td><td><div class='skin skin-square skin-section checkbox icheck form-group'><label for='square-checkbox-2' class='icheck'><input tabindex='6' type='checkbox' name='chk_"+cantdet+"' id='chk_"+cantdet+"' "+chk+" class='detmant' /></label></div></td><td><input type='text' name='det_"+cantdet+"' id='det_"+cantdet+"' value='"+etiqueta+"' maxlength='250' size='30' onkeypress="+'"'+"return permite(event,'todo')"+'"'+" /> <select name='comp_"+cantdet+"' id='comp_"+cantdet+"' style='max-width:200px;'></select></td><td><input type='text' name='obs_"+cantdet+"' id='obs_"+cantdet+"' value='"+obs+"' maxlength='250' size='45' onkeypress="+'"'+"return permite(event,'todo')"+'"'+" /></td></tr>");
	
	icheck();
	
	$('#comp_'+cantdet).append(ListComp);
	document.getElementById('comp_'+cantdet).value = comp;
}
</script>
		                        </section>
		                        <h2>Facturas</h2>
		                        <section style="width:915px;">
<table class="table">
	<thead>
		<tr>
<th colspan="6" align="right"><label style="padding-left:750px;">Agregar Factura</label> <img src="../img/plus.png" width="20" height="20" style="margin-left:20px;" onclick="agregar_factura();" /></th>
		</tr>
	</thead>
	<thead>
		<tr>
		    <th>Nro de Factura</th>
		    <th>Fecha Factura</th>
		    <th>Fecha Recibido</th>
            <th>Proveedor de Servicios</th>
            <th>Monto Bfs</th>
            <th>Archivo</th>
		</tr>
	</thead>
	<tbody id="facturas">
<tr><td colspan="6">Debe Agregar Facturas</td></tr>                         
	</tbody>
</table>	
<script> 
var cantfact = 0;
function agregar_factura(){ 
	if(cantfact == 0) $('#facturas').empty();
	
	if(cantfact<10) { 
		cantfact++;
	
		$('#facturas').append("<tr><td><input type='text' size='14' maxlength='20' name='nro_"+cantfact+"' id='nro_"+cantfact+"' value='' onkeypress="+'"'+"return permite(event,'todo')"+'"'+" class='nfacts' /></td><td><input type='text' size='12' name='ff_"+cantfact+"' id='ff_"+cantfact+"' value='' readonly='readonly' class='fechas' /></td><td><input type='text' size='12' name='fr_"+cantfact+"' id='fr_"+cantfact+"' value='' readonly='readonly' /></td><td><select name='prov_"+cantfact+"' id='prov_"+cantfact+"' style='max-width:240px;'></select></td><td><input type='text' size='18' maxlength='20' name='monto_"+cantfact+"' id='monto_"+cantfact+"' value='' onkeypress="+'"'+"return permite(event,'float')"+'"'+" /></td><td width='200'><input type='file' name='img_"+cantfact+"' id='img_"+cantfact+"' size='0' /></td></tr> ");
	
		$('#cant_fact').attr('value',cantfact);
		$('#fr_'+cantfact).datepicker();
		$('#ff_'+cantfact).datepicker();
		$('#prov_'+cantfact).append(ListProv);
	}
}
</script>
								</section>
		                        <h2>Datos Adicionales</h2>
		                        <section style="width:915px;">

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >                                
<div class="form-group"><label>Fecha de Ejecucion</label>
<input id="fe" name="fe" type="text" placeholder="Fecha de Registro de la Ejecución del Mantenimiento" class="form-control" value="<?php echo date("d / m / Y");?>" readonly="readonly" /></div></div>

<!-- <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
<div class="form-group"><label>Tiempo de Duración del Mantenimiento</label>
<input id="dur" name="dur" type="text" placeholder="Tiempo que se Tardo para Llevar Acabo el Mantenimiento" class="form-control" value="<?php echo $dur;?>" readonly="readonly" /></div></div> -->

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">                                
<div class="form-group"><label>Cantidad de Tareas</label>
<input id="cant_t" name="cant_t" type="text" placeholder="Cantidad de Tareas" class="form-control" value="<?php echo $cant_t;?>" readonly="readonly" /></div></div>

<!-- <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p></div> -->

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">                                
<div class="form-group"><label>Cantidad de Tareas Sin Realizar</label>
<input id="cant_tsr" name="cant_tsr" type="text" placeholder="Cantidad de Tareas Sin Realizar" class="form-control" value="<?php echo $cant_tsr;?>" readonly="readonly" /></div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">                                
<div class="form-group"><label>Cantidad de Tareas Realizadas</label>
<input id="cant_tr" name="cant_tr" type="text" placeholder="Cantidad de Tareas Realizadas" class="form-control" value="<?php echo $cant_tr;?>" readonly="readonly" /></div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="form-group"><label>Porcentaje de Tareas Sin Realizar</label>
<input id="porc_tsr" name="porc_tsr" type="text" placeholder="Porcentaje de Tareas Sin Realizar" class="form-control" value="<?php echo $porc_tsr;?>" readonly="readonly" /></div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="form-group"><label>Porcentaje de Tareas Realizadas</label>
<input id="porc_tr" name="porc_tr" type="text" placeholder="Porcentaje de Tareas Realizadas" class="form-control" value="<?php echo $porc_tr;?>" readonly="readonly" /></div></div> 


<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">                                
<div class="form-group"><label>Cantidad de Proveedores de Servicio</label>
<input id="cant_prov" name="cant_prov" type="text" placeholder="Cantidad de Proveedores de Servicio que Intervinieron en el Mantenimiento" class="form-control" value="<?php echo $cant_prov;?>" readonly="readonly" /></div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">                                
<div class="form-group"><label>Cantidad de Facturas</label>
<input id="cant_fact" name="cant_fact" type="text" placeholder="Cantidad de Facturas Generadas Por el Mantenimiento" class="form-control" value="<?php echo $cant_fact;?>" readonly="readonly" /></div></div> 

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="form-group"><label>Costo Total Bfs.</label>
<input id="costo" name="costo" type="text" placeholder="Costo Total del Mantenimiento en Bfs." class="form-control" value="<?php echo $costo;?>" readonly="readonly" /></div></div>
 
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>                    
                                                                                   
<div class="form-group"><label>Observaciones</label>
<textarea rows="12" name="obs" id="obs" class="form-control" onkeypress="return permite(event,'todo');"><?php echo $obs; ?></textarea></div>

<input type="hidden" name="total_fact" id="total_fact" value="0" />
		                        </section>
		                    </div>
                            
<p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Cancelar" class="btn btn-info btn-block" onclick="window.close();"/></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="submit" name="guardar" value="Guardar" class="btn btn-primary btn-block"/></div></div>  
</div></div>

                            



</form>
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

<script src="../Legend/admin/assets/stepswizard/js/jquery.steps.min.js"></script>
<script> function stepswizard() {
     $("#wizard3").steps({
         headerTag: "h2",
         bodyTag: "section",
         transitionEffect: "none",
         enableFinishButton: false,
         enablePagination: false,
         enableAllSteps: true,
         titleTemplate: "#title#",
         cssClass: "tabcontrol"
     });
 }
stepswizard();</script>

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
</script>

<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>

<script>
var ListProv = ""; 
function ActListProv(){
var code = 3;
$.get("../combox/dependencia_proveedores.php", { code: code },
	function(resultado){
		if(resultado == false){ alert("Error"); }
		else { ListProv = resultado; }
	}
); 
}

var ListComp = "";
function ActListComp(){ 
$.get("det_composiciones.php", 					
	function(resultado){ 
		if(resultado == false){  
		} else { ListComp = resultado; } 
	}
); 
}
</script>

<script> 
setInterval(function(){ 
	var cant_t = $('.detmant').length; 
	var cant_tr = $('.detmant:checked').length; 
	var cant_tsr = cant_t - cant_tr;
	var porc_tr = Math.round( ( cant_tr * 100 ) / cant_t );
	var porc_tsr = Math.round( 100 - porc_tr );
	
	$('#cant_t').attr('value',cant_t);
	$('#cant_tr').attr('value',cant_tr);
	$('#cant_tsr').attr('value',cant_tsr);
	$('#porc_tr').attr('value',porc_tr+" %");
	$('#porc_tsr').attr('value',porc_tsr+" %");
	
	var nfact = Number( $('.nfacts').length );
	var cont=0;
	var cont2=0;
	var total = 0;
	
	for(i=1; i<=nfact; i++){ 
		if( $('#nro_'+i).val().length > 0 ) { 
			cont++; 
			if( $('#prov_'+i).val() != "0") cont2++;
			if( $('#monto_'+i).val().length > 0 ) total += Number($('#monto_'+i).val());
		}
	}

	$('#cant_fact').attr('value',cont);
	$('#cant_prov').attr('value',cont2);
	$('#costo').attr('value',total+' Bfs.');
	$('#total_fact').attr('value',nfact);
	
	if(ListComp.length<1) ActListComp();
	if(ListProv.length<1) ActListProv();
}, 3000);
</script>

<script>function calcular(){ 
	document.getElementById('total').value = Number(document.getElementById('monto').value)+Number(document.getElementById('monto2').value)+Number(document.getElementById('monto3').value);
}</script>

<?php $cont = count($_SESSION['detplan']); 
for($i=0; $i<$cont; $i++) { 
	if(strcmp($_SESSION['detplan'][$i][1],"on")==0) $chk="checked"; else $chk="";
echo "<script>setTimeout(function(){ agregar_detalle2('$chk','".$_SESSION['detplan'][$i][0]."','".$_SESSION['detplan'][$i][2]."', ".$_SESSION['detplan'][$i][4].") }, 7000);</script>";
} ?> 

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
<?php } ?>