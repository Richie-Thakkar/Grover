<?php

@include 'config.php';

session_start();
if(isset($_SESSION['user_id']))
{
   $user_id=$_SESSION['user_id'];
}
require_once 'vendor/autoload.php';
if (isset($_GET['code'])) {
   if(!isset($_SESSION['user_id']))
   {
   $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
   $client->setAccessToken($token['access_token']);
   // get profile info
   $google_oauth = new Google_Service_Oauth2($client);
   $google_account_info = $google_oauth->userinfo->get();
   $userinfo = [
     'email' => $google_account_info['email'],
     'first_name' => $google_account_info['givenName'],
     'last_name' => $google_account_info['familyName'],
     'gender' => $google_account_info['gender'],
     'full_name' => $google_account_info['name'],
     'picture' => $google_account_info['picture'],
     'verifiedEmail' => $google_account_info['verifiedEmail'],
     'token' => $google_account_info['id'],
   ];
   $check_user = $conn->prepare("SELECT * FROM `users` WHERE token = ?");
   $check_user->execute([$userinfo['token']]);
   $fetch_user = $check_user->fetch(PDO::FETCH_ASSOC);
if($check_user->rowCount()>0)
{
   $user_id=$fetch_user['id'];
   $_SESSION['user_id']=$user_id;
}
else{
   $e=$userinfo['email'];
   $t=$userinfo['token'];
   $n=$userinfo['full_name'];
$sql = $conn->prepare("INSERT INTO `users`(email, token,name,password,user_type) VALUES (?,?,?,?,?)");
$sql->execute([$e,$t,$n,md5('1234554321'),'user']);
$fetch_sql = $sql->fetch(PDO::FETCH_ASSOC);
if($sql)
{
   $getid=$conn->prepare("select * from `users` where token = ?");
   $fetch_i=$getid->fetch(PDO::FETCH_ASSOC);
   $t=$fetch_i['token'];
   $getid->execute([$t]);
   $fetch_id = $getid->fetch(PDO::FETCH_ASSOC);
   $_SESSION['user_id'] = $fetch_id['id'];
   $user_id=$_SESSION['user_id'];
}
else {
   echo "User is not created";
   die();
} 

}
   }
//$_SESSION['user_token'] = $token;
}

if(isset($_SESSION['user_id']))
{
   $user_id=$_SESSION['user_id'];
}
if(!isset($user_id)){
   header('location:login.php');
};
/*if(isset($_SESSION['user_id']))
{
   $user_id = $_SESSION['user_id'];
}
if(isset($_SESSION['user_token']))
{
   $tkn=$_SESSION['user_token'];
$sql1=$conn->query("SELECT * FROM `users` WHERE token= $tkn");
if($sql1->rowcount()>0)
{
   $userinfo=$sql1->fetch(PDO::FETCH_ASSOC);
}
}*/

if(isset($_POST['add_to_wishlist'])){

   $pid = $_POST['pid'];
   
   $p_name = $_POST['p_name'];
   
   $p_price = $_POST['p_price'];
   
   $p_image = $_POST['p_image'];
   

   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'already added to wishlist!';
   }elseif($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'added to wishlist!';
   }

}


$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://low-carb-recipes.p.rapidapi.com/random",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"X-RapidAPI-Host: low-carb-recipes.p.rapidapi.com",
		"X-RapidAPI-Key: 6877f18f61msh7d1b6decb1d44cfp103d1cjsn92e52483016d"
	],
]);

$response = json_decode(curl_exec($curl));
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
   if(isset($response->image))
   {
	$suff="?rapidapi-key=6877f18f61msh7d1b6decb1d44cfp103d1cjsn92e52483016d\"";
   $resim1=$response->image;
   $cnt=count($response->ingredients);
   $cn=count($response->steps);
   $naa=$response->name;
   $de=$response->description;
   $ct=$response->cookTime;
   $pt=$response->prepareTime;
   //echo $resim;
   }
   else{
      $resim1="https://low-carb-recipes.p.rapidapi.com/images/2807982c-986a-4def-9e3a-153a3066af7a.jpeg?rapidapi-key=6877f18f61msh7d1b6decb1d44cfp103d1cjsn92e52483016d";
      $naa="Ultimate Keto Blueberry Sponge Cake In A Mug";
      $de="Instead of making pancakes or waffles, make this easy Keto breakfast recipe that takes way less time to make. This blueberry sponge cake in a mug is soft and fluffy like a pancake but sweet like a cake. Don’t worry, you won’t be adding many carbs to your breakfast, as all sweetening products are replaced with Keto-friendly ingredients. You can assemble the recipe in under 5 minutes, so this is also a great Keto breakfast recipe for anyone who always ends up in a rush in the mornings. Even if you’re in a rush, you can still enjoy this sweet and delicious sponge cake breakfast! If you want to serve your sponge cake with a little whipped cream, you can whip heavy cream and liquid stevia together in a stand mixer or food processor. ### Other ingredients to add Like to have a little more in your sponge cake? Try stirring in crushed nuts for more texture. Keto-friendly nuts include cashews, walnuts, pecan, hazelnuts, and even peanuts. Blueberries also happen to taste very good with lemon. Try mixing a little lemon zest in your dry ingredients! ### Can other berries be used? Blueberries are one of the most Keto-friendly berries out there. If you don’t like blueberries, try raspberries or strawberries. If you’re using strawberries in the mug cake, make sure to chop them finely. ### What type of mug should I use? A heat-safe mug or dish can easily be a coffee cup from your kitchen. Choose any type of ceramic mug to keep your hands safe as well as cook the sponge cake. If you own a ceramic ramekin, you can also cook your sponge cake in there.";
   $ct="2";
   $pt="3";
   }
}


