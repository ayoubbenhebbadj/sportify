<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sportify - Find & Book Sports Facilities</title>
    <link rel="stylesheet" href="../style/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    </head>
<body>
    <?php
    // Include your existing database connection file
    // Ensure this path is correct relative to where index.php is located
    require_once '../phpconfig/constants.php';

    // Check if the connection variable ($conn) is available and successful
    // In mysqli_connect, if connection fails, it returns false or triggers a die().
    // So, we just check if $conn exists and is an object.
    if (!isset($conn) || !$conn) {
        // In a production environment, you might log this error and show a generic message
        // For development, we'll show a more direct error.
        echo '<div style="color: red; padding: 10px; border: 1px solid red; margin: 20px;">';
        echo '<strong>Database Connection Error:</strong> Could not connect to the database. Please check `../phpconfig/constants.php`.';
        // If $conn is false, there's no ->connect_error, so we just show a generic message.
        echo '</div>';
        // You might want to exit here if database connection is critical for page rendering
        // exit();
    }
    ?>

    <nav>
        <div class="logo">
            <a href="#"><img src="../img/logo.png" alt="Sportify Logo" onerror="this.onerror=null;this.src='https://placehold.co/100x50/cccccc/000000?text=Logo+Not+Found';" /></a>
        </div>
        <ul class="nav_links">
            <li class="links"><a href="#" class="active">Home</a></li>
            <li class="links"><a href="#terrain">Terrain</a></li>
            <li class="links"><a href="#reservations">Reservation</a></li>
            <li class="links"><a href="#services">Service</a></li>
            <li class="links"><a href="#about">About</a></li>
            <li class="links"><a href="#contact">Contact us</a></li>
        </ul>
        <a href="login.php" class="btn">Log in</a> </nav>

    <section class="hero" id="hero">
        <div class="welcome-img">
            <img src="../img/football-arena.jpg" alt="Football Arena" onerror="this.onerror=null;this.src='https://placehold.co/1200x400/cccccc/000000?text=Hero+Image+Not+Found';">
        </div>
        <div class="writing-section">
            <div class="title">
                <h1> <I> TIME TO BOOK <br>
                    EASY & SIMPLE</I></h1>
                <p>Find and reserve stadiums,
                    sports fields, and pools with just a
                    few clicks. Enjoy seamless booking and
                    real-time availability.</p>
            </div>
            <a href="#reservations">Get Started</a>
        </div>
    </section>

    <div class="reversed" id="terrain">
        <h1>Our Stadiums</h1><br>
        <div class="stadium-container" id="reservations">
            <?php
            // Check if $conn connection is established before querying
            if (isset($conn) && $conn) { // Ensure $conn is not false
                // SQL query to select stadiums where 'available' is 1 and 'state' is 'Good'
                $sql = "SELECT id, name, type, description, location, image_name FROM infrastructure WHERE available = 1 AND state = 'Good'";
                $result = mysqli_query($conn, $sql); // Use mysqli_query with $conn

                if ($result) {
                    if (mysqli_num_rows($result) > 0) { // Use mysqli_num_rows
                        // Loop through each stadium and generate its HTML
                        while ($stadium = mysqli_fetch_assoc($result)) { // Use mysqli_fetch_assoc
                            // You might need to add specific 'capacity' and 'surface' columns
                            // to your 'infrastructure' table if you want dynamic values here.
                            // For now, using placeholders or extracting from description if possible.
                            $capacity = 'N/A'; // Placeholder
                            $surface = ($stadium['type'] === 'Football') ? 'Natural Grass/Artificial Turf' : 'N/A'; // Example based on type

                            echo '<div class="stadium">';
                            // Updated path for dynamically loaded stadium images
                            echo '    <img src="../img/infrastructure/' . htmlspecialchars($stadium['image_name']) . '" alt="' . htmlspecialchars($stadium['name']) . '" loading="lazy" onerror="this.onerror=null;this.src=\'https://placehold.co/300x200/cccccc/000000?text=Stadium+Image+Not+Found\';">';
                            echo '    <p>' . htmlspecialchars($stadium['name']) . '</p>';
                            echo '    <button class="view-more-btn">View More</button>';
                            echo '    <div class="stadium-details">';
                            echo '        <p>Type: ' . htmlspecialchars($stadium['type']) . '</p>';
                            echo '        <p>Location: ' . htmlspecialchars($stadium['location']) . '</p>';
                            echo '        <p>Description: ' . htmlspecialchars($stadium['description']) . '</p>';
                            echo '        <p>Capacity: ' . htmlspecialchars($capacity) . '</p>'; // Using placeholder
                            echo '        <p>Surface: ' . htmlspecialchars($surface) . '</p>'; // Using placeholder
                            echo '        <button class="book-now-btn" data-stadium-id="' . htmlspecialchars($stadium['id']) . '">Book Now</button>';
                            echo '    </div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No available stadiums found at the moment.</p>';
                    }
                    mysqli_free_result($result); // Use mysqli_free_result
                } else {
                    echo '<p>Error retrieving stadiums from the database. Please try again later.</p>';
                    error_log("SQL Error: " . mysqli_error($conn)); // Log the actual SQL error using $conn
                }
            } else {
                echo '<p>Stadiums cannot be loaded due to a database connection issue.</p>';
            }
            ?>
        </div>
    </div>

    <div class="rev" id="reviews">
        <h1> What people say </h1>
        <section class="reviews">
            <div class="review">
                <div class="profile">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User 1" loading="lazy" onerror="this.onerror=null;this.src='https://placehold.co/100x100/cccccc/000000?text=User+Image';">
                </div>
                <h3>Mohamed Benaraba</h3>
                <p class="job-title">Football Coach</p>
                <p class="stars">⭐⭐⭐⭐⭐</p>
                <p class="review-text">"The field is well maintained, and it's great for training my team!"</p>
            </div>

            <div class="review">
                <div class="profile">
                    <img src="https://i.pravatar.cc/100?img=12" alt="User 2" loading="lazy" onerror="this.onerror=null;this.src='https://placehold.co/100x100/cccccc/000000?text=User+Image';">
                </div>
                <h3>Ali ait ali</h3>
                <p class="job-title">Football player</p>
                <p class="stars">⭐⭐⭐⭐⭐</p>
                <p class="review-text">"I love playing here with my friends after school. The turf is amazing!"</p>
            </div>

            <div class="review">
                <div class="profile">
                    <img src="https://randomuser.me/api/portraits/men/50.jpg" alt="User 3" loading="lazy" onerror="this.onerror=null;this.src='https://placehold.co/100x100/cccccc/000000?text=User+Image';">
                </div>
                <h3>Omar Benamoun</h3>
                <p class="job-title">Football Enthusiast</p>
                <p class="stars">⭐⭐⭐⭐⭐</p>
                <p class="review-text">"Best football field I've played on. Great for weekend matches!"</p>
            </div>
        </section>
    </div>

    <section id="services" class="services-section">
        <h1>Our Services</h1>
        <div class="services-container">
            <div class="service-card">
                <i class="bi bi-calendar-check"></i>
                <h3>Easy Booking</h3>
                <p>Reserve your favorite sports facilities with just a few clicks. Our intuitive platform makes booking quick and hassle-free.</p>
            </div>
            <div class="service-card">
                <i class="bi bi-geo-alt"></i>
                <h3>Wide Range of Locations</h3>
                <p>Discover stadiums, fields, and pools in various locations. Find the perfect venue near you for your sporting activities.</p>
            </div>
            <div class="service-card">
                <i class="bi bi-clock"></i>
                <h3>Real-Time Availability</h3>
                <p>Check the availability of facilities in real-time. Plan your games and practices with up-to-date scheduling information.</p>
            </div>
            <div class="service-card">
                <i class="bi bi-credit-card"></i>
                <h3>Secure Payment</h3>
                <p>Enjoy secure and reliable payment options for your bookings. Our platform ensures your transaction details are protected.</p>
            </div>
            <div class="service-card">
                <i class="bi bi-chat-dots"></i>
                <h3>Customer Support</h3>
                <p>Our dedicated customer support team is here to assist you with any inquiries or issues. Contact us for prompt and helpful assistance.</p>
            </div>
            <div class="service-card">
                <i class="bi bi-star-fill"></i>
                <h3>Verified Venues</h3>
                <p>We partner with verified and well-maintained sports facilities to ensure you have a quality sporting experience.</p>
            </div>
        </div>
    </section>

    <section id="contact" class="contact-section">
        <h1>Contact Us</h1>
        <div class="contact-container">
            <div class="contact-info">
                <h2>Get in Touch</h2>
                <p>Have questions or need assistance? Feel free to reach out to us.</p>
                <ul class="contact-list">
                    <li><i class="bi bi-envelope"></i> Email: contact@Sportify.com</li>
                    <li><i class="bi bi-phone"></i> Phone: +213 5 49 42 15 7</li>
                    <li><i class="bi bi-geo-alt"></i> Address: Algiers, Algeria</li>
                </ul>
            </div>
            <div class="contact-form">
                <h2>Send us a Message</h2>
                <form id="contactForm" action="backend/send_message.php" method="POST">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <footer id="about">
        <div class="footer-container">
            <div class="footer-section about">
                <h2>About Us</h2>
                <p>Book stadiums, sports fields, and pools easily with our online platform. Enjoy seamless reservations and real-time availability.</p>
            </div>

            <div class="footer-section links">
                <h2>Quick Links</h2>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#terrain">Terrain</a></li>
                    <li><a href="#reservations">Reservation</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                </ul>
            </div>

            <div class="footer-section contact">
                <h2>Contact</h2>
                <p>Email: contact@Sportify.com</p>
                <p>Phone: +213 5 49 42 15 7</p>
                <div class="social-icons">
                    <a href="#"><img src="https://cdn-icons-png.flaticon.com/128/733/733547.png" alt="Facebook" onerror="this.onerror=null;this.src='https://placehold.co/32x32/cccccc/000000?text=FB';"></a>
                    <a href="#"><img src="https://cdn-icons-png.flaticon.com/128/733/733558.png" alt="Twitter" onerror="this.onerror=null;this.src='https://placehold.co/32x32/cccccc/000000?text=TW';"></a>
                    <a href="#"><img src="https://cdn-icons-png.flaticon.com/128/733/733579.png" alt="Instagram" onerror="this.onerror=null;this.src='https://placehold.co/32x32/cccccc/000000?text=IG';"></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 Sportify. All rights reserved.</p>
        </div>
    </footer>

    <script src="../js/scripts.js"></script>
    <?php
    // Close the database connection at the end of the page rendering
    // Only close if the connection was successfully established
    if (isset($conn) && $conn) {
        mysqli_close($conn); // Use mysqli_close with $conn
    }
    ?>
</body>
</html>
