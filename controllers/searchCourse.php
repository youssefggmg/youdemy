<?php 
include "../class/cours.php";
include "../instance/instace.php";
$cours = new Cours($pdo);
$searchResult = $cours->searchCoursesByTitle($_GET["search"]);



?>