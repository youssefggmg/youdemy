<?php
include "../rolleValidation/roleValidaiton.php";
include "../instance/instace.php";
include "../class/student.php";
$roleValidaiton = new RoleValidaiton($_COOKIE["userROLE"], "Student", "../index.php");
$student = new Student($pdo);
$myCourses = $student->viewMyCourses($_COOKIE["userROLE"]);
if ($myCourses["status"] == 1) {
    $result = $myCourses["data"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Topbar -->
    <div class="hidden lg:block bg-white">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <div class="w-1/4">
                    <a href="#" class="text-decoration-none">
                        <h1 class="text-2xl font-bold"><span class="text-blue-600">E</span>COURSES</h1>
                    </a>
                </div>
                
                <!-- Office Location -->
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt text-2xl text-blue-600 mr-3"></i>
                    <div>
                        <h6 class="font-semibold mb-1">Our Office</h6>
                        <small class="text-gray-600">123 Street, New York, USA</small>
                    </div>
                </div>
                
                <!-- Email -->
                <div class="flex items-center">
                    <i class="fas fa-envelope text-2xl text-blue-600 mr-3"></i>
                    <div>
                        <h6 class="font-semibold mb-1">Email Us</h6>
                        <small class="text-gray-600">info@example.com</small>
                    </div>
                </div>
                
                <!-- Phone -->
                <div class="flex items-center">
                    <i class="fas fa-phone text-2xl text-blue-600 mr-3"></i>
                    <div>
                        <h6 class="font-semibold mb-1">Call Us</h6>
                        <small class="text-gray-600">+012 345 6789</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <div class="border-t">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap">
                <!-- Search Bar (Desktop) -->
                <div class="hidden lg:block w-1/4 py-4">
                    <input type="email" placeholder="Search Something..." 
                        class="w-full px-4 py-3 border rounded focus:outline-none focus:border-blue-500 text-sm text-gray-600">
                </div>
                
                <!-- Navigation -->
                <div class="w-full lg:w-3/4">
                    <nav class="bg-white">
                        <div class="container mx-auto">
                            <!-- Mobile Logo -->
                            <div class="lg:hidden flex items-center justify-between py-4">
                                <a href="#" class="text-xl font-bold">
                                    <span class="text-blue-600">E</span>COURSES
                                </a>
                                <button id="menu-toggle" class="lg:hidden focus:outline-none">
                                    <i class="fas fa-bars text-xl"></i>
                                </button>
                            </div>
                            
                            <!-- Navigation Links -->
                            <div id="menu" class="hidden lg:flex flex-col lg:flex-row justify-between items-center py-4">
                                <div class="flex flex-col lg:flex-row space-y-2 lg:space-y-0 lg:space-x-6">
                                    <a href="index.php" class="text-gray-800 hover:text-blue-600 font-medium">Home</a>
                                    <a href="about.php" class="text-gray-800 hover:text-blue-600 font-medium">About</a>
                                    <a href="course.php" class="text-gray-800 hover:text-blue-600 font-medium">Courses</a>
                                    <a href="teacher.php" class="text-gray-800 hover:text-blue-600 font-medium">Teachers</a>
                                    <a href="myCourses.php" class="text-gray-800 hover:text-blue-600 font-medium">MyCourse's</a>
                                    <a href="contact.php" class="text-gray-800 hover:text-blue-600 font-medium">Contact</a>
                                </div>
                                <a href="../controllers/logout.php" 
                                    class="hidden lg:inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition duration-200">
                                    Logout
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar End -->
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto">
                <h5 class="text-blue-200 uppercase tracking-[0.5em] mb-4 text-sm font-semibold">Expand Your Knowledge
                </h5>
                <h1 class="text-5xl font-bold mb-6">Our Featured Courses</h1>
                <p class="text-blue-100 text-lg">Discover our selection of professionally crafted courses designed to
                    help you achieve your goals.</p>
            </div>
        </div>
    </div>

    <!-- Courses Section -->
    <div class="py-16 px-4 -mt-10">
        <div class="max-w-7xl mx-auto">
            <!-- Course Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Course Card 1 -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                    <div class="relative">
                        <img src="/api/placeholder/400/250" alt="Course Image" class="w-full h-48 object-cover">
                        <div
                            class="absolute top-4 right-4 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            Popular
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center mb-3">
                            <span
                                class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">Frontend</span>
                            <span class="ml-2 text-gray-500 text-sm">• 12 weeks</span>
                        </div>
                        <a href="#" class="block text-xl font-bold text-gray-800 hover:text-blue-600 mb-3">
                            Web Development Masterclass
                        </a>
                        <p class="text-gray-600 mb-4 text-sm">Learn modern web development with HTML, CSS, and
                            JavaScript.</p>
                        <div class="border-t pt-4">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <span class="ml-2 text-sm text-gray-600">(128)</span>
                                </div>
                                <button
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transform hover:scale-105 transition-all duration-200">
                                    Enroll Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>