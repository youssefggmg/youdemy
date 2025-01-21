<?php
include "user.php";
class Admine extends User
{
    private $userlist;
    private $coursList;

    public function __construct($db)
    {
        parent::__construct($db);
    }
    public function ActivatUser($id)
    {
        try {
            $query = "SELECT account_status from User where id = '$id'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            if ($result['account_status'] == "active") {
                return ["status" => 0, "error" => "User is already active"];
            } else {
                $query = "UPDATE User SET account_status = 'active' WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id);
                $executed = $stmt->execute();
                if ($executed) {
                    return ["status" => 1, "message" => "user was activated"];
                } else {
                    return ["status" => 0, "message" => "user was not activated"];
                }
            }
        } catch (PDOException $e) {
            die("error" . $e->getMessage());
        }
    }
    public function DeactivateUser($id)
    {
        try {
            $query = "SELECT account_status FROM User WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch();
            if ($result['account_status'] === "inactive") {
                return ["status" => 0, "error" => "User is already inactive"];
            } else {
                $query = "UPDATE User SET account_status = 'inactive' WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id);
                $executed = $stmt->execute();
                if ($executed) {
                    return ["status" => 1, "message" => "User was deactivated"];
                } else {
                    return ["status" => 0, "message" => "User was not deactivated"];
                }
            }
        } catch (PDOException $e) {
            return ["status" => 0, "error" => "Error: " . $e->getMessage()];
        }
    }
    public function deleteUser($id)
    {
        try {
            $query = "SELECT id FROM User WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch();
            if (!$result) {
                return ["status" => 0, "error" => "User not found"];
            }
            $query = "DELETE FROM User WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $executed = $stmt->execute();

            if ($executed) {
                return ["status" => 1, "message" => "User deleted successfully"];
            } else {
                return ["status" => 0, "message" => "User could not be deleted"];
            }
        } catch (PDOException $e) {
            return ["status" => 0, "error" => "Error: " . $e->getMessage()];
        }
    }
    public function getTeachersAccount()
    {
        try {
            $query = "SELECT * FROM User WHERE user_type = 'Teacher'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return ["status" => 1, "message" => $result];
        } catch (PDOException $e) {
            return ["status" => 0, "error" => "Error: " . $e->getMessage()];
        }
    }
    public function getAllStudents()
    {
        try {
            $query = "SELECT * FROM User WHERE user_type = 'Student'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return ["status" => 1, "message" => $result];
        } catch (PDOException $e) {
            return ["status" => 0, "error" => "Error: " . $e->getMessage()];
        }
    }
    public function approveCourse($id)
    {
        try {
            $query = "SELECT status form Course where id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch();
            if ($result["status"] == "accepted") {
                return ["status" => 0, "message" => "Course is already approved"];
            } else {
                $query = "UPDATE Course SET status = 'accepted' WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $executed = $stmt->execute([
                    ':id' => $id
                ]);
                if ($executed) {
                    return ["status" => 1, "message" => "Course approved successfully"];
                } else {
                    return ["status" => 0, "message" => "Course could not be approved"];
                }
            }
        } catch (PDOException $e) {
            return ["status" => 0, "error" => "Error: " . $e->getMessage()];
        }
    }
    public function rejectCourse($id)
    {
        try {
            $query = "SELECT status FROM Course WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch();

            if ($result["status"] == "rejected") {
                return ["status" => 0, "message" => "Course is already rejected"];
            } else {
                $query = "UPDATE Course SET status = 'rejected' WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $executed = $stmt->execute([':id' => $id]);

                if ($executed) {
                    return ["status" => 1, "message" => "Course rejected successfully"];
                } else {
                    return ["status" => 0, "message" => "Course could not be rejected"];
                }
            }
        } catch (PDOException $e) {
            return ["status" => 0, "error" => "Error: " . $e->getMessage()];
        }
    }

    public function generatePlatformStatistics()
    {
        try {
            // Total number of courses
            $query = "SELECT COUNT(*) as total FROM Course";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            $totalCourses = $result["total"];
            // Total number of approved courses
            $query = "SELECT COUNT(*) as approved FROM Course WHERE status = 'approved'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            $totalApprovedCourses = $result["approved"];
            // Total number of rejected courses
            $query = "SELECT COUNT(*) as rejected FROM Course WHERE status = 'rejected'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            $totalRejectedCourses = $result["rejected"];
            // Total number of pending courses
            $query = "SELECT COUNT(*) as pending FROM Course WHERE status = 'pending'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            $totalPendingCourses = $result["pending"];
            // Total number of users
            $query = "SELECT COUNT(*) as totalUsers FROM User";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            $totalUsers = $result["totalUsers"];
            // Total number of active teachers
            $query = "SELECT COUNT(*) as activeTeachers FROM User WHERE account_status = 'Active' and user_type = 'Teacher'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            $activeTeachers = $result["activeTeachers"];
            // Total number of inactive teachers
            $query = "SELECT COUNT(*) as inactiveTeachers FROM User WHERE account_status = 'Inactive' and  user_type = 'Teacher'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            $inactiveTeachers = $result["inactiveTeachers"];
            // Compile the statistics
            $query = "SELECT *, 
                         (SELECT COUNT(*) FROM Enrollment WHERE course_id = Course.id) as enrollment_count 
                  FROM Course
                  ORDER BY enrollment_count DESC
                  LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $mostEnrolledCourse = $stmt->fetch();
            
            $platformStatistics = [
                "totalCourses" => $totalCourses,
                "totalApprovedCourses" => $totalApprovedCourses,
                "totalRejectedCourses" => $totalRejectedCourses,
                "totalPendingCourses" => $totalPendingCourses,
                "totalUsers" => $totalUsers,
                "activeTeachers" => $activeTeachers,
                "inactiveTeachers" => $inactiveTeachers,
                "mostEnrolledCourse"=>$mostEnrolledCourse
            ];

            return ["status" => 1, "message" => $platformStatistics];

        } catch (PDOException $e) {
            return ["status" => 0, "error" => "Error: " . $e->getMessage()];
        }
    }


}
?>