<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title></title>

  <meta charset="utf-8">
  <meta http-equiv="Content-type" content="text/html; charset=utf8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">

  <link rel="stylesheet" type="text/css" href="../css/homepage-showcase.css">
  <link rel="stylesheet" type="text/css" href="../css/homepage-articles.css">
  <link rel="stylesheet" type="text/css" href="../css/styles.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
  <script type="text/javascript" src="../scripts/bootstrap.min.js"></script>

  <?php
    require '../config.php';
    $passError=0;


    if (isset($_POST["username"]) && isset($_POST["password"])) {
      $user = mysqli_real_escape_string($connection,$_POST['username']);
      $pwd = mysqli_real_escape_string($connection,$_POST['password']);

      $query="select id_user from user where username='$user'";
      $result=mysqli_query($connection, $query);
      $row=mysqli_fetch_row($result);
      $iduser = $row[0];

      //Create Dynamic salt (based on user id)
      $salt=md5($iduser);
      //Applt md5 hash password
      $pwd=md5($salt.$pwd);

      $query = "select username from user where username='".$user."' and password='".$pwd."'";
      $result=mysqli_query($connection, $query);

      if (mysqli_num_rows($result) == 0) {
        $passError = 1;
      } else {
        $_SESSION['username']=ucfirst($user);

        //Remember Session
        if (isset($_POST['remember'])) {
          setcookie('remember_session', $user, time() + (86400 * 30) , '/');
        }

        echo "
          <script>
            parent.$('.btn-user').attr('data-toggle', 'dropdown');
            parent.$('.btn-user').attr('data-target', 'dropdown');
            parent.$('.session-username').text('".$_SESSION['username']."');
            parent.$('#userPop').modal('hide');
          </script>
        ";
      }
    }
  ?>
</head>
<body>
  <div class="container-fluid">
          <form id="login-form" method="post">
            <div class="form-group row" id="username-login">
              <div class="col">
                <input class="form-control form-control-danger" type="text" name="username" id="username" placeholder="Username">
              </div>
            </div>
            <div class="form-group row" id="password-login">
              <div class="col">
                <input class="form-control form-control-danger" type="password" name="password" id="password" placeholder="Password">
              </div>
            </div>
            <div class="form-group row">
            <div class="col">
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" name="remember"> Remember Me
                </label>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="offset-2 col-8">
              <button type="submit" style="width: 100%;" class="btn btn-primary" id="btn-login">Sign in</button>
            </div>
          </div>
          </form>
        </div>
      </div>
  
  <script type="text/javascript" src="../scripts/validate.min.js"></script>

  <script type="text/javascript">
    $( document ).ready(function() {
      var constraints = {
        username: {
          presence: {message: "Username is required!"}
        },
        password: {
          presence: {message: "Password is required!"}
        }
      }

      var passError= "<?php echo $passError ?>";

      if (passError==1) {
        $("#username").val("<?php echo $user ?>");
        $("#password-login").addClass("has-danger");
        $("#password").attr("placeholder", "Incorrect password!");
        $("#password").val("");
      }

      $("#login-form").submit(function(event){
        var errors = validate($("#login-form"), constraints, {fullMessages: false});       

        if (errors){
          event.preventDefault();      
          for (var key in errors) {
            console.log(errors[key]);
            $("#"+key+"-login").addClass("has-danger");
            $("#"+key).attr("placeholder", errors[key]);
            $("#"+key).val("");
          }
          return;
        }

        $('input').focus(function(){
        var elemId = "#"+ $(this).attr('id') +"-login";
        if ($(elemId).hasClass("has-danger")) {
          $(elemId).removeClass("has-danger");
        }
      });
      });   
    });
  </script>

</body>
</html>