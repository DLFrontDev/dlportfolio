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
    $log = fopen('log.txt','w');
    $email=0;

    if (isset($_POST["username"])) {
            $user = mysqli_real_escape_string($connection, $_POST['username']);

            $query="select email, id_user from user where username='$user'";
            $result=mysqli_query($connection, $query);

            if (mysqli_num_rows($result) > 0) {
              $row=mysqli_fetch_row($result);
              $emailAdd = $row[0];
              $id=$row[1];

              $emailTo = $emailAdd;
            
              $subject = "Password Reset";
              fwrite($log,$subject.PHP_EOL);

              $token= date('Y-m-d H:i:s');
              $salt=md5($emailAdd);
              $token=md5($salt.$token);
              $link="https://greenoracle.000webhostapp.com/resetpass.php?token=$token&email=$emailAdd";
              
              $content = "
                <html>
                  <head>
                  </head>
                  <body>
                  You can reset your password using the link below:<br>
                  <a href='$link'>$link</a>
                  </body>
                </html>
              ";
              fwrite($log,$content.PHP_EOL);

              $headers = "MIME-Version: 1.0" . "\r\n";
              $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
              $headers .= "From: reset@greenoracle.com" . "\r\n";
              fwrite($log,$headers.PHP_EOL);

              if (mail($emailTo, $subject, $content, $headers)) {
                $queryCheckToken=mysqli_query($connection, "select id_user from reset_tokens where id_user=".$id);

                if (mysqli_num_rows($queryCheckToken)>0) {
                  mysqli_query($connection, "delete from reset_tokens where id_user=".$id);
                }


                $queryInsertToken="insert into reset_tokens values(".$id.", '".$token."', addtime(now(), '06:00:00'))";
                if(mysqli_query($connection, $queryInsertToken)) {
                  $email=1;
                }
              } else {
                $email=2;
              }
            }
          }
  ?>
</head>
<body>
  <div class="container-fluid">
          <form id="reset-form" method="post">
            <div class="form-group row" id="username-reset">
              <div class="col">
                <input class="form-control form-control-danger" type="text" name="username" id="username" placeholder="Your username">
              </div>
            </div>
          <div class="form-group row">
            <div class="offset-2 col-8">
              <button type="submit" style="width: 100%;" class="btn btn-primary" id="btn-login">Send reset email</button>
            </div>
          </div>
          </form>
          <div class="row">
            <div class="col">
              <div class="alert" role="alert" id="email-text">
                      You will be sent an email containing a link to a page where you can reset your password.
              </div>
            </div>
          </div>
        </div>
      </div>
  
  <script type="text/javascript" src="../scripts/validate.min.js"></script>

  <script type="text/javascript">
    $( document ).ready(function() {
      var constraints = {
        username: {
          presence: {message: "Username is required!"}
        }
      }

      var emailAlert = "<?=$email?>";

      if (emailAlert!=0) {
        $("#email-alert").collapse('show');
        if (emailAlert==1) {
          $("#email-text").addClass('alert-success');
          $("#email-text").text('Email sent with success!');
        } else {
          $("#email-text").addClass('alert-danger');
          $("#email-text").text('Error sending email!');
        }
      }

      $("#reset-form").submit(function(event){
        var errors = validate($("#reset-form"), constraints, {fullMessages: false});       

        if (errors){
          event.preventDefault();      
          for (var key in errors) {
            //console.log(errors[key]);
            $("#"+key+"-reset").addClass("has-danger");
            $("#"+key).attr("placeholder", errors[key]);
            $("#"+key).val("");
          }
          return;
        }

        $('input').focus(function(){
        var elemId = "#"+ $(this).attr('id') +"-reset";
        if ($(elemId).hasClass("has-danger")) {
          $(elemId).removeClass("has-danger");
        }
      });
      });   
    });
  </script>

</body>
</html>