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
<link href="assets/icheck/skins/square/_all.css" rel="stylesheet"/>
<?php echo "<link href='assets/css/styles.css' rel='stylesheet'/>";?>        
       
<!--[if lt IE 9]>
        
<script src="assets/js/html5shiv.js"></script>
        <script src="assets/js/respond.min.js"></script>
        <![endif]-->
        <style> /* iCheck page styles */
            .colors ul { padding: 0; }
            .colors li{list-style-type: none;position:relative;float:left;width:16px;height:16px;margin:2px 1px 0 0;background:#000;cursor:pointer;filter:alpha(opacity=50);opacity:.5;-webkit-transition:opacity .2s;-moz-transition:opacity .2s;-ms-transition:opacity .2s;-o-transition:opacity .2s;transition:opacity .2s;}
            .colors li:hover{filter:alpha(opacity=100);opacity:1}
            .colors li.active{height:20px;margin-top:0;filter:alpha(opacity=75);opacity:.75}
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
	                    	<div class="header">Square Skin <a class="headerclose"><i class="icon-remove pull-right"></i></a> <a class="headerrefresh"><i class="icon-refresh pull-right"></i></a> <a class="headershrink"><i class="icon-chevron-down pull-right"></i></a></div>
	                        <div class="skin skin-square">
	                            <div class="skin-section">
	                                <div class="row">
	                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
	                                        <div class="checkbox icheck form-group">
<label for="square-checkbox-1" class="icheck">
<input tabindex="9" type="checkbox" id="square-checkbox-1"> Checkbox 1
</label>
	                                        </div>
	                                        <div class="checkbox icheck form-group">
<label for="square-checkbox-2" class="icheck">
<input tabindex="6" type="checkbox" id="square-checkbox-2" checked> Checkbox 2
</label>
	                                        </div>
	                                        <div class="checkbox icheck form-group">
<label for="square-checkbox-disabled" class="icheck">
<input type="checkbox" id="square-checkbox-disabled" disabled> Disabled
</label>
	                                        </div>
	                                        <div class="checkbox icheck form-group">
<label for="square-checkbox-disabled-checked" class="icheck">
<input type="checkbox" id="square-checkbox-disabled-checked" checked disabled> Disabled & checked </label>
	                                        </div>
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

        <script src="assets/js/theme.js"></script>
<script> 
var check = jQuery.noConflict();
function icheck() {
     check('.colors li').click(function () {
         var self = check(this);

         if (!self.hasClass('active')) {
             self.siblings().removeClass('active');
			 
             var skin = self.closest('.skin'),
                 color = self.attr('class') ? '-' + self.attr('class') : '',
                 checkbox = skin.data('icheckbox'),
                 checkbox_default = 'icheckbox_minimal';

             if (skin.hasClass('skin-square')) {
                 checkbox_default = 'icheckbox_square';
                 checkbox == undefined && (checkbox = 'icheckbox_square');
             };

             checkbox == undefined && (checkbox = checkbox_default);

             skin.find('input, .skin-states .state').each(function () {
                 var element = check(this).hasClass('state') ? check(this) : check(this).parent(),
                     element_class = element.attr('class').replace(checkbox, checkbox_default + color);

                 element.attr('class', element_class);
             });

             skin.data('icheckbox', checkbox_default + color);
         
             self.addClass('active');
         };
     });
     check('.skin-square input').iCheck({
         checkboxClass: 'icheckbox_square-blue',
        
         increaseArea: '20%'
     });
 }
icheck();</script>
    </body>
</html>