<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  	<link rel="shortcut icon" type="image/x-icon" href="images/go.ico" />
	<title>Green Oracle</title>

	<meta charset="utf-8">
	<meta http-equiv="Content-type" content="text/html; charset=utf8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="./css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">

	<link rel="stylesheet" type="text/css" href="./css/article.css">
	<link rel="stylesheet" type="text/css" href="./css/homepage-articles.css">
	<link rel="stylesheet" type="text/css" href="./css/styles.css">

	<style type="text/css">
		td, th {
			padding: 5px;
		}
	</style>

	<?php
		require ("config.php");
	?>
</head>
<body>

<!-- Content Start -->

<img id="bg" src="./images/background.png">

<nav class="navbar sticky-top navbar-toggleable-md navbar-light bg-faded">

			<a class="navbar-brand" href="index.php" style="color: #858f1c;">
			  <img src="./images/green-oracle-icon.png" class="d-inline-block align-top" alt="">
			  Green Oracle
			</a>

			<div class="navbar-toggler-right btn-group" role="group">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-search" aria-controls="navbar-search" aria-expanded="false" aria-label="Toggle navigation">
		    		<i class="fa fa-search" aria-hidden="true"></i>
			  	</button>		  	

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarLinks" aria-controls="navbarLinks" aria-expanded="false" aria-label="Toggle navigation">
		    		<i class="fa fa-bars" aria-hidden="true"></i>
			  	</button>

			  	<div class="btn-group" role="group" style="margin-left: 0.5rem;">
				  	<button class="navbar-toggler btn-user" type="button" <?php
					    	if (isset($_SESSION['username'])) {
					    		echo "data-toggle='dropdown' data-target=''";
					    	} else {
					    		echo "data-toggle='modal' data-target='#userPop'";
					    	}
					    ?>>
				  		<i class="fa fa-user" aria-hidden="true"></i>
				  	</button>
				  	<div class="dropdown-menu">
				    	<div class="dropdown-item session-username"><?=$_SESSION['username']?></div>
				    	<a class="dropdown-item" href="user-area.php">User Area</a>
				    	<a class="dropdown-item" href="logout.php" >Logout</a>
				    </div>
				</div>		
			</div>

		  <div class="collapse navbar-collapse" id="navbarLinks">
		    <ul class="navbar-nav mr-auto">
			  	<li class="nav-item">
			      <a class="nav-link" href="categories.php?category=environment">Environment</a>
			    </li>
			    <li class="nav-item">
			      <a class="nav-link" href="categories.php?category=innovation">Innovation</a>
			    </li>
			    <li class="nav-item">
			      <a class="nav-link" href="categories.php?category=eco+living">Eco Living</a>
			    </li>
			    <li class="nav-item">
			   <a class="nav-link" href="categories.php?category=politics">Politics</a>
			  </li>
			</ul>
		  </div>

		  <div class="collapse navbar-collapse justify-content-end" id="navbar-search">
		  	<form class="navbar-form navbar-left" method="get" action="search.php">
			  <div class="input-group">
			    <input type="text" class="form-control" placeholder="Search" id="search-fld" name="search">
			    <div class="input-group-btn">
			      <button class="btn btn-default" type="submit">
			        <i id="search-icon" class="fa fa-search" aria-hidden="true"></i>
			      </button>
			    </div>
			  </div>
			</form>
			<div class="hidden-md-down" style="margin-left: 0.5rem;">
				<div class="btn-group" role="group">
				    <button type="button" class="btn btn-default dropdown-toggle btn-user" <?php
				    	if (isset($_SESSION['username'])) {
				    		echo "data-toggle='dropdown' data-target=''";
				    	} else {
				    		echo "data-toggle='modal' data-target='#userPop'";
				    	}
				    ?> aria-haspopup="true" aria-expanded="false">
				      <i class="fa fa-user" aria-hidden="true"></i>
				    </button>
				    <div class="dropdown-menu">
				    	<div class="dropdown-item session-username"><?=$_SESSION['username']?></div>
				    	<a class="dropdown-item" href="user-area.php">User Area</a>
				    	<a class="dropdown-item" href="logout.php" >Logout</a>
				    </div>
			  	</div>
			</div>
		  </div>
	</nav>

<div class="container" style="padding-top: 2em;">

	<div class="row justify-content-center" id="content-container" style="margin-top: 0px;">
		<div class="col">
			<h1>Privacy Policy</h1>
			<hr>
			<h4>Usage Policies <br>
			Last Revised: November 23, 2016</h4>
			<?php
				$fh = fopen("docs/privacy.txt", 'r');

			    $pageText = fread($fh, 25000);

			    echo nl2br($pageText);
			?>
			<br><br>
			<table border="1px">
				<tbody>
					<tr>
						<th width="20%">Name</th>
						<th width="20%">Set by/for</th>
						<th width="30%">Description</th>
						<th width="30%">Further reading</th>
					</tr>
					<tr>
						<td>__utma<br> __utmb<br> __utmc<br> __utmz</td>
						<td>Google Analytics</td><td>We use Google Analytics to allow us to analyse the traffic to our site and to review a number of statistics regarding our visitors.</td>
						<td><a title="Google Privacy Policy" href="http://www.google.co.uk/intl/en/analytics/privacyoverview.html" target="_blank">Overview of Google Analytics privacy</a></td>
					</tr>
				</tbody>
			</table>
			<br><br>
			To opt out of being tracked by Google Analytics across all websites visit <a href="http://tools.google.com/dlpage/gaoptout">here</a>.
		</div>
	</div>
</div>

<nav id="footer" class="navbar navbar-toggleable-sm navbar-light bg-faded">
  	<ul class="navbar-nav">
    	<li class="nav-item">
        	<a class="nav-link" href="about.php">About Us</a>
      	</li>
      	<li class="nav-item">
        	<a class="nav-link" href="about.php#contact">Contact</a>
      	</li>
      	<li class="nav-item">
        	<a class="nav-link" href="terms.php">Terms of Use</a>
      	</li>
      	<li class="nav-item">
        	<a class="nav-link" href="privacy.php">Privacy Policy</a>
      	</li>
   	</ul>
   	<div>Green Oracle LLC <i class="fa fa-copyright" aria-hidden="true"></i> GreenOracle.com 2017</div>
</nav>

<!-- Modal Login -->
<div class="modal fade" id="userPop" tabindex="-1" role="dialog" aria-labelledby="userPop" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<iframe src="iframes/login-form.php" width="100%" height="230px">

      	</iframe>
      	<div class="col">
    		<div>Lost your <a data-toggle="modal" href="#reset-modal" data-dismiss="modal">password</a>?</div>
			<div>Not a Member? <a data-toggle="modal" href="#register-modal" data-dismiss="modal">Sign up here</a></div>
    	</div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Register -->
<div class="modal fade" id="register-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userPop">Sign up</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<iframe src="iframes/register-form.php" width="100%" height="350px">
      		
      	</iframe>      	
      </div>
    </div>
  </div>
</div>

<!-- Modal Reset -->
<div class="modal fade" id="reset-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userPop">Reset Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<iframe src="iframes/password-form.php" width="100%" height="250px">
      		
      	</iframe>      	
      </div>
    </div>
  </div>
</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
	<script type="text/javascript" src="scripts/bootstrap.min.js"></script>
</body>
</html>