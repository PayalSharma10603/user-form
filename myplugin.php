<?php
/*
Plugin Name: My Custom User Form Plugin
Description: A plugin to create a custom form and save data in a custom table.
Version: 1.0
Author: Your Name
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

register_activation_hook(__FILE__, 'myplugin_create_table');

function myplugin_create_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'myplugin_users';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        email varchar(255) NOT NULL UNIQUE,
        age int(3) NOT NULL,
        gender varchar(10) NOT NULL,
        whatsapp varchar(20) NOT NULL,
        city varchar(100) NOT NULL,
        state varchar(100) NOT NULL,
        country varchar(100) NOT NULL,
        stotra text(100) NOT NULL,
        language text(20) NOT NULL,
        exam_time text(50) NOT NULL,
        special_requirements text NOT NULL,
        score int DEFAULT 0 NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


add_shortcode('myplugin_user_form', 'myplugin_user_form_shortcode');

function myplugin_user_form_shortcode() {
    ob_start();

    $error_message = '';
    if (isset($_GET['error'])) {
        switch ($_GET['error']) {
            case 'email_exists':
                $error_message = '<p style="color: red;">This email is already taken. Please use a different email.</p>';
                break;
            case 'invalid_whatsapp':
                $error_message = '<p style="color: red;">WhatsApp number must be exactly 10 digits.</p>';
                break;
        }
    }

    $success_message = '';
    if (isset($_GET['success']) && $_GET['success'] == '1') {
        $success_message = '<p style="color: green; font-size: 26px;">Thank you for your submission.</p>';
    }
    ?>

    <!-- Your form HTML with added span for error messages -->
     
    <?php echo $success_message; ?>
    <div class="poster-image">
        <img style="width: 100%; height: 180px; border-radius: 10px;" src="https://lh6.googleusercontent.com/RF0GkHpkfWgM4FjXrJol_xrnv-agvnCTJEKGW7YYx3VOVcRo_ZWWh72VY180sNHxBRYKJ2CUga57qHa7G09Ik71QTjpYB-tpN_VjOlR9V3KR_7UIiTcUxMqsC82DgQt94g=w1280" alt="Background Image" />
    </div>
    <style>
        .wp-block-group.has-global-padding.is-layout-constrained.wp-block-group-is-layout-constrained {
            margin-bottom: -120px;
        }
        .entry-content.wp-block-post-content.has-global-padding.is-layout-constrained.wp-block-post-content-is-layout-constrained {
            margin-left: -150px;
        }
        .error-msg {
            color: red;
            margin-top: 5px;
            display: block;
        }
        .success-msg {
            color: green;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .field-label {
            margin-bottom: 5px;
            display: block;
            font-weight: bold;
            padding-top: 10px;
            padding-bottom: 10px;
            font-family: 'docs-Book Antiqua';
            font-weight: bold;
            font-size: 14pt;
            line-height: 1.5;
            letter-spacing: 0;
        }
        .field-label-checkbox {
            margin-bottom: -20px;
            display: block;
            font-weight: bold;
            padding-top: 10px;
            padding-bottom: 10px;
            font-family: 'docs-Book Antiqua';
            font-weight: bold;
            font-size: 14pt;
            line-height: 1.5;
            letter-spacing: 0;
        }
        .field-group {
            /* border: 1px solid black; */
            margin-bottom: 20px;
            padding-left: 20px;
            border-radius: 10px;
            padding-top: 20px;
            padding-bottom: 20px;
            border: 1px solid rgb(218, 220, 224);
        }
        .field-group-checkbox {
            /* border: 1px solid black; */
            border: 1px solid rgb(218, 220, 224);
            margin-bottom: 20px;
            padding-left: 20px;
            border-radius: 10px;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        input:not([type="checkbox"]):not([type="submit"]) {
            border-top: none;
            border-left: none;
            border-right: none;
            width: 50%;
            border-bottom: 1px solid rgb(218, 220, 224);
        }
        input[type="submit"] {
            width: 15%;
            padding: 10px;
            background-color: rgb(153, 122, 0);
            border-radius: 8px;
            color: white;
            cursor: pointer;
            font-size: 15px;
            font-weight: bold;
            border:none;
        }
        select#gender {
            width: 30%;
            padding: 10px;
            border: 1px solid rgb(218, 220, 224);
        }
        button {
            border: none;
            color: rgb(153, 122, 0);
            cursor: pointer;
            margin-left: 430px;
            font-size: 15px;
            font-weight: bold;
        }
    </style>

    <div class="first-div">
        <b><h2>भागवत मुखस्थ परीक्षा<br>
        Gopi Geet/Rudra<br> Geet/Prahlad Stuti</h2></b>
    
        भागवत मुखस्थ परीक्षा का आयोजन श्रीमद् भागवत महापुराण में आने वाली कुछ महत्वपूर्ण, भक्ति- मुक्ति दायक, सुंदर स्तुतियों के मुखस्थीकरण हेतु किया गया है।<br><br>
        परीक्षा की तिथि इस प्रकार रहेगी :-<br><br>
        Gopi Geet :- Sunday, 6 th October 2024<br>
        Rudra Geet :- Sunday 13 th October 2024<br>
        Prahlad Stuti :- Saturday 19th October 2024
   <br>
        <a href="http://www.smbrk.org" target="_blank">www.smbrk.org</a>
    </div>



    <form method="post" id="myForm" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return validateForm();">
        <?php wp_nonce_field('myplugin_form_submit', 'myplugin_form_nonce'); ?>
        <input type="hidden" name="action" value="myplugin_user_form">

        <div class="field-group">
            <label for="email" class="field-label">Email <span style="color: red;">*</span></label>
            <input type="email" id="email" name="email" placeholder="Your answer" required>
            <span class="error-msg" id="email-error"><?php echo $error_message; ?></span>
        </div>
        <div class="field-group">
            <label for="name" class="field-label">Name / नाम: <span style="color: red;">*</span></label>
            <input type="text" id="name" name="name" placeholder="Your answer" required>
            <span class="error-msg" id="name-error"></span>
        </div>

        <div class="field-group">
            <label for="age" class="field-label">Age / आयु: <span style="color: red;">*</span></label>
            <input type="number" id="age" name="age" placeholder="Your answer" required>
            <span class="error-msg" id="age-error"></span>
        </div>

        <div class="field-group">
            <label for="gender" class="field-label">Gender / लिंग: <span style="color: red;">*</span></label>
            <select id="gender" name="gender" required>
                <option value="" disabled selected hidden>Choose</option>
                <option value="Man">Man</option>
                <option value="Woman">Woman</option>
            </select>
            <span class="error-msg" id="gender-error"></span>
        </div>

        <div class="field-group">
            <label for="whatsapp" class="field-label">WhatsApp Number / व्हाट्सएप नंबर: <span style="color: red;">*</span></label>
            <input type="text" id="whatsapp" name="whatsapp" placeholder="Your answer" required>
            <span class="error-msg" id="whatsapp-error"></span>
        </div>

        <div class="field-group">
            <label for="city" class="field-label">City / शहर: <span style="color: red;">*</span></label>
            <input type="text" id="city" name="city" placeholder="Your answer" required>
            <span class="error-msg" id="city-error"></span>
        </div>

        <div class="field-group">
            <label for="state" class="field-label">State / राज्य: <span style="color: red;">*</span></label>
            <input type="text" id="state" name="state" placeholder="Your answer" required>
            <span class="error-msg" id="state-error"></span>
        </div>

        <div class="field-group">
            <label for="country" class="field-label">Country / देश: <span style="color: red;">*</span></label>
            <input type="text" id="country" name="country" placeholder="Your answer" required>
            <span class="error-msg" id="country-error"></span>
        </div>

        <div class="field-group-checkbox">
            <label for="stotra" class="field-label-checkbox">In which Stotra would you like to give the exam? / आप किस स्तोत्र के लिए परीक्षा देना चाहते हैं? <span style="color: red;">*</span></label><br>
            <input type="checkbox" id="gopi_geet" name="stotra[]" value="Gopi Geet">
            <label for="gopi_geet">Gopi Geet</label><br>
            <input type="checkbox" id="rudra_geet" name="stotra[]" value="Rudra Geet">
            <label for="rudra_geet">Rudra Geet</label><br>
            <input type="checkbox" id="prahlad_stuti" name="stotra[]" value="Prahlad Stuti">
            <label for="prahlad_stuti">Prahlad Stuti</label>
            <span class="error-msg" id="stotra-error"></span>
        </div>

        <div class="field-group-checkbox">
            <label for="language" class="field-label-checkbox">Language / भाषा: <span style="color: red;">*</span></label><br>
            <input type="checkbox" id="hindi" name="language[]" value="Hindi">
            <label for="hindi">Hindi</label><br>
            <input type="checkbox" id="marathi" name="language[]" value="Marathi">
            <label for="marathi">Marathi</label><br>
            <input type="checkbox" id="english" name="language[]" value="English">
            <label for="english">English</label>
            <span class="error-msg" id="language-error"></span>
        </div>

        <div class="field-group-checkbox">
            <label for="exam_time" class="field-label-checkbox">Time / समय: <br> (कृपया ध्यान पूर्वक समय का चयन करें जिससे कि बाद में परीक्षा देने का समय परिवर्तित ना करना पड़े,  क्योंकि यह हमारे लिए संभव नहीं होगा)  <span style="color: red;">*</span></label><br>
            <input type="checkbox" id="morning" name="exam_time[]" value="Morning (7 AM to 9 AM)">
            <label for="morning">Morning (7 AM to 9 AM)</label><br>
            <input type="checkbox" id="afternoon" name="exam_time[]" value="Afternoon (2 PM to 4 PM)">
            <label for="afternoon">Afternoon (2 PM to 4 PM)</label><br>
            <input type="checkbox" id="evening" name="exam_time[]" value="Evening (5 PM to 7 PM)">
            <label for="evening">Evening (5 PM to 7 PM)</label>
            <span class="error-msg" id="exam_time-error"></span>
        </div>

        <div class="field-group">
            <label for="special_requirements" class="field-label">Any Special Requirement during the Exam (if Needed) /  परीक्षा के समय कोई विशेष आवश्यकता (यदि आवश्यक हो)</label>
            <input type="text" id="special_requirements" name="special_requirements" placeholder="Your answer">
            <span class="error-msg" id="special_requirements-error"></span>
        </div>

        <div class="field-group-btn">
            <input type="submit" name="submit_myplugin_form" value="Submit">
            <button type="button" onclick="document.getElementById('myForm').reset();">Clear Form</button><br><br>
        </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('myForm');
        const requiredInputs = form.querySelectorAll('[required]');
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');

        requiredInputs.forEach(function(input) {
            input.addEventListener('blur', function() {
                validateField(input);
            });
        });

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                validateCheckboxGroup();
            });
        });

        function validateField(field) {
            const errorSpan = document.getElementById(`${field.id}-error`);

            if (!errorSpan) return; // Exit if no error span is found

            if (field.value.trim() === '') {
                // If the field is empty, display a required field error
                errorSpan.textContent = 'This field is required.';
            } else if (field.id === 'whatsapp') {
                // If the field is a WhatsApp number, check for exactly 10 digits
                if (!/^\d{10}$/.test(field.value)) {
                    errorSpan.textContent = 'WhatsApp number must be exactly 10 digits.';
                } else {
                    errorSpan.textContent = '';
                }
            } else {
                // Clear error if field is not empty and not specific validation
                errorSpan.textContent = '';
            }
        }


        function validateCheckboxGroup() {
            const checkboxGroups = {
                'stotra': 'stotra-error',
                'language': 'language-error',
                'exam_time': 'exam_time-error'
            };

            Object.keys(checkboxGroups).forEach(function(group) {
                const checkboxes = form.querySelectorAll(`input[name="${group}[]"]`);
                const errorSpan = document.getElementById(checkboxGroups[group]);

                if ([...checkboxes].some(cb => cb.checked)) {
                    errorSpan.textContent = '';
                } else {
                    errorSpan.textContent = 'At least one option must be selected.';
                }
            });
        }

        form.addEventListener('submit', function(event) {
            let valid = true;
            requiredInputs.forEach(function(input) {
                validateField(input);
                if (!input.checkValidity()) {
                    valid = false;
                }
            });

            validateCheckboxGroup();

            if (!valid || [...form.querySelectorAll('.error-msg')].some(el => el.textContent)) {
                event.preventDefault();
            }
        });
    });
