# Youdemy Online Course Platform

The **Youdemy** online course platform aims to revolutionize learning by offering an interactive and personalized system for students and teachers.

---

## Required Features

### Front Office

#### Visitor
- Access to the course catalog with pagination.
- Course search by keywords.
- Account creation with role selection (Student or Teacher).

#### Student
- View the course catalog.
- Search and view course details (description, content, teacher, etc.).
- Register for a course after authentication.
- Access a "My Courses" section displaying joined courses.

#### Teacher
- Add new courses with details such as:
  - Title, description, content (video or document), tags, and category.
- Manage courses:
  - Modify, delete, and view registrations.
- Access a "Statistics" section for courses:
  - Number of registered students, number of courses, etc.

---

### Back Office

#### Administrator
- Validate teacher accounts.
- Manage users:
  - Activate, suspend, or delete accounts.
- Manage content:
  - Courses, categories, and tags.
  - Mass insert tags for efficiency.
- Access global statistics:
  - Total number of courses.
  - Breakdown by category.
  - Course with the most students.
  - Top 3 teachers.

---

## Transversal Features
- Courses can contain multiple tags (many-to-many relationship).
- Polymorphism applied in methods such as adding and viewing courses.
- Authentication and authorization system to protect sensitive routes.
- Access control: each user can access features corresponding to their role.

---

## Technical Requirements
- Compliance with OOP principles (encapsulation, inheritance, polymorphism).
- Relational database with relationship management (one-to-many, many-to-many).
- Use PHP sessions to manage logged-in users.
- Implement user data validation to ensure security.

---

## Bonus Features
- Advanced search with filters (category, tags, author).
- Advanced statistics:
  - Engagement rate by course.
  - Most popular categories.
- Notification system:
  - For example, teacher account validation or course registration confirmation.
- Comment or assessment system for courses.
- PDF completion certificates for students.