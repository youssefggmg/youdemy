<?php
class Catigory
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }
    public function createTag($name){
        try {
            $sql = "INSERT INTO tags (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name'=>$name]);
        return ["status"=>1,"message"=>"tag created"];
        } catch (PDOException $e) {
            return [
                "status" => 0,
                "error" => "Error: " . $e->getMessage()
            ];
        }
    }
}
?>