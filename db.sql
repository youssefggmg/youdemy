create database youdemy;
use youdemy;
CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('Student', 'Teacher', 'Administrator') NOT NULL,
    account_status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
);
CREATE TABLE Category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    catImage text not null
);
CREATE TABLE Tag (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);
CREATE TABLE Course (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    content_type ENUM('Video', 'Text') NOT NULL,
    status ENUM('accepted', 'pending','rejected') NOT NULL DEFAULT 'pending',
    vedio_url VARCHAR(255) ,
    content TEXT ,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    teacher_ID int ,
    CONSTRAINT fk_teacher FOREIGN KEY (teacher_ID) REFERENCES User(id)
);

CREATE TABLE Course_Category (
	id int primary key auto_increment,
    course_id INT,
    category_id INT,
    CONSTRAINT fk_course FOREIGN KEY (course_id) REFERENCES Course(id) ON DELETE CASCADE,
    CONSTRAINT fk_category FOREIGN KEY (category_id) REFERENCES Category(id) ON DELETE CASCADE
);
CREATE TABLE Course_Tag (
	id int primary key auto_increment,
    course_id INT,
    tag_id INT,
    CONSTRAINT fk_course_tag FOREIGN KEY (course_id) REFERENCES Course(id) ON DELETE CASCADE,
    CONSTRAINT fk_tag FOREIGN KEY (tag_id) REFERENCES Tag(id) ON DELETE CASCADE
);
CREATE TABLE Enrollment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    enrollment_date DATE NOT NULL,
    status ENUM('Active', 'Completed', 'Canceled') NOT NULL,
    CONSTRAINT fk_student FOREIGN KEY (student_id) REFERENCES User(id) ON DELETE CASCADE,
    CONSTRAINT fk_course_enrollment FOREIGN KEY (course_id) REFERENCES Course(id) ON DELETE CASCADE
);