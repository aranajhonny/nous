<?php 
session_start();

if(isset($_POST['guardar'])){ 
	echo $_POST;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="assets/dropzone/css/dropzone.css" rel="stylesheet">
<?php echo "
<link href='assets/bootstrap/css/bootstrap.css' rel='stylesheet'>
<link href='assets/css/styles.css' rel='stylesheet'/> "; ?>
        <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.js"></script>
        <script src="assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>


<form method="post" action="dropzone.php" class="dropzone" enctype="multipart/form-data">
							  <div class="fallback">
<input type="file" name="file" multiple accept="image/jpeg" />
							  </div>
                              <div>
<input type="submit" name="guardar" value="Prueba" /></div>
</form>

        <script src="../../jquery/development-bundle/jquery-1.10.2.js"></script>
        <script type="text/javascript">$(document).bind("mobileinit", function(){$.extend(  $.mobile , {autoInitializePage: false})});</script>
        <script src="../../jquerymobile/jquery.mobile.custom.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        
        <script src="assets/dropzone/js/dropzone.js"></script>

    </body>
</html>