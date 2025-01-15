<?php
class User
{
    private $id = null;
    private $name = null;
    private $email = null;
    private $password = null;
    private $user_type = null;
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }
    public function signUp($name, $email, $password, $user_type)
    {

        try {
            $query = "SELECT id FROM users WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':email' => $email]);

            if ($stmt->fetch()) {
                return [
                    'status' => 0,
                    'message' => 'Email already exists.'
                ];
            }

            $query = "INSERT INTO users (name, email, password, user_type) VALUES (:name, :email, :password, :user_type)";
            $stmt = $this->db->prepare($query);

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT); 

            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':user_type' => $user_type
            ]);

            return [
                'status' => 1,
                'message' => 'User signed up successfully.'
            ];
        } catch (PDOException $e) {
            return [
                'status' => 0,
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
    public function logIn($email, $password)
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
            } else {
                return [
                    'status' => 0,
                    'message' => 'Invalid email or password.'
                ];
            }
        } catch (PDOException $e) {
            return [
                'status' => 0,
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
    public function getUserInfo($id)
    {
        try {

            $query = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch();

            if (!$user) {
                return [
                    'status' => 0,
                    'message' => 'User not found.'
                ];
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
                $query = "SELECT c.id AS course_id, c.title, c.description
                          FROM courses c
                          JOIN enrollments e ON c.id = e.course_id
                          WHERE e.student_id = :student_id";
                $stmt = $this->db->prepare($query);
                $stmt->execute([':student_id' => $this->id]);
                $courses = $stmt->fetchAll();

                if ($courses) {
                    $userData['enrolled_courses'] = $courses;
                } else {
                    $userData['enrolled_courses'] = [];
                }
            }

            return [
                'status' => 1,
                'data' => $userData
            ];
        } catch (Exception $e) {
            return [
                'status' => 0,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}
?>