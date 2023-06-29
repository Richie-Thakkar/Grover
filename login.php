<?php
error_reporting(0);
@include 'config.php';
require_once 'vendor/autoload.php';
session_start();
if(isset($_POST['submit'])){
   
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['password']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $sql = "SELECT * FROM `users` WHERE email = ? AND password = ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$email, $pass]);
   $rowCount = $stmt->rowCount();  

   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   if($rowCount > 0){

      if($row['user_type'] == 'admin'){

         $_SESSION['admin_id'] = $row['id'];
         header('location:admin_page.php');

      }elseif($row['user_type'] == 'user'){

         $_SESSION['user_id'] = $row['id'];
         header('location:home.php');

      }else{
         $message[] = 'no user found!';
      }

   }else{
      echo '<script>alert("Incorrect email or password");</script>';
   }

}
if(isset($_POST['submit-reg'])){
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = md5($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   
   $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select->execute([$email]);

   if($select->rowCount() > 0){
      echo '<script>alert("User email already exist!");</script>';
   }else{
      if($pass != $cpass){
         echo '<script>alert("Passwords did not match!");</script>';
      }else{
         $insert = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert->execute([$name, $email, $pass]);

         if($insert){
            echo '<script>alert("Registered Succesfully!");</script>';
               header('location:login.php');
            
         }

      }
   }

}
if (isset($_SESSION['user_token'])) {
   header("Location: home.php");
 } else {

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Grover</title>
   <link href="data:image/x-icon;base64,AAABAAEAEBAAAAEAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABWoAAAVp8APVWfAKRVngDgVZ4A+FWeAPJWnwDMVqAAcVehAAZXogBnWKQAq1mmAJkAAAAAAAAAAAAAAABXoQAEVqAAllafAP5VnwD/VZ4A/1WeAP9VngD/Vp8A/1agAP9XoQCbV6IAq1ikAP9ZpgDkAAAAAAAAAAAAAAAAV6EAkFagAP9WnwD/VZ4A/1WeALpVngBiVZ4AUlafAIFWoADrV6EA+VeiANhYpAD/WaYA5AAAAAAAAAAAV6IAM1ehAP1WoAD/Vp8A/1WeAIJVngABAAAAAAAAAAAAAAAAVqAAIFehAOZYowD/WKQA/1mmAOQAAAAAAAAAAFeiAJ9WoQD/VqAA/1afAM1VngACAAAAAAAAAAAAAAAAAAAAAAAAAABXoQBmWKMA/1mkAP9apgDkAAAAAFijAAFXogDlVqEA/1agAP9WnwBrAAAAAAAAAAAAAAAAVZ8AE1afAD9WoAA/V6EAVlijAP9ZpQD/WqYA5AAAAABYowAUV6IA/lehAP9WoAD/Vp8AMgAAAAAAAAAAAAAAAFafAExWoAD/VqAA/1eiAP9YowD/WaUA/1qnAOQAAAAAWKMAJVeiAP9XoQD/VqAA/1afABgAAAAAAAAAAAAAAABWnwBMVqAA/1ehAP9XogD/WKMA/1mlAP9apwDkAAAAAFijACNXogD/V6EA/1agAP9WoAAXAAAAAAAAAAAAAAAAVp8AC1agACdXoQAnV6IAJ1ikACdZpQAnWqcAIgAAAABYpAANWKIA+1ehAP9XoQD/VqAAMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAVqQAAFijANNXogD/V6EA/1agAGsAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABYowALWaQAh1mmAIdaqACFW6oABQAAAABYpAB+WKMA/1eiAP9XoQDRVqEABAAAAAAAAAAAAAAAAAAAAAAAAAAAWKMAUFmlAP9apgD/W6gA4lyqAAEAAAAAWKQAElijAOtXogD/V6IA/1ehAI5XoQACAAAAAAAAAAAAAAAAWKMAD1ikANNZpQD/WqcA/1upAJEAAAAAAAAAAAAAAABYpABMWKMA+1eiAP9XogD/V6IAw1eiAGdXogBQWKIAdFijANxZpAD/WaYA/1qnAOtbqQAZAAAAAAAAAAAAAAAAAAAAAFikAEtYowDqWKMA/1eiAP9XogD/WKMA/1ijAP9YpAD/WaUA/1qmAONaqAA1AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAWKQAEVijAHZYowDFWKMA71ijAPtYpADtWaQAwlmlAHRapwAOAAAAAAAAAAAAAAAA4AEAAMABAADAAQAAgcEAAIPhAAAHAQAABwEAAAcBAAAHAQAAB/8AAAfgAACD4AAAgcEAAMABAADgAwAA8AcAAA==" rel="icon" type="image/x-icon">
   <link rel="stylesheet" href="css/style1.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800&display=swap" rel="stylesheet">
</head>
<body>
   
   <div class="cont">
    <div class="form sign-in">
      <h2>Sign In</h2>
      <form action="" method="POST">
      <label>
        <span>Email Address</span>
        <input type="email" id="email" name="email" required>
      </label>
      <label>
        <span>Password</span>
        <input type="password" id="password" name="password" required>
      </label>
      <button class="submit" id="b" value="login now" name="submit" type="submit" >Sign In</button>
      <h4 align=center>OR</h4><br>      
</form>
      <?php
echo "<div style='display:flex;justify-content:center;'><a href='" . $client->createAuthUrl() . "'><img src='https://cdn.pixabay.com/photo/2015/12/11/11/43/google-1088004__340.png' style='width:25px';></a></div>"; 
}
      ?>
    </div>

    <div class="sub-cont">
      <div class="img">
        <div class="img-text m-up">
          <h1>New to Grover?</h1>
          <p>Sign up and Let us be your shopping cart!</p>
        </div>
        <div class="img-text m-in">
          <h1>Part of Grover Family?</h1>
          <p>If you already have an account, just sign in. We've missed you!</p>
        </div>
        <div class="img-btn">
          <span class="m-up">Sign Up</span>
          <span class="m-in">Sign In</span>
        </div>
      </div>
      <div class="form sign-up">
        <h2>Sign Up</h2>
        <form action="" method="POST">
        <label>
          <span>Name</span>
          <input type="text" name="name">
        </label>
        <label>
          <span>Email</span>
          <input type="email" name="email">
        </label>
        <label>
          <span>Password</span>
          <input type="password" name="pass">
        </label>
        <label>
          <span>Confirm Password</span>
          <input type="password" name="cpass">
        </label>
        <button type="submit" class="submit" name="submit-reg">Sign Up Now</button>
</form>
      </div>
    </div>
  </div>
<script type="text/javascript" src="js/s.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8"crossorigin="anonymous"></script>
</body>
</html>


