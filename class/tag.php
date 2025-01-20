<?php
class tag
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }
    public function createTags($tags)
    {
        try {
            $sql = "INSERT INTO Tag (name) VALUES (:name)";
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
    public function deleteTag($id)
    {
        try {
            $sql = "DELETE FROM Tag WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return ["status" => 1, "message" => "Tag deleted successfully."];
        } catch (PDOException $e) {
            return [
                "status" => 0,
                "error" => "Error: " . $e->getMessage()
            ];
        }
    }
    public function listTags()
    {
        try {
            $sql = "SELECT * FROM Tag ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $tags = $stmt->fetchAll();
            return ["status" => 1, "message" => $tags];
        } catch (PDOException $e) {
            return [
                "status" => 0,
                "error" => "Error: " . $e->getMessage()
            ];
        }
    }
    public function asignTags($tags, $courseId)
    {
        try {
            $sql = "INSERT INTO Course_Tag (course_id,tag_id) VALUES (:course_id, :tag_id)";
            foreach ($tags as $tag) {
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    'course_id' =>$courseId,
                    'tag_id' => $tag
                ]);
            }
        } catch (PDOException $e) {
            return [
                "status" => 0,
                "error" => "Error: " . $e->getMessage()
            ];
        }
    }

}
?>