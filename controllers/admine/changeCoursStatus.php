<?php
include "../../class/cours.php";
include "../../instance/instace.php";

$cours = new Cours();
$cours->getConnection($pdo);
$id = $_GET["id"];
$status = $_GET["status"];
$cours->changeStatus($id,$status);

?>