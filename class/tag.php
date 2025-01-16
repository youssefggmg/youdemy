<?php
class Catigory
{
    private $db;
    public function createTags($tags)
    {
        try {
            $sql = "INSERT INTO tags (name) VALUES (:name)";
            $stmt = $this->db->prepare($sql);
            foreach ($tags as $tag) {
                $stmt->execute(['name' => $tag]);
            }
            return ["status" => 1, "message" => count($tags) . " tags created successfully."];
        } catch (PDOException $e) {
            return [
                "status" => 0,
                "error" => "Error: " . $e->getMessage()
            ];
        }
    }
}
?>