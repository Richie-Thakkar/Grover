<?php
@include 'config.php';
require_once 'vendor/autoload.php';
session_start();
if (isset($_POST['fp'])) {
  ?>
  <script>
    let email = prompt("Enter your email address");
    let expirationTime = new Date();
    expirationTime.setTime(expirationTime.getTime() + (15 * 60 * 1000)); 
    let expires = "expires=" + expirationTime.toUTCString();
    document.cookie = "email=" + encodeURIComponent(email) + ";" + expires + ";path=/";
  </script>
  <?php
    if (isset($_COOKIE['email'])) {
      $em = filter_var($_COOKIE['email']);
      $findem = "SELECT * FROM `users` WHERE email = ? AND token IS NULL";
      $stmt = $conn->prepare($findem);
      $stmt->execute([$em]);
      $rowCount = $stmt->rowCount();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($rowCount > 0) {
        $otp = substr(bin2hex(random_bytes(4)), 0, 7);
        $curl = curl_init();
  
        curl_setopt_array($curl, [
          CURLOPT_URL => "https://rapidprod-sendgrid-v1.p.rapidapi.com/mail/send",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode([
            'personalizations' => [
              [
                'to' => [
                  [
                    'email' => $em
                  ]
                ],
                'subject' => 'Otp'
              ]
            ],
            'from' => [
              'email' => 'richiethakkar@gmail.com'
            ],
            'content' => [
              [
                'type' => 'text/plain',
                'value' => $otp
              ]
            ]
          ]),
          CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: rapidprod-sendgrid-v1.p.rapidapi.com",
            "X-RapidAPI-Key: 9eabe601b6msh1ea06cb8d54a0d4p15c5ffjsn511bfb4c034f",
            "content-type: application/json"
          ],
        ]);
  
        $response = curl_exec($curl);
        $err = curl_error($curl);
  
        curl_close($curl);
  
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo "<script>console.log(" . json_encode($response) . ")</script>";
        }
  ?>
  <script>
    let delayTime = 5000; // Delay in milliseconds
    setTimeout(() => {
      let otp = prompt("Enter the OTP sent to your email address");
      if (otp !== null) {
        let expirationTime = new Date();
        expirationTime.setTime(expirationTime.getTime() + (15 * 60 * 1000)); // 15 minutes in milliseconds
        let expires = "expires=" + expirationTime.toUTCString();
        document.cookie = "otp=" + encodeURIComponent(otp) + ";" + expires + ";path=/";
        window.location.href = "forgotpass.php"; // Redirect to forgotpass.php after prompt
      } else {
        alert("OTP input cancelled.");
      }
    }, delayTime);
  </script>
  <?php
      } else {
        echo "<script>alert('Entered email address is not registered with us')</script>";
      }
    }
  }
    
  
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
           
</form>
      <?php
echo "<div style='display:flex;justify-content:center;'>Sign in with &nbsp;<a href='" . $client->createAuthUrl() . "'><img src='https://cdn.pixabay.com/photo/2015/12/11/11/43/google-1088004__340.png' style='width:25px';></a></div>"; 
}
      ?>
      <form style="text-align:center;margin-top:5px;" method="POST">
      <button class="submit" name="fp" >Forgot Password</button>    
      </form>
        <div style="text-align:center;margin-top:5px;">
      <p>Please note that first time users will not directly be redirected to the web page on using google sign in. They have to login again through google sign in</p>   
      </div>
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
  <div class="login-wrap">
  <div class="login-html">
    <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Sign In</label>
    <input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab">Sign Up</label>
    <div class="login-form">
      <form action="" class="sign-in-htm" method="POST">
        <div class="group">
          <label for="user" class="label">Email Address</label>
          <input id="user" type="text" name="email" class="input">
        </div>
        <div class="group">
          <label for="pass" class="label">Password</label>
          <input id="pass" type="password" name="password" class="input" data-type="password">
        </div>
        <div class="group">
          <input type="submit" class="button" name="submit" value="Sign In">
        </div>
        <div class="group">
        <?php
echo "<div style='display:flex;justify-content:center;'>Sign in with &nbsp;<a href='" . $client->createAuthUrl() . "'><img src='https://cdn.pixabay.com/photo/2015/12/11/11/43/google-1088004__340.png' style='width:25px';></a></div>"; 
?>
        </div>
        <div class="group" style="text-align:center;">
        <a href="#forgot" style="color:blue;">Forgot Password?</a>
        </div>
        <div class="hr"></div>
        <div class="foot-lnk">
          
        <p>Please note that first time users will not directly be redirected to the web page on using google sign in. They have to login again through google sign in</P>
        </div>
</form>
      <form action="" class="sign-up-htm" method="POST">
        <div class="group">
          <label for="user" class="label">Name</label>
          <input id="user" type="text" name="name" class="input">
        </div>
        <div class="group">
          <label for="pass" class="label">Password</label>
          <input id="pass" type="password"  name="pass" class="input" data-type="password">
        </div>
        <div class="group">
          <label for="pass" class="label">Repeat Password</label>
          <input id="pass" type="password" name="cpass" class="input" data-type="password">
        </div>
        <div class="group">
          <label for="pass" class="label">Email Address</label>
          <input id="pass" type="text" name="email" class="input">
        </div>
        <div class="group">
          <input type="submit" class="button" name="submit-reg" value="Sign Up">
        </div>
        <div class="hr"></div>
        <div class="foot-lnk">
          <label for="tab-1">Already Member?</a>
        </div>
</form>
    </div>
  </div>
</div>
<script type="text/javascript" src="js/s.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8"crossorigin="anonymous"></script>
</body>
</html>