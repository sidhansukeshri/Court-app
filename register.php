<?php
$host = "localhost";
$port = "5432";
$dbname = "courtapp";
$db_username = "postgres";
$db_password = "Your_password"; //Your_password

try {
    // Establish a database connection
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$db_username;password=$db_password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $court_name = $_POST['court_name'];
        $user_input_username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $role = $_POST['role'];

        // Check if the username already exists
        $check_username_sql = "SELECT * FROM user_t WHERE user_name = ?";
        $stmt_check_username = $conn->prepare($check_username_sql);
        $stmt_check_username->execute([$user_input_username]);

        if ($stmt_check_username->rowCount() > 0) {
            // Username already exists
            echo json_encode([
                "success" => false,
                "message" => [
                    "username" => "Username already exists. Please choose another username."
                ]
            ]);
        } else {
            if ($password !== $confirm_password) {
                // Passwords do not match
                echo json_encode([
                    "success" => false,
                    "message" => [
                        "password" => "Passwords do not match. Please try again."
                    ]
                ]);
            } else {
                // Hash the password
                $password_hashed = password_hash($password, PASSWORD_BCRYPT);

                // Insert the new user into the user_t table
                $insert_sql = "INSERT INTO user_t (user_name, password, user_status, user_from_date, user_role_id, user_created_dt, user_created_by) VALUES (?, ?, ?, CURRENT_DATE, ?, CURRENT_DATE, ?)";
                $stmt_insert = $conn->prepare($insert_sql);
                $stmt_insert->execute([$user_input_username, $password_hashed, 'A', $role, 'your_login_user_id']);

                if ($stmt_insert->rowCount() > 0) {
                    // Registration successful
                    echo json_encode(["success" => true]);
                } else {
                    // Error inserting user
                    echo json_encode([
                        "success" => false,
                        "message" => [
                            "other" => "Error inserting user."
                        ]
                    ]);
                }
            }
        }
    } else {
        // Handle the case when the script is accessed directly without an HTTP POST request
        echo json_encode([
            "success" => false,
            "message" => [
                "other" => "This script should be accessed via an HTTP POST request."
            ]
        ]);
    }
} catch (PDOException $e) {
    // Database error
    echo json_encode([
        "success" => false,
        "message" => [
            "other" => "Database error: " . $e->getMessage()
        ]
    ]);
}
?>
