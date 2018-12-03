<?php 
include("session.php");
$link = pg_connect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014",PGSQL_CONNECT_FORCE_NEW);
?>