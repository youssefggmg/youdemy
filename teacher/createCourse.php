<?php
include "../rolleValidation/roleValidaiton.php";
include "../instance/instace.php";
include "../helper/isAccountvalidated.php";


$roleValidaiton = new RoleValidaiton($_COOKIE["userROLE"], "Teacher", "../index.php");

$validateStatus = new IsAccountvalidated($pdo);
$validateStatus->validateAccount($_COOKIE["userID"]);

$accountstatus = $validateStatus->getAccountStatus();
if ($accountstatus == "Inactive") {
    header("Location: inactive.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const contentTypeRadios = document.querySelectorAll('input[name="content_type"]');
            const videoUrlField = document.getElementById('videoUrlField');
            const textContentField = document.getElementById('textContentField');

            // Hide both initially
            videoUrlField.style.display = 'none';
            textContentField.style.display = 'none';

            contentTypeRadios.forEach(radio => {
                radio.addEventListener('change', () => {
                    if (radio.value === 'Video') {
                        videoUrlField.style.display = 'block';
                        textContentField.style.display = 'none';
                    } else if (radio.value === 'Text') {
                        videoUrlField.style.display = 'none';
                        textContentField.style.display = 'block';
                    }
                });
            });
        });
    </script>
</head>
<div class="container mx-auto px-4">
    <div class="flex flex-wrap border-t px-4 xl:px-5">
        <div class="lg:w-1/4 hidden lg:block relative">
            <input type="text" placeholder="Search Something..." id="searchInput"
                class="w-full outline-none bg-white text-gray-600 text-sm px-4 py-3" />
            <div id="searchResults"
                class="absolute z-10 bg-white border border-gray-300 w-full max-h-72 overflow-y-auto hidden">
                <!-- Search results will be appended here -->
            </div>
        </div>
        <div class="lg:w-3/4">
            <nav class="bg-light py-3 px-0">
                <a href="#" class="block lg:hidden text-decoration-none">
                    <h1 class="m-0"><span class="text-primary">E</span>COURSES</h1>
                </a>
                <button type="button" class="navbar-toggler lg:hidden">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="lg:flex justify-between">
                    <div class="flex space-x-4 py-0">
                        <a href="index.php" class="text-gray-700 hover:text-primary">Home</a>
                        <a href="about.php" class="text-gray-700 hover:text-primary">About</a>
                        <a href="course.php" class="text-gray-700 hover:text-primary">Courses</a>
                        <a href="teacher.php" class="text-gray-700 hover:text-primary">Teachers</a>
                        <a href="myCourses.php" class="text-gray-700 hover:text-primary">MyCourse's</a>
                        <a href="contact.php" class="text-gray-700 hover:text-primary">Contact</a>
                    </div>
                    <a class="hidden lg:block bg-blue-500 text-white py-2 px-4 ml-auto"
                        href="../controllers/logout.php">Logout</a>
                </div>
            </nav>
        </div>
    </div>
</div>

<body class="bg-gradient-to-r from-blue-50 to-blue-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl overflow-hidden max-w-3xl w-full">
        <div class="bg-blue-600 text-white p-6 text-center">
            <h1 class="text-3xl font-bold">Create a New Course</h1>
            <p class="text-sm mt-2">Fill in the details below to add a new course to the platform.</p>
        </div>
        <div class="p-8">
            <form action="../../controllers/createCourse.php" method="POST" class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Course Title</label>
                    <input type="text" id="title" name="title" required 
                        class="block w-full border border-gray-300 rounded-md px-4 py-2 text-sm shadow focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4" required 
                        class="block w-full border border-gray-300 rounded-md px-4 py-2 text-sm shadow focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"></textarea>
                </div>

                <!-- Content Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Content Type</label>
                    <div class="flex items-center space-x-6">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="content_type" value="Video" required 
                                class="form-radio h-4 w-4 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Video</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="content_type" value="Text" required 
                                class="form-radio h-4 w-4 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Text</span>
                        </label>
                    </div>
                </div>

                <!-- Video URL -->
                <div id="videoUrlField">
                    <label for="video_url" class="block text-sm font-medium text-gray-700 mb-2">Video URL</label>
                    <input type="url" id="video_url" name="video_url" 
                        class="block w-full border border-gray-300 rounded-md px-4 py-2 text-sm shadow focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                </div>

                <!-- Text Content -->
                <div id="textContentField">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                    <textarea id="content" name="content" rows="4" 
                        class="block w-full border border-gray-300 rounded-md px-4 py-2 text-sm shadow focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"></textarea>
                </div>

                <!-- Teacher ID -->
                <div>
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">Teacher ID</label>
                    <input type="number" id="teacher_id" name="teacher_id" required 
                        class="block w-full border border-gray-300 rounded-md px-4 py-2 text-sm shadow focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-6">
                    <button type="submit" 
                        class="bg-blue-600 text-white font-medium py-3 px-8 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition-all duration-300 shadow-lg transform hover:scale-105">
                        Create Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

