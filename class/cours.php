<?php
class Cours
{
    private $id;
    private $title;
    private $description;
    private $content;
    private $video;
    private $status;
    private $contentType;
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Add a course
    public function addCours($title, $description, $content, $video, $status, $contentType)
    {
        try {
            $query = "INSERT INTO Course (title, description, content, video, status, content_type) 
                        VALUES (:title, :description, :content, :video, :status, :contentType)";
            $stmt = $this->db->prepare($query);

            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':content' => $content,
                ':video' => $video,
                ':status' => $status,
                ':contentType' => $contentType,
            ]);

            return [
                "status" => 1,
                "message" => "Course added successfully.",
                "course_id" => $this->db->lastInsertId()
            ];
        } catch (PDOException $e) {
            return ["status" => 0, "error" => "Error: " . $e->getMessage()];
        }
    }

    // Update a course
    public function updateCourse($id, $title, $description, $content, $video, $status, $contentType)
    {
        try {
            $query = "UPDATE Course 
                        SET title = :title, description = :description, content = :content, 
                            video = :video, status = :status, content_type = :contentType 
                        WHERE id = :id";
            $stmt = $this->db->prepare($query);

            $executed = $stmt->execute([
                ':id' => $id,
                ':title' => $title,
                ':description' => $description,
                ':content' => $content,
                ':video' => $video,
                ':status' => $status,
                ':contentType' => $contentType,
            ]);

            if ($executed) {
                return ["status" => 1, "message" => "Course updated successfully."];
            } else {
                return ["status" => 0, "message" => "Failed to update course."];
            }
        } catch (PDOException $e) {
            return ["status" => 0, "error" => "Error: " . $e->getMessage()];
        }
    }

    // Delete a course
    public function deleteCourse($id)
    {
        try {
            $query = "DELETE FROM Course WHERE id = :id";
            $stmt = $this->db->prepare($query);

            $executed = $stmt->execute([':id' => $id]);

            if ($executed) {
                return ["status" => 1, "message" => "Course deleted successfully."];
            } else {
                return ["status" => 0, "message" => "Failed to delete course."];
            }
        } catch (PDOException $e) {
            return ["status" => 0, "error" => "Error: " . $e->getMessage()];
        }
    }

    // List all approved courses
    public function listApprovedCourses()
    {
        try {
            $query = "SELECT * FROM Course WHERE status = 'approved'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $courses = $stmt->fetchAll();

            if ($courses) {
                return ["status" => 1, "courses" => $courses];
            } else {
                return ["status" => 0, "message" => "No approved courses found."];
            }
        } catch (PDOException $e) {
            return ["status" => 0, "error" => "Error: " . $e->getMessage()];
        }
    }
    public function listCoursesByTag($tagName)
    {
        try {
            $query = "SELECT c.* 
                      FROM Course c
                      JOIN Course_Tag ct ON c.id = ct.course_id
                      JOIN Tag t ON ct.tag_id = t.id
                      WHERE t.name = :tagName";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':tagName', $tagName, );
            $stmt->execute();

            $courses = $stmt->fetchAll();

            if ($courses) {
                return ["status" => 1, "courses" => $courses];
            } else {
                return ["status" => 0, "message" => "No courses found with the given tag."];
            }
        } catch (PDOException $e) {
            return ["status" => 0, "error" => "Error: " . $e->getMessage()];
        }
    }

    public function listCoursesByCategory($categoryName)
    {
        try {
            $query = "SELECT c.* 
                      FROM Course c
                      JOIN Course_Category cc ON c.id = cc.course_id
                      JOIN Category cat ON cc.category_id = cat.id
                      WHERE cat.name = :categoryName";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':categoryName', $categoryName);
            $stmt->execute();

            $courses = $stmt->fetchAll();

            if ($courses) {
                return ["status" => 1, "courses" => $courses];
            } else {
                return ["status" => 0, "message" => "No courses found in the given category."];
            }
        } catch (PDOException $e) {
            return ["status" => 0, "error" => "Error: " . $e->getMessage()];
        }
    }

    public function getCourseDetails($id)
    {
        try {
            $query = "SELECT * FROM Course WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $result = $stmt->fetch();

            if ($result) {
                return [
                    "status" => 1,
                    "course" => $result
                ];
            } else {
                return [
                    "status" => 0,
                    "message" => "Course not found."
                ];
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