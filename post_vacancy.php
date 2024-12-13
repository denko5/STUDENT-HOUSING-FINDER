<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $distance_km = $_POST['distance_km'];
    $landlord_contact = $_POST['landlord_contact'];
    $image_path = "";

    // Check if an image file is uploaded
    if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] == 0) {
        $target_dir = "uploads/"; // Directory to store images
        $target_file = $target_dir . basename($_FILES["image_path"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (in_array($imageFileType, $allowed_types)) {
            // Ensure the uploads directory exists and is writable
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
            }
            // Move the upload file to the target directory
            if (move_uploaded_file($_FILES["image_path"]["tmp_name"], $target_file)) {
                $image_path = $target_file; // Save the path for database
            } else {
                die("Error: Failed to move upload file.");
            }
        } else {
            die("Error: Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.");
        }
    } else {
        die("Error: No file uploaded or upload error occured.");
    }

    // Database connection
    $conn = new mysqli("localhost", "root", "password", "student_housing");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert data into databse
    $stmt = $conn->prepare("INSERT INTO vacancies (type, location, price, distance_km, landlord_contact, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $type, $location, $price, $distance_km, $landlord_contact, $image_path);

    if ($stmt->execute()) {
        header("Location: Homepage.php");
    } else {
        die("Database Error: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}

?>