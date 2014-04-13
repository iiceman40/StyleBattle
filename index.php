<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Style Battle</title>

		<!-- Bootstrap -->
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
		<link href="//netdna.bootstrapcdn.com/bootswatch/3.1.1/slate/bootstrap.min.css" rel="stylesheet">
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
		<link href='http://fonts.googleapis.com/css?family=Audiowide' rel='stylesheet' type='text/css'>
		<link href="css/dropzone.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">StyleMash</a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<form class="navbar-form navbar-left" role="search">
						<input type="text" class="form-control" placeholder="Suchen">
						<button type="submit" class="btn btn-default">Suchen</button>
					</form>
					<ul class="nav navbar-nav navbar-right">
						<li><div class="navbar-text" data-bind="text: loggedInAs().nickname"></div></li>
						<li data-bind="visible: !loggedInAs().email()">
							<div class="navbar-text">
								<i class="glyphicon glyphicon-log-in"></i> <a href="#" data-toggle="modal" data-target="#loginModal">Login</a>
							</div>
						</li>
						<li class="dropdown" data-bind="visible: loggedInAs().email()">
							<div class="navbar-text">
								<i class="glyphicon glyphicon-user"></i> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mein Account <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="#" data-bind="click: function(){goToPage('battle')}">Battle!</a></li>
									<li><a href="#" data-bind="click: function(){goToPage('myStyles')}">Meine Styles</a></li>
									<li class="divider"></li>
									<li><a href="#" data-bind="click: logout">Logout</a></li>
								</ul>
							</div>
						</li>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
		<div class="container">
			<h1>StyleMash</h1>
			<div data-bind="template: { name: page }"></div>

			<script type="text/html" id="battle">
				<div class="row">
					<div class="col-sm-6">
						<button type="button" class="btn btn-default btn-lg btn-block no-padding" data-bind="click: style1().setWinner, disable: style1().loading">
							<span data-bind="text: style1().description"></span>
							<div data-bind="text: style1().rating"></div>
							<img class="img-responsive" data-bind="attr: {src: style1().image}" />
							<i class="fa fa-spinner fa-spin loader" data-bind="visible: style1().loading"></i>
						</button>
						<h4>Comments:</h4>
						<ul class="list-group comments">
							<li class="list-group-item">Cras justo odio</li>
							<li class="list-group-item">Dapibus ac facilisis in</li>
							<li class="list-group-item">Morbi leo risus</li>
							<li class="list-group-item">Porta ac consectetur ac</li>
							<li class="list-group-item">Vestibulum at eros</li>
						</ul>
					</div>
					<div class="col-sm-6">
						<button type="button" class="btn btn-default btn-lg btn-block no-padding" data-bind="click: style2().setWinner, disable: style2().loading">
							<div data-bind="text: style2().description"></div>
							<div data-bind="text: style2().rating"></div>
							<img class="img-responsive" data-bind="attr: {src: style2().image}" />
							<i class="fa fa-spinner fa-spin loader" data-bind="visible: style2().loading"></i>
						</button>
						<h4>Comments:</h4>
						<ul class="list-group comments">
							<li class="list-group-item">Cras justo odio</li>
							<li class="list-group-item">Dapibus ac facilisis in</li>
							<li class="list-group-item">Morbi leo risus</li>
							<li class="list-group-item">Porta ac consectetur ac</li>
							<li class="list-group-item">Vestibulum at eros</li>
							<li class="list-group-item">Dapibus ac facilisis in</li>
							<li class="list-group-item">Porta ac consectetur ac</li>
						</ul>
					</div>
				</div>
			</script>

			<script type="text/html" id="myStyles">
				<div class="row">
					<div class="col-sm-6">
						<h2>Meine Styles</h2>
					</div>
					<div class="col-sm-6">
						<a href="#" class="pull-right" data-toggle="modal" data-target="#addStyleModal"><i class="glyphicon glyphicon-plus"></i> Style hinzufügen</a>
					</div>
				</div>
				<div class="row" id="myStyles" data-bind="foreach: loggedInAs().styles()">
					<div class="col-sm-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<div class="row">
									<div class="col-sm-10">
										<span data-bind="text: description"></span>
									</div>
									<div class="col-sm-2">
										<button type="button" class="close" data-bind="click: $parent.removeStyle">&times;</button>
									</div>
								</div>
							</div>
							<div class="panel-body">
								<img class="img-responsive img-rounded" data-bind="attr: {src: image}" />
							</div>
							<div class="panel-footer">
								Rating: <span data-bind="text: rating"></span>
							</div>
						</div>
					</div>
				</div>
			</script>
		</div>

		<!-- Login Modal -->
		<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Login</h4>
					</div>
					<div class="modal-body">
						<iframe id="remember" name="remember" class="hidden" src="ajax/blank.php"></iframe>
						<form id="loginForm" target="remember" method="post" action="ajax/blank.php" class="form-horizontal" role="form">
							<div class="form-group">
								<label for="loginEmail" class="col-sm-2 control-label">Email</label>
								<div class="col-sm-10">
									<input type="email" class="form-control" id="loginEmail" placeholder="Email" name="email" />
								</div>
							</div>
							<div class="form-group">
								<label for="loginPassword" class="col-sm-2 control-label">Password</label>
								<div class="col-sm-10">
									<input type="password" class="form-control" id="loginPassword" placeholder="Password" name="password" />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<div class="checkbox">
										<label>
											<input type="checkbox" data-bind="checked: stayLoggedIn" /> eingeloggt bleiben
										</label>
									</div>
									<br />
									<span data-bind="html: loginMsg, visible: loginMsg"></span>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<a href="#" class="btn btn-link pull-left">Account anlegen</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
						<button type="button" class="btn btn-primary">Facebook-Login</button>
						<button type="button" class="btn btn-success" data-bind="click: login">Einloggen</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Add Style Modal -->
		<div class="modal fade" id="addStyleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Neuer Style</h4>
					</div>
					<div class="modal-body">
						<form id="addStyleForm" action="ajax/addimage.php" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
							<div class="form-group">
								<label for="newStyleDescription" class="col-sm-2 control-label">Titel</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="newStyleDescription" name="newStyleDescription" data-bind="value: newStyleDescription" placeholder="Kurze Beschreibung oder Name des Styles." />
								</div>
							</div>
							<input type="hidden" class="form-control" readonly data-bind="value: newPathToFile" />
							<input type="hidden" class="form-control" id="imageName" name="newStyleImageName" placeholder="Dateiname" readonly data-bind="value: newStyleImageName" />
							<input type="hidden" class="form-control" id="imageId" name="newStyleImageId" placeholder="Id" readonly data-bind="value: newStyleImageId" />
						</form>
						<form id="addImageForm" action="ajax/addimage.php" method="post" class="dropzone form-horizontal" role="form" enctype="multipart/form-data"></form>
						<div class="alert alert-info" data-bind="text: newStyleError, visible: newStyleError"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
						<button type="button" class="btn btn-success" data-bind="click: addStyle, disable: disableNewStyle">Style hinzufügen</button>
					</div>
				</div>
			</div>
		</div>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/knockout/3.1.0/knockout-min.js"></script>
		<script src="js/md5.js"></script>
		<script src="js/cookie.js"></script>
		<script src="js/dropzone.js"></script>
		<script src="js/masonry.js"></script>
		<script src="js/custom.js"></script>
	</body>
</html>

