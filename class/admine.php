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
    public function ActivatUser($id){
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
}
?>