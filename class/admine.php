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
            $query = "SELECT COUNT(*) as total FROM Course";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            $totalCourses = $result["total"];
            $query = "SELECT COUNT(*) as approved FROM Course WHERE status = 'approved'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            $totalApprovedCourses = $result["total"];
            $query = "SELECT COUNT(*) as rejected FROM Course WHERE status = 'rejected'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            $totalRejectedCourses = $result["total"];
            $query = "SELECT COUNT(*) as pending FROM Course WHERE status = 'pending'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            $totalPendingCourses = $result["total"];
            $platformStatistics = [
                "totalCourses" => $totalCourses,
                "totalApprovedCourses" => $totalApprovedCourses,
                "totalRejectedCourses" => $totalRejectedCourses,
                "totalPendingCourses" => $totalPendingCourses
                ];
                return ["status"=>1,"message"=>$platformStatistics];
        } catch (PDOException $e) {
            return ["status" => 0, "error" => "Error: " . $e->getMessage()];
        }
    }
    
}
?>