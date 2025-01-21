<?php 
include "../class/cours.php";
include "../instance/instace.php";
$cours = new Cours();
$cours->getConnection($pdo);
$searchResult = $cours->searchCoursesByTitle($_GET["search"]);



?>