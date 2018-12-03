<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");



$_SESSION['acc']['mod'] = 41;
$_SESSION['acc']['form'] = 148;
include("../complementos/permisos.php");




if(isset($_REQUEST['id'])==false){ 
	Auditoria("En Seguimiento de Unidad Especifico Acceso Invalido Archivo Alarmas",0);
	header("location: vacio.php");
	exit();
} else { 
	$id = filtrar_campo('int', 6, $_REQUEST['id']);
}



?><!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../Legend/admin/assets/ico/apple-touch-icon-144-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../Legend/admin/assets/ico/apple-touch-icon-114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../Legend/admin/assets/ico/apple-touch-icon-72-precomposed.png" />
<link rel="apple-touch-icon-precomposed" href="../Legend/admin/assets/ico/apple-touch-icon-57-precomposed.png" />  
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<title>.:: NousTrack ::.</title>
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'>"; ?>        
<link href="../Legend/admin/assets/stepswizard/css/jquery.steps.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/bootstrapui/css/jquery-ui-1.9.2.custom.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'> "; ?>
    </head>
    <body style="max-width:890px; background:#FFF;">
            	<div class="row">
            		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		                <div class="well">
	
<div id='wizard3'>       
<h2>Ultima Semana</h2><section><iframe src='ultima_semana.php?id=<?php echo $id;?>' name='recorrido1' id='recorrido1' height='600' width='770' scrolling='no' style='border:none;background:none;'></iframe></section>                    

<h2>Fechas</h2>
<section>

<div class='form-group'>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<label>Fecha Desde</label>
<input id='fi' name='fi' type='text' placeholder='Fecha Desde' class='form-control' maxlength='12' value='' onchange='fechas();' />
</div>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<label>Fecha Hasta</label>
<input id='ff' name='ff' type='text' placeholder='Fecha Hasta' class='form-control' maxlength='12' value='' onchange='fechas();' />
</div>  
<p>&nbsp;</p>    
</div> 

<iframe src='vacio.php' name='fechas' id='fechas' height='600' width='915' scrolling='no' style='border:none;background:none;'></iframe>

</section> 

</div>
</div>


		</div>                
	</div>                         
</div>

<script src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script type="text/javascript">$(document).bind("mobileinit", function(){$.extend(  $.mobile , {autoInitializePage: false})});</script>
<script src="../jquerymobile/jquery.mobile.custom.js"></script>
<script src="../Legend/admin/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="../Legend/admin/assets/js/theme.js"></script>

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
 stepswizard();
</script>

<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="../jquery/development-bundle/themes/custom-theme/jquery.ui.datepicker.css"/>
<script> 
function calendarios() {
	$( "#fi" ).datepicker({ 
		defaultDate: "0",
		maxDate: "+36M +1D",
		onClose: function( selectedDate ) {
			$( "#ff" ).datepicker( "option", "minDate", selectedDate );
		},
		onSelect: function( selectedDate ){ fechas(); }
	});
	$( "#ff" ).datepicker({
		defaultDate: "0",
		maxDate: "+36M +1D",
		onClose: function( selectedDate ) {
			$( "#fi" ).datepicker( "option", "maxDate", selectedDate );
		},
		onSelect: function( selectedDate ){ fechas(); }
	});
}
calendarios();
</script>

<script>
function fechas(){ 
	var ini = $('#fi').val();
	var fin = $('#ff').val();
	if(ini.length>0 && fin.length>0){ 
		document.getElementById('fechas').src= "fechas.php?fi="+ini+"&ff="+fin+"&id="+<?php echo $_REQUEST['id'];?>;
	}
}
</script>

<?php include("../complementos/closdb.php"); ?>
</body>
</html>