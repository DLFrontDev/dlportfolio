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

	<?php
		require ("config.php");
		$queryUpdateTokens="delete from reset_tokens where expires<now()";
		mysqli_query($connection, $queryUpdateTokens);
		$expired='';
		$password=0;

		if (isset($_GET['token']) && isset($_GET['email'])) {
			$token=mysqli_real_escape_string($connection, $_GET['token']);
			$email=mysqli_real_escape_string($connection, $_GET['email']);

			$queryCheckToken="select * from reset_tokens where token='".$token."'";
			$result=mysqli_query($connection, $queryCheckToken);
			if (mysqli_num_rows($result)>0) {
				if (isset($_POST['password'])) {
					$input=mysqli_real_escape_string($connection, $_POST['password']);

					$queryId=mysqli_query($connection, "select id_user from user where email='".$email."'");
					$row=mysqli_fetch_row($queryId);
					$id=$row[0];

					//Create Dynamic salt (based on user id)
			        $salt=md5($id);
			        //Applt md5 hash password
			        $password=md5($salt.$input);

					$queryUpdatePass="update user set password='".$password."' where email='".$email."'";
					mysqli_query($connection, $queryUpdatePass);

					$queryDeleteToken="delete from reset_tokens where id_user=".$id;
					mysqli_query($connection, $queryDeleteToken);

					$password=1;
				}
			} else {
				$expired='disabled';
			}
		} else {
			header('location: index.php');
		}
		
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
			<h1>Password Reset</h1>
			<hr style="margin-bottom: 4em">
			<form id="reset-form" method="post">
				<div class="row form-group"  id="password-reset">
	            	<div class="col-lg-2 col-12">
			            <label for="password">
			            	<h5>Password</h5>
			            </label>
	            	</div>
	            	<div class="col-lg-4 col-12">
	            		<input type="password" class="form-control form-control-danger" id="password" name="password" placeholder="Your new password" data-toggle="tooltip" data-placement="top" data-trigger="focus" title="5 Characters Minimum, must contain letters and numbers" <?=$expired?>>
	            	</div>
	            </div>
	            <div class="row form-group" id="confPassword-reset">
	            	<div class="col-lg-2 col-12">
			            <label for="confPassword">
			            	<h5>Confirm Password</h5>
			            </label>
	            	</div>
	            	<div class="col-lg-4 col-12">
	            		<input type="password" class="form-control form-control-danger" id="confPassword" name="confPassword" placeholder="Confirm password" <?=$expired?>>
	            	</div>
	            </div>
				<div class="form-group row" style="margin-top: 2em">
					<div class="col-md-6 col">
						<button class="btn btn-primary btn-block" type="submit" <?=$expired?>>Reset Password</button>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="alert <?php
						if ($password==1) {
							echo 'alert-success';
						}
						if ($expired=='disabled') {
							echo 'alert-danger';
						}
						?>" role="alert" style="display: <?php
							if ($password==0 && $expired!='disabled') {
								echo 'none';
							} else {
								echo 'block';
							}
						?>">
							<center>
									<?php
										if ($password==1) {
											echo 'Password reset successfully!';
										}
										if ($expired=='disabled') {
											echo 'Reset token expired, resend reset request!';
										}
									?>
							</center>							
						</div>
					</div>
				</div>
			</form>
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
	<script type="text/javascript" src="scripts/validate.min.js"></script>

	<script type="text/javascript">
		$( document ).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();

			function hasNumbers(t) {
				var regex = /\d/;
				return regex.test(t);
			} 

			var constraints = {
			password: {
			  equality: {
			    attribute: "confPassword",
			    message: "Fields do not match!"
			  },
			  length: {
			    minimum: 5,
			    message: "Password is too short!"
			  }
			},
			confPassword: {
			  equality: {
			    attribute: "password",
			    message: "Fields do not match!"
			  }
			}
			}
			var passVal =  $("#password").val();

			$("#reset-form").submit(function(event){
				var errors = validate($("#reset-form"), constraints, {fullMessages: false});

				if (hasNumbers(passVal)==true) {
				  event.preventDefault();
				  $("#password-reset").addClass("has-danger");
				  $("#password").attr("placeholder", "Must contain a number!");
				  $("#password").val("");
				  $("#confPassword").val("");
				}

				if (errors){
				  event.preventDefault();      
				  for (var key in errors) {
				    //console.log(errors[key]);
				    $("#"+key+"-reset").addClass("has-danger");
				    $("#"+key).attr("placeholder", errors[key]);
				    $("#"+key).val("");
				    if (key=="password" && errors[key].length>1) {
				      $("#"+key).attr("placeholder", "Password is too short!");
				    }
				    if (key=="confPassword") {
				      $("#password").val("");
				    }
				  }
				  return;
				}
			});

			//Clear has-danger class on input focus
			$('input').focus(function(){
				var elemId = "#"+ $(this).attr('id') +"-reset";
				if ($(elemId).hasClass("has-danger")) {
				  $(elemId).removeClass("has-danger");
				}
			});
			
		});		
	</script>
</body>
</html>