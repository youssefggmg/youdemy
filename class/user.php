<?php
class User
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $email = null;
    private ?string $password = null;
    private ?string $user_type = null;
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function signUp(string $name, string $email, string $password, string $user_type): array
    {
        try {
            // Check if the email already exists
            $query = "SELECT id FROM users WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':email' => $email]);

            if ($stmt->fetch()) {
                return ['status' => 0, 'message' => 'Email already exists.'];
            }

            // Determine account_status based on user_type
            $accountStatus = ($user_type === 'Student') ? 'Active' : 'Inactive';

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert the user into the database
            $query = "INSERT INTO users (name, email, password, user_type, account_status) 
                        VALUES (:name, :email, :password, :user_type, :account_status)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':user_type' => $user_type,
                ':account_status' => $accountStatus
            ]);

            return ['status' => 1, 'message' => 'User signed up successfully.'];
        } catch (PDOException $e) {
            return ['status' => 0, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function logIn(string $email, string $password): array
    {
        try {
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $this->id = $user['id'];
                $this->name = $user['name'];
                $this->email = $user['email'];
                $this->user_type = $user['user_type'];

                return [
                    'status' => 1,
                    'data' => [
                        'id' => $this->id,
                        'name' => $this->name,
                        'email' => $this->email,
                        'user_type' => $this->user_type
                    ]
                ];
            }
            return ['status' => 0, 'message' => 'Invalid email or password.'];
        } catch (PDOException $e) {
            return ['status' => 0, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
    public function getUserInfo(int $id): array
    {
        try {
            $query = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch();

            if (!$user) {
                return ['status' => 0, 'message' => 'User not found.'];
            }

            $this->name = $user['name'];
            $this->email = $user['email'];
            $this->user_type = $user['user_type'];

            $userData = [
                'id' => $id,
                'name' => $this->name,
                'email' => $this->email,
                'user_type' => $this->user_type
            ];
            if ($this->user_type === 'Student') {
                $userData['enrolled_courses'] = $this->getStudentCourses($this->id);
            }

            return ['status' => 1, 'data' => $userData];
        } catch (Exception $e) {
            return ['status' => 0, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    private function getStudentCourses(int $studentId): array
    {
        $query = "SELECT c.id AS course_id, c.title, c.description
                  FROM courses c
                  JOIN enrollments e ON c.id = e.course_id
                  WHERE e.student_id = :student_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':student_id' => $studentId]);

        return $stmt->fetchAll() ?: [];
    }
}
?>