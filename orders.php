<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>
   <link href="data:image/x-icon;base64,AAABAAEAEBAAAAEAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABWoAAAVp8APVWfAKRVngDgVZ4A+FWeAPJWnwDMVqAAcVehAAZXogBnWKQAq1mmAJkAAAAAAAAAAAAAAABXoQAEVqAAllafAP5VnwD/VZ4A/1WeAP9VngD/Vp8A/1agAP9XoQCbV6IAq1ikAP9ZpgDkAAAAAAAAAAAAAAAAV6EAkFagAP9WnwD/VZ4A/1WeALpVngBiVZ4AUlafAIFWoADrV6EA+VeiANhYpAD/WaYA5AAAAAAAAAAAV6IAM1ehAP1WoAD/Vp8A/1WeAIJVngABAAAAAAAAAAAAAAAAVqAAIFehAOZYowD/WKQA/1mmAOQAAAAAAAAAAFeiAJ9WoQD/VqAA/1afAM1VngACAAAAAAAAAAAAAAAAAAAAAAAAAABXoQBmWKMA/1mkAP9apgDkAAAAAFijAAFXogDlVqEA/1agAP9WnwBrAAAAAAAAAAAAAAAAVZ8AE1afAD9WoAA/V6EAVlijAP9ZpQD/WqYA5AAAAABYowAUV6IA/lehAP9WoAD/Vp8AMgAAAAAAAAAAAAAAAFafAExWoAD/VqAA/1eiAP9YowD/WaUA/1qnAOQAAAAAWKMAJVeiAP9XoQD/VqAA/1afABgAAAAAAAAAAAAAAABWnwBMVqAA/1ehAP9XogD/WKMA/1mlAP9apwDkAAAAAFijACNXogD/V6EA/1agAP9WoAAXAAAAAAAAAAAAAAAAVp8AC1agACdXoQAnV6IAJ1ikACdZpQAnWqcAIgAAAABYpAANWKIA+1ehAP9XoQD/VqAAMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAVqQAAFijANNXogD/V6EA/1agAGsAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABYowALWaQAh1mmAIdaqACFW6oABQAAAABYpAB+WKMA/1eiAP9XoQDRVqEABAAAAAAAAAAAAAAAAAAAAAAAAAAAWKMAUFmlAP9apgD/W6gA4lyqAAEAAAAAWKQAElijAOtXogD/V6IA/1ehAI5XoQACAAAAAAAAAAAAAAAAWKMAD1ikANNZpQD/WqcA/1upAJEAAAAAAAAAAAAAAABYpABMWKMA+1eiAP9XogD/V6IAw1eiAGdXogBQWKIAdFijANxZpAD/WaYA/1qnAOtbqQAZAAAAAAAAAAAAAAAAAAAAAFikAEtYowDqWKMA/1eiAP9XogD/WKMA/1ijAP9YpAD/WaUA/1qmAONaqAA1AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAWKQAEVijAHZYowDFWKMA71ijAPtYpADtWaQAwlmlAHRapwAOAAAAAAAAAAAAAAAA4AEAAMABAADAAQAAgcEAAIPhAAAHAQAABwEAAAcBAAAHAQAAB/8AAAfgAACD4AAAgcEAAMABAADgAwAA8AcAAA==" rel="icon" type="image/x-icon">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="placed-orders">

   <h1 class="title">placed orders</h1>

   <div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
      $select_orders->execute([$user_id]);
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <p> placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> name : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> number : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> email : <span><?= $fetch_orders['email']; ?></span> </p>
      <p> address : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> payment method : <span><?= $fetch_orders['method']; ?></span> </p>
      <p> your orders : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> total price : <span>Rs.<?= $fetch_orders['total_price']; ?>/-</span> </p>
      <p> payment status : <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">no orders placed yet!</p>';
   }
   ?>

   </div>

</section>









<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>