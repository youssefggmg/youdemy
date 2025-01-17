<?php 
include "../class/user.php";
include "../instance/instace.php";
var_dump($_POST);
$user = new User($db);
$reault= $user->signUp($_POST["username"],$_POST["email"],$_POST["password"],$_POST["user_type"]);
if ($reault["r"]) {
    # code...
}


?>