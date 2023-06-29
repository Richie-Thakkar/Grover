<?php
@include '../config.php';
header("Content-Type:application/json");
if ((isset($_GET['name']) && $_GET['name']!=""))
{
$na=$_GET['name'];
$select_products = $conn->prepare("SELECT * FROM products where name like '%'".($na)."'%'");
      $select_products->execute();
      if($select_products->rowCount() > 0){
        while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
        $id=$fetch_products['id'];
        $name=$fetch_products['name'];
        $category=$fetch_products['category'];
        $details=$fetch_products['details'];
        $price=$fetch_products['price'];
        $image=$fetch_products['image'];
        response($id,$name,$category,$details,$price,$image);
    }
    }
    else{
        echo "No records found!";
    }
}
    function response($id,$name,$category,$details,$price,$image)
    {
        $response['id']=$id;
        $response['name']=$name;
        $response['category']=$category;
        $response['details']=$details;
        $response['price']=$price;
        $response['image']=$image;
        $json_response = json_encode($response);
        echo $json_response;
    }
?>