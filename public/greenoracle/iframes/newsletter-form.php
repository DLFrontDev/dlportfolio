<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title></title>

  <meta charset="utf-8">
  <meta http-equiv="Content-type" content="text/html; charset=utf8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">

  <link rel="stylesheet" type="text/css" href="../css/homepage-showcase.css">
  <link rel="stylesheet" type="text/css" href="../css/homepage-articles.css">
  <link rel="stylesheet" type="text/css" href="../css/styles.css">


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
  <script type="text/javascript" src="../scripts/bootstrap.min.js"></script>

  <?php
    require '../config.php';
    $emailError=0;

    
     if (isset($_POST['email'])) {
       $email = htmlspecialchars($_POST['email']);

       $queryNewsletter = mysqli_query($connection, "select email from newsletter where email='".$email."'");

       if (mysqli_num_rows($queryNewsletter)>0) {
          //Email address already registered within newsletter list
          $emailError=1;
       } else {
          //Email Address not registered within newsletter list
          $queryCheckUser=("select id_user from user where email='".$email."'");
          if (mysqli_query($connection, $queryCheckUser)) {
            $queryCheckUser=mysqli_query($connection, $queryCheckUser);
            $row=mysqli_fetch_row($queryCheckUser);
            $id_user=$row[0];
            $queryInsert="insert into newsletter values ('".$email."', ".$id_user.")";
          } else {
            $queryInsert="insert into newsletter values ('".$email."', null)";
          }
          $queryInsertEmail=mysqli_query($connection, $queryInsert);
          echo "
          <script>
            $( document ).ready(function() {
              $('#email-news').addClass('has-success');
              $('#email').attr('placeholder', 'Registered successfully!');              
            });
          </script>
          ";
       }
     }

  ?>
</head>
<body>
    <form id="newsletter-form" method="post" style="margin-bottom: 1rem">
          <h5 class="sidebar-text" style="margin-bottom: 0.5rem">
            Need more <span class="oracle-green">Green</span> in your inbox?<br><span class="oracle-green">Subscribe</span> to our newsletter!
          </h5>
      <div id="email-news" style="margin-bottom: 0.5rem">
        <input type="text" class="form-control form-control-danger form-control-success" id="email" name="email" placeholder="Your email address here">
      </div>
      <button class="btn btn-primary btn-block">Subscribe</button>
    </form>

  <script type="text/javascript" src="../scripts/validate.min.js"></script>

  <script type="text/javascript">
    $( document ).ready(function() {

      var constraints = {
        email: {
          presence: {message: "Email address is required!"},
          email: {message: "Please enter a valid email address!"}
        }
      }

      var emailError="<?php echo $emailError ?>";

      if (emailError==1) {
        $("#email-news").addClass("has-danger");
        $("#email").attr("placeholder", "Email already in use!");
        $("#email").val("");
      }

      $("#newsletter-form").submit(function(event){
        var errors = validate($("#newsletter-form"), constraints, {fullMessages: false});

        if (errors){
          event.preventDefault();
          for (var key in errors) {
                console.log(errors);
                $("#"+key+"-news").addClass("has-danger");
                $("#"+key).attr("placeholder", errors[key]);
                $("#"+key).val("");
              }         
          return;
        }
      });

      //Clear has-danger class on input focus
      $('input').focus(function(){
        var elemId = $(this).parent().parent();
        if (elemId.hasClass("has-danger")) {
          elemId.removeClass("has-danger");
        }
      });     
    });
  </script>

</body>
</html>