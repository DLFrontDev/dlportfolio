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

	<script type="text/javascript" src="scripts/moment.min.js"></script>

	<?php
		require ("config.php");
		$email=0;
		$userEmail='';
		$username='';

		if (isset($_SESSION['username'])) {
			$queryUserEmail="select email from user where username='".$_SESSION['username']."'";
			$result=mysqli_query($connection, $queryUserEmail);
			$row=mysqli_fetch_row($result);
			$userEmail=$row[0];
			$username=$_SESSION['username'];
		}
		
		if (isset($_POST["name"]) && isset($_POST["subject"]) && isset($_POST["email"]) && isset($_POST["message"])) {
            $name = mysqli_real_escape_string($connection, $_POST['name']);
            $subject = mysqli_real_escape_string($connection, $_POST['subject']);
            $email = mysqli_real_escape_string($connection, $_POST['email']);
            $message = mysqli_real_escape_string($connection, $_POST['message']);
            $emailTo = "diogocardosoluis@gmail.com";

            $message = $message." \n by ".$name;
            $headers = "From: ".$email. "\r\n";

			if (mail($emailTo, $subject, $message, $headers)) {
                $email=1;
          	} else {
            	$email=2;
          	}
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

<!-- Showcase -->
<div class="container">
	<div class="row">
		<div class="col" style="padding: 0;">
			<div class="jumbotron jumbotron-fluid" id="showcase-jumbotron" style="background-image: url('images/about.jpg'); background-size: cover;">
			  <div class="container-fluid">
			  	<div class="row">
			  		<div class="col" id="showcase-foreground">
						<div class="title">About Us</div>
			  		</div>
			  	</div>
			  </div>
			</div>
		</div>
	</div>

	<div class="row" id="content-container">
		<div class="col">
			<div class="row" style="margin-bottom: 3em">
				<div class="col">
					<div class="row">
						<div class="col">
							Feel free to contact us! We'd love to hear from you regarding tips, sponsorship ideas or anything else.
							<br><br>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-12">
							Please recognize that we can't always respond to all emails, as we get a lot. If you happen to write to one of us and don't hear back, try again. Usually when we don't respond to emails, its simply for lack of time rather than anything else.
							<div class="hidden-md-down">
								<br><br>
								<h6> <i class="fa fa-envelope" aria-hidden="true"></i> Email Address </h6>
								info@greenoracle.com 
		 						<br><br>			 						
								<h6> <i class="fa fa-map-marker" aria-hidden="true"></i> Street Address </h6>
								Green Oracle <br>
								Greenworld Inc <br>
								909 N Sepulveda Blvd <br>
								El Segundo, CA 90245 <br><br>
								<h6> <i class="fa fa-phone" aria-hidden="true"></i> Phone Number </h6>
								(646) 820-2724<br>
							</div>										
						</div>
						<div class="col-lg-6 col-12" id="map-container">
							<h5>Come visit our HQ!</h5>
							<hr>
							<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3310.445404265606!2d-118.39867698500856!3d33.929670631627395!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c2b1344c6a8ff9%3A0xe7b0ad1f2dc366d0!2s909+N+Sepulveda+Blvd%2C+El+Segundo%2C+CA+90245%2C+USA!5e0!3m2!1sen!2spt!4v1496414129438" width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
						</div>
					</div>
					<div class="row hidden-lg-up">
						<div class="col">
							<br><br>
							<h6> <i class="fa fa-envelope" aria-hidden="true"></i> Email Address </h6>
							info@greenoracle.com 
	 						<br><br>			 						
							<h6> <i class="fa fa-map-marker" aria-hidden="true"></i> Street Address </h6>
							Green Oracle <br>
							Greenworld Inc <br>
							909 N Sepulveda Blvd <br>
							El Segundo, CA 90245 <br><br>
							<h6> <i class="fa fa-phone" aria-hidden="true"></i> Phone Number </h6>
							(646) 820-2724<br>					
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="row" style="margin-bottom: 3em">
						<div class="col">
							<h4 id="contact">Send Us a Message</h4>
							<hr>
							<?php
								if (!isset($_SESSION['username'])) {
									echo "
										Have an account?
										<br>
										Log in to make the process easier!
										<br><br>
										<a data-toggle='modal' href='#userPop'><strong>Login here</strong></a>
									";
								}

							?>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<form id="email-form" method="post">
								<div class="row">
									<div class="col-md-4 col-12 form-group" id="email-email">
										<input class="form-control form-control-danger" type="text" name="email" placeholder="Your email address" id="email"
										value=<?=$userEmail?> >	
									</div>
									<div class="col-md-4 col-12 form-group" id="name-email">
										<input class="form-control form-control-danger" type="text" name="name" placeholder="Your full name" id="name" value=<?=$username?>>
									</div>
									<div class="col-md-4 col-12 form-group" id="subject-email">
										<input class="form-control form-control-danger" type="text" name="subject" placeholder="Message subject" id="subject">
									</div>
								</div>
								<div class="form-group row">
									<div class="col" id="message-email">
										<textarea class="form-control" rows="8" name="message" placeholder="Your message" id="message" style="resize: none;"></textarea>									
									</div>
								</div>
								<div class="row justify-content-center">
									<div class="col">
										<div class="alert collapse" role="alert" id="alert-text" style="text-align: center;">
						                    
							            </div>
									</div>
								</div>
								<div class="form-group row justify-content-center">
									<div class="col-md-4 col">
										<button class="btn btn-primary btn-block">Send Message</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
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

      var constraints = {
        name: {
          presence: {message: "Name is required!"}
        },
        email: {
          presence: {message: "Email address is required!"},
          email: {message: "Please enter a valid email!"}
        },
        subject: {
          presence: {message: "Subject is required!"}
        },
        message: {
          presence: {message: "Message field must contain something!"}
        }
      }

      $("#email-form").submit(function(event){
        var errors = validate($("#email-form"), constraints, {fullMessages: false});

        if (errors){
          event.preventDefault();      
          for (var key in errors) {
            //console.log(errors[key]);
            $("#"+key+"-email").addClass("has-danger");
            $("#"+key).attr("placeholder", errors[key]);
            $("#"+key).val("");
          }
          return;
        }
      });

      //Clear has-danger class on input focus
      $('input').focus(function(){
        var elemId = "#"+ $(this).attr('id') +"-form";
        if ($(elemId).hasClass("has-danger")) {
          $(elemId).removeClass("has-danger");
        }
      });

      var emailAlert = "<?=$email?>";

      if (emailAlert!=0) {
        $("#alert-text").collapse('show');
        if (emailAlert==1) {
          $("#alert-text").addClass('alert-success');
          $("#alert-text").text('Email sent with success!');
        } else {
          $("#alert-text").addClass('alert-danger');
          $("#alert-text").text('Error sending email!');
        }
      }     
    });
  </script>
</body>
</html>