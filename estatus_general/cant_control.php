<?php session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$area = $cant = "";

$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

$rs = pg_query($link, filtrar_sql("select count(unidades.id_unidad), controles.nombre from controles, sensores, unidades  where (controles.id_cliente=$c and sensores.id_cliente=$c and unidades.id_cliente=$c) and sensores.id_control = controles.id_control and sensores.id_unidad = unidades.id_unidad and unidades.estatus_control <> 'Estable' group by controles.id_control order by count desc") );
	$total = 0;
	while($r = pg_fetch_array($rs)){ 
		$area .= "'".$r[1]."',";
		$cant .= $r[0].",";
		$total += $r[0];
	}
	$area .= "'TOTAL DE CONTROLES'";
	$cant .= "$total";

include("../complementos/closdb.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta charset="utf-8">
<title>Nro de Controles Fuera de Rango</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<?php echo "<link href='../controlfrog/css/bootstrap.css' rel='stylesheet'>
<link href='../controlfrog/css/controlfrog.css' rel='stylesheet' media='screen'>";   ?>
	
<script src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script src="../controlfrog/js/moment.js"></script>
<script src="../controlfrog/js/bootstrap.js"></script>
<script src="../controlfrog/js/controlfrog-plugins.js"></script>

<!--[if lt IE 9]>
	<script src="../controlfrog/js/respond.min.js"></script>
	<script src="../controlfrog/js/excanvas.min.js"></script>
<![endif]-->

<script> var themeColour = 'white'; </script>
<script src="../controlfrog/js/controlfrog.js"></script>
<script>
$(document).ready(function(){
	$('.cf-funnel').each(function(){
		funData = [<?php echo $cant;?>];
		funLabels = [<?php echo $area;?>];
		funOptions = {barOpacity:true};
		cf_rFunnels[$(this).prop('id')] = new FunnelChart($(this).prop('id'), funData, funLabels, funOptions);
	});
});
</script>
</head>
<body class="white">
  <div class="row">
      <div class="col-sm-3 cf-item">
          <header>
              <p><span></span>Nro de Controles Fuera de Rango</p>
          </header>
          <div class="content">
              <div id="cf-funnel-1" class="cf-funnel"  style="height:260px;"></div>
          </div>
      </div> <!-- //end cf-item -->
  </div> <!-- //end row -->
</body>
</html>