$curl1 = curl_init();

curl_setopt_array($curl1, [
	CURLOPT_URL => "https://quotes-inspirational-quotes-motivational-quotes.p.rapidapi.com/quote?token=ipworld.info",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"X-RapidAPI-Host: quotes-inspirational-quotes-motivational-quotes.p.rapidapi.com",
		"X-RapidAPI-Key: 9eabe601b6msh1ea06cb8d54a0d4p15c5ffjsn511bfb4c034f"
	],
]);

$response1 = curl_exec($curl1);
$response1= json_decode($response1,true);
$err1 = curl_error($curl1);

curl_close($curl1);

if ($err1) {
	echo "cURL Error #:" . $err1;
} 
 
if(isset($_POST['add_to_cart'])){

   $pid = $_POST['pid'];
   
   $p_name = $_POST['p_name'];
   $p_price = $_POST['p_price'];
   $p_image = $_POST['p_image'];
   $p_qty = $_POST['p_qty'];
   
   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'added to cart!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home page</title>
   <link href="data:image/x-icon;base64,AAABAAEAEBAAAAEAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABWoAAAVp8APVWfAKRVngDgVZ4A+FWeAPJWnwDMVqAAcVehAAZXogBnWKQAq1mmAJkAAAAAAAAAAAAAAABXoQAEVqAAllafAP5VnwD/VZ4A/1WeAP9VngD/Vp8A/1agAP9XoQCbV6IAq1ikAP9ZpgDkAAAAAAAAAAAAAAAAV6EAkFagAP9WnwD/VZ4A/1WeALpVngBiVZ4AUlafAIFWoADrV6EA+VeiANhYpAD/WaYA5AAAAAAAAAAAV6IAM1ehAP1WoAD/Vp8A/1WeAIJVngABAAAAAAAAAAAAAAAAVqAAIFehAOZYowD/WKQA/1mmAOQAAAAAAAAAAFeiAJ9WoQD/VqAA/1afAM1VngACAAAAAAAAAAAAAAAAAAAAAAAAAABXoQBmWKMA/1mkAP9apgDkAAAAAFijAAFXogDlVqEA/1agAP9WnwBrAAAAAAAAAAAAAAAAVZ8AE1afAD9WoAA/V6EAVlijAP9ZpQD/WqYA5AAAAABYowAUV6IA/lehAP9WoAD/Vp8AMgAAAAAAAAAAAAAAAFafAExWoAD/VqAA/1eiAP9YowD/WaUA/1qnAOQAAAAAWKMAJVeiAP9XoQD/VqAA/1afABgAAAAAAAAAAAAAAABWnwBMVqAA/1ehAP9XogD/WKMA/1mlAP9apwDkAAAAAFijACNXogD/V6EA/1agAP9WoAAXAAAAAAAAAAAAAAAAVp8AC1agACdXoQAnV6IAJ1ikACdZpQAnWqcAIgAAAABYpAANWKIA+1ehAP9XoQD/VqAAMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAVqQAAFijANNXogD/V6EA/1agAGsAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABYowALWaQAh1mmAIdaqACFW6oABQAAAABYpAB+WKMA/1eiAP9XoQDRVqEABAAAAAAAAAAAAAAAAAAAAAAAAAAAWKMAUFmlAP9apgD/W6gA4lyqAAEAAAAAWKQAElijAOtXogD/V6IA/1ehAI5XoQACAAAAAAAAAAAAAAAAWKMAD1ikANNZpQD/WqcA/1upAJEAAAAAAAAAAAAAAABYpABMWKMA+1eiAP9XogD/V6IAw1eiAGdXogBQWKIAdFijANxZpAD/WaYA/1qnAOtbqQAZAAAAAAAAAAAAAAAAAAAAAFikAEtYowDqWKMA/1eiAP9XogD/WKMA/1ijAP9YpAD/WaUA/1qmAONaqAA1AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAWKQAEVijAHZYowDFWKMA71ijAPtYpADtWaQAwlmlAHRapwAOAAAAAAAAAAAAAAAA4AEAAMABAADAAQAAgcEAAIPhAAAHAQAABwEAAAcBAAAHAQAAB/8AAAfgAACD4AAAgcEAAMABAADgAwAA8AcAAA==" rel="icon" type="image/x-icon">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="home-bg">

   <section class="home">

      <div class="content">
         <span>Worth Relying on Us</span>
         <h3>Reach For A Healthier You With Organic Foods</h3>
         <p>At Grover, we assure you the finest quality of fruits, veggies and groceries delivered to you in less than 30 minutes....Grockety Promise :-)</p>
         <a href="about.php" class="btn">about us</a>
      </div>

   </section>

