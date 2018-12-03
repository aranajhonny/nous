<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 50;
$_SESSION['acc']['form'] = 161;
include("../complementos/permisos.php");

if(isset($_REQUEST['eje'])){ $_SESSION['ejemant']=$_REQUEST['eje']; }

if(isset($_SESSION['ejemant'])){ 
	$rs = pg_query("select * from ejemant where id_ejemant = ".$_SESSION['ejemant']);
	$rs = pg_fetch_array($rs);
	$unidad = $rs[2];
	$plan = $rs[3];
	$_SESSION['progmant']['planmant']=$rs[3];
	$_SESSION['progmant']['unidad']=$rs[2];
	$confunid = $rs[4];
	$fe = date1($rs[5]);
	$costo = (1*$rs[6])." Bfs.";
	$obs = $rs[7];
	
	$rs = pg_query("select confunid.codigo_principal, unidades.codigo_principal from confunid, unidades where unidades.id_confunid = confunid.id_confunid and unidades.id_unidad = $unidad"); $rs = pg_fetch_array($rs); 
	$unidades = $rs[0]." ".$rs[1];
	
	if($plan==0){ 
		$plan = " - - "; 
	} else { 
		$rs = pg_query("select descripcion from planmant where id_planmant = $plan"); 
		$rs = pg_fetch_array($rs); 
		$plan = $rs[0];
	}
	
	$rs = pg_query("select * from evaluaciones where id_ejemant = ".$_SESSION['ejemant']);
	$rs = pg_fetch_array($rs);
	$tiempo = $rs[2];
	$fi = date1($rs[3]);
	$ff = date1($rs[4]);
	$cant_t = $rs[5];
	$cant_tr = $rs[6];
	$cant_tsr = $cant_t - $cant_tr;
	$porc_tr = $rs[7]."%";
	$porc_tsr = (100 - $porc_tr)."%";
	$cant_prov = $rs[8];
	$cant_fact = $rs[9];
	
$rs = pg_query("select id_composicion, UPPER(descripcion), obs, hecho from det_ejemant where id_ejemant = ".$_SESSION['ejemant']." order by id_detejemant asc ");
$r = pg_num_rows($rs);
if($r!=false && $r>0){ $i=0; 
	while($r = pg_fetch_array($rs)){  
		$_SESSION['detplan'][$i][0] = $r[1]; // etiqueta
		// valor del chk
		if($r[3]=='t') $_SESSION['detplan'][$i][1] = "checked"; 
		else $_SESSION['detplan'][$i][1] = "";
		$_SESSION['detplan'][$i][2] = $r[2]; // observacion
		
		if($r[0]==0){ $comp = ""; } else { 
			$qs = pg_query("select descripcion from composiciones where 
			id_composicion = ".$r[0]);
			$qs = pg_fetch_array($qs);
			$comp = " - ".$qs[0];
		}
		$_SESSION['detplan'][$i][3] = $comp; // composicion
		$i++; 
	} 
} else { 
	unset($_SESSION['detplan']);
}

$rs = pg_query("select id_provserv, nro, ff, fr, monto from factmant where id_ejemant = ".$_SESSION['ejemant']." order by id_factmant asc ");
$r = pg_num_rows($rs);
if($r!=false && $r>0){ $i=0; 
	while($r = pg_fetch_array($rs)){  
		$_SESSION['detfact'][$i][1] = "#".$r[1]; // nro
		$_SESSION['detfact'][$i][2] = date1($r[2]); // ff
		$_SESSION['detfact'][$i][3] = date1($r[3]); // fr
		$_SESSION['detfact'][$i][4] = $r[4]." Bfs."; // monto
		
		if($r[0]==0){ $prov= ""; } else { 
		$qs = pg_query("select rif, nombre_prov from provserv where id_provserv = ".$r[0]);
			$qs = pg_fetch_array($qs);
			$prov = $qs[0]." ".$qs[1];
		}
		$_SESSION['detfact'][$i][0] = $prov; // proveedor
		$i++; 
	} 
} else { 
	unset($_SESSION['detfact']);
}
	
	Auditoria("Accedio Al Modulo Ver Ejecución de Mantenimiento para la Unidad: $unidades Segun Plan de Mantenimiento: $plan en Fecha: $fe",$_SESSION['ejemant']);
	
} else { 
	$_SESSION['mensaje1']="No se identifico La Ejecución del Mantenimiento";
	header("location: listado.php");
	exit();
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
<form name="ver" method="post" action="ver.php">


<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">
<div class="header">Agregar Ejecución de Mantenimiento<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>


<div id="wizard3">
		                        <h2>Unidad</h2>
		                        <section>
<iframe src="ver_unidad.php" name="unid" id="unid" height="700" width="915" scrolling="no" style="border:none;background:none;"></iframe>
		                        </section>
								<h2>Plan de Mant.</h2>
		                        <section>
<iframe src="ver_planmant.php" name="plan" id="plan" height="700" width="915" scrolling="no" style="border:none;background:none;"></iframe>
		                        </section>
		                        <h2>Detalle del Mant.</h2>
		                        <section style="width:915px;">
<table class="table">
	<thead>
		<tr>
		    <th>Nro</th>
		    <th>Hecho</th>
		    <th>Detalle</th>
            <th>Observaciones</th>
		</tr>
	</thead>
	<tbody id="detalles">
  
	</tbody>
</table>

<script>
var cantdet = 0;
function agregar_detalle2(chk, etiqueta, comp, obs){  
cantdet++;
$('#detalles').append("<tr><td>"+cantdet+"</td><td><div class='skin skin-square skin-section checkbox icheck form-group'><label for='square-checkbox-2' class='icheck'><input tabindex='6' type='checkbox' "+chk+" class='detmant' disabled /></label></div></td><td>"+etiqueta+" "+comp+"</td><td>"+obs+"</td></tr>");
	
icheck();

}
</script>
		                        </section>
		                        <h2>Facturas</h2>
		                        <section style="width:915px;">
<table class="table">
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
                       
	</tbody>
</table>	
<script> 
var cantfact = 0;
function agregar_factura(prov, nro, ff, fr, monto){ 
cantfact++;
$('#facturas').append("<tr><td>"+nro+"</td><td>"+ff+"</td><td>"+fr+"</td><td>"+prov+"</td><td>"+monto+"</td><td width='200'></td></tr> ");

}
</script>
								</section>
		                        <h2>Datos Adicionales</h2>
		                        <section style="width:915px;">

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >                                
<div class="form-group"><label>Fecha de Ejecucion</label>
<input id="fe" name="fe" type="text" placeholder="Fecha de Registro de la Ejecución del Mantenimiento" class="form-control" value="<?php echo $fe;?>" readonly="readonly" /></div></div>

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
<textarea rows="12" name="obs" id="obs" class="form-control" onkeypress="return permite(event,'todo');" readonly="readonly"><?php echo $obs; ?></textarea></div>

<input type="hidden" name="total_fact" id="total_fact" value="0" />
		                        </section>
		                    </div>
                            
<p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="window.close();"/></div> 
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







<?php $cont = count($_SESSION['detplan']); 
for($i=0; $i<$cont; $i++) { 
echo "<script>agregar_detalle2('".$_SESSION['detplan'][$i][1]."','".$_SESSION['detplan'][$i][0]."','".$_SESSION['detplan'][$i][3]."', '".$_SESSION['detplan'][$i][2]."');</script>";
} ?> 

<?php $cont = count($_SESSION['detfact']); 
for($i=0; $i<$cont; $i++) { 
echo "<script>agregar_factura('".$_SESSION['detfact'][$i][0]."','".$_SESSION['detfact'][$i][1]."','".$_SESSION['detfact'][$i][2]."','".$_SESSION['detfact'][$i][3]."','".$_SESSION['detfact'][$i][4]."');</script>";
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