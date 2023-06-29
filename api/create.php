<?php
@include '../config.php';
header("Content-Type:application/json");
$apidata=json_decode(file_get_contents('php://input'),true);
$insert_products = $conn->prepare("INSERT INTO `products`(name, category, details, price, image) VALUES(?,?,?,?,?)");
$insert_products->execute([$apidata["name"], $apidata["category"], $apidata["details"], $apidata["price"], $apidata["image"]]);
echo "Succesful Insertion Through POST!!";
return $apidata;
?>
