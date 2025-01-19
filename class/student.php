<?php
include "user.php";
class Student extends User
{
    public function __construct($db)
    {
        parent::__construct($db); 
    }


    public function enrollInCourse($courseId ,$student_id): array
    {
        try {
            $query = "INSERT INTO enrollments (student_id, course_id) VALUES (:student_id, :course_id)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':student_id' => $student_id,
                ':course_id' => $courseId,
            ]);

            return ['status' => 1, 'message' => 'Successfully enrolled in the course.'];
        } catch (PDOException $e) {
            return ['status' => 0, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function viewMyCourses($id): array
    {
        try {
            $query = "SELECT c.id, c.title, c.description, c.content_type
                        FROM Course c 
                        JOIN Enrollment e ON c.id = e.course_id 
                        WHERE e.student_id = :student_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':student_id' => $id]);
            $courses = $stmt->fetchAll();
            return ['status' => 1, 'data' => $courses];
        } catch (PDOException $e) {
            return ['status' => 0, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
    public function getProfile(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'user_type' => 'Student'
        ];
    }
}
?>