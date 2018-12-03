<?php 
session_start();
include("../../complementos/condb.php");



?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="description" content="">
        <meta name="author" content="">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="assets/ico/favicon.png">
        <title>Legend</title>
<?php echo "<link href='assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>"; ?>
<link href="assets/bootstrapui/css/jquery-ui-1.9.2.custom.css" rel="stylesheet">
<link href="assets/fullcalendar/css/fullcalendar.css" rel="stylesheet" />
<link href="assets/fullcalendar/css/fullcalendar.print.css" rel="stylesheet" media="print" />
<?php echo "<link href='assets/css/styles.css' rel='stylesheet'/>"; ?>
        <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.js"></script>
        <script src="assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>

        <div class="overlay"></div>
        <div class="controlshint"><img src="assets/img/swipe.png" alt="Menu Help"></div>
        <section class="wrap">
            <div class="container">
            	<ol class="breadcrumb">
					<li><a href="#">Miscellaneous</a></li>
					<li><a href="#">Calendar</a></li>
					<li><a href="#">Full Calendar</a></li>
					<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
				</ol>
            	<div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		                <div class="well">
<div class="header">FullCallendar <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
		                	<div class="row">
		                       <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		                            <div id='calendar'></div>
		                        </div>
		                    </div>
		                </div>
                    </div>
            	</div>
            </div>
        </section>
        <script src="../../jquery/development-bundle/jquery-1.10.2.js"></script>
        <script type="text/javascript">$(document).bind("mobileinit", function(){$.extend(  $.mobile , {autoInitializePage: false})});</script>
        <script src="../../jquerymobile/jquery.mobile.custom.js"></script>

<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/bootstrapui/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="assets/fullcalendar/js/fullcalendar.js" type="text/javascript"></script>

<script>
 function fullcalendar() {
     $('#calendar').fullCalendar({
         header: {
             left: 'prev,next today',
             center: 'title',
             right: 'month,agendaWeek,agendaDay'
         },
		 events: [<?php include("Cargar_ProgMantenimientos.php"); ?>],
		 eventClick: function(calEvent, jsEvent, view) {
        	alert('Actividad: ' + calEvent.title);
        	//alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
        	//alert('View: ' + view.name);
    	 },
         editable: true,
         droppable: true, 
         drop: function (date, allDay) { 
             var originalEventObject = $(this).data('eventObject');
             var copiedEventObject = $.extend({}, originalEventObject);
             copiedEventObject.start = date;
             copiedEventObject.allDay = allDay;
             $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
             if ($('#drop-remove').is(':checked')) { $(this).remove(); }
         }
     });
 }		
fullcalendar();</script>
    </body>
</html>