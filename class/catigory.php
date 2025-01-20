<?php
class Category
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function createCategories($categories)
    {
        try {
            $sql = "INSERT INTO Category (name) VALUES (:name)";
            $stmt = $this->db->prepare($sql);
            foreach ($categories as $category) {
                $stmt->execute(['name' => $category]);
            }
            return ["status" => 1, "message" => count($categories) . " categories created successfully."];
        } catch (PDOException $e) {
            return [
                "status" => 0,
                "error" => "Error: " . $e->getMessage()
            ];
        }
    }
    public function deleteCategory($id)
    {
        try {
            $sql = "DELETE FROM Category WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return ["status" => 1, "message" => "Category deleted successfully."];
        } catch (PDOException $e) {
            return [
                "status" => 0,
                "error" => "Error: " . $e->getMessage()
            ];
        }
    }
    public function listCategories()
    {
        try {
            $sql = "SELECT * FROM Category";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $categories = $stmt->fetchAll();
            return ["status" => 1, "categories" => $categories];
        } catch (PDOException $e) {
            return [
                "status" => 0,
                "error" => "Error: " . $e->getMessage()
            ];
        }
    }
    public function getCategoryCourseCounts()
    {
        try {
            $sql = "
            SELECT 
                id AS category_id,
                name AS category_name,
                catImage AS category_image,
                (
                    SELECT COUNT(*) 
                    FROM Course_Category 
                    WHERE Course_Category.category_id = Category.id
                ) AS course_count
            FROM 
                Category
            ORDER BY 
                name;
        ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ["status" => 1, "categories" => $categories];
        } catch (PDOException $e) {
            return ["status" => 0, "error" => "Error: " . $e->getMessage()];
        }
    }
    public function assignCategories($categoryId, $courseId)
    {
        try {
            $sql = "INSERT INTO Course_Category (course_id, category_id) VALUES (:course_id, :category_id)";
            $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    'course_id' => $courseId,
                    'category_id' => $categoryId
                ]);
            return ["status" => 1, "message" => "Categories assigned to the course successfully."];
        } catch (PDOException $e) {
            return [
                "status" => 0,
                "error" => "Error: " . $e->getMessage()
            ];
        }
    }
}
?>