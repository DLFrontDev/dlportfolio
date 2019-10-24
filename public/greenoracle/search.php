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
	
	<link rel="stylesheet" type="text/css" href="./css/homepage-articles.css">
	<link rel="stylesheet" type="text/css" href="./css/styles.css">
	<script type="text/javascript" src="scripts/moment.min.js"></script>

	<?php
		require ("config.php");
		if (isset($_GET["search"])) {
	        $search = htmlspecialchars($_GET["search"]);
	    }

		if (isset($_GET["page"])) {
	        $page = htmlspecialchars($_GET["page"]);
	    } else {
	    	$page=1;
	    }

	    $hlimit=$page*10;
	    $lolimit=$hlimit-10;

		$queryArticle = mysqli_query($connection, "select  id_article, tag, title, comment_nr, date_created
													from article
													where title like '%".$search."%'
													ORDER BY date_created desc
													limit ".$hlimit." offset ".$lolimit.";");
		$article = array();
		while ($rowsArticle = mysqli_fetch_assoc($queryArticle)) {
		  $article[] = $rowsArticle;
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
	<div class="row justify-content-center" id="content-container">
		<div class="col">
			<div class="row" style="margin-bottom: 3em">
				<div class="col">
					<h2>Searched articles with "<?=ucfirst($search)?>"</h2>
				</div>
			</div>
			<div class="row">
				<div class="col col-lg-8">
					<?php
						foreach ($article as $block) {
							$time = new DateTime($block['date_created']);
							$time = $time->format("Y/m/d H:i:s");

							$text=file_get_contents("articles/".$block['id_article']."/text.txt");
							$pos=strpos($text, ' ', 200);
							$text=substr($text,0,$pos );

							echo "
								<div class='row article'>
									<div class='col'>
										<div class='row'>
											<div class='col-12 col-lg-6 articleImg'>
												<div class='img-tag'>
													".$block['tag']."
												</div>
												<a href='article.php?article=".$block['id_article']."'>
													<img class='hidden-md' src='articles/".$block['id_article']."/images/header_small.jpg'>
													<img class='hidden-sm-down hidden-lg-up' src='articles/".$block['id_article']."/images/header.jpg'>
												</a>	
											</div>
											<div class='col col-lg-6'>
												<div class='row'>										
													<div class='col'>
														<h5><a href='article.php?article=".$block['id_article']."'>".$block['title']."</a></h5>
													</div>
												</div>
												<div class='row hidden-md-up'>
													<div class='col comments-container'>
														<i class='fa fa-commenting' aria-hidden='true'></i> <div class='comment-count'>".$block['comment_nr']."</div>
														<i class='fa fa-clock-o'></i> <div class='time-count'><script> document.write(moment('$time').fromNow()); </script></div>
													</div>
												</div>	
												<div class='row'>
													<div class='col'>
														<a href='article.php?article=".$block['id_article']."'>$text ...</a>
													</div>
												</div>
												<div class='row article-link'>
													<div class='col'>
														<a href='article.php?article=".$block['id_article']."'><h5>Read More</h5></a>
													</div>
												</div>
											</div>
										</div>	
										<div class='row'>
											<div class='col social-container'>
												<i class='fa fa-facebook-square'></i>
												<i class='fa fa-twitter-square' aria-hidden='true'></i> <br class='hidden-xs-up'>
												<i class='fa fa-pinterest-square' aria-hidden='true'></i>
												<i class='fa fa-share-alt-square' aria-hidden='true'></i>
											</div>
											<div class='col comments-container hidden-sm-down'>
												<i class='fa fa-commenting' aria-hidden='true'></i> <div class='comment-count'>".$block['comment_nr']."</div>
												<i class='fa fa-clock-o' aria-hidden='true'></i> <div class='time-count'><script> document.write(moment('$time').fromNow()); </script></div>
											</div>
										</div>
									</div>
								</div>
							";
						}
					?>

					<?php
						$pageMax=ceil(sizeof($article)/10);

						if ($page==1) {
							$previousLock='disabled';
							$previousTab='-1';
						}

						if ($page==$pageMax) {
							$nextLock='disabled';
							$nextTab='-1';
						}

						$previousPage=$page-1;
						$nextPage=$page+1;

						echo "
							<ul class='pagination justify-content-center' style='margin-top: 2em'>
								<li class='page-item ".$previousLock."'>
									<a class='page-link' href='search.php?search=$search&page=".$previousPage."' tabindex=".$previousTab." >
										<i class='fa fa-angle-double-left' aria-hidden='true'></i>
									</a>
								</li>
						";

						for ($i=1; $i <= $pageMax; $i++) {
							echo "
								<li class='page-item'>
									<a class='page-link' href='search.php?search=$search&page=".$i."'>".$i."</a>
								</li>
							";
						}

						echo "
								<li class='page-item ".$nextLock."'>
									<a class='page-link' href='search.php?search=$search&page=".$nextPage."' tabindex=".$nextTab." >
										<i class='fa fa-angle-double-right' aria-hidden='true'></i>
									</a>
								</li>
							</ul>
						";
					?>

					<!--h1 id="page-selector">
						<?php
							
							$pageNum=ceil(sizeof($article)/10);

							if ($pageNum<=5 || $page<=3) {
								for ($i=1; $i <= 5; $i++) {
									if ($i<=$pageNum) {
										if ($i==$page) {
											echo "<a class='current' href='categories.php?category=$category&page=$i'>$i</a>";
										} else {
											echo "<a href='categories.php?category=$category&page=$i'>$i</a>";
										}										
									}									
								}
							} elseif ($page+2>$pageNum) {
								for ($i=$pageNum-4; $i <= $pageNum; $i++) {
									echo "<a href='categories.php?category=$category&page=$i'>$i</a>";
								}
							} else {
								for ($i=-2; $i <= 2; $i++) {
									$curpage=$page+$i;
									echo "<a href='categories.php?category=$category&page=$curpage'>$curpage</a>";
								}
							}		

						?>
					</h1-->
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

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
	<script type="text/javascript" src="scripts/jquery-ui.min.js"></script>
	<script type="text/javascript" src="scripts/bootstrap.min.js"></script>
	<script type="text/javascript" src="scripts/slick.min.js"></script>
	<script type="text/javascript" src="scripts/scripts.js"></script>
</body>
</html>