</script>
    <?php
    return ob_get_clean();
}


add_action('admin_post_nopriv_myplugin_user_form', 'myplugin_handle_form_submission');
add_action('admin_post_myplugin_user_form', 'myplugin_handle_form_submission');

function myplugin_handle_form_submission() {
    global $wpdb;

    // Check nonce
    if (!isset($_POST['myplugin_form_nonce']) || !wp_verify_nonce($_POST['myplugin_form_nonce'], 'myplugin_form_submit')) {
        wp_redirect(add_query_arg('error', 'invalid_nonce', wp_get_referer()));
        exit;
    }

    // Sanitize and validate input
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $age = intval($_POST['age']);
    $gender = sanitize_text_field($_POST['gender']);
    $whatsapp = sanitize_text_field($_POST['whatsapp']);
    $city = sanitize_text_field($_POST['city']);
    $state = sanitize_text_field($_POST['state']);
    $country = sanitize_text_field($_POST['country']);
    $stotra = isset($_POST['stotra']) ? array_map('sanitize_text_field', $_POST['stotra']) : [];
    $language = isset($_POST['language']) ? array_map('sanitize_text_field', $_POST['language']) : [];
    $exam_time = isset($_POST['exam_time']) ? array_map('sanitize_text_field', $_POST['exam_time']) : [];
    $special_requirements = sanitize_textarea_field($_POST['special_requirements']);

    // Convert arrays to comma-separated strings
    $stotra_str = implode(', ', $stotra);
    $language_str = implode(', ', $language);
    $exam_time_str = implode(', ', $exam_time);

    // Check if email already exists
    $table_name = $wpdb->prefix . 'myplugin_users';
    $existing_user = $wpdb->get_row($wpdb->prepare("SELECT id FROM $table_name WHERE email = %s", $email));

    if ($existing_user) {
        wp_redirect(add_query_arg('error', 'email_exists', wp_get_referer()));
        exit;
    }

    // Insert into database
    $wpdb->insert(
        $table_name,
        array(
            'name' => $name,
            'email' => $email,
            'age' => $age,
            'gender' => $gender,
            'whatsapp' => $whatsapp,
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'stotra' => $stotra_str,
            'language' => $language_str,
            'exam_time' => $exam_time_str,
            'special_requirements' => $special_requirements,
            'score' => 0
        )
    );

    wp_redirect(add_query_arg('success', '1', wp_get_referer()));
    exit;
}



