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
	<link rel="stylesheet" type="text/css" href="./css/slick.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.css">

	<link rel="stylesheet" type="text/css" href="./css/article.css">
	<link rel="stylesheet" type="text/css" href="./css/homepage-articles.css">
	<link rel="stylesheet" type="text/css" href="./css/styles.css">

	<script type="text/javascript" src="scripts/moment.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

	<?php
		require ("config.php");
		if (isset($_GET["article"])) {
	        $id = htmlspecialchars($_GET["article"]);
	    }

		$queryUpdComm = mysqli_query($connection, 'select  count(c.id_comments)
															from user u, comments c, article a
															where c.user_id_user = u.id_user and c.article_id_article = a.id_article
															and a.id_article = "'.$id.'";');
		$rowUpdComm = mysqli_fetch_row($queryUpdComm);
		$queryUpdComm = mysqli_query($connection, 'update article SET comment_nr="'.$rowUpdComm[0].'" WHERE id_article="'.$id.'";');

		$query = mysqli_query($connection, "select id_article, tag, title, author, comment_nr, date_created from article where id_article=$id;");
		$rowArticle = mysqli_fetch_row($query);

		$time = new DateTime($rowArticle[5]);
		$time = $time->format("Y/m/d H:i:s");
		$userError=0;
		//$date = $time->format('d/m/Y');
		//$time = $time->format('H:i:s');

		$id = $rowArticle[0];

		$queryRel = mysqli_query($connection, "select id_article, title, comment_nr, date_created FROM article where tag = '".$rowArticle[1]."' AND id_article != $id ORDER BY RAND() LIMIT 4");

		$related = array();
		while ($rowsRel = mysqli_fetch_assoc($queryRel)) {
		  $related[] = $rowsRel;
		}

		// Atenção! Retorna um array bidimensional! $related[0]['title'] para o título do primeiro related block

		//Comment Handling
		if (isset($_POST['comment'])) {
			if (isset($_SESSION['username'])) {
		        $idNewComment=0;
		        $query = mysqli_query($connection, "select id_comments from comments where id_comments=".$idNewComment);
		        $row = mysqli_fetch_row($query);
		        while ($row[0]!=null) {
		          $idNewComment++;
		          $query = mysqli_query($connection, "select id_comments from comments where id_comments=".$idNewComment);
		          $row = mysqli_fetch_row($query);
		        }

		        $queryGetUserId = mysqli_query($connection, "select id_user from user where username = '".$_SESSION['username']."'");
		        $userId = mysqli_fetch_row($queryGetUserId);
		        $userId = $userId[0];

		        $text = htmlspecialchars($_POST["comment"]);
		        $insertComment = mysqli_query($connection, "insert into comments values (".$idNewComment.", ".$id.", ".$userId.", '".$text."' , now())");
		        $_POST['comment']="";
			} else {
				$userError=1;
			}
		}

		$queryComments = mysqli_query($connection, "select  id_article, text, c.date_created comment_date, id_user, username
														from user u, comments c, article a
														where c.user_id_user = u.id_user and c.article_id_article = a.id_article
														and a.id_article = $id
														ORDER BY comment_date desc;");

		$comments=array();
		while ($rowsComments = mysqli_fetch_assoc($queryComments)) {
			$comments[] = $rowsComments;
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
			<div class="jumbotron jumbotron-fluid" id="showcase-jumbotron" style="background-image: url('<?php echo "articles/$id/images/header.jpg"; ?>');">
			  <div class="container-fluid">
			  	<div class="row">
			  		<div class="col" id="showcase-foreground">
						<div class="title"><?php echo $rowArticle[2]; ?></div>
						<div class="subtitle">by <?php echo $rowArticle[3]; ?> - <script> document.write(moment("<?php echo $time; ?>").format("DD/MM/YYYY")); </script></div>
						<div class="news-tag"><?php echo $rowArticle[1]; ?></div>
						<h6><i class="fa fa-commenting" aria-hidden="true"></i><div id="comment-count" class="d-inline"><?php echo $rowArticle[4]; ?></div>
							<iframe src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&width=51&layout=button&action=like&size=small&show_faces=false&share=false&height=65&appId" width="51" height="65" style="border:none;overflow:hidden;display: inline-block; height: 20px; vertical-align: middle;" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
						</h6>
			  		</div>
			  	</div>
			  </div>
			</div>
		</div>
	</div>

	<div class="row justify-content-center" id="content-container">
		<div class="col col-lg-8">
			<div class="row">
				<div class="col">
					<?php

					$content=explode("|",file_get_contents("articles/".$id."/text.txt"));
					//var_dump($content);

					foreach ($content as $entry) {
						if (strpos($entry,".jpg")==true || strpos($entry,".png")==true) {
							$image=explode("/", $entry);
							echo "
								<img class='img-fluid img-article' src='articles/".$id."/images/".$image[0]."' alt='Card image cap'>
							";
						} else {
							echo nl2br($entry);
						}						
					}

					?>
				</div>
			</div>
			
			<div class="row">
				<div class="col">
					<div id="article-carousel">
						<?php
							$content=scandir("articles/".$id."/images");

							foreach ($content as $entry) {
								if ($entry!="header.jpg" && $entry!="header_small.jpg" && $entry!="." && $entry!="..") {
									echo "
										<div>
											<img class='carousel-img' src='articles/".$id."/images/".$entry."'>
										</div>
									";
								}
													
							}
						?>				
					</div>
					<hr class="hidden-sm-down">
					<center>
						<div id="article-preview" class="hidden-sm-down">
							<?php
								$content=scandir("articles/".$id."/images/");

								foreach ($content as $entry) {
									if ($entry!="header.jpg" && $entry!="header_small.jpg" && $entry!="." && $entry!="..") {
										echo "
											<div>
												<img class='carousel-img' src='articles/".$id."/images/".$entry."'>
											</div>
										";
									}					
								}
						?>
						</div>
					</center>					
				</div>
			</div>
		</div>

		<div class="col-lg-4 hidden-md-down">
				<div class="row">
				  	<div class="col">
						<iframe src="iframes/newsletter-form.php" width="100%" height="250px">
							
						</iframe>
					</div>
				</div>
				<div class="row sidebar-block">
					<div class="col">
						<h5 class="sidebar-text">
							Check out <span class="oracle-green">Green Oracle</span> on:
						</h5>
						<div id="social-networks">
							<i class="fa fa-facebook-square"></i>
							<i class="fa fa-twitter-square"></i>
							<i class="fa fa-pinterest-square"></i>
							<br>
							<i class="fa fa-reddit-square"></i>
							<i class="fa fa-linkedin-square"></i>
						</div>
					</div>
				</div>

				<!-- Related Container -->
				<div class="row sidebar-block">
					<div class="col">
						<h5 class="sidebar-text">
							Related <span class="oracle-green">News</span>
						</h5>
						<?php 

							foreach ($related as $block) {
								$time = new DateTime($block['date_created']);
								$time = $time->format("Y/m/d H:i:s");
								echo "
									<div class='row related-block'>
										<div class='col-3 relative-thumb'>
											<a href='article.php?article=".$block['id_article']."' ><img src='articles/".$block['id_article']."/images/header_small.jpg'></a>
										</div>
										<div class='col comment-text'>
											<div class='row'>
												<div class='col'>
													<a href='article.php?article=".$block['id_article']."' ><h6>".$block['title']."</h6></a>
												</div>
											</div>
											<div class='row'>
												<div class='col'>
													<i class='fa fa-commenting' aria-hidden='true'></i> ".$block['comment_nr']." <i class='fa fa-clock-o' aria-hidden='true'></i> <script> document.write(moment('$time').fromNow()); </script>
												</div>
											</div>											
										</div>
									</div>
								";
							}

						?>
					</div>
				</div>
			</div>
	</div>

	<!-- Related News Tablet-->
	<div class="row hidden-sm-down hidden-lg-up related-block-tablet">
		<div class="col">
			<div class="row">
				<div class="col">
					<h3>Related News</h3>							
				</div>
			</div>
				<div class="row">
					<?php 

							foreach ($related as $block) {
								$time = new DateTime($block['date_created']);
								$time = $time->format("Y/m/d H:i:s");
								echo "
									<div class='col-6'>
										<div class='row related-block'>
											<div class='col-3 relative-thumb'>
												<a href='article.php?article=".$block['id_article']."' ><img src='articles/".$block['id_article']."/images/header_small.jpg'></a>
											</div>
											<div class='col comment-text'>
												<div class='row'>
													<div class='col'>
														<a href='article.php?article=".$block['id_article']."' ><h6>".$block['title']."</h6></a>
													</div>
												</div>
												<div class='row'>
													<div class='col'>
														<i class='fa fa-commenting'></i> ".$block['comment_nr']." <i class='fa fa-clock-o'></i> <script> document.write(moment('$time').fromNow()); </script>
													</div>
												</div>
											</div>
										</div>
									</div>
								";
							}

						?>
					
				</div>
		</div>
	</div>

	<div class="row" id="comment-container">
				<div class="col col-lg-8">
					<div class="row form-group">
						<div class="col-8">
							<h5><i class='fa fa-commenting' aria-hidden='true'></i> Leave a comment</h5>
							<?php
								if (!isset($_SESSION['username'])) {
									echo "
							You have to be logged in to comment <br>
							<a data-toggle='modal' href='#userPop'><strong>Login here</strong></a>
									";
								}

							?>
						</div>
						<div class="col-4" style="text-align: right;">
							<h5><i class="fa fa-share-alt"></i> Share</h5>
							<div class="share-icons row">
								<div class="col-12 hidden-md-up">
									<i class="fa fa-facebook-square"></i>
									<i class="fa fa-twitter-square"></i>
								</div>
								<div class="col-12 hidden-md-up">
									<i class="fa fa-pinterest-square"></i>
									<i class="fa fa-share-alt-square"></i>
								</div>
								<div class="col hidden-sm-down">
									<i class="fa fa-facebook-square"></i>
									<i class="fa fa-twitter-square"></i>
									<i class="fa fa-pinterest-square"></i>
									<i class="fa fa-share-alt-square"></i>
								</div>
							</div>							
						</div>
					</div>
					<div class="row">
						<div class="col">
							<form id="comment-form" method="post" action="article.php?article=<?=$id?>#comment-form">
								<div class="row form-group">
									<div class="col">
										<div class="row" style="margin-bottom: 0.4rem">
											<div class="col">
												<textarea class="form-control form-control-danger" rows="8" onload="countChar(this)" onkeyup="countChar(this)" id="comment" name="comment"><?php
													if (isset($_POST['comment'])) {
														echo $_POST['comment'];
													}
												?></textarea>
											</div>
										</div>										
										<div class="row comment-descript">
											<div class="col">
												Comment text is limited to 255 characters
											</div>
											<div class="col" id="char-count">
												<script type="text/javascript">
													var len = $("#comment").val().length;
											        if (len >= 255) {
											          $('#char-count').text(len - 255+" characters over limit");
											        } else {
											          $('#char-count').text(255 - len+" characters remaining");
											        }
												</script>
											</div>
										</div>									
									</div>
								</div>
								<div class="row collapse" id="comment-alert">
									<div class="col">
										<div class="alert alert-danger" role="alert" id="alert-text">
										 	
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										 <input class="btn btn-primary" type="submit" id="comment-submit">
									</div>
								</div>							
							</form>
						</div>
					</div>

					<div class="row">
						<div class="col">
							<!-- Comment Block Start -->
							<?php
								$i=0;
								while ($i<3 && $i<sizeof($comments)) {
									$time = new DateTime($comments[$i]['comment_date']);
									$date = $time->format('d/m/Y');
									$time = $time->format('H:i');


									if (file_exists("users/".$comments[$i]['id_user']."/profile.jpg")) {
										$path="users/".$comments[$i]['id_user']."/profile.jpg";
									} else {
										$path="images/default.jpg";
									}

									echo "
										<div class='row comment-block'>
											<div class='col'>
												<div class='row'>
													<div class='col'>
														<h5>".$comments[$i]['username'].", $date at $time</h5>
													</div>
												</div>
												<div class='row'>
													<div class='col-3 col-lg-2 comment-thumb'>
														<img class='img-fluid img-thumbnail' src='".$path."' width='150'>
													</div>
													<div class='col comment-text'>
														".$comments[$i]['text']."
													</div>
												</div>
											</div>
										</div>
									";
									$i++;
								}

								if (sizeof($comments)>3) {
									echo "<div class='collapse' id='hidden-comments'>";
									while ($i<sizeof($comments)) {
										$time = new DateTime($comments[$i]['comment_date']);
										$date = $time->format('d/m/Y');
										$time = $time->format('H:i');

										if (file_exists("users/".$comments[$i]['id_user']."/profile.jpg")) {
											$path="users/".$comments[$i]['id_user']."/profile.jpg";
										} else {
											$path="images/default.jpg";
										}
										echo "
											<div class='row comment-block'>
												<div class='col'>
													<div class='row'>
														<div class='col'>
															<h5>".$comments[$i]['username'].", $date at $time</h5>
														</div>
													</div>
													<div class='row'>
														<div class='col-3 col-lg-2 comment-thumb'>
															<img class='img-fluid img-thumbnail' src='".$path."' width='150'>
														</div>
														<div class='col comment-text'>
															".htmlspecialchars($comments[$i]['text'])."
														</div>
													</div>
												</div>
											</div>
										";
										$i++;
									}
									echo "</div>";
								}
								
							?>
							<div class="row">
								<div class="col">
									<button data-toggle="collapse" style="display: <?php if (sizeof($comments)<=3) { echo 'none';} ?>;" data-target="#hidden-comments" class="btn btn-primary btn-block" id="comment-load">Load more comments</button>
								</div>
							</div>						
						</div>
					</div>
				</div>
			</div>

	<!-- Social Networks Mobile -->
	<div class="row hidden-lg-up" id="social-mobile">
		<div class="col">
			<h5 class="sidebar-text">
				Check out <span class="oracle-green">Green Oracle</span> on:
			</h5>
			<div id="social-networks">
				<i class="fa fa-facebook-square"></i>
				<i class="fa fa-twitter-square"></i>
				<i class="fa fa-pinterest-square"></i>
				<i class="fa fa-reddit-square"></i>
				<i class="fa fa-linkedin-square"></i>
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
		
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
	<script type="text/javascript" src="scripts/bootstrap.min.js"></script>
	<script type="text/javascript" src="./scripts/slick.min.js"></script>
 	<script type="text/javascript" src="./scripts/validate.min.js"></script>

	<script type="text/javascript">
		$( document ).ready(function() {
	      var constraints = {
	        comment: {
	          presence: {message: "The comment field cannot be empty!"}
	        }
	      }

	      var userError= "<?php echo $userError ?>";

	      //console.log(userError);

	      if (userError==1) {
	      	$("#comment-alert").collapse('show');
	        $("#alert-text").text('Only users may comment! Log in please!');
	      }

	      $("#comment-form").submit(function(event){
	        var errors = validate($("#comment-form"), constraints, {fullMessages: false});       

	        if (errors){
	          event.preventDefault();      
	          for (var key in errors) {
	            //console.log(errors[key]);
	            $("#"+key).parent().parent().addClass("has-danger");
	            $("#"+key).attr("placeholder", errors[key]);
	            $("#"+key).val("");
	          }
	          return;
	        }

	        if ($("#comment").value.length>255) {
	        	event.preventDefault();
	        	$("#comment-alert").collapse('show');
	        	$("#alert-text").text('Comment is too long!');
	        	return;
	        }
	      });

	      $('#hidden-comments').on('shown.bs.collapse', function () {
			  $('#comment-load').detach();
		  });
	    });

	    function countChar(val) {
	    	var len = val.value.length;
	        if (len > 255) {
	          $('#char-count').text(len - 255+" characters over limit");
	        } else {
	          $('#char-count').text(255 - len+" characters remaining");
	        }
	    }

	    $('#article-carousel').slick({
			infinite: true,
			arrows: true,
			dots: true,
			asNavFor:'#article-preview'

		});

		$('#article-preview').slick({
			infinite: true,
			slidesToShow: 3,
			slidesToScroll: 1,
			dots: true,
			arrows: false,
			focusOnSelect: true,
			asNavFor:'#article-carousel'
		});
	</script>
</body>
</html>