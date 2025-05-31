<?php
        include("../phpConfig/constants.php");

        // Fetch infrastructures
        $sql = "SELECT * FROM infrastructure";
        $result = mysqli_query($conn, $sql);
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Football Fields</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
            <style>
                :root {
                    --background: #f0f0f0; /* Light grey background */
                    --navbar-width: 256px;
                    --navbar-width-min: 80px;
                    --navbar-dark-primary: #004d40; /* Dark teal for navbar */
                    --navbar-dark-secondary: #00796b; /* Teal for navbar highlights */
                    --navbar-light-primary: #ffffff; /* White text */
                    --navbar-light-secondary: #b2dfdb; /* Light teal for secondary text */
                    --accent-color: #ffab00; /* Amber for accents (buttons, etc.) */
                }

                html, body {
                    margin: 0;
                    background: var(--background);
                    font-family: 'Roboto', sans-serif; /* Modern, clean font */
                }

                #nav-toggle:checked ~ #nav-header {
                    width: calc(var(--navbar-width-min) - 16px);
                }

                #nav-toggle:checked ~ #nav-content, #nav-toggle:checked ~ #nav-footer {
                    width: var(--navbar-width-min);
                }

                #nav-toggle:checked ~ #nav-header #nav-title {
                    opacity: 0;
                    pointer-events: none;
                    transition: opacity .1s;
                }

                #nav-toggle:checked ~ #nav-header label[for="nav-toggle"] {
                    left: calc(50% - 8px);
                    transform: translate(-50%);
                }

                #nav-toggle:checked ~ #nav-header #nav-toggle-burger {
                    background: var(--navbar-light-primary);
                }

                #nav-toggle:checked ~ #nav-header #nav-toggle-burger:before, #nav-toggle:checked ~ #nav-header #nav-toggle-burger::after {
                    width: 16px;
                    background: var(--navbar-light-secondary);
                    transform: translate(0, 0) rotate(0deg);
                }

                #nav-toggle:checked ~ #nav-content .nav-button span {
                    opacity: 0;
                    transition: opacity .1s;
                }

                #nav-toggle:checked ~ #nav-content .nav-button .fas {
                    min-width: calc(100% - 16px);
                }

                #nav-toggle:checked ~ #nav-footer #nav-footer-avatar {
                    margin-left: 0;
                    left: 50%;
                    transform: translate(-50%);
                }

                #nav-toggle:checked ~ #nav-footer #nav-footer-titlebox, #nav-toggle:checked ~ #nav-footer label[for="nav-footer-toggle"] {
                    opacity: 0;
                    transition: opacity .1s;
                    pointer-events: none;
                }

                #nav-bar {
                    position: absolute;
                    left: 1vw;
                    top: 1vw;
                    height: calc(100% - 2vw);
                    background: var(--navbar-dark-primary);
                    border-radius: 16px;
                    display: flex;
                    flex-direction: column;
                    color: var(--navbar-light-primary);
                    font-family: Verdana, Geneva, Tahoma, sans-serif;
                    overflow: hidden;
                    user-select: none;
                }

                #nav-bar hr {
                    margin: 0;
                    position: relative;
                    left: 16px;
                    width: calc(100% - 32px);
                    border: none;
                    border-top: solid 1px var(--navbar-dark-secondary);
                }

                #nav-bar a {
                    color: inherit;
                    text-decoration: inherit;
                }

                #nav-bar input[type="checkbox"] {
                    display: none;
                }

                #nav-header {
                    position: relative;
                    width: var(--navbar-width);
                    left: 16px;
                    width: calc(var(--navbar-width) - 16px);
                    min-height: 80px;
                    background: var(--navbar-dark-primary);
                    border-radius: 16px;
                    z-index: 2;
                    display: flex;
                    align-items: center;
                    transition: width .2s;
                }

                #nav-header hr {
                    position: absolute;
                    bottom: 0;
                }

                #nav-title {
                     font-size: 1.5rem;
                     transition: opacity 1s;
                     display: flex; /* Add flexbox for icon alignment */
                     align-items: center; /* Vertically center the icon */
                 }

                 #nav-title i {
                     margin-right: 0.5rem; /* Add spacing between the icon and text */
                 }

                label[for="nav-toggle"] {
                    position: absolute;
                    right: 0;
                    width: 3rem;
                    height: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                }

                #nav-toggle-burger {
                    position: relative;
                    width: 16px;
                    height: 2px;
                    background: var(--navbar-dark-primary);
                    border-radius: 99px;
                    transition: background .2s;
                }

                #nav-toggle-burger:before, #nav-toggle-burger:after {
                    content: '';
                    position: absolute;
                    top: -6px;
                    width: 10px;
                    height: 2px;
                    background: var(--navbar-light-primary);
                    border-radius: 99px;
                    transform: translate(2px, 8px) rotate(30deg);
                    transition: .2s;
                }

                #nav-toggle-burger:after {
                    top: 6px;
                    transform: translate(2px, -8px) rotate(-30deg);
                }

                #nav-content {
                    margin: -16px 0;
                    padding: 16px 0;
                    position: relative;
                    flex: 1;
                    width: var(--navbar-width);
                    background: var(--navbar-dark-primary);
                    box-shadow: 0 0 0 16px var(--navbar-dark-primary);
                    direction: rtl;
                    overflow-x: hidden;
                    transition: width .2s;
                }

                #nav-content::-webkit-scrollbar {
                    width: 8px;
                    height: 8px;
                }

                #nav-content::-webkit-scrollbar-thumb {
                    border-radius: 99px;
                    background-color: #D62929;
                }

                #nav-content::-webkit-scrollbar-button {
                    height: 16px;
                }

                #nav-content-highlight {
                    position: absolute;
                    left: 16px;
                    top: -54px; /* Adjusted initial top */
                    width: calc(100% - 16px);
                    height: 54px;
                    background: var(--accent-color); /* Amber highlight */
                    background-attachment: fixed;
                    border-radius: 16px 0 0 16px;
                    transition: top .2s;
                }

                #nav-content-highlight:before, #nav-content-highlight:after {
                    content: '';
                    position: absolute;
                    right: 0;
                    bottom: 100%;
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    box-shadow: 16px 16px var(--accent-color);
                }

                #nav-content-highlight:after {
                    top: 100%;
                    box-shadow: 16px -16px var(--accent-color);
                }

                 .nav-button {
                     position: relative;
                     margin-left: 16px;
                     height: 54px;
                     display: flex;
                     align-items: center;
                     color: var(--navbar-light-secondary);
                     direction: ltr;
                     cursor: pointer;
                     z-index: 1;
                     transition: color .2s;
                 }

                 .nav-button span {
                     transition: opacity 1s;
                     margin-left: 0.5rem; /* Add spacing between icon and text */
                 }

                 .nav-button .fas {
                     min-width: 3rem;
                     text-align: center;
                 }

                <?php for ($i = 1; $i <= 8; $i++): ?>
                .nav-button:nth-of-type(<?php echo $i; ?>):hover {
                    color: var(--navbar-light-primary);
                }
                .nav-button:nth-of-type(<?php echo $i; ?>):hover ~ #nav-content-highlight {
                    top: <?php echo ($i - 1) * 54 + 16; ?>px;
                }
                <?php endfor; ?>

                #nav-bar .fas {
                    min-width: 3rem;
                    text-align: center;
                }

                #nav-footer {
                    position: relative;
                    width: var(--navbar-width);
                    height: 54px;
                    background: var(--navbar-dark-secondary);
                    border-radius: 16px;
                    display: flex;
                    flex-direction: column;
                    z-index: 2;
                    transition: width .2s, height .2s;
                }

                #nav-footer-heading {
                    position: relative;
                    width: 100%;
                    height: 54px;
                    display: flex;
                    align-items: center;
                }

                #nav-footer-avatar {
                    position: relative;
                    margin: 11px 0 11px 16px;
                    left: 0;
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    overflow: hidden;
                    transform: translate(0);
                    transition: .2s;
                }

                #nav-footer-avatar img {
                    height: 100%;
                }

                #nav-footer-titlebox {
                    position: relative;
                    margin-left: 16px;
                    width: 10px;
                    display: flex;
                    flex-direction: column;
                    transition: opacity 1s;
                }

                #nav-footer-subtitle {
                    color: var(--navbar-light-secondary);
                    font-size: .6rem;
                }

                #nav-toggle:not(:checked) ~ #nav-footer-toggle:checked + #nav-footer {
                    height: 30%;
                    min-height: 54px;
                }

                #nav-toggle:not(:checked) ~ #nav-footer-toggle:checked + #nav-footer label[for="nav-footer-toggle"] {
                    transform: rotate(180deg);
                }

                label[for="nav-footer-toggle"] {
                    position: absolute;
                    right: 0;
                    width: 3rem;
                    height: 100%;
                    display: flex;
                    align-items: center;
                    cursor: pointer;
                    transition: transform .2s, opacity .2s;
                }

                #nav-footer-content {
                    margin: 0 16px 16px 16px;
                    border-top: solid 1px var(--navbar-light-secondary);
                    padding: 16px 0;
                    color: var(--navbar-light-secondary);
                    font-size: .8rem;
                    overflow: auto;
                }

                #nav-footer-content::-webkit-scrollbar {
                    width: 8px;
                    height: 8px;
                }

                #nav-footer-content::-webkit-scrollbar-thumb {
                    border-radius: 99px;
                    background-color: #D62929;
                }

                .container {
                    margin-left: 280px;
                    padding: 20px;
                }

                .main-content {
                    background: #fff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                .field-grid {
                    display: flex;
                    flex-wrap: wrap;
                    margin-right: -20px; /* Adjust for card spacing */
                }

                .row {
                    display: flex;
                    flex-wrap: wrap;
                    margin-right: -20px; /* Adjust for card spacing */
                }

                .col-md-4 {
                    width: calc(33.33% - 20px);
                    margin-right: 20px;
                    margin-bottom: 20px;
                    box-sizing: border-box;
                }

                .card {
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
                    display: flex;  /* Use flexbox for card layout */
                    flex-direction: column; /* Stack image and body */
                }

                .card-img-top {
                    width: 100%;
                    height: 200px; /* Fixed height for image */
                    object-fit: cover; /* Ensure image fills the space */
                    display: block;
                }

                .card-body {
                    padding: 15px;
                    flex: 1; /* Allow body to fill remaining space */
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between; /* Space content evenly */
                }

                .card-title {
                    font-size: 1.25rem;
                    margin-bottom: 10px;
                    color: #333;
                }

                .card-text {
                    font-size: 1rem;
                    color: #555;
                    margin-bottom: 15px;
                }

                .btn {
                    display: inline-block;
                    padding: 8px 12px;
                    font-size: 1rem;
                    font-weight: 400;
                    text-align: center;
                    text-decoration: none;
                    border-radius: 0.25rem;
                    cursor: pointer;
                    transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out, color 0.2s ease-in-out;
                }

                .btn-secondary {
                    color: #fff;
                    background-color: #6c757d;
                    border-color: #6c757d;
                }

                .btn-secondary:hover {
                    background-color: #545b62;
                    border-color: #4e555b;
                }

                .btn-info {
                    color: #fff;
                    background-color: var(--accent-color); /* Use accent color for reserve button */
                    border-color: var(--accent-color);
                }

                .btn-info:hover {
                    background-color: #e0a000;
                    border-color: #d39500;
                }

                h1 {
                    color: #333;
                    border-bottom: 2px solid var(--accent-color);
                    padding-bottom: 0.5em;
                }
                /* Example Football Icons - Replace with actual URLs or inline SVGs */
                .fa-futbol:before { content: "\f1e3"; } /* Unicode for football icon */
                .fa-calendar-alt:before { content: "\f073"; } /* Unicode for calendar icon */
                .fa-comments:before { content: "\f086"; } /* Unicode for comments/feedback icon */
                .fa-exclamation-triangle:before { content: "\f071"; } /* Unicode for report problem icon */
                .fa-cog:before { content: "\f013"; } /* Unicode for parameters/settings icon */
                .fa-history:before { content: "\f1da"; } /* Unicode for history icon */
                .fa-sign-out-alt:before { content: "\f2f5"; } /* Unicode for logout icon */
                
            </style>
        </head>
        <body>

            <div id="nav-bar">
                <input id="nav-toggle" type="checkbox">
                <div id="nav-header">
                    <a id="nav-title" href="#" target="_blank">
                        <i class="fas fa-futbol"></i>  Football Fields
                    </a>
                    <label for="nav-toggle">
                        <span id="nav-toggle-burger"></span>
                    </label>
                    <hr>
                </div>
                 <div id="nav-content">
                     <a href="#" class="nav-button"><i class="fas fa-home"></i><span>Home</span></a>
                     <a href="#" class="nav-button"><i class="fas fa-calendar-alt"></i><span>Reserve Field</span></a>
                     <a href="#" class="nav-button"><i class="fas fa-comments"></i><span>Feedback</span></a>
                     <a href="#" class="nav-button"><i class="fas fa-exclamation-triangle"></i><span>Report a Problem</span></a>
                     <a href="#" class="nav-button"><i class="fas fa-cog"></i><span>Parameters</span></a>
                     <a href="#" class="nav-button"><i class="fas fa-history"></i><span>Historic</span></a>
                     <a href="#" class="nav-button"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
                     <div id="nav-content-highlight"></div>
                 </div>
                <input id="nav-footer-toggle" type="checkbox">
                <div id="nav-footer">
                    <div id="nav-footer-heading">
                        <div id="nav-footer-avatar">
                            <img src="https://gravatar.com/avatar/4474ca42d303761c2901fa819c4f2547" alt="User Avatar">
                        </div>
                        <div id="nav-footer-titlebox">
                            <a id="nav-footer-title" href="#" target="_blank">user</a>
                            <span id="nav-footer-subtitle">User</span>
                        </div>
                        <label for="nav-footer-toggle">
                            <i class="fas fa-caret-up"></i>
                        </label>
                    </div>
                    <div id="nav-footer-content">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    </div>
                </div>
            </div>

            <div class="container">
                <main class="main-content">
                    <h1>Available Football Fields</h1>
                    <div class="field-grid">
                        <div class="row">
                            <?php
                            // SQL query using prepared statements to fetch infrastructure data
                            $sql = "SELECT id,name,description,location,image_name FROM infrastructure";

                            // Prepare the SQL statement
                            $stmt = mysqli_prepare($conn, $sql);

                            // Execute the statement
                            mysqli_stmt_execute($stmt);

                            // Bind the result to variables (make sure the number of variables matches the number of columns)
                            mysqli_stmt_bind_result($stmt,$id,$name,$description,$location,$image_name);

                            // Check if there are results
                            while (mysqli_stmt_fetch($stmt)) {
                                ?>
                                <div class="col-md-4">
                                    <div class="card">
                                        <img src="../img/infrastructure/<?php echo htmlspecialchars($image_name); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($name); ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($name); ?></h5>
                                            <p class="card-text"><?php echo htmlspecialchars($description); ?></p>
                                            <div class="d-flex justify-content-between">
                                                <a href="<?php echo SITEURL;?>InfrastructureManage/see-more.php?id=<?php echo $id;?>&image_name=<?php echo $image_name;?>" class="btn btn-secondary">See More</a>
                                                <a href="<?php echo SITEURL;?>reservationManage/calendar.php?infrastructure_id=<?php echo $id;?>" class="btn btn-info">Reserve</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </main>
            </div>
<!-- Chatbot UI -->
<div id="chatbot-icon" onclick="toggleChat()">💬</div>
<div id="chatbot-window" style="display:none;">
  <div id="chatbot-messages" style="height:300px; overflow:auto; border:1px solid #ccc; padding:10px;"></div>
  <input id="chatbot-input" type="text" placeholder="Ask something..." onkeypress="handleChatKey(event)">
</div>

<!-- Chatbot Styles (optional: move to external CSS file) -->
<style>
#chatbot-icon {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: #007bff;
  color: white;
  border-radius: 50%;
  padding: 10px;
  cursor: pointer;
  font-size: 24px;
}
#chatbot-window {
  position: fixed;
  bottom: 70px;
  right: 20px;
  width: 300px;
  background: white;
  border-radius: 10px;
  padding: 10px;
  box-shadow: 0 0 10px rgba(0,0,0,0.2);
}
</style>

<!-- Chatbot Script -->
<script src="chatbot.js"></script> <!-- This will be replaced below -->

        </body>
        </html