// Hook to add menu page and submenu
add_action('admin_menu', 'myplugin_add_admin_menu');


// Function to create the top-level menu and submenus
function myplugin_add_admin_menu() {
    // Add top-level menu page
    add_menu_page(
        'My Plugin',                   // Page title
        'My Plugin',                   // Menu title
        'manage_options',              // Capability required to view this page
        'myplugin_main_menu',          // Menu slug
        'myplugin_main_menu_page',     // Callback function to display content (optional)
        'dashicons-admin-generic',     // Icon for the menu
        6                              // Position in the menu
    );

    // Add submenu page for User Submissions
    add_submenu_page(
        'myplugin_main_menu',          // Parent slug
        'User Submissions',            // Page title
        'User Submissions',            // Submenu title
        'manage_options',              // Capability required to view this page
        'myplugin_user_submissions',   // Submenu slug
        'myplugin_user_submissions_page' // Callback function to display content
    );

    // Add submenu page for Teachers List
    add_submenu_page(
        'myplugin_main_menu',          // Parent slug
        'Teachers List',               // Page title
        'Teachers List',               // Submenu title
        'manage_options',              // Capability required to view this page
        'myplugin_teacher_list',       // Submenu slug
        'myplugin_teacher_list_page'   // Callback function to display content
    );

    // Add submenu page for Students List
    add_submenu_page(
        'myplugin_main_menu',          // Parent slug
        'Students List',               // Page title
        'Students List',               // Submenu title
        'manage_options',              // Capability required to view this page
        'myplugin_students_list',      // Submenu slug
        'myplugin_students_list_page'    // Callback function to display content
    );

    // Add submenu page for Add Teacher
    add_submenu_page(
        'myplugin_main_menu',          // Parent slug
        'Add Teacher',                 // Page title
        'Add Teacher',                 // Submenu title
        'manage_options',              // Capability required to view this page
        'myplugin_add_teacher',        // Submenu slug
        'myplugin_add_teacher_page'    // Callback function to display content
    );

    // Add submenu page for Assign Users
    add_submenu_page(
        'myplugin_main_menu',          // Parent slug
        'Assign Users',                // Page title
        'Assign Users',                // Submenu title
        'manage_options',              // Capability required to view this page
        'myplugin_assign_users',       // Submenu slug
        'myplugin_assign_users_page'   // Callback function to display content
    );
}

// Function to display content for the main menu page (optional)
function myplugin_main_menu_page() {
    echo '<div class="wrap">';
    echo '<h1>Welcome to My Plugin</h1>';
    echo '<p>This is the main page of the My Plugin menu. Use the submenu to view user submissions.</p>';
    echo '</div>';
}


