<?php
// Set the response content type to JSON
header('Content-Type: application/json');

// Database connection details
$host = "localhost";
$port = "5432";
$dbname = "courtapp";
$db_username = "postgres";
$db_password = "Your_password"; //Your_password

try {
    // Create a PDO database connection
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$db_username;password=$db_password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Decode the JSON data sent in the request
        $data = json_decode(file_get_contents("php://input"));
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
                echo json_encode([
                    "success" => true
                ]);
                exit();
            }
        }
    }

    // Login failed
    echo json_encode([
        "success" => false
    ]);
} catch (PDOException $e) {
    // Database connection error
    die("Connection failed: " . $e->getMessage());
}
?>
