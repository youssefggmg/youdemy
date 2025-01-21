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

    public function __construct($id = "",$title = "",$description = "",$content = "",$video = "",$status = "",$contentType = ""
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->content = $content;
        $this->video = $video;
        $this->status = $status;
        $this->contentType = $contentType;
    }
    public function getConnection($db){
        $this->db = $db;
    }
    public function __get($name)
    {
        return $this->$name;
    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    // Add a course
    public function addCours($title, $description, $content = "", $video = "", $contentType, $teacherID)
    {
        try {
            // Validate inputs
            if (empty($title) || strlen($title) > 255) {
                return ["status" => 0, "message" => "Title is required and must not exceed 255 characters."];
            }

            if (empty($description)) {
                return ["status" => 0, "message" => "Description is required."];
            }

            if ($contentType !== "Text" && $contentType !== "Video") {
                return ["status" => 0, "message" => "Content type must be either 'Text' or 'Video'."];
            }

            if ($contentType === "Text" && empty($content)) {
                return ["status" => 0, "message" => "Content is required for text-based courses."];
            }

            if ($contentType === "Video" && empty($video)) {
                return ["status" => 0, "message" => "Video URL is required for video-based courses."];
            }

            if (!filter_var($video, FILTER_VALIDATE_URL) && $contentType === "Video") {
                return ["status" => 0, "message" => "Invalid video URL format."];
            }

            // Prepare and execute the SQL query
            $query = "INSERT INTO Course (title, description, content, vedio_url, content_type, teacher_ID) 
                    VALUES (:title, :description, :content, :video, :contentType, :teacher_ID)";
            $stmt = $this->db->prepare($query);

            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':content' => $content,
                ':video' => $video,
                ':contentType' => $contentType,
                ':teacher_ID' => $teacherID
            ]);

            return [
                "status" => 1,
                "message" => "Course added successfully.",
                "course_id" => $this->db->lastInsertId()
            ];
        } catch (PDOException $e) {
            return ["status" => 0, "message" => "Error: " . $e->getMessage()];
        }
    }
    // Update a course
    public function updateCourse($id, $title = "", $description = "", $content = "", $video = "", $contentType = "")
    {
        try {
            // Initialize the base query
            $query = "UPDATE Course SET ";
            $params = [];

            // Dynamically add fields and parameters
            if (!empty($title)) {
                $query .= "title = :title, ";
                $params[':title'] = $title;
            }
            if (!empty($description)) {
                $query .= "description = :description, ";
                $params[':description'] = $description;
            }
            if (!empty($content)) {
                $query .= "content = :content, ";
                $params[':content'] = $content;
            }
            if (!empty($video)) {
                $query .= "video = :video, ";
                $params[':video'] = $video;
            }
            if (!empty($contentType)) {
                $query .= "content_type = :contentType, ";
                $params[':contentType'] = $contentType;
            }

            // Remove the trailing comma and space
            $query = rtrim($query, ', ');

            // Add the WHERE clause
            $query .= " WHERE id = :id";
            $params[':id'] = $id;

            // Prepare and execute the statement
            $stmt = $this->db->prepare($query);
            $executed = $stmt->execute($params);

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
    
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative arrays
            $theCourses = [];
    
            foreach ($courses as $course) {
                $theCourses[] = new Cours($course["id"] ?? "",$course["title"] ?? "",$course["description"] ?? "",$course["content"] ?? "",$course["vedio_url"] ?? "", $course["status"] ?? "",$course["content_type"] ?? "");
            }
    
            if (!empty($theCourses)) {
                return ["status" => 1, "courses" => $theCourses];
            } else {
                return ["status" => 0, "message" => "No approved courses found."];
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
                "message" => "Error: " . $e->getMessage()
            ];
        }
    }
    public function searchCoursesByTitle($title)
    {
        try {
            $query = "SELECT * FROM Course WHERE title LIKE :title";
            $stmt = $this->db->prepare($query);
            $searchTerm = '%' . $title . '%';
            $stmt->bindParam(':title', $searchTerm, );
            $stmt->execute();
            $courses = $stmt->fetchAll();

            return json_encode([
                'status' => 1,
                'courses' => $courses
            ]);
        } catch (PDOException $e) {
            return json_encode([
                'status' => 0,
                'error' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
?>