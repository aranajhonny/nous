<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 67;
$_SESSION['acc']['form'] = 173;
include("../complementos/permisos.php");



if(isset($_SESSION['vis']['ant'])){ 

	$rs = pg_query($link, filtrar_sql("select id, dir from log_img where id_unidad = ".$_SESSION['vis']['unidad']." order by id desc limit 1 "));
	$rs = pg_fetch_array($rs);
	if($_SESSION['vis']['ant']<$rs[0]){ 
		unset($_SESSION['vis']); ?>

<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"></head><body>
<img src='<?php echo $rs[1];?>' width='880' height='500' />
</body></html> 

<?php	} else { ?>
	
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="refresh" content="5" />
<meta charset="utf-8">
<title>CARGANDO</title>
<link href="../Legend/admin/assets/bootstrapui/css/jquery-ui-1.9.2.custom.css" rel="stylesheet">
<style> 
.preview {   
	height:500px;
	width:880px;
	background:#FFF;
	font-size:26px;
	color:#B0B0B0;
	font-family:"Arial Black", Gadget, sans-serif;
}
</style>
</head>
<body>
<div class="preview" align="center">
	<p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    CARGANDO
</div>
<script src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script src="../Legend/admin/assets/spin/js/spin.min.js"></script>
<script>
function widgets() {
  $.fn.spin = function (opts) {
	 this.each(function () {
		 var $this = $(this),
			 data = $this.data();
  
		 if (data.spinner) {
			 data.spinner.stop();
			 delete data.spinner;
		 }
		 if (opts !== false) {
			 data.spinner = new Spinner($.extend({
				 color: $this.css('color')
			 }, opts)).spin(this);
		 }
	 });
	 return this;
  };
  var opts = {};
  opts['lines'] = 20;
  opts['length'] = 25;
  opts['width'] = 3;
  opts['radius'] = 90;
  opts['corners'] = 1;
  opts['rotate'] = 0;
  opts['trail'] = 64;
  opts['speedslider'] = 1;
  opts['direction'] = 1;
  opts['shadow'] = false;
  opts['hwaccel'] = true;
  opts['color'] = '#0076AE';
  $('.preview').spin(opts);
}		
widgets();</script>
</body>
</html>    
    
<?php	}

} 
?>