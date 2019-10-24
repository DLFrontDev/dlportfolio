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
	<link rel="stylesheet" type="text/css" href="./css/croppie.css">

	<link rel="stylesheet" type="text/css" href="./css/article.css">
	<link rel="stylesheet" type="text/css" href="./css/homepage-articles.css">
	<link rel="stylesheet" type="text/css" href="./css/styles.css">
	
	<script type="text/javascript" src="scripts/moment.min.js"></script>

	<?php
		require ("config.php");

		if (isset($_SESSION['username'])) {
			$queryUser = mysqli_query($connection, "select id_user, username, email from user where username = '".$_SESSION['username']."'");
			$rowUser = mysqli_fetch_row($queryUser);
			$userError=0;
			$emailError=0;
			$newsletter='';

			//Profile Picture Update
			if(isset($_POST['avatar'])){
		        $data = $_POST['avatar'];
		    	$type='';

		        list($type, $data) = explode(';', $data);
		        list(, $data)      = explode(',', $data);
		        $data = base64_decode($data);

		        file_put_contents('users/'.$rowUser[0].'/profile.jpg', $data);
		    }

			//Username Update
			if (isset($_POST["username"]) && $_POST["username"]!=$rowUser[1]) {
				$newUser=mysqli_real_escape_string($connection, $_POST["username"]);
				$queryUsername = mysqli_query($connection, "select username from user where username='".$newUser."'");
				if (mysqli_num_rows($queryUsername > 0)) {
					//Username já registado
	          		$userError=1;
				} else {
					$queryUpdate = mysqli_query($connection, "update user set username='".$newUser."' where id_user=".$rowUser[0]);
				}

			}

			//Email Update
			if (isset($_POST["email"]) && $_POST["email"]!=$rowUser[2]) {
				$newEmail=mysqli_real_escape_string($connection, $_POST["email"]);
				$queryEmail = mysqli_query($connection, "select email from user where email=".$newEmail);
				if (mysqli_num_rows($queryEmail > 0)) {
					//Username já registado
	          		$emailError=1;
				} else {
					$queryUpdate = mysqli_query($connection, "update user set email='".$newEmail."' where id_user=".$rowUser[0]);
					if (mysqli_query($connection, "select * from newsletter where id_user=".$rowUser[0])) {
						$queryUpdateNewsletterEmail = mysqli_query($connection, "update newsletter set email='".$newEmail."' where id_user=".$rowUser[0]);
					}					
				}
			}

			//Password Update
			if (isset($_POST["password"])) {
      			$pwd=mysqli_real_escape_string($connection, $_POST["password"]);
				//Create Dynamic salt (based on user id)
		        $salt=md5($rowUser[0]);
		        //Applt md5 hash password
		        $pwd=md5($salt.$pwd);
				$queryUpdate = mysqli_query($connection, "update user set password='".$pwd."' where id_user=".$rowUser[0]);
			}

			//Newsletter Update
			$queryNewsletter = mysqli_query($connection, "select * from newsletter where id_user = ".$rowUser[0]);
			$rowNewsletter = mysqli_fetch_row($queryNewsletter);
			if ($rowNewsletter[1]!=null) {
				$newsletter="checked";
			}

			if (isset($_POST["newsletter"])) {
				$check=htmlspecialchars($_POST["newsletter"]);

				if ($check=="checked") {
					$queryUpdateNewsletter = mysqli_query($connection, "insert into newsletter values ( '".$rowUser[2]."', ".$rowUser[0].")");
					$newsletter="checked";	
				} else {
					$queryUpdateNewsletter = mysqli_query($connection, "delete from newsletter where id_user='".$rowUser[0]."'");
					$newsletter="";	
				}				

				$tags=array();

				foreach ($_POST as $key => $value) {
					if ($key=="newsletter") continue;
					$tags[]=$value;
				}

				$tags=implode("|", $tags);
				$queryUpdateTags = mysqli_query($connection, "update user set news_tags='".$tags."' where id_user=".$rowUser[0]);				
			}

			//Redo Checks after Updates
			if (isset($_POST["username"]) || isset($_POST["email"])) {
				$queryUser = mysqli_query($connection, "select id_user, username, email from user where username = '".$_SESSION['username']."'");
				$rowUser = mysqli_fetch_row($queryUser);
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
			<h1> <?=$rowUser[1] ?> - User Area</h1>

			<hr style="margin-bottom: 2em">

			<!-- User Profile Picture -->
			<div class="row" style="margin-bottom: 3em">
				<div class="col-lg-2 col-md-4 col-sm-12">
					<div class="row collapse" id="cropper-frame">
						<div class="col">
							<div id="cropper"></div>
						</div>
					</div>
					<div class="row collapse" id="image-frame">
						<div class="col">
							<img src=<?php
								if (file_exists("users/".$rowUser[0]."/profile.jpg")) {
									echo "users/".$rowUser[0]."/profile.jpg";
								} else {
									echo "images/default.jpg";
								}
							?> class="img-thumbnail" id="profile-pic" width="150" height="150">						
						</div>
					</div>				
				</div>
				<div class="col-lg-10 col">
					<div class="row">
						<div class="col">
							<form enctype='multipart/form-data' method="post" id="avatar-form">
								<div class="form-group">
									<label for="avatar">
										<h4>Choose your profile picture</h4>
									</label>
									<input type="file" class="form-control-file" id="avatar">
									<input type="hidden" name="avatar" id="imagebase64">
								</div>
							</form>
						</div>
					</div>
					<div class="row collapse" id="cropper-handler">
						<div class="col">
							<button class="btn btn-primary" form="avatar-form">Submit Image</button> <button class="btn btn-secondary">Discard Changes</button>
						</div>						 
					</div>
				</div>				
			</div>

			<!-- User Data Updates -->
			<div class="row" style="margin-bottom: 3em">
				<div class="col">
					<form id="user-form" method="post">
			            <div class="form-group row" id="username-user">
			            	<div class="col-lg-2 col-12">
					            <label for="username">
					            	<h5>Username</h5>
					            </label>
			            	</div>
			            	<div class="col-lg-4 col-12">
			            		<input type="text" class="form-control form-control-danger" id="username" name="username" value="<?=$rowUser[1]?>" placeholder="Your new username" data-toggle="tooltip" data-placement="top" data-trigger="focus" title="20 Characters Maximum">
			            	</div>
			            </div>
			            <div class="form-group row"  id="email-user" style="margin-bottom: 2em">
			            	<div class="col-lg-2 col-12">
					            <label for="email">
					            	<h5>Email Address</h5>
					            </label>
			            	</div>
			            	<div class="col-lg-4 col-12">
			            		<input type="text" class="form-control form-control-danger" id="email" name="email" value="<?=$rowUser[2]?>" placeholder="Your new email address">
			            	</div>
			            </div>
			            <div class="form-group row">
			            	<div class="col">
					            <label for="username">
					            	<h4>Change Password</h4>
					            </label>
					            <div class="row form-group"  id="password-user">
					            	<div class="col-lg-2 col-12">
							            <label for="password">
							            	<h5>Password</h5>
							            </label>
					            	</div>
					            	<div class="col-lg-4 col-12">
					            		<input type="password" class="form-control form-control-danger" id="password" name="password" placeholder="Your new password" data-toggle="tooltip" data-placement="top" data-trigger="focus" title="5 Characters Minimum, must contain letters and numbers">
					            	</div>
					            </div>
					            <div class="row form-group" id="confPassword-user">
					            	<div class="col-lg-2 col-12">
							            <label for="confPassword">
							            	<h5>Confirm Password</h5>
							            </label>
					            	</div>
					            	<div class="col-lg-4 col-12">
					            		<input type="password" class="form-control form-control-danger" id="confPassword" name="confPassword" placeholder="Confirm password">
					            	</div>
					            </div>
			            	</div>
			            </div>
			            <div class="form-group row">
			            	<div class="col-lg-3 col form-group">
			            		<button type="submit" class="btn btn-primary btn-block">Save Changes</button>
			            	</div>
			            	<div class="col-lg-3 col form-group">
			            		<button type="reset" class="btn btn-secondary btn-block">Discard Changes</button>
			            	</div>
			            </div>
		        	</form>
				</div>
			</div>

			<div class="row">
				<div class="col">
					<h4>Newsletter</h4>
					<hr>
					<form id="newsletter-form" method="post">
						<div class="form-group row">
							<div class="col">
								<div class="form-check">
									<label class="form-check-label">
										<input class="form-check-input" id="newsletter-check" type="checkbox" <?=$newsletter?> >
										<input type="hidden" id="newsletter-value" name="newsletter" value="<?=$newsletter?>">
										Subscribe to Newsletter
									</label>
								</div>
							</div>							
						</div>
						<div class="form-group row">
							<div class="col">
								<h5>Customize your Newsletter</h5>
								<div class="row">
									<div class="col">
										<h5>Enabled Tags</h5>
										<div class="list-border" id="tags-enable">
											<?php
												$queryUserTags=mysqli_query($connection, "select news_tags from user where id_user=".$rowUser[0]);
												$rowUserTags=mysqli_fetch_row($queryUserTags);
												if ($rowUserTags[0]!=null) {
													$rowUserTags=explode("|", $rowUserTags[0]);

													foreach ($rowUserTags as $key => $value) {
														echo "
															<button type='button' class='btn btn-primary btn-block btn-newsletter'>
																".ucfirst($value)."
																<input type='hidden' name='tag-$value' value='$value'>
															</button>
														";
													}
												}												
											?>
										</div>										
									</div>
									<div class="col">
										<h5>Disabled Tags</h5>
										<div class="list-border" id="tags-disable">
											<?php
												$queryTags=mysqli_query($connection, "select * from tags");
												$tags = array();
												while ($rowTags = mysqli_fetch_assoc($queryTags)) {
												  $tags[] = $rowTags["tag"];
												}
												
												foreach ($tags as $key => $value) {
													if (!in_array($value, $rowUserTags)) {
														echo "
															<button type='button' class='btn btn-primary btn-block btn-newsletter'>
																".ucfirst($value)."
																<input type='hidden' name='' value='$value'>
															</button>
														";
													}													
												}
											?>										
										</div>
									</div>
								</div>
							</div>
						</div>
			            <div class="form-group row">
			            	<div class="col-lg-6 col form-group">
			            		<button type="submit" class="btn btn-primary btn-block">Save Changes</button>
			            	</div>
			            	<div class="col-lg-6 col form-group">
			            		<button type="button" class="btn btn-secondary btn-block" onClick="window.location.reload()">Discard Changes</button>
			            	</div>
			            </div>
					</form>
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
	<script type="text/javascript" src="scripts/croppie.min.js"></script>
 	<script type="text/javascript" src="./scripts/validate.min.js"></script>

	<script type="text/javascript">
		$( document ).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();

			function hasNumbers(t) {
				var regex = /\d/;
				return regex.test(t);
			} 

			var constraints = {
			username: {
			  presence: {message: "Username is required!"},
			  length: {
			    maximum: 20,
			    message: "Username is too long!"
			  }
			},
			email: {
			  presence: {message: "Email address is required!"},
			  email: {message: "Please enter a valid email!"}
			},
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
			var userError="<?php echo $userError ?>";
			var emailError="<?php echo $emailError ?>";
			var passVal =  $("#password").val();

			if (userError==1) {
				$("#username-user").addClass("has-danger");
				$("#username").attr("placeholder", "Username already in use!");
				$("#username").val("");
			}
			if (emailError==1) {
				$("#email-user").addClass("has-danger");
				$("#email").attr("placeholder", "Email already in use!");
				$("#email").val("");
			}

			$("#user-form").submit(function(event){
				var errors = validate($("#user-form"), constraints, {fullMessages: false});

				if (hasNumbers(passVal)==true) {
				  event.preventDefault();
				  $("#password-user").addClass("has-danger");
				  $("#password").attr("placeholder", "Must contain a number!");
				  $("#password").val("");
				  $("#confPassword").val("");
				}

				if (errors){
				  event.preventDefault();      
				  for (var key in errors) {
				    //console.log(errors[key]);
				    $("#"+key+"-user").addClass("has-danger");
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
				var elemId = "#"+ $(this).attr('id') +"-user";
				if ($(elemId).hasClass("has-danger")) {
				  $(elemId).removeClass("has-danger");
				}
			});

			//Submit Newsletter tags
			$(".btn-newsletter").click(function(){
				if ($(this).parent().attr("id")=="tags-disable") {
					$("#tags-enable").append(this);
					$(this).find("input").attr("name", "tag-"+ $(this).find("input").val());
				} else {
					$("#tags-disable").append(this);
					$(this).find("input").attr("name", "");			
				}				
			});

			$("#newsletter-check").click(function(){
				var valContain=$("#newsletter-value");
				if (valContain.val()=="checked") {
					valContain.val("unchecked");
				} else {
					valContain.val("checked");
				}
				
			});	

			//Profile Picture Form
			$('#image-frame').collapse('show');
			var $uploadCrop;

		    function readFile(input) {
		        if (input.files && input.files[0]) {
		            var reader = new FileReader();          
		            reader.onload = function (e) {
		                $uploadCrop.croppie('bind', {
		                    url: e.target.result
		                });
		                $('#cropper').addClass('ready');
		            }           
		            reader.readAsDataURL(input.files[0]);
		        }
		    }

		    $uploadCrop = $('#cropper').croppie({
		        viewport: {
		            width: 150,
		            height: 150
		        },
			    enforceBoundary: false,
			    customClass: "img-thumbnail"
		    });

		    $('#avatar').on('change', function () {
		    	$('#image-frame').collapse('hide');
		    	readFile(this);
		    	$('#cropper-frame').collapse('show');
		    	$('#cropper-handler').collapse('show');
		    	
		    });

			$("#avatar-form").submit(function(event){
				$uploadCrop.croppie('result', {
		            type: 'canvas',
		            size: 'viewport'
		        }).then(function (resp) {
		            $('#imagebase64').val(resp);
		        });
			});


			
		});
	</script>
</body>
</html>