</div>

<section class="home-category">

   <h1 class="title">shop by category</h1>

   <div class="box-container">

      <div class="box" style="height:500px;">
         <img src="images/cat-1.png" style="height:200px;" alt="fruits">
         <h3>Fruits</h3>
         <p>We at grover assure you the best quality organic fruits delivered straight from farm to your home</p>
         <a href="category.php?category=fruits" class="btn">Fruits</a>
      </div>

      <div class="box" style="height:500px;">
         <img src="images/cat-2.png" style="height:200px;" alt="meat">
         <h3>Meat</h3>
         <p>Meat processed to perfection available only at grover. Meat goes through a variety of before recahing you </p>
         <a href="category.php?category=meat" class="btn">Meat</a>
      </div>

      <div class="box" style="height:500px;">
         <img src="images/cat-3.png " style="height:200px;" alt="vegetables">
         <h3>Vegetables</h3>
         <p>We at grover assure you the best quality organic vegetables delivered straight from farm to your home</p>
         <a href="category.php?category=vegitables" class="btn">Vegetables</a>
      </div>

      <div class="box" style="height:500px;">
         <img src="images/cat-4.png" style="height:200px;" alt="fish">
         <h3>Fish</h3>
         <p>Best quality freshwater and seawater fishes delivered hassle free from sea to your home ensuring freshness and hygiene</p>
         <a href="category.php?category=fish" class="btn">Fish</a>
      </div>

   </div>

</section>

<section class="products">

   <h1 class="title">latest products</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" style="height:600px" method="POST">
      <div class="price">Rs.<span><?= $fetch_products['price']; ?></span>/-</div>
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
      <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist">
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

</section>
<section class="home-category">
<h1 class="title">Some motivation to keep you fresh just like our products</h1><br>
<p style="font-family:'Rubik', sans-serif;font-size:27px;text-align:center"><i><?=$response1['text']?></i></p>
</section>
<section class="home-category">
<h1 class="title">Recipe of the day</h1><br>
<div style="display:flex;justify-content:center;">
<img src=<?=$resim1?> style="height:250px;border:2px solid black;border-radius:25%;margin:7px;">
</div>
<h3 class="title" style="font-size:25px;";><?= $naa?></h3>
<h3 class="title" style="font-size:18px;text-align:left;";>Description:</h3>
<p style="font-family:'Rubik', sans-serif;font-size:17px"><?=$de?></p><br>
<h3 class="title" style="font-size:18px;text-align:left;">Cook Time:</h3>
<p style="font-family:'Rubik', sans-serif;font-size:17px"><?=$ct?>&nbsp;minutes</p><br>
<h3 class="title" style="font-size:18px;text-align :left;">Preparation Time:</h3>
<p style="font-family:'Rubik', sans-serif;font-size:17px"><?=$pt?>&nbsp;minutes</p><br>
<h3 class="title" style="font-size:18px;text-align:left;">Ingredients:</h3>
<p style="font-family:'Rubik', sans-serif;font-size:17px">
<?php
if(isset($response->name))
{
for($j=0;$j<$cnt;$j++)
{
   echo "(";
   echo $j+1;
   echo ") ";
   echo $response->ingredients[$j]->name;
   $cnt--;
   echo "<br>";
}
}
else{
   echo "Butter<br>Cream Cheese<br>Coconut Flour<br>Vanilla Extract<br>Baking Powder<br>Raw Egg<br>Blueberries";
}
//echo var_dump($response->ingredients);
?></p><br><br>
<h3 class="title" style="font-size:18px;text-align:left;">Steps:</h3>
<p style="font-family:'Rubik', sans-serif;font-size:17px">
<?php
if(isset($response->name))
{
for($i=0;$i<$cn;$i++)
{
   echo "(";
   echo $i+1;
   echo ") ";
   echo $response->steps[$i];
   echo "<br><br>";
}
}
else{
   echo"1. Combine the butter and cream cheese together in a heat-safe container. Microwave the ingredients on high heat for 20 seconds until they’re melted. Stir the butter and cream cheese together into one mixture.<br>";
   echo"2. Combine the butter and cream cheese mixture with coconut flour, brown sugar substitute, and vanilla extract in the heat-safe dish. You may also wish to add a small pinch of salt. If necessary, you can mix the ingredients in a separate mixing bowl before adding it to your heat-safe dish or mug.<br>";
   echo"3. Mix the egg into the batter. Follow by folding the blueberries into the batter. It may help you to freeze the blueberries beforehand so they don’t break up and bleed in the batter.<br>";
   echo"4. Microwave your mug of batter on high heat for 1 minute. The cake should puff considerably! Serve with whipped cream if desired.<br>";
}
//echo var_dump($response->ingredients);
?></p><br>
</section>






<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>