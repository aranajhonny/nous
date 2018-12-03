<?php

if(isset($_REQUEST['id'])){ 
	if(isset($_SESSION['confperm']['paso']) && $_SESSION['confperm']['paso'] < 5){ echo "Incompleto";
	} else { echo "ok"; }
} else { 
	echo "error";
}

?>