<!DOCTYPE html>
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
<?php echo "<link href='assets/bootstrap/css/bootstrap.css' rel='stylesheet'>"; ?>        
        <link href="assets/bootstrapdatatables/css/DT_bootstrap.css" rel="stylesheet">
        <link href="assets/selectboxIt/css/jquery.selectBoxIt.css" rel="stylesheet">
        <link href="assets/bootstrapui/css/jquery-ui-1.9.2.custom.css" rel="stylesheet">
        <link href="assets/jqgridbootstrap/jqGrid/css/ui.jqgrid.css" rel="stylesheet">
        <link href="assets/jqgridbootstrap/css/jqGrid.bootstrap.css" rel="stylesheet">
<?php echo "<link href='assets/css/styles.css' rel='stylesheet'> "; ?>        
        
        <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.js"></script>
        <script src="assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body style="max-width:900px;">
            	<div class="row">
            		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		                <div class="well">
		                	<div class="header">Data Table Bootstrap <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
			                <table class="table table-striped table-bordered" id="managed-table">
			                    <thead>
			                        <tr>
			                            <th>Rendering engine</th>
			                            <th>Browser</th>
			                            <th>Platform(s)</th>
			                            <th>Engine version</th>
			                            <th>CSS grade</th>
			                        </tr>
			                    </thead>
			                    <tbody>
			                        <tr class="odd gradeX">
			                            <td>Trident</td>
			                            <td>Internet
			                                Explorer 4.0
			                            </td>
			                            <td>Win 95+</td>
			                            <td class="center"> 4</td>
			                            <td class="center">X</td>
			                        </tr>
			                        <tr class="even gradeC">
			                            <td>Trident</td>
			                            <td>Internet
			                                Explorer 5.0
			                            </td>
			                            <td>Win 95+</td>
			                            <td class="center">5</td>
			                            <td class="center">C</td>
			                        </tr>
			                        <tr class="odd gradeA">
			                            <td>Trident</td>
			                            <td>Internet
			                                Explorer 5.5
			                            </td>
			                            <td>Win 95+</td>
			                            <td class="center">5.5</td>
			                            <td class="center">A</td>
			                        </tr>
			                        
			                    </tbody>
			                </table>
		                </div>
		                
            		</div>

        <script src="../../jquery/development-bundle/jquery-1.10.2.js"></script>
        <script type="text/javascript">$(document).bind("mobileinit", function(){$.extend(  $.mobile , {autoInitializePage: false})});</script>
        <script src="../../jquerymobile/jquery.mobile.custom.js"></script>
        <script src="assets/bootstrapui/js/jquery-ui-1.9.2.custom.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/bootstrapdatatables/js/jquery.dataTables.js"></script>
        <script src="assets/bootstrapdatatables/js/DT_bootstrap.js"></script>
        <script src="assets/selectboxIt/js/jquery.selectBoxIt.min.js"></script>
        <script src="assets/jqgridbootstrap/jqGrid/js/i18n/grid.locale-en.js"></script>
        <script src="assets/jqgridbootstrap/jqGrid/js/jquery.jqGrid.min.js"></script>
        <script src="assets/js/leftmenu.js"></script>
        <script src="assets/js/theme.js"></script>
        <script src="assets/js/script.js"></script>
        <script>datatables();</script>
    </body>
</html>