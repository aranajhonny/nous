<?php

if(isset($_REQUEST['id'])){ 
	if(isset($_SESSION['confini']['paso']) && $_SESSION['confini']['paso'] < 6){ echo "Incompleto";
	} else { echo "ok"; }
} else { 
	echo "error";
}

?>