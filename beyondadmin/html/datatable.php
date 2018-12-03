<!DOCTYPE html>
<!--
BeyondAdmin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.2.0
Version: 1.0.0
Purchase: http://wrapbootstrap.com
-->

<html xmlns="http://www.w3.org/1999/xhtml">
<!-- Head -->
<head>
    <meta charset="utf-8" />
    <title>Data Tables</title>

    <meta name="description" content="data tables" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">
<?php echo '
    <!--Basic Styles-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link id="bootstrap-rtl-link" href="" rel="stylesheet" />
    <link href="assets/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/css/weather-icons.min.css" rel="stylesheet" />

    <!--Fonts-->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300" rel="stylesheet" type="text/css">

    <!--Beyond styles-->
    <link id="beyond-link" href="assets/css/beyond.min.css" rel="stylesheet" />
    <link href="assets/css/demo.min.css" rel="stylesheet" />
    <link href="assets/css/typicons.min.css" rel="stylesheet" />
    <link href="assets/css/animate.min.css" rel="stylesheet" />
    <link id="skin-link" href="" rel="stylesheet" type="text/css" /> '; ?>

    <!--Page Related styles-->
    <link href="assets/css/dataTables.bootstrap.css" rel="stylesheet" />

</head>
<!-- /Head -->
<!-- Body -->
<body>


    <!-- Main Container -->
    <div class="main-container container-fluid">
        <!-- Page Container -->
        <div class="page-container">

            <!-- Page Content -->
            <div class="page-content">
                
                <!-- Page Body -->
                <div class="page-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="widget">
                                <div class="widget-header ">
                                    <span class="widget-caption">Simple DataTable</span>
                                    <div class="widget-buttons">
                                        <a href="#" data-toggle="maximize">
                                            <i class="fa fa-expand"></i>
                                        </a>
                                        <a href="#" data-toggle="collapse">
                                            <i class="fa fa-minus"></i>
                                        </a>
                                        <a href="#" data-toggle="dispose">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="widget-body">
                                    <table class="table table-striped table-bordered table-hover" id="simpledatatable">
                                        <thead>
                                            <tr>
                                             	<th>
                                                    ID
                                                </th>
                                                <th>
                                                    Username
                                                </th>
                                                <th>
                                                    Email
                                                </th>
                                                <th>
                                                    Points
                                                </th>
                                                <th>
                                                    Joined
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
<tr><td></td><td>shuxer</td><td><a href="mailto:shuxer@gmail.com">shuxer@gmail.com</a></td><td>120</td><td class="center ">12 Jan 2012</td></tr>

<tr><td></td><td>shuxer</td><td><a href="mailto:shuxer@gmail.com">shuxer@gmail.com</a></td><td>120</td><td class="center ">12 Jan 2014</td></tr>

<tr><td></td><td>shuxer</td><td><a href="mailto:shuxer@gmail.com">shuxer@gmail.com</a></td><td>120</td><td class="center ">12 Jan 2014</td></tr>

<tr><td></td><td>shuxer</td><td><a href="mailto:shuxer@gmail.com">shuxer@gmail.com</a></td><td>120</td><td class="center ">12 Jan 2012</td></tr>

<tr><td></td><td>shuxer</td><td><a href="mailto:shuxer@gmail.com">shuxer@gmail.com</a></td><td>120</td><td class="center ">12 Jan 2013</td></tr>

<tr><td></td><td>shuxer</td><td><a href="mailto:shuxer@gmail.com">shuxer@gmail.com</a></td><td>120</td><td class="center ">12 Jan 2013</td></tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                </div>
                <!-- /Page Body -->
            </div>
            <!-- /Page Content -->
        </div>
        <!-- /Page Container -->
        <!-- Main Container -->

    </div>

    <!--Basic Scripts-->
    <script src="assets/js/jquery-2.0.3.min.js"></script>




    <!--Page Related Scripts-->
    <script src="assets/js/datatable/jquery.dataTables.min.js"></script>
    <script src="assets/js/datatable/ZeroClipboard.js"></script>
    <script src="assets/js/datatable/dataTables.tableTools.min.js"></script>
    <script src="assets/js/datatable/dataTables.bootstrap.min.js"></script>

    <script>
var InitiateSimpleDataTable = function () {
    return {
        init: function () {
            //Datatable Initiating
            var oTable = $('#simpledatatable').dataTable({
                "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
                "iDisplayLength": 5,
                "oTableTools": {
                    "aButtons": [
                        "copy", "csv", "xls", "pdf", "print"
                    ],
                    "sSwfPath": "assets/swf/copy_csv_xls_pdf.swf"
                },
                "language": {
                    "search": "",
                    "sLengthMenu": "_MENU_",
                    "oPaginate": {
                        "sPrevious": "Ant",
                        "sNext": "Sig"
                    }
                },
                "aoColumns": [
                  { "bSortable": false },
                  null,
                  { "bSortable": false },
                  null,
                  { "bSortable": false }
                ],
                "aaSorting": []
            });


        }

    };

}();	
	
        InitiateSimpleDataTable.init();
    </script>

</body>
<!--  /Body -->
</html>
