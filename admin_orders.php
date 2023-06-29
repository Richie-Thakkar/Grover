<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_order'])){

   $order_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);
   $update_orders = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_orders->execute([$update_payment, $order_id]);
   $message[] = 'payment has been updated!';

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_orders->execute([$delete_id]);
   header('location:admin_orders.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<link href="data:image/x-icon;base64,AAABAAEAEBAAAAEAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABWoAAAVp8APVWfAKRVngDgVZ4A+FWeAPJWnwDMVqAAcVehAAZXogBnWKQAq1mmAJkAAAAAAAAAAAAAAABXoQAEVqAAllafAP5VnwD/VZ4A/1WeAP9VngD/Vp8A/1agAP9XoQCbV6IAq1ikAP9ZpgDkAAAAAAAAAAAAAAAAV6EAkFagAP9WnwD/VZ4A/1WeALpVngBiVZ4AUlafAIFWoADrV6EA+VeiANhYpAD/WaYA5AAAAAAAAAAAV6IAM1ehAP1WoAD/Vp8A/1WeAIJVngABAAAAAAAAAAAAAAAAVqAAIFehAOZYowD/WKQA/1mmAOQAAAAAAAAAAFeiAJ9WoQD/VqAA/1afAM1VngACAAAAAAAAAAAAAAAAAAAAAAAAAABXoQBmWKMA/1mkAP9apgDkAAAAAFijAAFXogDlVqEA/1agAP9WnwBrAAAAAAAAAAAAAAAAVZ8AE1afAD9WoAA/V6EAVlijAP9ZpQD/WqYA5AAAAABYowAUV6IA/lehAP9WoAD/Vp8AMgAAAAAAAAAAAAAAAFafAExWoAD/VqAA/1eiAP9YowD/WaUA/1qnAOQAAAAAWKMAJVeiAP9XoQD/VqAA/1afABgAAAAAAAAAAAAAAABWnwBMVqAA/1ehAP9XogD/WKMA/1mlAP9apwDkAAAAAFijACNXogD/V6EA/1agAP9WoAAXAAAAAAAAAAAAAAAAVp8AC1agACdXoQAnV6IAJ1ikACdZpQAnWqcAIgAAAABYpAANWKIA+1ehAP9XoQD/VqAAMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAVqQAAFijANNXogD/V6EA/1agAGsAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABYowALWaQAh1mmAIdaqACFW6oABQAAAABYpAB+WKMA/1eiAP9XoQDRVqEABAAAAAAAAAAAAAAAAAAAAAAAAAAAWKMAUFmlAP9apgD/W6gA4lyqAAEAAAAAWKQAElijAOtXogD/V6IA/1ehAI5XoQACAAAAAAAAAAAAAAAAWKMAD1ikANNZpQD/WqcA/1upAJEAAAAAAAAAAAAAAABYpABMWKMA+1eiAP9XogD/V6IAw1eiAGdXogBQWKIAdFijANxZpAD/WaYA/1qnAOtbqQAZAAAAAAAAAAAAAAAAAAAAAFikAEtYowDqWKMA/1eiAP9XogD/WKMA/1ijAP9YpAD/WaUA/1qmAONaqAA1AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAWKQAEVijAHZYowDFWKMA71ijAPtYpADtWaQAwlmlAHRapwAOAAAAAAAAAAAAAAAA4AEAAMABAADAAQAAgcEAAIPhAAAHAQAABwEAAAcBAAAHAQAAB/8AAAfgAACD4AAAgcEAAMABAADgAwAA8AcAAA==" rel="icon" type="image/x-icon">
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="placed-orders">

   <h1 class="title">placed orders</h1>

   <div class="box-container">

      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="box">
         <p> user id : <span><?= $fetch_orders['user_id']; ?></span> </p>
         <p> placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
         <p> name : <span><?= $fetch_orders['name']; ?></span> </p>
         <p> email : <span><?= $fetch_orders['email']; ?></span> </p>
         <p> number : <span><?= $fetch_orders['number']; ?></span> </p>
         <p> address : <span><?= $fetch_orders['address']; ?></span> </p>
         <p> total products : <span><?= $fetch_orders['total_products']; ?></span> </p>
         <p> total price : <span>$<?= $fetch_orders['total_price']; ?>/-</span> </p>
         <p> payment method : <span><?= $fetch_orders['method']; ?></span> </p>
         <form action="" method="POST">
            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
            <select name="update_payment" class="drop-down">
               <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
               <option value="pending">pending</option>
               <option value="completed">completed</option>
            </select>
            <div class="flex-btn">
               <input type="submit" name="update_order" class="option-btn" value="update">
               <a href="admin_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
            </div>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      ?>

   </div>

</section>












<script src="js/script.js"></script>

</body>
</html>