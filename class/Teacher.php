<?php
include "user.php";
class Teacher extends User
{
    private $CourseStatistics;
    private $isValidate;
    public function __construct($db)
    {
        parent::__construct($db);
    }
    public function viewCourseStatistics($id)
    {
        try {
            $query = "SELECT COUNT(*) AS totalCourses FROM Course WHERE teacher_id = :teacher_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':teacher_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $totalCourses = $stmt->fetch()['totalCourses'];

            $query = "SELECT COUNT(*) AS totalEnrollments 
                  FROM Enrollment e 
                  JOIN Course c ON e.course_id = c.id 
                  WHERE c.teacher_id = :teacher_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':teacher_id', $id);
            $stmt->execute();
            $totalEnrollments = $stmt->fetch()['totalEnrollments'];

            $query = "SELECT COUNT(*) AS completedEnrollments 
                  FROM Enrollment e 
                  JOIN Course c ON e.course_id = c.id 
                  WHERE c.teacher_id = :teacher_id AND e.status = 'Completed'";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':teacher_id', $id);
            $stmt->execute();
            $completedEnrollments = $stmt->fetch()['completedEnrollments'];
            $this->CourseStatistics=[
                'totalCourses' => $totalCourses,
                'totalEnrollments' => $totalEnrollments,
                'completedEnrollments' => $completedEnrollments
            ];
            return [
                "status" => 1,
                "result" =>$this->CourseStatistics
            ];
        } catch (PDOException $e) {
            return [
                'status' => 0,
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
    public function getCoursesByTeacherId($teacherId)
    {
        try {
            $query = "SELECT * FROM Course WHERE teacher_ID = :teacher_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':teacher_id', $teacherId, PDO::PARAM_INT);
            $stmt->execute();
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($courses) {
                return [
                    "status" => 1,
                    "courses" => $courses
                ];
            } else {
                return [
                    "status" => 0,
                    "message" => "No courses found for the given teacher ID."
                ];
            }
        } catch (PDOException $e) {
            return [
                'status' => 0,
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
}
?>