<?php 
include "../../class/admine.php";
include "../../instance/instace.php";

$admine = new Admine($pdo);
$result = $admine->ActivatUser($_GET["userID"]);
if($result['status']==1){
    header("location: ../../admine/users.php");
}else {
    die($result['error']);
}
?>