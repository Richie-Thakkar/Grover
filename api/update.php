<?php
@include '../config.php';
header("Content-Type:application/json");
$apidata=json_decode(file_get_contents('php://input'),true);
$update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, details = ?, price = ? WHERE id = ?");
   $update_product->execute([$apidata["name"], $apidata["category"], $apidata["details"], $apidata["price"], $apidata["pid"]]);
   $image_folder = '../uploaded_img/'.$apidata["image"];
   if(!empty($apidata["image"])){
      if($apidata["image_size"] > 2000000){
         $message[] = 'image size is too large!';
      }else{
         $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
         $update_image->execute([$apidata["image"], $apidata["pid"]]);
         if($update_image){
            move_uploaded_file($apidata["image_tmp_name"], $image_folder);
            //unlink('../uploaded_img/'.$apidata["old_image"]);
         }
      }
   }
   echo "Suceesful update through PUT!!";
   return $apidata;
?>