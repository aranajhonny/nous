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
<?php echo "<link href='assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>"; ?>
        <link href="assets/icheck/skins/all.css" rel="stylesheet">
<?php echo "<link href='assets/css/styles.css' rel='stylesheet'/>"; ?>
        <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.js"></script>
        <script src="assets/js/respond.min.js"></script>
        <![endif]-->
        <style> /* iCheck page styles */
            .colors ul { padding: 0; }
            .colors li{list-style-type: none;position:relative;float:left;width:16px;height:16px;margin:2px 1px 0 0;background:#000;cursor:pointer;filter:alpha(opacity=50);opacity:.5;-webkit-transition:opacity .2s;-moz-transition:opacity .2s;-ms-transition:opacity .2s;-o-transition:opacity .2s;transition:opacity .2s;}
            .colors li:hover{filter:alpha(opacity=100);opacity:1}
            .colors li.active{height:20px;margin-top:0;filter:alpha(opacity=75);opacity:.75}
            .colors li.red{background:#d54e21}
            .colors li.green{background:#78a300}
            .colors li.blue{background:#0e76a8}
            .colors li.aero{background:#9cc2cb}
            .colors li.grey{background:#73716e}
            .colors li.orange{background:#f70}
            .colors li.yellow{background:#fc0}
            .colors li.pink{background:#ff66b5}
            .colors li.purple{background:#6a5a8c}
            .skin-square .colors li.red{background:#e56c69}
            .skin-square .colors li.green{background:#1b7e5a}
            .skin-square .colors li.blue{background:#2489c5}
            .skin-square .colors li.aero{background:#9cc2cb}
            .skin-square .colors li.grey{background:#73716e}
            .skin-square .colors li.yellow{background:#fc3}
            .skin-square .colors li.pink{background:#a77a94}
            .skin-square .colors li.purple{background:#6a5a8c}
            .skin-square .colors li.orange{background:#f70}
            .skin-flat .colors li.red{background:#ec7063}
            .skin-flat .colors li.green{background:#1abc9c}
            .skin-flat .colors li.blue{background:#3498db}
            .skin-flat .colors li.grey{background:#95a5a6}
            .skin-flat .colors li.orange{background:#f39c12}
            .skin-flat .colors li.yellow{background:#f1c40f}
            .skin-flat .colors li.pink{background:#af7ac5}
            .skin-flat .colors li.purple{background:#8677a7}
            .skin-line .colors li.yellow{background:#ffc414}
        </style>
    </head>
    <body>

        <div class="overlay"></div>
        <div class="controlshint"><img src="assets/img/swipe.png" alt="Menu Help"></div>
        <section class="wrap">
            <div class="container">
            	<ol class="breadcrumb">
					<li><a href="#">Form Elements</a></li>
					<li><a href="#">Components</a></li>
					<li><a href="#">iCheck</a></li>
					<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
				</ol>
                <div class="row">

	                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                    <div class="well">
	                    	<div class="header">Line Skin <a class="headerclose"><i class="icon-remove pull-right"></i></a> <a class="headerrefresh"><i class="icon-refresh pull-right"></i></a> <a class="headershrink"><i class="icon-chevron-down pull-right"></i></a></div>
	                        <div class="skin skin-line">
	                            <div class="skin-section">
	                                <div class="row">
	                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
	                                        <div class="form-group">
	                                            <input tabindex="9" type="checkbox" id="line-checkbox-1"> 
	                                            <label for="line-checkbox-1">Checkbox 1</label>
	                                        </div>
	                                        <div class="form-group">
	                                            <input tabindex="6" type="checkbox" id="line-checkbox-2" checked>
	                                            <label for="line-checkbox-2">Checkbox 2</label>
	                                        </div>
	                                        <div class="form-group">
	                                            <input type="checkbox" id="line-checkbox-disabled" disabled>
	                                            <label for="line-checkbox-disabled">Disabled</label>
	                                        </div>
	                                        <div class="form-group">
	                                            <input type="checkbox" id="line-checkbox-disabled-checked" checked disabled> 
	                                            <label for="line-checkbox-disabled-checked">Disabled & checked</label>
	                                        </div>
	                                    </div>
	                                    
	                                </div>
	                                <div class="colors clearfix">
	                                    <p class="text-muted"><small>Color schemes</small></p>
	                                    <ul>
	                                        <li class="active" title="Black"></li>
	                                        <li class="red" title="Red"></li>
	                                        <li class="green" title="Green"></li>
	                                        <li class="blue" title="Blue"></li>
	                                        <li class="aero" title="Aero"></li>
	                                        <li class="grey" title="Grey"></li>
	                                        <li class="orange" title="Orange"></li>
	                                        <li class="yellow" title="Yellow"></li>
	                                        <li class="pink" title="Pink"></li>
	                                        <li class="purple" title="Purple"></li>
	                                    </ul>
	                                </div>
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
        <script src="assets/icheck/js/jquery.icheck.min.js"></script>

<script>
		$('.skin-line input').each(function () {
         var self = $(this),
             label = self.next(),
             label_text = label.text();

         label.remove();
         self.iCheck({
             checkboxClass: 'icheckbox_line-blue',
           
             insert: '<div class="icheck_line-icon"></div>' + label_text
         });
     });
</script>
    </body>
</html>