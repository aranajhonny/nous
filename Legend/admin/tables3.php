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
        <?php echo "<link href='assets/bootstrap/css/bootstrap.css' rel='stylesheet'> "; ?>
        <link href="assets/tablecloth/css/tablecloth.css" rel="stylesheet">
        <?php echo "<link href='assets/css/styles.css' rel='stylesheet'> "; ?>
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
					<li><a href="#">Tables</a></li>
					<li><a href="#">Bootstrap Tables</a></li>
					<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
				</ol>
                <div class="row">
                	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                    <div class="well">
	                    	<div class="header">Table <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
	                    	<div class="table-responsive">
		                    	<table class="table">
		                            <thead>
		                                <tr>
		                                    <th>#</th>
		                                    <th>First Name</th>
		                                    <th>Last Name</th>
		                                    <th>Username</th>
		                                </tr>
		                            </thead>
		                            <tbody>
		                                <tr>
		                                    <td>1</td>
		                                    <td>Jack</td>
		                                    <td>Shephard</td>
		                                    <td>lost101</td>
		                                </tr>
		                                <tr>
		                                    <td>2</td>
		                                    <td>Sterling</td>
		                                    <td>Archer</td>
		                                    <td>dangerzone</td>
		                                </tr>
		                                <tr>
		                                    <td>3</td>
		                                    <td>Stan</td>
		                                    <td>Smith</td>
		                                    <td>Indacia</td>
		                                </tr>
		                                <tr>
		                                    <td>4</td>
		                                    <td>Peter</td>
		                                    <td>Griffin</td>
		                                    <td>sumeg</td>
		                                </tr>
		                                <tr>
		                                    <td>5</td>
		                                    <td>Michael</td>
		                                    <td>Bluth</td>
		                                    <td>ItsAD</td>
		                                </tr>
		                                <tr>
		                                    <td>6</td>
		                                    <td>Walter</td>
		                                    <td>White</td>
		                                    <td>BBcooking</td>
		                                </tr>
		                            </tbody>
		                        </table>
	                    	</div>
	                    </div>
	                    <div class="well">
	                    	<div class="header">Bordered Table <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
	                    	<div class="table-responsive">
		                    	<table class="table table-bordered">
	                                <thead>
	                                    <tr>
	                                        <th>Username</th>
	                                        <th>Date registered</th>
	                                        <th>Role</th>
	                                        <th>Status</th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <tr>
	                                        <td>Donna R. Folse</td>
	                                        <td>2012/05/06</td>
	                                        <td>Admin</td>
	                                        <td><span class="label label-success">Active</span>
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td>Emily F. Burns</td>
	                                        <td>2011/12/01</td>
	                                        <td>Staff</td>
	                                        <td><span class="label label-danger">Banned</span></td>
	                                    </tr>
	                                    <tr>
	                                        <td>Andrew A. Stout</td>
	                                        <td>2010/08/21</td>
	                                        <td>Editor</td>
	                                        <td><span class="label label-default">Inactive</span></td>
	                                    </tr>
	                                    <tr>
	                                        <td>Mary M. Bryan</td>
	                                        <td>2009/04/11</td>
	                                        <td>Staff</td>
	                                        <td><span class="label label-warning">Pending</span></td>
	                                    </tr>
	                                    <tr>
	                                        <td>Mary A. Lewis</td>
	                                        <td>2007/02/01</td>
	                                        <td>User</td>
	                                        <td><span class="label label-success">Active</span></td>
	                                    </tr>
	                                </tbody>
	                            </table>
	                    	</div>
	                    </div>
	                    <div class="well">
	                    	<div class="header">Hover Table <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
	                    	<div class="table-responsive">
	                    		<table class="table table-hover">
	                                <thead>
	                                    <tr>
	                                        <th>Username</th>
	                                        <th>Date registered</th>
	                                        <th>Role</th>
	                                        <th>Status</th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <tr>
	                                        <td>Donna R. Folse</td>
	                                        <td>2012/05/06</td>
	                                        <td>Editor</td>
	                                        <td><span class="label label-success">Active</span>
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td>Emily F. Burns</td>
	                                        <td>2011/12/01</td>
	                                        <td>Staff</td>
	                                        <td><span class="label label-danger">Banned</span></td>
	                                    </tr>
	                                    <tr>
	                                        <td>Andrew A. Stout</td>
	                                        <td>2010/08/21</td>
	                                        <td>User</td>
	                                        <td><span class="label label-default">Inactive</span></td>
	                                    </tr>
	                                    <tr>
	                                        <td>Mary M. Bryan</td>
	                                        <td>2009/04/11</td>
	                                        <td>Editor</td>
	                                        <td><span class="label label-warning">Pending</span></td>
	                                    </tr>
	                                    <tr>
	                                        <td>Mary A. Lewis</td>
	                                        <td>2007/02/01</td>
	                                        <td>Staff</td>
	                                        <td><span class="label label-success">Active</span></td>
	                                    </tr>
	                                </tbody>
	                            </table>
	                    	</div>
	                    </div>
	                    <div class="well">
	                    	<div class="header">Condensed Table <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
	                    	<div class="table-responsive">
		                    	<table class="table table-condensed">
	                                <thead>
	                                    <tr>
	                                        <th>Username</th>
	                                        <th>Date registered</th>
	                                        <th>Role</th>
	                                        <th>Status</th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <tr>
	                                        <td>Donna R. Folse</td>
	                                        <td>2012/05/06</td>
	                                        <td>Editor</td>
	                                        <td><span class="label label-success">Active</span>
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td>Emily F. Burns</td>
	                                        <td>2011/12/01</td>
	                                        <td>Staff</td>
	                                        <td><span class="label label-danger">Banned</span></td>
	                                    </tr>
	                                    <tr>
	                                        <td>Andrew A. Stout</td>
	                                        <td>2010/08/21</td>
	                                        <td>User</td>
	                                        <td><span class="label label-default">Inactive</span></td>
	                                    </tr>
	                                    <tr>
	                                        <td>Mary M. Bryan</td>
	                                        <td>2009/04/11</td>
	                                        <td>Editor</td>
	                                        <td><span class="label label-warning">Pending</span></td>
	                                    </tr>
	                                    <tr>
	                                        <td>Mary A. Lewis</td>
	                                        <td>2007/02/01</td>
	                                        <td>Staff</td>
	                                        <td><span class="label label-success">Active</span></td>
	                                    </tr>
	                                </tbody>
	                            </table>
	                    	</div>
	                    </div>
	                </div>
	                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                	<div class="well">
	                    	<div class="header">Contextual Table <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
	                    	<div class="table-responsive">
	                    		<table class="table">
							        <thead>
							          <tr>
							            <th>#</th>
							            <th>Column heading</th>
							            <th>Column heading</th>
							            <th>Column heading</th>
							          </tr>
							        </thead>
							        <tbody>
							          <tr class="active">
							            <td>1</td>
							            <td>Column content</td>
							            <td>Column content</td>
							            <td>Column content</td>
							          </tr>
							          <tr>
							            <td>2</td>
							            <td>Column content</td>
							            <td>Column content</td>
							            <td>Column content</td>
							          </tr>
							          <tr class="success">
							            <td>3</td>
							            <td>Column content</td>
							            <td>Column content</td>
							            <td>Column content</td>
							          </tr>
							          <tr class="warning">
							            <td>5</td>
							            <td>Column content</td>
							            <td>Column content</td>
							            <td>Column content</td>
							          </tr>
							          <tr class="danger">
							            <td>7</td>
							            <td>Column content</td>
							            <td>Column content</td>
							            <td>Column content</td>
							          </tr>
							        </tbody>
							    </table>
	                    	</div>
	                    </div>
	                    <div class="well">
	                    	<div class="header">Theme Dark <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
	                    	<div class="table-responsive">
		                    	<table class="table" id="darktable">
	                                <thead>
	                                    <tr>
	                                        <th>Username</th>
	                                        <th>Date registered</th>
	                                        <th>Role</th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <tr>
	                                        <td>Donna R. Folse</td>
	                                        <td>2012/05/06</td>
	                                        <td>Editor</td>
	                                    </tr>
	                                    <tr>
	                                        <td>Emily F. Burns</td>
	                                        <td>2011/12/01</td>
	                                        <td>Staff</td>
	                                    </tr>
	                                    <tr>
	                                        <td>Andrew A. Stout</td>
	                                        <td>2010/08/21</td>
	                                        <td>User</td>
	                                    </tr>
	                                    <tr>
	                                        <td>Mary M. Bryan</td>
	                                        <td>2009/04/11</td>
	                                        <td>Editor</td>
	                                    </tr>
	                                    <tr>
	                                        <td>Mary A. Lewis</td>
	                                        <td>2007/02/01</td>
	                                        <td>Staff</td>
	                                    </tr>
	                                </tbody>
	                            </table>
	                    	</div>
	                    </div>
	                    <div class="well">
	                    	<div class="header">Theme Stats <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
	                    	<div class="table-responsive">
		                    	<table class="table" id="statstable">
	                                <thead>
	                                    <tr>
	                                        <th>Username</th>
	                                        <th>Date registered</th>
	                                        <th>Role</th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <tr>
	                                        <td>Donna R. Folse</td>
	                                        <td>2012/05/06</td>
	                                        <td>Editor</td>
	                                    </tr>
	                                    <tr>
	                                        <td>Emily F. Burns</td>
	                                        <td>2011/12/01</td>
	                                        <td>Staff</td>
	                                    </tr>
	                                    <tr>
	                                        <td>Andrew A. Stout</td>
	                                        <td>2010/08/21</td>
	                                        <td>User</td>
	                                    </tr>
	                                    <tr>
	                                        <td>Mary M. Bryan</td>
	                                        <td>2009/04/11</td>
	                                        <td>Editor</td>
	                                    </tr>
	                                    <tr>
	                                        <td>Mary A. Lewis</td>
	                                        <td>2007/02/01</td>
	                                        <td>Staff</td>
	                                    </tr>
	                                </tbody>
	                            </table>
	                    	</div>
	                    </div>
	                    <div class="well">
	                    	<div class="header">Theme Paper <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
	                    	<div class="table-responsive">
		                    	<table class="table" id="papertable">
	                                <thead>
	                                    <tr>
	                                        <th>Username</th>
	                                        <th>Date registered</th>
	                                        <th>Role</th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <tr>
	                                        <td>Donna R. Folse</td>
	                                        <td>2012/05/06</td>
	                                        <td>Editor</td>
	                                    </tr>
	                                    <tr>
	                                        <td>Emily F. Burns</td>
	                                        <td>2011/12/01</td>
	                                        <td>Staff</td>
	                                    </tr>
	                                    <tr>
	                                        <td>Andrew A. Stout</td>
	                                        <td>2010/08/21</td>
	                                        <td>User</td>
	                                    </tr>
	                                    <tr>
	                                        <td>Mary M. Bryan</td>
	                                        <td>2009/04/11</td>
	                                        <td>Editor</td>
	                                    </tr>
	                                    <tr>
	                                        <td>Mary A. Lewis</td>
	                                        <td>2007/02/01</td>
	                                        <td>Staff</td>
	                                    </tr>
	                                </tbody>
	                            </table>
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
        <script src="assets/tablecloth/js/jquery.tablesorter.min.js"></script>
        <script src="assets/tablecloth/js/jquery.tablecloth.js"></script>
        <script src="assets/js/leftmenu.js"></script>
        <script src="assets/js/theme.js"></script>
        <script src="assets/js/script.js"></script>
        <script>tables();</script>
    </body>
</html>