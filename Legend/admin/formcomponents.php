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
<?php echo "
<link href='assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>
<link href='assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="assets/css/leftmenu.css" rel="stylesheet"/>
        <link href="assets/select2/css/select2.css" rel="stylesheet">
        <link href="assets/fseditor/css/fseditor.css" rel="stylesheet">
        <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.js"></script>
        <script src="assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <nav class="sidenav left" role="navigation">
            <ul class="menu">
                <li class="user">
                    <div class="search-block">
                        <input type="text" class="search">
                        <span>Search <i class="fa fa-search"></i></span>
                    </div>
                    <div class="content">
                        <img class="img-circle" height="54" src="assets/img/user.jpg" alt="">
                        <p>Micheal Lance
                            <br />
                            <small>Lorem ipsum dolor sit amet</small>
                        </p>
                    </div>
                </li>
                <li>
                    <a href="index.html">
                        Dashboard 
                        <div>
                            <img src="assets/img/icons/dashboard.png" alt="">
                        </div>
                    </a>
                </li>
                <li>
                    <a>
                        Layouts
                        <div>
                            <img src="assets/img/icons/cog.png" alt="">
                        </div>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                        <li><a href="blank.php">Blank Page</a></li>
                        <li><a href="leftmenu.html">Left Menu</a></li>
                        <li><a href="rightmenu.html">Right Menu</a></li>
                        <li><a href="topmenu.html">Top Menu</a></li>
                        <li><a href="topmenufixed.html">Top Menu Fixed</a></li>
                    </ul>
                </li>
                <li>
                    <a>
                        Ui Features
                        <div>
                            <img src="assets/img/icons/layers.png" alt="">
                        </div>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                        <li>
                            <a>
                            Alerts <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li><a href="bootstrapalerts.html">Bootstrap Alerts</a></li>
                                <li><a href="browserrejection.html">Browser Rejection</a></li>
                                <li><a href="humanealerts.html">Humane Alerts</a></li>
                                <li><a href="ionsoundalerts.html">Ion Sound Alerts</a></li>
                                <li><a href="messengeralerts.html">Messenger Alerts</a></li>
                                <li><a href="pinesalerts.html">Pines Notifications</a></li>
                                <li><a href="toastralerts.html">Toastr Alerts</a></li>
                                <li><a href="vexalerts.php">Vex Dialogs Alerts</a></li>
                            </ul>
                        </li>
                        <li>
                            <a>
                            Buttons <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li><a href="3dbuttons.html">3D Buttons</a></li>
                                <li><a href="buttons.php">Bootstrap Buttons</a></li>
                                <li><a href="creativebuttons.html">Creative Buttons</a></li>
                                <li><a href="laddabootstrap.html">Ladda Buttons</a></li>
                                <li><a href="modernbuttons.html">Modern Buttons</a></li>
                                <li><a href="socialbootstrap.html">Social Bootstrap Buttons</a></li>
                            </ul>
                        </li>
                        <li><a href="components.php">Components</a></li>
                        <li><a href="grid.php">Grid</a></li>
                        <li>
                            <a>
                            Icons <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li><a href="fontawesome.html">Font Awesome</a></li>
                                <li><a href="glyphicons.html">Glyphicons</a></li>
                                <li><a href="pictonicons.html">Pictonic Icons</a></li>
                                <li><a href="raphaelicons.html">Rapha&#235;l Icons</a></li>
                                <li><a href="themeicons.html">Theme Icons</a></li>
                                <li><a href="typicons.html">Typicons Icons</a></li>
                            </ul>
                        </li>
                        <li><a href="jqueryui.html">jQuery Ui</a></li>
                        <li><a href="typography.html">Typography</a></li>
                        <li>
                            <a>
                            Widgets <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li><a href="dragwidgets.html">Draggable</a></li>
                                <li><a href="treeview.html">Tree View</a></li>
                                <li><a href="widgets.html">Widgets</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a>
                        Forms Elements
                        <div>
                            <img src="assets/img/icons/mailopen.png" alt="">
                        </div>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                        <li>
                            <a>
                            Components <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li><a href="formcomponents.html">Bootstrap Components</a></li>
                                <li><a href="colorpicker.html">Color Pickers</a></li>
                                <li><a href="datetimepicker.html">Date & Time Pickers</a></li>
                                <li><a href="icheck.html">iCheck</a></li>
                                <li>
                                    <a>
                                    Select Plugins <i class="fa fa-angle-right pull-right"></i>
                                    </a>
                                    <ul class="dropdown-menu second" role="menu">
                                        <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                        <li><a href="bootstrapmultiselect.html">Bootstrap Multiselect</a></li>
                                        <li><a href="bootstrapselect.html">Bootstrap Select</a></li>
                                        <li><a href="chosen.html">Chosen</a></li>
                                        <li><a href="select2.html">Select2</a></li>
                                        <li><a href="selectboxit.html">SelectBoxIt</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a>
                                    Range Sliders <i class="fa fa-angle-right pull-right"></i>
                                    </a>
                                    <ul class="dropdown-menu second" role="menu">
                                        <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                        <li><a href="jqrangeslider.html">jQuery Range Slider</a></li>
                                        <li><a href="jqueryuislider.html">jQuery Ui Sliders</a></li>
                                        <li><a href="nouislider.html">noUiSlider</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a>
                                    Tags <i class="fa fa-angle-right pull-right"></i>
                                    </a>
                                    <ul class="dropdown-menu second" role="menu">
                                        <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                        <li><a href="bootstraptokenfield.html">Bootstrap Tokenfield</a></li>
                                        <li><a href="tagmanager.html">Tag Manager</a></li>
                                    </ul>
                                </li>
                                <li><a href="toggles.html">Toggles</a></li>
                                <li><a href="validation.php">Validation</a></li>
                                <li><a href="xeditable.html">X-editable</a></li>
                            </ul>
                        </li>
                        <li>
                            <a>
                            Editors <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li><a href="markdown.html">Markdown</a></li>
                                <li><a href="mediumeditor.html">Medium Editor</a></li>
                                <li><a href="summernote.html">Summernote</a></li>
                                <li><a href="tinymce.html">tinyMCE</a></li>
                                <li><a href="wysihtml5.html">wysihtml5</a></li>
                            </ul>
                        </li>
                        <li>
                            <a>
                            Wizards <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li><a href="fueluxwizard.html">Fuelux Wizard</a></li>
                                <li><a href="jwizard.html">jWizard</a></li>
                                <li><a href="stepswizard.html">Steps Wizard</a></li>
                            </ul>
                        </li>
                        <li>
                            <a>
                            Upload <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li><a href="dropzone.html">Dropzone</a></li>
                                <li><a href="inputfilestyle.html">Input File Style</a></li>
                                <li><a href="jansyfileupload.html">Jansy File Upload</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a>
                        Charts
                        <div>
                            <img src="assets/img/icons/charts.png" alt="">
                        </div>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                        <li><a href="charts.html">Flot Charts</a></li>
                        <li><a href="charts3.html">Other Charts</a></li>
                        <li><a href="charts2.html">xCharts</a></li>
                    </ul>
                </li>
                <li>
                    <a>
                        Maps
                        <div>
                            <img src="assets/img/icons/map.png" alt="">
                        </div>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                        <li><a href="googlemaps.html">Google Maps</a></li>
                        <li><a href="vectormaps.html">Vector Maps</a></li>
                    </ul>
                </li>
                <li>
                    <a>
                        Example Pages 
                        <div>
                            <img src="assets/img/icons/documents.png" alt="">
                        </div>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                        <li>
                            <a>
                            Blog <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li><a href="blog.html">Blog</a></li>
                                <li><a href="blogpost.html">Blog Post</a></li>
                            </ul>
                        </li>
                        <li><a href="comingsoon.html">Coming Soon</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li>
                            <a>
                            Email <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li><a href="inbox.html">Inbox</a></li>
                                <li><a href="readmessage.html">Read Message</a></li>
                                <li><a href="writemessage.html">Write Message</a></li>
                            </ul>
                        </li>
                        <li>
                            <a>
                            Error Pages <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li><a href="403.html">Error 403</a></li>
                                <li><a href="404.html">Error 404</a></li>
                                <li><a href="405.html">Error 405</a></li>
                                <li><a href="500.html">Error 500</a></li>
                                <li><a href="503.html">Error 503</a></li>
                                <li><a href="maintenance.html">Maintenance</a></li>
                            </ul>
                        </li>
                        <li><a href="invoice.html">Invoice</a></li>
                        <li><a href="lockscreen.html">Lock Screen</a></li>
                        <li><a href="login.html">Login</a></li>
                        <li>
                            <a>
                            News <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li><a href="news.html">News Feed</a></li>
                                <li><a href="newsarticle.html">News Article</a></li>
                            </ul>
                        </li>
                        <li><a href="profile.html">Profile</a></li>
                        <li><a href="register.html">Register</a></li>
                        <li><a href="searchresults.html">Search Results</a></li>
                    </ul>
                </li>
                <li>
                    <a>
                        Miscellaneous 
                        <div>
                            <img src="assets/img/icons/folder.png" alt="">
                        </div>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                        <li>
                            <a>
                            3rd Level <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li>
                                    <a>
                                    4th Level <i class="fa fa-angle-right pull-right"></i>
                                    </a>
                                    <ul class="dropdown-menu second" role="menu">
                                        <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                        <li>
                                            <a>
                                            5th Level <i class="fa fa-angle-right pull-right"></i>
                                            </a>
                                            <ul class="dropdown-menu second" role="menu">
                                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                                <li><a href="#">This could go on forever</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a>
                            Calendar <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li><a href="fullcalendar.html">Full Calendar</a></li>
                                <li><a href="biccalendar.html">Bic Calendar</a></li>
                            </ul>
                        </li>
                        <li>
                            <a>
                            Gallery <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li><a href="gallery.html">Bootstrap Gallery</a></li>
                                <li><a href="lightbox.html">Lightbox</a></li>
                                <li><a href="photogrid.html">Photoset Grid</a></li>
                            </ul>
                        </li>
                        <li>
                            <a>
                            Tables <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li><a href="tables.html">Bootstrap Tables</a></li>
                                <li><a href="datatables.html">Data Tables</a></li>
                                <li><a href="pricingtables.html">Pricing Tables</a></li>
                            </ul>
                        </li>
                        <li>
                            <a>
                            Email Templates <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu second" role="menu">
                                <li><a class="back"><i class="fa fa-angle-left"></i> Back</a></li>
                                <li><a href="../emails/newsletter.html">Newsletter</a></li>
                                <li><a href="../emails/user.html">User</a></li>
                                <li><a href="../emails/alert.html">User Alert</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="logout">
                    <a href="login.html">
                        Logout 
                        <div>
                            <img src="assets/img/icons/off.png" alt="">
                        </div>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="overlay"></div>
        <div class="controlshint"><img src="assets/img/swipe.png" alt="Menu Help"></div>
        <section class="wrap">
            <div class="container">
            	<ol class="breadcrumb">
					<li><a href="#">Form Elements</a></li>
					<li><a href="#">Components</a></li>
					<li><a href="#">Bootstrap Components</a></li>
					<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
				</ol>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="well">
                            <div class="header">Address Form <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
                            <form>
                                <fieldset>
                                    <div class="form-group">
                                        <label>
                                        Full Name
                                        </label>
                                        <input id="full-name" name="full-name" type="text" placeholder="full name" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>
                                        Address Line 1
                                        </label>
                                        <input id="address-line1" name="address-line1" type="text" placeholder="address line 1" class="form-control">
                                        <p class="help-block">
                                            Street address, P.O. box, company name, c/o
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label>
                                        Address Line 2
                                        </label>
                                        <input id="address-line2" name="address-line2" type="text" placeholder="address line 2" class="form-control">
                                        <p class="help-block">
                                            Apartment, suite , unit, building, floor, etc.
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label>
                                        City / Town
                                        </label>
                                        <input id="city" name="city" type="text" placeholder="city" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>
                                        State / Province / Region
                                        </label>
                                        <input id="region" name="region" type="text" placeholder="state / province / region" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>
                                        Zip / Postal Code
                                        </label>
                                        <input id="postal-code" name="postal-code" type="text" placeholder="zip or postal code" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>
                                        Country
                                        </label>
                                        <div>
                                            <select id="country" name="country" class="selectpicker">
                                                <option value="" selected="selected">(please select a country)</option>
                                                <option value="AF">Afghanistan</option>
                                                <option value="AL">Albania</option>
                                                <option value="DZ">Algeria</option>
                                                <option value="AS">American Samoa</option>
                                                <option value="AD">Andorra</option>
                                                <option value="AO">Angola</option>
                                                <option value="AI">Anguilla</option>
                                                <option value="AQ">Antarctica</option>
                                                <option value="AG">Antigua and Barbuda</option>
                                                <option value="AR">Argentina</option>
                                                <option value="AM">Armenia</option>
                                                <option value="AW">Aruba</option>
                                                <option value="AU">Australia</option>
                                                <option value="AT">Austria</option>
                                                <option value="AZ">Azerbaijan</option>
                                                <option value="BS">Bahamas</option>
                                                <option value="BH">Bahrain</option>
                                                <option value="BD">Bangladesh</option>
                                                <option value="BB">Barbados</option>
                                                <option value="BY">Belarus</option>
                                                <option value="BE">Belgium</option>
                                                <option value="BZ">Belize</option>
                                                <option value="BJ">Benin</option>
                                                <option value="BM">Bermuda</option>
                                                <option value="BT">Bhutan</option>
                                                <option value="BO">Bolivia</option>
                                                <option value="BA">Bosnia and Herzegowina</option>
                                                <option value="BW">Botswana</option>
                                                <option value="BV">Bouvet Island</option>
                                                <option value="BR">Brazil</option>
                                                <option value="IO">British Indian Ocean Territory</option>
                                                <option value="BN">Brunei Darussalam</option>
                                                <option value="BG">Bulgaria</option>
                                                <option value="BF">Burkina Faso</option>
                                                <option value="BI">Burundi</option>
                                                <option value="KH">Cambodia</option>
                                                <option value="CM">Cameroon</option>
                                                <option value="CA">Canada</option>
                                                <option value="CV">Cape Verde</option>
                                                <option value="KY">Cayman Islands</option>
                                                <option value="CF">Central African Republic</option>
                                                <option value="TD">Chad</option>
                                                <option value="CL">Chile</option>
                                                <option value="CN">China</option>
                                                <option value="CX">Christmas Island</option>
                                                <option value="CC">Cocos (Keeling) Islands</option>
                                                <option value="CO">Colombia</option>
                                                <option value="KM">Comoros</option>
                                                <option value="CG">Congo</option>
                                                <option value="CD">Congo, the Democratic Republic of the</option>
                                                <option value="CK">Cook Islands</option>
                                                <option value="CR">Costa Rica</option>
                                                <option value="CI">Cote d'Ivoire</option>
                                                <option value="HR">Croatia (Hrvatska)</option>
                                                <option value="CU">Cuba</option>
                                                <option value="CY">Cyprus</option>
                                                <option value="CZ">Czech Republic</option>
                                                <option value="DK">Denmark</option>
                                                <option value="DJ">Djibouti</option>
                                                <option value="DM">Dominica</option>
                                                <option value="DO">Dominican Republic</option>
                                                <option value="TP">East Timor</option>
                                                <option value="EC">Ecuador</option>
                                                <option value="EG">Egypt</option>
                                                <option value="SV">El Salvador</option>
                                                <option value="GQ">Equatorial Guinea</option>
                                                <option value="ER">Eritrea</option>
                                                <option value="EE">Estonia</option>
                                                <option value="ET">Ethiopia</option>
                                                <option value="FK">Falkland Islands (Malvinas)</option>
                                                <option value="FO">Faroe Islands</option>
                                                <option value="FJ">Fiji</option>
                                                <option value="FI">Finland</option>
                                                <option value="FR">France</option>
                                                <option value="FX">France, Metropolitan</option>
                                                <option value="GF">French Guiana</option>
                                                <option value="PF">French Polynesia</option>
                                                <option value="TF">French Southern Territories</option>
                                                <option value="GA">Gabon</option>
                                                <option value="GM">Gambia</option>
                                                <option value="GE">Georgia</option>
                                                <option value="DE">Germany</option>
                                                <option value="GH">Ghana</option>
                                                <option value="GI">Gibraltar</option>
                                                <option value="GR">Greece</option>
                                                <option value="GL">Greenland</option>
                                                <option value="GD">Grenada</option>
                                                <option value="GP">Guadeloupe</option>
                                                <option value="GU">Guam</option>
                                                <option value="GT">Guatemala</option>
                                                <option value="GN">Guinea</option>
                                                <option value="GW">Guinea-Bissau</option>
                                                <option value="GY">Guyana</option>
                                                <option value="HT">Haiti</option>
                                                <option value="HM">Heard and Mc Donald Islands</option>
                                                <option value="VA">Holy See (Vatican City State)</option>
                                                <option value="HN">Honduras</option>
                                                <option value="HK">Hong Kong</option>
                                                <option value="HU">Hungary</option>
                                                <option value="IS">Iceland</option>
                                                <option value="IN">India</option>
                                                <option value="ID">Indonesia</option>
                                                <option value="IR">Iran (Islamic Republic of)</option>
                                                <option value="IQ">Iraq</option>
                                                <option value="IE">Ireland</option>
                                                <option value="IL">Israel</option>
                                                <option value="IT">Italy</option>
                                                <option value="JM">Jamaica</option>
                                                <option value="JP">Japan</option>
                                                <option value="JO">Jordan</option>
                                                <option value="KZ">Kazakhstan</option>
                                                <option value="KE">Kenya</option>
                                                <option value="KI">Kiribati</option>
                                                <option value="KP">Korea, Democratic People's Republic of</option>
                                                <option value="KR">Korea, Republic of</option>
                                                <option value="KW">Kuwait</option>
                                                <option value="KG">Kyrgyzstan</option>
                                                <option value="LA">Lao People's Democratic Republic</option>
                                                <option value="LV">Latvia</option>
                                                <option value="LB">Lebanon</option>
                                                <option value="LS">Lesotho</option>
                                                <option value="LR">Liberia</option>
                                                <option value="LY">Libyan Arab Jamahiriya</option>
                                                <option value="LI">Liechtenstein</option>
                                                <option value="LT">Lithuania</option>
                                                <option value="LU">Luxembourg</option>
                                                <option value="MO">Macau</option>
                                                <option value="MK">Macedonia, The Former Yugoslav Republic of</option>
                                                <option value="MG">Madagascar</option>
                                                <option value="MW">Malawi</option>
                                                <option value="MY">Malaysia</option>
                                                <option value="MV">Maldives</option>
                                                <option value="ML">Mali</option>
                                                <option value="MT">Malta</option>
                                                <option value="MH">Marshall Islands</option>
                                                <option value="MQ">Martinique</option>
                                                <option value="MR">Mauritania</option>
                                                <option value="MU">Mauritius</option>
                                                <option value="YT">Mayotte</option>
                                                <option value="MX">Mexico</option>
                                                <option value="FM">Micronesia, Federated States of</option>
                                                <option value="MD">Moldova, Republic of</option>
                                                <option value="MC">Monaco</option>
                                                <option value="MN">Mongolia</option>
                                                <option value="MS">Montserrat</option>
                                                <option value="MA">Morocco</option>
                                                <option value="MZ">Mozambique</option>
                                                <option value="MM">Myanmar</option>
                                                <option value="NA">Namibia</option>
                                                <option value="NR">Nauru</option>
                                                <option value="NP">Nepal</option>
                                                <option value="NL">Netherlands</option>
                                                <option value="AN">Netherlands Antilles</option>
                                                <option value="NC">New Caledonia</option>
                                                <option value="NZ">New Zealand</option>
                                                <option value="NI">Nicaragua</option>
                                                <option value="NE">Niger</option>
                                                <option value="NG">Nigeria</option>
                                                <option value="NU">Niue</option>
                                                <option value="NF">Norfolk Island</option>
                                                <option value="MP">Northern Mariana Islands</option>
                                                <option value="NO">Norway</option>
                                                <option value="OM">Oman</option>
                                                <option value="PK">Pakistan</option>
                                                <option value="PW">Palau</option>
                                                <option value="PA">Panama</option>
                                                <option value="PG">Papua New Guinea</option>
                                                <option value="PY">Paraguay</option>
                                                <option value="PE">Peru</option>
                                                <option value="PH">Philippines</option>
                                                <option value="PN">Pitcairn</option>
                                                <option value="PL">Poland</option>
                                                <option value="PT">Portugal</option>
                                                <option value="PR">Puerto Rico</option>
                                                <option value="QA">Qatar</option>
                                                <option value="RE">Reunion</option>
                                                <option value="RO">Romania</option>
                                                <option value="RU">Russian Federation</option>
                                                <option value="RW">Rwanda</option>
                                                <option value="KN">Saint Kitts and Nevis</option>
                                                <option value="LC">Saint LUCIA</option>
                                                <option value="VC">Saint Vincent and the Grenadines</option>
                                                <option value="WS">Samoa</option>
                                                <option value="SM">San Marino</option>
                                                <option value="ST">Sao Tome and Principe</option>
                                                <option value="SA">Saudi Arabia</option>
                                                <option value="SN">Senegal</option>
                                                <option value="SC">Seychelles</option>
                                                <option value="SL">Sierra Leone</option>
                                                <option value="SG">Singapore</option>
                                                <option value="SK">Slovakia (Slovak Republic)</option>
                                                <option value="SI">Slovenia</option>
                                                <option value="SB">Solomon Islands</option>
                                                <option value="SO">Somalia</option>
                                                <option value="ZA">South Africa</option>
                                                <option value="GS">South Georgia and the South Sandwich Islands</option>
                                                <option value="ES">Spain</option>
                                                <option value="LK">Sri Lanka</option>
                                                <option value="SH">St. Helena</option>
                                                <option value="PM">St. Pierre and Miquelon</option>
                                                <option value="SD">Sudan</option>
                                                <option value="SR">Suriname</option>
                                                <option value="SJ">Svalbard and Jan Mayen Islands</option>
                                                <option value="SZ">Swaziland</option>
                                                <option value="SE">Sweden</option>
                                                <option value="CH">Switzerland</option>
                                                <option value="SY">Syrian Arab Republic</option>
                                                <option value="TW">Taiwan, Province of China</option>
                                                <option value="TJ">Tajikistan</option>
                                                <option value="TZ">Tanzania, United Republic of</option>
                                                <option value="TH">Thailand</option>
                                                <option value="TG">Togo</option>
                                                <option value="TK">Tokelau</option>
                                                <option value="TO">Tonga</option>
                                                <option value="TT">Trinidad and Tobago</option>
                                                <option value="TN">Tunisia</option>
                                                <option value="TR">Turkey</option>
                                                <option value="TM">Turkmenistan</option>
                                                <option value="TC">Turks and Caicos Islands</option>
                                                <option value="TV">Tuvalu</option>
                                                <option value="UG">Uganda</option>
                                                <option value="UA">Ukraine</option>
                                                <option value="AE">United Arab Emirates</option>
                                                <option value="GB">United Kingdom</option>
                                                <option value="US">United States</option>
                                                <option value="UM">United States Minor Outlying Islands</option>
                                                <option value="UY">Uruguay</option>
                                                <option value="UZ">Uzbekistan</option>
                                                <option value="VU">Vanuatu</option>
                                                <option value="VE">Venezuela</option>
                                                <option value="VN">Viet Nam</option>
                                                <option value="VG">Virgin Islands (British)</option>
                                                <option value="VI">Virgin Islands (U.S.)</option>
                                                <option value="WF">Wallis and Futuna Islands</option>
                                                <option value="EH">Western Sahara</option>
                                                <option value="YE">Yemen</option>
                                                <option value="YU">Yugoslavia</option>
                                                <option value="ZM">Zambia</option>
                                                <option value="ZW">Zimbabwe</option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                        <div class="well">
                            <div class="header">Form Components <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
                            <form>
                                <div class="form-group">
                                    <label>
                                    Input
                                    </label>
                                    <input id="focusedInput" type="text" value="This is an input" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>
                                    Disabled input
                                    </label>
                                    <input class="form-control" type="text" id="disabledInput" placeholder="Computer says no" disabled>
                                </div>
                                <div class="form-group">
                                    <label>
                                    Disabled checkbox
                                    </label>
                                    <label class="checkbox">
                                        <span class="checker disabled">
                                            <input type="checkbox" id="optionsCheckbox2" value="option1" disabled="">
                                        </span>
                                        This is a disabled checkbox
                                    </label>
                                </div>
                                <div class="form-group has-success">
                                    <label class="control-label" for="inputSuccess">Input with success</label>
                                    <input type="text" class="form-control" id="inputSuccess">
                                </div>
                                <div class="form-group has-warning">
                                    <label class="control-label" for="inputWarning">Input with warning</label>
                                    <input type="text" class="form-control" id="inputWarning">
                                </div>
                                <div class="form-group has-error">
                                    <label class="control-label" for="inputError">Input with error</label>
                                    <input type="text" class="form-control" id="inputError">
                                </div>
                            </form>
                        </div>
                        <div class="well">
                            <div class="header">Column sizing <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
                            <div class="row">
                                <div class="col-lg-2">
                                    <input type="text" class="form-control" placeholder=".col-lg-2">
                                </div>
                                <div class="col-lg-3">
                                    <input type="text" class="form-control" placeholder=".col-lg-3">
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" placeholder=".col-lg-4">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="well">
                            <div class="header">Horizontal Form <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
                            <form class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label for="inputEmail1" class="col-lg-2 control-label">Email</label>
                                    <div class="col-lg-10">
                                        <input type="email" class="form-control" id="inputEmail1" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword1" class="col-lg-2 control-label">Password</label>
                                    <div class="col-lg-10">
                                        <input type="password" class="form-control" id="inputPassword1" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <div class="checkbox">
                                            <label>
                                            <input type="checkbox"> Remember me
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button type="submit" class="btn btn-default">Sign in</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="well">
                            <div class="header">Control sizing <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
                            <div class="form-group">
                                <input class="form-control input-lg" type="text" placeholder=".input-lg">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="Default input">
                            </div>
                            <div class="form-group">
                                <input class="form-control input-sm" type="text" placeholder=".input-sm">
                            </div>
                        </div>
                        <div class="well">
                            <div class="header">Auto Resizing Textarea <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
                            <textarea rows="5" class="form-control">Just press the enter key and see it react</textarea>
                        </div>
                        <div class="well">
                            <div class="header">Knob Input<a class="headerclose"><i class="icon-remove pull-right"></i></a> <a class="headerrefresh"><i class="icon-refresh pull-right"></i></a> <a class="headershrink"><i class="icon-chevron-down pull-right"></i></a></div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 text-center">
                                    <input type="text" value="50" class="dial" data-thickness=".3" data-inputcolor="#333" data-fgcolor="#64A3D7" data-bgcolor="#ececec" data-width="100%">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 text-center">
                                    <input type="text" value="25" data-min="-50" data-max="50" class="dial2" data-thickness=".3" data-inputcolor="#333" data-fgcolor="#64A3D7" data-bgcolor="#ececec" data-width="100%">
                                </div>
                            </div>
                        </div>
                        <div class="well">
                            <div class="header">Bootstrap Maxlength <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
                            <div class="form-group">
                                <p class="text-muted">
                                    <small>Badge to show up when there are 25 chars or less</small>
                                </p>
                                <input id="mxlenght" maxlength="25" class="form-control" type="text" />
                            </div>
                            <div class="form-group">
                                <p class="text-muted">
                                    <small>badge changes according to length </small>
                                </p>
                                <input id="mxlenght2" maxlength="25" class="form-control" type="text" />
                            </div>
                        </div>
                        <div class="well">
                            <div class="header">jQuery Fullscreen Editor <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
                            <textarea rows="5" class="textarea form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="../../jquery/development-bundle/jquery-1.10.2.js"></script>
        <script type="text/javascript">$(document).bind("mobileinit", function(){$.extend(  $.mobile , {autoInitializePage: false})});</script>
        <script src="../../jquerymobile/jquery.mobile.custom.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/autosize/js/jquery.autosize.min.js"></script>
        <script src="assets/knob/js/jquery.knob.js"></script>
        <script src="assets/bootstrapmaxlength/js/bootstrap-maxlength.min.js"></script>
        <script src="assets/select2/js/select2.min.js"></script>
        <script src="assets/fseditor/js/jquery.fseditor-min.js"></script>
        <script src="assets/js/leftmenu.js"></script>
        <script src="assets/js/theme.js"></script>
        <script src="assets/js/script.js"></script>
        <script>formcomponents();</script>
    </body>
</html>