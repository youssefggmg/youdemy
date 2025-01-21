<?php
include "../../class/tag.php";
include "../../instance/instace.php";

$tag = new Tag($pdo);
$tagInputs = $_POST; // 
$numTags = intval($tagInputs['inputCounter']);

$response = $tag->createTags($tagInputs, $numTags);
if ($response['status'] === 1) {
    header("location: ../../admine/tags.php");
} else {
    echo "Error: " . $response['error'];
}
?>