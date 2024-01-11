<?php
// Database connection details
$host = "localhost";
$port = "5432";
$dbname = "courtapp";
$db_username = "postgres";
$db_password = "Spysid@#69";

try {
    // Create a PDO database connection
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$db_username;password=$db_password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Decode the JSON data sent in the request
        $data = json_decode(file_get_contents("php://input"));

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode([
                "success" => false,
                "message" => "JSON decoding error: " . json_last_error_msg(),
            ]);
            exit();
        }

        $loginUsername = $data->loginUsername;
        $loginPassword = $data->loginPassword;

        // Check if the username exists in the database
        $check_user_sql = "SELECT * FROM user_t WHERE user_name = ?";
        $stmt_check_user = $conn->prepare($check_user_sql);
        $stmt_check_user->execute([$loginUsername]);

        if ($stmt_check_user->rowCount() == 1) {
            $user_row = $stmt_check_user->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $user_row["password"];
            $user_status = $user_row["user_status"];

            if ($user_status === 'A' && password_verify($loginPassword, $hashed_password)) {
                // Password is correct, and the user status is 'A', login successful
                
                // Insert a record into the userlog_t table
                $insert_log_sql = "INSERT INTO userlog_t (user_name, login_ip, login_time, role_id) VALUES (?, ?, CURRENT_TIMESTAMP, ?)";
                $stmt_insert_log = $conn->prepare($insert_log_sql);
                $stmt_insert_log->execute([$loginUsername, $_SERVER['REMOTE_ADDR'], $user_row['role_id']]);

                // Set the response content type to JSON
                header('Content-Type: application/json');

                // Return a valid JSON response
                echo json_encode([
                    "success" => true
                ]);
                exit();
            }
        }

        // Login failed
        // Set the response content type to JSON
        header('Content-Type: application/json');

        echo json_encode([
            "success" => false,
            "message" => "Incorrect username or password."
        ]);
        exit();
    } else {
        // Invalid request method
        // Set the response content type to JSON
        header('Content-Type: application/json');

        echo json_encode([
            "success" => false,
            "message" => "Invalid request method."
        ]);
        exit();
    }
} catch (PDOException $e) {
    // Log the PDO error for debugging
    error_log("PDO Error: " . $e->getMessage());

    // Set the response content type to JSON
    header('Content-Type: application/json');

    // Return a valid JSON response with a 500 Internal Server Error status code
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Database connection error."
    ]);
    exit();
}
?>
