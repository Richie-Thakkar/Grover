<script>
<?php
@include 'config.php';
if(isset($_POST["fpsubmit"]))
{
if($_POST["pw"]!=$_POST["cpw"])
{
    echo "<script>alert('Password and Confirm Password did not match');</script>";
}
else
{
    if(isset($_COOKIE["email"]))
    {
        $em=$_COOKIE["email"];
        $pass=md5($_POST["pw"]);
        $update = $conn->prepare("Update `users` SET password = ? where email = ? and token IS NULL");
         $update->execute([$pass, $em]);
         if($update){
            header("location:login.php");
         }
         else{
            echo "<script>alert('Password update Failed');</script>";
         }
    }
    else{
        echo "<script>alert('Your cookies have expired...Please request a new otp from the login page');</script>";
        header("location:login.php");
    }
}
}
?>
</script>
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
        <span>New Password</span>
        <input type="password"  name="pw" required>
      </label>
      <label>
        <span>Confirm New Password</span>
        <input type="password"  name="cpw" required>
      </label>
      <button class="submit" id="b" value="login now" name="fpsubmit" type="submit" >Update Password</button>
           
</form>
      </div>

    <div class="sub-cont">
      <div class="img">
        <div class="img-text m-up">
          <h1>Grover</h1>
          <p>Reset Your Password</p>
        </div>
      </div>
      
    </div>
  </div>
  <div class="login-wrap">
  <div class="login-html">
    <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Sign In</label>
    <div class="login-form">
      <form action="" class="sign-in-htm" style="transform:none;" method="POST">
        <div class="group">
          <label for="user" class="label">Enter New Password</label>
          <input id="user" type="password" name="pw" class="input">
        </div>
        <div class="group">
          <label for="pass" class="label">Conifrm New Password</label>
          <input id="pass" type="password" name="cpw" class="input" data-type="password">
        </div>
        <div class="group">
          <input type="submit" class="button" name="fpsubmit" value="Sign In">
        </div>
        <div class="hr"></div>
        
</form>
      
    </div>
  </div>
</div>
<script type="text/javascript" src="js/s.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8"crossorigin="anonymous"></script>
</body>
</html>