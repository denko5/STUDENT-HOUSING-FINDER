<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="Homepage.css">
</head>
<body>
    <header class="header">
        <h1 class="header-title"><b>STUDENT HOUSING FINDER</b></h1>
    </header>
    <section class="links">
        <a href="post_vacancy.html" class="link-item">Post Vacancy</a>
        <a href="login.php" class="link-item">Login In</a>
        <a href="remove_vacancy.html" class="link-item">Remove Vacancy</a>
    </section>

    <!-- General Search Bar -->
    <section class="search-section">
        <form method="POST" action="">
            <input type="text" name="search_query" class="search-bar" placeholder="Search for houses">
            <button type="submit" name="search_button" class="search-button">Search</button>
        </form>
    </section>

    <!-- Filters Section -->
    <section class="filters-section">
        <form class="filters-section" method="POST" action="">
            <div class="filter-item">
                <label for="price_range">Price Range (KES):</label>
                <input type="number" id="price_range" name="price_range" class="filter-input" placeholder="Enter max price">
            </div>
            <div class="filter-item">
                <label for="distance">Distance to Campus (KM):</label>
                <input type="number" id="distance" name="distance" class="filter-input" placeholder="Enter Distance">
            </div>
            <div class="filter-item">
                <label for="location">Location:</label>
                <select id="location" name="location" class="filter-input">
                    <option value="" disabled selected>Select a location</option>
                    <option value="Waridi">Waridi</option>
                    <option value="Mamlaka">Mamlaka</option>
                    <option value="Jaal">Jaal</option>
                    <option value="Student Center">Student Center</option>
                    <option value="Walcon">Walcon</option>
                    <Option value="Milai">Milai</Option>
                    <option value="Elion">Elion</option>
                    <option value="Docker Hostels">Docker Hostels</option>
                    <option value="Manhattan">Manhattan</option>
                </select>
            </div><br>
            <button type="submit" name="apply_filters" class="filter-button">Apply Filters</button>
        </form>
    </section>

    <!-- Listings Section -->
    <section class="listings-section">

        <?php
        
        // Database connection
        $conn = new mysqli("localhost", "root", "password", "student_housing");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Base SQL query
        $sql = "SELECT * FROM vacancies WHERE 1=1 And is_booked = 0";

        // Handle Filters
        if (isset($_POST['apply_filters'])) {
            // Filter by Price Range
            if (!empty($_POST['price_range'])) {
                $price_range = $_POST['price_range'];
                $sql .= " AND price = " . intval($price_range);
            }
            // Filter by Distance
            if (!empty($_POST['distance'])) {
                $distance = $_POST['distance'];
                $sql .= " AND distance_km = " . intval($distance);
            }
            // Filter by Location
            if (!empty($_POST['location'])) {
                $location = $conn->real_escape_string($_POST['location']);
                $sql .= " AND location = '$location'";
            }
        }

        // Handle General Search
        if (isset($_POST['search_button'])) {
            if (!empty($_POST['search_query'])) {
                $search_query = $conn->real_escape_string($_POST['search_query']);
                $sql .= " AND (type LIKE '%$search_query%' OR location LIKE '%$search_query%')";
            }
        }

        // Execute Query
        $result = $conn->query($sql);

        // Display Vacancies
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="listings">
                        <img src="' . $row["image_path"] . '" alt="House Image" class="house-image">
                        <div class="listing-details">
                            <p><strong><ID:</strong> ' . $row["id"] . '</p>
                            <p><strong>Type:</strong> ' . htmlspecialchars($row["type"]) . '</p>
                            <p><strong>Location:</strong> ' . htmlspecialchars($row["location"]) . '</p>
                            <p><strong>Price:</strong> Ksh ' . htmlspecialchars($row["price"]) . '</p>
                            <p><strong>Distance to Campus:</strong> ' . htmlspecialchars($row["distance_km"]) . ' Km</p>
                            <p><strong>Landlord Contact:</strong> ' . htmlspecialchars($row["landlord_contact"]) . '</p>
                        </div>
                            <button type="submit" class="view-more-button">Book Now</button>
                    </div>';
            }
        } else {
            echo "<p>No results found.</p>";
        }

        $conn->close();
        ?>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <p class="footer-text">2024 Student Housing Finder. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>


