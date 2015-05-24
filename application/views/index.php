<html lang="en">
<head>
<meta charset="utf-8">
<title>Red Basket</title>
<meta name="description" content="">
<!-- Mobile viewport optimized: h5bp.com/viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->
<link rel="stylesheet" href="/public/css/bootstrap.css">
<link rel="stylesheet" href="/public/plugins/font-awesome/css/font-awesome.css">
<link rel="stylesheet" href="/public/css/body.css">
<link rel="stylesheet" href="/public/css/validationEngine.jquery.css"
	type="text/css" />

<script src="/public/js/jquery-2.0.0.min.js" type="text/javascript"> </script>
<script src="/public/js/bootstrap.js"></script>

</head>
<body>	
<div style="width:100% ;height:80px">
</div>
<div class="container-fluid">
	<div id="page-login" class="row">
		<div class="col-xs-12 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
			
			<!--<div class="text-right">
				<a href=".welcome/signup" class="txt-default">Sign Up</a>
			</div>
			-->
			<div class="box">
				<div class="box-content">
					<div class="text-center">
						<h3 class="page-header">Red Basket</h3>
					</div>
					<form class="well form-horizontal" role="form" id="signinform" method="post" action="/welcome/login" enctype="multipart/form-data" >
					<div class="form-group">
						<label class="control-label">Username</label>
						<input type="text" class="form-control" name="username" id = "username" placeholder="UserName"/>
					</div>
					<div class="form-group">
						<label class="control-label">Password</label>
						<input type="password" class="form-control" name="password" placeholder="Password" />
					</div>
					<div class="form-group">
						<div class="col-sm-offset-5 col-sm-4">
						  <button type="submit" class="btn btn-primary">Log In</button>
						</div>
					</div>
					<?php
						if($correctflag)
							echo("<div class='text-center' id='message' style = 'color:red'>Please You Enter Correct Password and UserName</div>");
					?>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>

$("#username").click(function(){ $("#message").hide();});
</script>
</body>
</html>