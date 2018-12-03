<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
   
        <link href="assets/fuelux/css/fuelux.min.css" rel="stylesheet">
<?php echo "
<link href='assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>
<link href='assets/css/styles.css' rel='stylesheet'/>"; ?>
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
					<li><a href="#">Form Elements</a></li>
					<li><a href="#">Wizards</a></li>
					<li><a href="#">Fuelux Wizard</a></li>
					<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
				</ol>
            	<div class="row">
                

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		                <div class="well">
		                    <div class="header">Fuelux Wizard <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
                            

		                    <div class="fuelux">
		                        <div id="MyWizard" class="wizard">
		                            <ul class="steps">
<li data-target="#step1"  onclick="$('#MyWizard').wizard('selectedItem', { step: 1 });"  class="active">Plan<span class="chevron"></span></li>
<li data-target="#step2"  onclick="$('#MyWizard').wizard('selectedItem', { step: 2 });" >Detalles del Mantenimiento<span class="chevron"></span></li>
		                            </ul>
		                            <div class="actions">
<button class="btn btn-default btn-xs btn-prev"><i class="icon-arrow-left"></i>Ant.</button>
<button class="btn btn-default btn-xs btn-next">Sig.<i class="icon-arrow-right"></i></button>
		                            </div>
		                        </div>
		                        <div class="step-content">
		                            <div class="step-pane active" id="step1">

		                            </div>
		                            <div class="step-pane" id="step2">
		                                
		                            </div>
		                            
		                        </div>
		                        <br>
<button type="button" class="btn btn-default" id="btnWizardPrev">Ant.</button>
<button type="button" class="btn btn-primary" id="btnWizardNext">Sig.</button>
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
        
        
		<script src="assets/fuelux/js/all.min.js"></script>
        <script src="assets/fuelux/js/loader.min.js"></script>
<script>
var wizard = jQuery.noConflict();
function fueluxwizard() {
     wizard('#MyWizard').on('change', function (e, data) {
         console.log('change');
         if (data.step === 3 && data.direction === 'next') {
             // return e.preventDefault();
         }
     });
     wizard('#MyWizard').on('changed', function (e, data) {
         console.log('changed');
     });
     wizard('#MyWizard').on('finished', function (e, data) {
         console.log('finished');
     });
     wizard('#btnWizardPrev').on('click', function () {
         wizard('#MyWizard').wizard('previous');
     });
     wizard('#btnWizardNext').on('click', function () {
         wizard('#MyWizard').wizard('next', 'foo');
     });
     wizard('#btnWizardStep').on('click', function () {
         var item = wizard('#MyWizard').wizard('selectedItem');
         console.log(item.step);
     });
     wizard('#MyWizard').on('stepclick', function (e, data) {
         console.log('step' + data.step + ' clicked');
         if (data.step === 1) {
             // return e.preventDefault();
         }
     });

     // optionally navigate back to 2nd step
     wizard('#btnStep2').on('click', function (e, data) {
         wizard('[data-target=#step2]').trigger("click");
     });

}
fueluxwizard();</script>
    </body>
</html>