function myplugin_user_submissions_page() {
    global $wpdb;

    // Table name
    $table_name = $wpdb->prefix . 'myplugin_users';

    // Fetch all records from the table
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    // HTML to display data
    echo '<div class="wrap">';
    echo '<h1>User Submissions</h1>';
    echo '<table class="widefat fixed" cellspacing="0">';
    echo '<thead>';
    echo '<tr>';
    echo '<th class="manage-column column-columnname" scope="col">Name</th>';
    echo '<th class="manage-column column-columnname" scope="col">Email</th>';
    echo '<th class="manage-column column-columnname" scope="col">Phone</th>';
    echo '<th class="manage-column column-columnname" scope="col">Address</th>';
    echo '<th class="manage-column column-columnname" scope="col">Score</th>';
    echo '<th class="manage-column column-columnname" scope="col">Actions</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Check if there are any records
    if ($results) {
        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . esc_html($row->name) . '</td>';
            echo '<td>' . esc_html($row->email) . '</td>';
            echo '<td>' . esc_html($row->whatsapp) . '</td>';
            echo '<td>' . esc_html($row->city) . ', ' . esc_html($row->state) . '</td>';
            echo '<td>' . esc_html($row->score) . '</td>';
            echo '<td>';
            echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
            echo '<input type="hidden" name="action" value="generate_certificate">';
            echo '<input type="hidden" name="user_id" value="' . esc_attr($row->id) . '">';
            echo '<input type="submit" name="generate_certificate" value="Generate Certificate" class="button button-primary">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        // If no records found
        echo '<tr>';
        echo '<td colspan="6">No submissions found.</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}



function myplugin_generate_certificate() {
    // Check if form was submitted
    if (isset($_POST['generate_certificate']) && isset($_POST['user_id'])) {
        echo 'Form submission detected. '; // Debugging
        global $wpdb;
        $user_id = intval($_POST['user_id']);
        $users_table = $wpdb->prefix . 'myplugin_users';

        // Fetch user details from the database
        $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $users_table WHERE id = %d", $user_id));
        if ($user) {
            echo 'User found: ' . esc_html($user->name) . '. '; // Debugging

            // Path to the certificate background image
            $background_image_path = plugin_dir_path(__FILE__) . 'certificate_background.png';

            if (file_exists($background_image_path)) {
                echo 'Background image found. '; // Debugging


                // Generate the certificate
                $certificate_image = myplugin_create_certificate($background_image_path, $user->name);


                // Output the generated certificate
                header('Content-Type: image/png');
                imagepng($certificate_image);
                imagedestroy($certificate_image);
                exit;
            } else {
                echo 'Error: Background image not found at path: ' . $background_image_path;
                die();
            }
        } else {
            echo 'Error: User not found with ID: ' . $user_id;
            die();
        }
    } else {
        echo 'Form data not set correctly.';
        die();
    }
}
add_action('admin_post_generate_certificate', 'myplugin_generate_certificate');


function myplugin_create_certificate($background_image_path, $user_name) {
    // Load the background image
    if (!file_exists($background_image_path)) {
        die('Error: Background image file does not exist.');
    }

    $image = @imagecreatefrompng($background_image_path);

    if (!$image) {
        die('Error: Failed to create image from PNG. Please ensure the file is a valid PNG image.');
    }

    // Convert to truecolor if it isn't already
    if (!imageistruecolor($image)) {
        imagepalettetotruecolor($image);
    }

    // Set text color (black)
    $text_color = imagecolorallocate($image, 0, 0, 0);

    // Check if color allocation failed
    if ($text_color === false) {
        imagedestroy($image);
        die('Error: Failed to allocate color for text. Ensure there is enough memory and the image is truecolor.');
    }

    // Set the font path and size
    // $font_path = plugin_dir_path(__FILE__) . 'arial.ttf';

    // if (!file_exists($font_path)) {
    //     imagedestroy($image);
    //     die('Error: Font file not found.');
    // }

    $font_size = 20;

    // Define the position to place the text
    $x_position = 200; // Adjust based on your image dimensions
    $y_position = 200; // Adjust based on your image dimensions

    // Add the text to the image
    $bbox = imagettftext($image, $font_size, 0, $x_position, $y_position, $text_color, $font_path, $user_name);

    if (!$bbox) {
        imagedestroy($image);
        die('Error: Failed to add text to the image.');
    }

    return $image;
}


// Function to display the Add Teacher form
function myplugin_add_teacher_page() {
    ?>
    <div class="wrap">
        <h1>Add Teacher</h1>
        <form method="post" action="">
            <?php wp_nonce_field('myplugin_add_teacher', 'myplugin_add_teacher_nonce'); ?>
            
            <label for="teacher_name">Teacher Name:</label>
            <input type="text" id="teacher_name" name="teacher_name" required><br><br>

            <label for="teacher_email">Email:</label>
            <input type="email" id="teacher_email" name="teacher_email" required><br><br>

            <label for="teacher_phone">Phone:</label>
            <input type="text" id="teacher_phone" name="teacher_phone" required><br><br>

            <label for="teacher_address">Address:</label>
            <textarea id="teacher_address" name="teacher_address" required></textarea><br><br>

            <label for="teacher_password">Password:</label>
            <input type="password" id="teacher_password" name="teacher_password" required><br><br>


            <input type="submit" name="submit_teacher_form" value="Add Teacher">
        </form>
    </div>
    <?php

    // Handle form submission
    if (isset($_POST['submit_teacher_form'])) {
        myplugin_handle_add_teacher_form_submission();
    }
}


// Function to handle the Add Teacher form submission
function myplugin_handle_add_teacher_form_submission() {
    // Verify the nonce for security
    if (!isset($_POST['myplugin_add_teacher_nonce']) || !wp_verify_nonce($_POST['myplugin_add_teacher_nonce'], 'myplugin_add_teacher')) {
        wp_die('Invalid form submission');
    }

    global $wpdb;

    // Sanitize input data
    $teacher_name = sanitize_text_field($_POST['teacher_name']);
    $teacher_email = sanitize_email($_POST['teacher_email']);
    $teacher_phone = sanitize_text_field($_POST['teacher_phone']);
    $teacher_address = sanitize_textarea_field($_POST['teacher_address']);
    $teacher_password = sanitize_text_field($_POST['teacher_password']);

    // Hash the password
    $hashed_password = wp_hash_password($teacher_password);

    // Table name
    $table_name = $wpdb->prefix . 'myplugin_teachers';

    // Insert data into the database
    $wpdb->insert(
        $table_name,
        array(
            'name'    => $teacher_name,
            'email'   => $teacher_email,
            'phone'   => $teacher_phone,
            'address' => $teacher_address,
            'password' => $hashed_password
        )
    );

    echo '<p style="color: green;">Teacher added successfully!</p>';
}

// Function to display the list of teachers
function myplugin_teacher_list_page() {
    global $wpdb;

    // Table name
    $table_name = $wpdb->prefix . 'myplugin_teachers';

    // Fetch all records from the table
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    // HTML to display data
    echo '<div class="wrap">';
    echo '<h1>Teachers List</h1>';
    echo '<table class="widefat fixed" cellspacing="0">';
    echo '<thead>';
    echo '<tr>';
    echo '<th class="manage-column column-columnname" scope="col">Name</th>';
    echo '<th class="manage-column column-columnname" scope="col">Email</th>';
    echo '<th class="manage-column column-columnname" scope="col">Phone</th>';
    echo '<th class="manage-column column-columnname" scope="col">Address</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Check if there are any records
    if ($results) {
        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . esc_html($row->name) . '</td>';
            echo '<td>' . esc_html($row->email) . '</td>';
            echo '<td>' . esc_html($row->phone) . '</td>';
            echo '<td>' . esc_html($row->address) . '</td>';
            echo '</tr>';
        }
    } else {
        // If no records found
        echo '<tr>';
        echo '<td colspan="4">No teachers found.</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

// Function to display the list of students
function myplugin_students_list_page() {
    global $wpdb;

    // Table name
    $table_name = $wpdb->prefix . 'myplugin_users';

    // Fetch all records from the table
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    // HTML to display data
    echo '<div class="wrap">';
    echo '<h1>Students List</h1>';
    echo '<table class="widefat fixed" cellspacing="0">';
    echo '<thead>';
    echo '<tr>';
    echo '<th class="manage-column column-columnname" scope="col">Name</th>';
    echo '<th class="manage-column column-columnname" scope="col">Email</th>';
    echo '<th class="manage-column column-columnname" scope="col">WhatsApp</th>';
    echo '<th class="manage-column column-columnname" scope="col">City</th>';
    echo '<th class="manage-column column-columnname" scope="col">State</th>';
    echo '<th class="manage-column column-columnname" scope="col">Score</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Check if there are any records
    if ($results) {
        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . esc_html($row->name) . '</td>';
            echo '<td>' . esc_html($row->email) . '</td>';
            echo '<td>' . esc_html($row->whatsapp) . '</td>';
            echo '<td>' . esc_html($row->city) . '</td>';
            echo '<td>' . esc_html($row->state) . '</td>';
            echo '<td>' . esc_html($row->score) . '</td>';
            echo '</tr>';
        }
    } else {
        // If no records found
        echo '<tr>';
        echo '<td colspan="4">No students found.</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}


// Hook for plugin activation to create a custom table for teachers
register_activation_hook(__FILE__, 'myplugin_create_teacher_table');

// Function to create the custom table
function myplugin_create_teacher_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'myplugin_teachers';

    // SQL query to create the table if it doesn't exist
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(20) NOT NULL,
        address text NOT NULL,
        password varchar(255) NOT NULL,  -- Adjusted length for storing hashed passwords
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


// Function to display the Assign Users form
function myplugin_assign_users_page() {
    global $wpdb;

    // Check for delete action
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['assignment_id'])) {
        $assignment_id = intval($_GET['assignment_id']);
        myplugin_handle_delete_assignment($assignment_id);
    }

    // Check if we're editing an existing assignment
    $is_edit = (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['assignment_id']));
    $assignment_id = $is_edit ? intval($_GET['assignment_id']) : 0;

    // Fetch users and teachers from the database
    $users_table = $wpdb->prefix . 'myplugin_users';
    $teachers_table = $wpdb->prefix . 'myplugin_teachers';

    $users = $wpdb->get_results("SELECT id, name FROM $users_table");
    $teachers = $wpdb->get_results("SELECT id, name FROM $teachers_table");

    // For edit mode, fetch the existing assignment data
    if ($is_edit) {
        $assignment_table = $wpdb->prefix . 'myplugin_user_teacher';
        $assignment = $wpdb->get_row($wpdb->prepare("SELECT * FROM $assignment_table WHERE id = %d", $assignment_id));

        if (!$assignment) {
            echo '<p>Assignment not found.</p>';
            return;
        }

        $selected_user_id = $assignment->user_id;
        $selected_teacher_ids = $wpdb->get_col($wpdb->prepare("SELECT teacher_id FROM $assignment_table WHERE user_id = %d", $selected_user_id));
    }

    ?>
    <div class="wrap">
        <h1><?php echo $is_edit ? 'Edit Assignment' : 'Assign Users to Teachers'; ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('myplugin_assign_users', 'myplugin_assign_users_nonce'); ?>

            <label for="user_id">Select User:</label>
            <select id="user_id" name="user_id" required>
                <option value="">Select User</option>
                <?php foreach ($users as $user) { ?>
                    <option value="<?php echo esc_attr($user->id); ?>" <?php selected($is_edit && $selected_user_id == $user->id); ?>><?php echo esc_html($user->name); ?></option>
                <?php } ?>
            </select><br><br>

            <label for="teacher_id">Select Teacher(s):</label>
            <select id="teacher_id" name="teacher_ids[]" multiple required>
                <?php foreach ($teachers as $teacher) { ?>
                    <option value="<?php echo esc_attr($teacher->id); ?>" <?php if ($is_edit && in_array($teacher->id, $selected_teacher_ids)) echo 'selected'; ?>><?php echo esc_html($teacher->name); ?></option>
                <?php } ?>
            </select><br><br>

            <input type="hidden" name="assignment_id" value="<?php echo esc_attr($assignment_id); ?>">
            <input type="submit" name="submit_assignment_form" value="<?php echo $is_edit ? 'Update Assignment' : 'Assign'; ?>">
        </form>

        <h2>Current Assignments</h2>
        <?php myplugin_display_assignments(); ?>
    </div>
    <?php

    // Handle form submission
    if (isset($_POST['submit_assignment_form'])) {
        myplugin_handle_assign_users_form_submission();
    }
}


// Function to handle the Assign Users form submission
function myplugin_handle_assign_users_form_submission() {
    // Verify the nonce for security
    if (!isset($_POST['myplugin_assign_users_nonce']) || !wp_verify_nonce($_POST['myplugin_assign_users_nonce'], 'myplugin_assign_users')) {
        wp_die('Invalid form submission');
    }

    global $wpdb;

    // Sanitize input data
    $user_id = intval($_POST['user_id']);
    $teacher_ids = array_map('intval', $_POST['teacher_ids']);

    // Table name
    $table_name = $wpdb->prefix . 'myplugin_user_teacher';

    // Insert data into the database (first delete existing assignments for the user)
    $wpdb->delete($table_name, array('user_id' => $user_id));
    
    foreach ($teacher_ids as $teacher_id) {
        $wpdb->insert(
            $table_name,
            array(
                'user_id' => $user_id,
                'teacher_id' => $teacher_id
            )
        );
    }

    echo '<p style="color: green;">User assigned to teacher(s) successfully!</p>';
}

// Function to display current assignments
function myplugin_display_assignments() {
    global $wpdb;

    // Table names
    $users_table = $wpdb->prefix . 'myplugin_users';
    $teachers_table = $wpdb->prefix . 'myplugin_teachers';
    $assignment_table = $wpdb->prefix . 'myplugin_user_teacher';

    // Fetch all assignments
    $assignments = $wpdb->get_results("
        SELECT a.id as assignment_id, u.id as user_id, u.name as user_name, t.id as teacher_id, t.name as teacher_name
        FROM $assignment_table as a
        JOIN $users_table as u ON a.user_id = u.id
        JOIN $teachers_table as t ON a.teacher_id = t.id
    ");

    if ($assignments) {
        echo '<table class="widefat fixed" cellspacing="0">';
        echo '<thead>';
        echo '<tr>';
        echo '<th class="manage-column column-columnname" scope="col">User</th>';
        echo '<th class="manage-column column-columnname" scope="col">Assigned Teacher</th>';
        echo '<th class="manage-column column-columnname" scope="col">Actions</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($assignments as $assignment) {
            echo '<tr>';
            echo '<td>' . esc_html($assignment->user_name) . '</td>';
            echo '<td>' . esc_html($assignment->teacher_name) . '</td>';
            echo '<td>
                <a href="?page=myplugin_assign_users&action=edit&assignment_id=' . esc_attr($assignment->assignment_id) . '">Edit</a> |
                <a href="?page=myplugin_assign_users&action=delete&assignment_id=' . esc_attr($assignment->assignment_id) . '" onclick="return confirm(\'Are you sure you want to delete this assignment?\')">Delete</a>
            </td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>No assignments found.</p>';
    }
}

// Hook for plugin activation to create a custom table for user-teacher assignments
register_activation_hook(__FILE__, 'myplugin_create_user_teacher_table');

// Function to create the custom table
function myplugin_create_user_teacher_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'myplugin_user_teacher';

    // SQL query to create the table if it doesn't exist
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id mediumint(9) NOT NULL,
        teacher_id mediumint(9) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


// Function to handle the deletion of an assignment
function myplugin_handle_delete_assignment($assignment_id) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'myplugin_user_teacher';

    // Delete the assignment
    $deleted = $wpdb->delete($table_name, array('id' => $assignment_id));

    if ($deleted) {
        echo '<div class="notice notice-success is-dismissible"><p>Assignment deleted successfully!</p></div>';
    } else {
        echo '<div class="notice notice-error is-dismissible"><p>Failed to delete assignment.</p></div>';
    }
}



// Register shortcode for teacher login form
function myplugin_teacher_login_form_shortcode() {
    ob_start();
    ?>
    <div class="teacher-login-form">
        <h2>Teacher Login</h2>
        <form method="post" action="">
            <?php wp_nonce_field('myplugin_teacher_login', 'myplugin_teacher_login_nonce'); ?>

            <label for="login_email">Email:</label>
            <input type="email" id="login_email" name="login_email" required><br><br>

            <label for="login_password">Password:</label>
            <input type="password" id="login_password" name="login_password" required><br><br>

            <input type="submit" name="submit_teacher_login" value="Login">
        </form>
    </div>
    <?php
    if (isset($_POST['submit_teacher_login'])) {
        myplugin_handle_teacher_login();
    }
    return ob_get_clean();
}
add_shortcode('teacher_login_form', 'myplugin_teacher_login_form_shortcode');


// Handle the teacher login
function myplugin_handle_teacher_login() {

     // Start the session if not already started
     if (!session_id()) {
        session_start();
    }
    // Verify the nonce for security
    if (!isset($_POST['myplugin_teacher_login_nonce']) || !wp_verify_nonce($_POST['myplugin_teacher_login_nonce'], 'myplugin_teacher_login')) {
        wp_die('Invalid form submission');
    }

    global $wpdb;

    // Sanitize and prepare input data
    $login_email = sanitize_email($_POST['login_email']);
    $login_password = sanitize_text_field($_POST['login_password']);

    // Table name
    $table_name = $wpdb->prefix . 'myplugin_teachers';

    // Fetch teacher data based on email
    $teacher = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE email = %s", $login_email));

    if ($teacher && wp_check_password($login_password, $teacher->password, $teacher->password)) {
        // Start the session for the teacher
        $_SESSION['teacher_id'] = $teacher->id;

        // Redirect to the page with the [teacher_assignments] shortcode
        wp_redirect(home_url('/index.php/marks/')); // Replace with the actual slug of your page
        exit;
    } else {
        echo '<div class="notice notice-error is-dismissible"><p>Invalid email or password.</p></div>';
    }
}



// Start session
add_action('init', 'myplugin_start_session', 1);
function myplugin_start_session() {
    if (!session_id()) {
        session_start();
    }
}


// Register shortcode for displaying teacher assignments
function myplugin_teacher_assignments_shortcode() {
    if (!isset($_SESSION['teacher_id'])) {
        return '<p>You need to log in to view your assignments.</p>';
    }

    global $wpdb;
    $teacher_id = intval($_SESSION['teacher_id']);

    // Table names
    $users_table = $wpdb->prefix . 'myplugin_users';
    $teachers_table = $wpdb->prefix . 'myplugin_teachers';
    $assignment_table = $wpdb->prefix . 'myplugin_user_teacher';

    // SQL query to get assignments
    $query = $wpdb->prepare("
        SELECT u.id as user_id, u.name as user_name, t.name as teacher_name, u.score
        FROM $assignment_table a
        JOIN $users_table u ON a.user_id = u.id
        JOIN $teachers_table t ON a.teacher_id = t.id
        WHERE a.teacher_id = %d
    ", $teacher_id);

    $assignments = $wpdb->get_results($query);

    ob_start();

    if ($assignments) {
        echo '<h1>Your Assignments</h1>';
        echo '<ul>';
        foreach ($assignments as $assignment) {
            echo '<li>' . esc_html($assignment->user_name) . ' - ' . esc_html($assignment->teacher_name) . ' - Score: ' . esc_html($assignment->score);
            echo ' <a href="?edit_user=' . esc_attr($assignment->user_id) . '">Edit Score</a></li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No assignments found.</p>';
    }

    // Check if edit_user parameter is set and display the edit form
    if (isset($_GET['edit_user'])) {
        $user_id = intval($_GET['edit_user']);
        display_edit_form($user_id);
    }

    return ob_get_clean();
}
add_shortcode('teacher_assignments', 'myplugin_teacher_assignments_shortcode');


function display_edit_form($user_id) {
    global $wpdb;
    $users_table = $wpdb->prefix . 'myplugin_users';

    // Get user details
    $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $users_table WHERE id = %d", $user_id));

    if ($user) {
        echo '<h2>Edit Score for ' . esc_html($user->name) . '</h2>';
        echo '<form method="post">';
        echo '<input type="hidden" name="user_id" value="' . esc_attr($user_id) . '">';
        echo '<p>Score: <input type="number" name="score" value="' . esc_attr($user->score) . '" required></p>';
        echo '<p><input type="submit" name="update_score" value="Update Score"></p>';
        echo '</form>';
    } else {
        echo '<p>User not found.</p>';
    }
}

function handle_score_update() {
    if (isset($_POST['update_score'])) {
        global $wpdb;
        $users_table = $wpdb->prefix . 'myplugin_users';

        $user_id = intval($_POST['user_id']);
        $score = intval($_POST['score']);

        // Update the score in the database
        $updated = $wpdb->update(
            $users_table,
            array('score' => $score),
            array('id' => $user_id),
            array('%d'),
            array('%d')
        );

        if ($updated !== false) {
            echo '<p>Score updated successfully!</p>';
        } else {
            echo '<p>Failed to update score. Please try again.</p>';
        }
    }
}
add_action('init', 'handle_score_update');



function teacher_user_form_shortcode() {
    // Initialize variables
    $first_shlok = $second_shlok = $third_shlok = $fourth_shlok = $fifth_shlok = '';
    $errors = [];

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teacher_form_submit'])) {
        // Sanitize input data
        $first_shlok = sanitize_text_field($_POST['first_shlok']);
        $second_shlok = sanitize_text_field($_POST['second_shlok']);
        $third_shlok = sanitize_text_field($_POST['third_shlok']);
        $fourth_shlok = sanitize_text_field($_POST['fourth_shlok']);
        $fifth_shlok = sanitize_text_field($_POST['fifth_shlok']);
        $selected_user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0; // Get selected user ID

        // Validate input fields
        if ($selected_user_id === 0) {
            $errors['user_id'] = 'कृपया परीक्षार्थी का नाम दर्ज करें। / Select an applicant';
        }

        // Validate the shlok marks to be between 1 and 10
        if (empty($first_shlok) || $first_shlok < 1 || $first_shlok > 10) {
            $errors['first_shlok'] = 'कृपया प्रथम श्लोक के अंक 1 से 10 के बीच दर्ज करें।';
        }
        if (empty($second_shlok) || $second_shlok < 1 || $second_shlok > 10) {
            $errors['second_shlok'] = 'कृपया द्वितीय श्लोक के अंक 1 से 10 के बीच दर्ज करें।';
        }
        if (empty($third_shlok) || $third_shlok < 1 || $third_shlok > 10) {
            $errors['third_shlok'] = 'कृपया तृतीय श्लोक के अंक 1 से 10 के बीच दर्ज करें।';
        }
        if (empty($fourth_shlok) || $fourth_shlok < 1 || $fourth_shlok > 10) {
            $errors['fourth_shlok'] = 'कृपया चतुर्थ श्लोक के अंक 1 से 10 के बीच दर्ज करें।';
        }
        if (empty($fifth_shlok) || $fifth_shlok < 1 || $fifth_shlok > 10) {
            $errors['fifth_shlok'] = 'कृपया पंचम श्लोक के अंक 1 से 10 के बीच दर्ज करें।';
        }

        // If no errors, process the form data and save it to the database
        if (empty($errors)) {
            global $wpdb;

            // Check if the user already has marks recorded in the database
            $marks_table = $wpdb->prefix . 'myplugin_marks';
            $existing_entry = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $marks_table WHERE user_id = %d", $selected_user_id));

            if ($existing_entry > 0) {
                $errors['user_id'] = 'इस परीक्षार्थी के लिए पहले से अंक दर्ज किए जा चुके हैं। / Marks for this applicant have already been recorded.';
            } else {
                // Get the selected user's name from the database
                $users_table = $wpdb->prefix . 'myplugin_users';
                $user = $wpdb->get_row($wpdb->prepare("SELECT name FROM $users_table WHERE id = %d", $selected_user_id));

                if ($user) {
                    // Insert data into the custom marks table
                    $teacher_id = intval($_SESSION['teacher_id']); // Get the teacher's ID from session

                    $wpdb->insert(
                        $marks_table, // Table name
                        [
                            'user_id' => $selected_user_id,
                            'teacher_id' => $teacher_id,
                            'name' => $user->name, // Store the user's name from the database
                            'first_shlok' => $first_shlok,
                            'second_shlok' => $second_shlok,
                            'third_shlok' => $third_shlok,
                            'fourth_shlok' => $fourth_shlok,
                            'fifth_shlok' => $fifth_shlok,
                        ],
                        ['%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s'] // Data types
                    );

                    // Show success message
                    echo '<div class="success-message">Form submitted successfully./फॉर्म सफलतापूर्वक सबमिट हो गया है।</div>';
                } else {
                    $errors['user_id'] = 'चयनित परीक्षार्थी को प्राप्त नहीं किया जा सका।';
                }
            }
        }
    }

    global $wpdb;

    // Get the current teacher's ID from session
    $teacher_id = intval($_SESSION['teacher_id']);

    // Define the table names
    $users_table = $wpdb->prefix . 'myplugin_users';
    $teachers_table = $wpdb->prefix . 'myplugin_teachers';
    $assignment_table = $wpdb->prefix . 'myplugin_user_teacher';

    // Fetch teacher's name
    $teacher_query = $wpdb->prepare("SELECT name FROM $teachers_table WHERE id = %d", $teacher_id);
    $teacher = $wpdb->get_row($teacher_query);

    // Fetch users assigned to the teacher
    $users_query = $wpdb->prepare("
        SELECT u.id as user_id, u.name as user_name
        FROM $assignment_table a
        JOIN $users_table u ON a.user_id = u.id
        WHERE a.teacher_id = %d
    ", $teacher_id);
    $users = $wpdb->get_results($users_query);

    ob_start();
    ?>
    <style>
        .wp-block-group.has-global-padding.is-layout-constrained.wp-block-group-is-layout-constrained {
            margin-bottom: -120px;
        }
        .entry-content.wp-block-post-content.has-global-padding.is-layout-constrained.wp-block-post-content-is-layout-constrained {
            margin-left: -150px;
        }
        .error-msg {
            color: red;
            margin-top: 5px;
            display: block;
        }
        .success-message {
            color: green;
            font-size: x-large;
            margin-left: 250px;
            margin-top: 40px;
            margin-bottom: -30px;
        }
    
        .field-label {
            margin-bottom: 5px;
            display: block;
            font-weight: bold;
            padding-top: 10px;
            padding-bottom: 10px;
            font-family: 'docs-Book Antiqua';
            font-weight: bold;
            font-size: 14pt;
            line-height: 1.5;
            letter-spacing: 0;
        }
       
        .field-group {
            /* border: 1px solid black; */
            margin-bottom: 20px;
            padding-left: 20px;
            border-radius: 10px;
            padding-top: 20px;
            padding-bottom: 20px;
            border: 1px solid rgb(218, 220, 224);
        }
        
        input:not([type="radio"]):not([type="submit"]) {
            border-top: none;
            border-left: none;
            border-right: none;
            width: 50%;
            border-bottom: 1px solid rgb(218, 220, 224);
        }
        input[type="submit"] {
            width: 15%;
            padding: 10px;
            background-color: rgb(153, 122, 0);
            border-radius: 8px;
            color: white;
            cursor: pointer;
            font-size: 15px;
            font-weight: bold;
            border:none;
        }
        select#gender {
            width: 30%;
            padding: 10px;
            border: 1px solid rgb(218, 220, 224);
        }
        button {
            border: none;
            color: rgb(153, 122, 0);
            cursor: pointer;
            margin-left: 430px;
            font-size: 15px;
            font-weight: bold;
        }
    </style>
    <?php echo $success_message; ?>
    <div class="poster-image">
        <img style="width: 100%; height: 180px; border-radius: 10px;" src="https://lh6.googleusercontent.com/RF0GkHpkfWgM4FjXrJol_xrnv-agvnCTJEKGW7YYx3VOVcRo_ZWWh72VY180sNHxBRYKJ2CUga57qHa7G09Ik71QTjpYB-tpN_VjOlR9V3KR_7UIiTcUxMqsC82DgQt94g=w1280" alt="Background Image" />
    </div>
    <div>
        <h2>SBRK मुखस्थ परीक्षा August 2024 - कुंती स्तुति</h2>
        <p>भागवत मुखस्थ परीक्षा का आयोजन श्रीमद् भागवत महापुराण में आने वाली कुछ महत्वपूर्ण, भक्ति- मुक्ति दायक,  सुंदर स्तुतियों के मुखस्थीकरण हेतु  किया गया है।
        <br><br>
        🌹 कुंती स्तुति - रविवार, 25 अगस्त  <br>
        Examiner Name: <?php echo esc_html($teacher->name); ?></p>
    </div>
    <!-- Display the form -->
    <form method="post" action="" id="user_teacher">
        <div class="field-group">
            <label for="user_id" class="field-label">परीक्षार्थी का नाम / Name of Participant:<span style="color: red;">*</span></label>
            <?php if ($users) : ?>
                <?php foreach ($users as $user) : ?>
                    <input type="radio" name="user_id" id="user_<?php echo esc_attr($user->user_id); ?>" value="<?php echo esc_attr($user->user_id); ?>" required <?php echo ($selected_user_id == $user->user_id) ? 'checked' : ''; ?>>
                    <label for="user_<?php echo esc_attr($user->user_id); ?>"><?php echo esc_html($user->user_name); ?></label><br>
                <?php endforeach; ?>
                <?php if (isset($errors['user_id'])) : ?>
                    <span class="error-msg"><?php echo esc_html($errors['user_id']); ?></span>
                <?php endif; ?>
            <?php else : ?>
                <p>No students found.</p>
            <?php endif; ?>
        </div>

        <!-- First Shlok -->
        <div class="field-group">
            <label for="first_shlok" class="field-label">प्रथम श्लोक / 1st Shlok (1-10 Marks):<span style="color: red;">*</span></label>
            <input type="text" name="first_shlok" id="first_shlok" value="<?php echo esc_attr($first_shlok); ?>" required>
            <?php if (isset($errors['first_shlok'])) : ?>
                <span class="error-msg"><?php echo esc_html($errors['first_shlok']); ?></span>
            <?php endif; ?>
        </div>

        <!-- Second Shlok -->
        <div class="field-group">
            <label for="second_shlok" class="field-label">द्वितीय श्लोक / 2nd Shlok (1-10 Marks):<span style="color: red;">*</span></label>
            <input type="text" name="second_shlok" id="second_shlok" value="<?php echo esc_attr($second_shlok); ?>" required>
            <?php if (isset($errors['second_shlok'])) : ?>
                <span class="error-msg"><?php echo esc_html($errors['second_shlok']); ?></span>
            <?php endif; ?>
        </div>

        <!-- Third Shlok -->
        <div class="field-group">
            <label for="third_shlok" class="field-label">तृतीय श्लोक / 3rd Shlok (1-10 Marks):<span style="color: red;">*</span></label>
            <input type="text" name="third_shlok" id="third_shlok" value="<?php echo esc_attr($third_shlok); ?>" required>
            <?php if (isset($errors['third_shlok'])) : ?>
                <span class="error-msg"><?php echo esc_html($errors['third_shlok']); ?></span>
            <?php endif; ?>
        </div>

        <!-- Fourth Shlok -->
        <div class="field-group">
            <label for="fourth_shlok" class="field-label">चतुर्थ श्लोक / 4th Shlok (1-10 Marks):<span style="color: red;">*</span></label>
            <input type="text" name="fourth_shlok" id="fourth_shlok" value="<?php echo esc_attr($fourth_shlok); ?>" required>
            <?php if (isset($errors['fourth_shlok'])) : ?>
                <span class="error-msg"><?php echo esc_html($errors['fourth_shlok']); ?></span>
            <?php endif; ?>
        </div>

        <!-- Fifth Shlok -->
        <div class="field-group">
            <label for="fifth_shlok" class="field-label">पंचम श्लोक / 5th Shlok (1-10 Marks):<span style="color: red;">*</span></label>
            <input type="text" name="fifth_shlok" id="fifth_shlok" value="<?php echo esc_attr($fifth_shlok); ?>" required>
            <?php if (isset($errors['fifth_shlok'])) : ?>
                <span class="error-msg"><?php echo esc_html($errors['fifth_shlok']); ?></span>
            <?php endif; ?>
        </div>

        <div class="field-group-btn">
            <input type="submit" name="teacher_form_submit" value="Submit">
            <button type="button" onclick="document.getElementById('user_teacher').reset();">Clear Form</button><br><br>
        </div>
    </form>
    
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('button[type="button"]').addEventListener('click', function() {
        clearForm();
    });

    function clearForm() {
        // Reset all input fields
        document.getElementById('user_teacher').reset();

        // Clear error messages
        document.querySelectorAll('.error-msg').forEach(function(span) {
            span.textContent = '';
        });
    }
});
</script>

    <?php
    return ob_get_clean();
}
add_shortcode('teacher_user_form', 'teacher_user_form_shortcode');


// Hook for plugin activation to create custom tables
register_activation_hook(__FILE__, 'myplugin_create_marks_table');

// Function to create the marks table
function myplugin_create_marks_table() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // Create marks table
    $marks_table = $wpdb->prefix . 'myplugin_marks';
    $sql_marks = "CREATE TABLE $marks_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id mediumint(9) NOT NULL,
        teacher_id mediumint(9) NOT NULL,
        name tinytext NOT NULL,
        first_shlok tinytext NOT NULL,
        second_shlok tinytext NOT NULL,
        third_shlok tinytext NOT NULL,
        fourth_shlok tinytext NOT NULL,
        fifth_shlok tinytext NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_marks);
}
