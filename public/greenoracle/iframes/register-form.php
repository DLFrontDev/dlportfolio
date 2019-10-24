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

  <?php
    require '../config.php';
    $userError=$emailError=0;
    $alert=3;

    if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["confPassword"]) && isset($_POST["email"])) {
      //Protecção de dados
      $user=mysqli_real_escape_string($connection, $_POST["username"]);
      $pwd=mysqli_real_escape_string($connection, $_POST["password"]);
      $cpwd=mysqli_real_escape_string($connection, $_POST["confPassword"]);
      $email=mysqli_real_escape_string($connection, $_POST["email"]);

      $queryUser = mysqli_query($connection, "select username from user where username = '$user'");
      $queryEmail = mysqli_query($connection, "select email from user where email = '$email'");

      if (mysqli_num_rows($queryUser) > 0 || mysqli_num_rows($queryEmail) > 0) {
        //Username já registado
        if (mysqli_num_rows($queryUser) > 0) {
          $userError=1;
        }

        //Email já registado
        if (mysqli_num_rows($queryEmail) > 0) {
          $emailError=1;
        }
      } else {
        //Username não registado procede ao registo
        $idNewUser=0;
        $query = mysqli_query($connection, "select id_user from user where id_user=".$idNewUser);
        $row = mysqli_fetch_row($query);
        
        while ($row[0]!=null) {
          $idNewUser++;
          $query = mysqli_query($connection, "select id_user from user where id_user=".$idNewUser);
          $row = mysqli_fetch_row($query);
        }

        //Create Dynamic salt (based on user id)
        $salt=md5($idNewUser);
        //Applt md5 hash password
        $pwd=md5($salt.$pwd);

        $tags='environment|innovation|eco living|politics';

        //Insert na Base de Dados
        $query = "insert into user (id_user, username, password, email, news_tags, date_created) values (".$idNewUser.",'".$user."','".$pwd."', '".$email."', '".$tags."' ,now())";
        if (!file_exists('../users/'.$idNewUser)) {
            mkdir("../users/".$idNewUser);
        }

        if (mysqli_query($connection, $query)) {
          $alert=1;
        } else {
          $alert=0;               
        }      
      }
    }
  ?>
</head>
<body>
  <div class="container-fluid">
    <form id="register-form" method="post">
    <div class="form-group row" id="username-register">
      <div class="col">
        <input class="form-control form-control-danger" type="text" name="username" id="username" placeholder="Your username" data-toggle="tooltip" data-placement="bottom" data-trigger="hover" title="20 Characters Maximum">
      </div>
    </div>
    <div class="form-group row" id="email-register">
      <div class="col">
        <input class="form-control form-control-danger" type="text" name="email" id="email" placeholder="Your email address">
      </div>
    </div>
    <div class="form-group row" id="password-register" style="margin-bottom: 0.5rem;">
      <div class="col">
        <input class="form-control form-control-danger" type="password" name="password" id="password" placeholder="Your password" data-toggle="tooltip" data-placement="bottom" data-trigger="hover" title="5 Characters Minimum, must contain letters and numbers">
      </div>
    </div>
    <div class="form-group row" id="confPassword-register">
      <div class="col">
        <input class="form-control form-control-danger" type="password" name="confPassword" id="confPassword" placeholder="Confirm password">
      </div>
    </div>
    <div class="form-group row">
      <div class="offset-2 col-8">
        <button type="submit" style="width: 100%;" class="btn btn-primary" id="btn-signup">Sign up</button>
      </div>
    </div>
    <div class="row">
      <div class="col" style="text-align: center;">
        <div role="alert" class=<?php
          if ($alert==1) {
            echo '"alert alert-success"';
            } elseif ($alert==0) {
              echo '"alert alert-danger"';
            } else {
              echo '""';
            }?> id="register-feedback">
          <?php
            if ($alert==1) {
              echo 'Registry successful!';
            } elseif ($alert==0) {
              echo 'Registry error!';
            } else {
              echo 'Welcome to <span class="oracle-green">Green Oracle</span>! Greener news for a greener tomorrow!';
            }
          ?>
        </div>
      </div>            
    </div>
    </form>
  </div>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
  <script type="text/javascript" src="../scripts/bootstrap.min.js"></script>
  <script type="text/javascript" src="../scripts/validate.min.js"></script>

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
          presence: {message: "Password is required!"},
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
          presence: {message: "Fields do not match!"},
          equality: {
            attribute: "password",
            message: "Fields do not match!"
          }
        }
      }
      var userError="<?php echo $userError ?>";
      var emailError="<?php echo $emailError ?>";
      var passVal=  $("#password").val();

      if (userError==1) {
        $("#username-register").addClass("has-danger");
        $("#username").attr("placeholder", "Username already in use!");
        $("#username").val("");
      }
      if (emailError==1) {
        $("#email-register").addClass("has-danger");
        $("#email").attr("placeholder", "Email already in use!");
        $("#email").val("");
      }

      $("#register-form").submit(function(event){
        var errors = validate($("#register-form"), constraints, {fullMessages: false});

        if (hasNumbers(passVal)==true) {
          event.preventDefault();
          $("#password-register").addClass("has-danger");
          $("#password").attr("placeholder", "Must contain a number!");
          $("#password").val("");
          $("#confPassword").val("");
        }

        if (errors){
          event.preventDefault();      
          for (var key in errors) {
            //console.log(errors[key]);
            $("#"+key+"-register").addClass("has-danger");
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
        var elemId = "#"+ $(this).attr('id') +"-register";
        if ($(elemId).hasClass("has-danger")) {
          $(elemId).removeClass("has-danger");
        }
      });

      //Reset Form
      parent.$('#register-modal').on('hidden.bs.modal', function (e) {
          $("#username").val("");
          $("#username").attr("placeholder", "Your username");
          $("#username-register").removeClass("has-danger");
          $("#password").val("");
          $("#password").attr("placeholder", "Your password");
          $("#password-register").removeClass("has-danger");
          $("#confPassword").val("");
          $("#confPassword").attr("placeholder", "Confirm password");
          $("#confPassword-register").removeClass("has-danger");
          $("#email").val("");
          $("#email").attr("placeholder", "Your email address");
          $("#email-register").removeClass("has-danger");
      });

      
    });
  </script>
</body>
</html>