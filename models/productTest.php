<?php
require_once('Product.php');
$product = new Product(1, "title", "ok", "img", "price", "color", "size", "desc");
header("Content-Type: application/json;charset=UTF-8");
echo json_encode($product->returnProductArray());