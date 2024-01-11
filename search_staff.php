<?php
// Your database connection setup here

$host = "localhost";
$port = "5432";
$dbname = "courtapp";
$db_username = "postgres";
$db_password = "Spysid@#69";

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["q"])) {
    try {
        // Establish a database connection
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$db_username;password=$db_password");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch staff name suggestions from the database
        $query = "SELECT biometric_id, emp_name || ' ' || emp_fathername AS display_name FROM employee_t WHERE emp_status = 'A' AND (emp_name ILIKE :search_query OR emp_fathername ILIKE :search_query)";
        $stmt = $conn->prepare($query);
        $search_query = "%" . $_GET["q"] . "%"; // Add wildcards for partial search
        $stmt->bindParam(":search_query", $search_query, PDO::PARAM_STR);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the results as JSON
        echo json_encode($results);
    } catch (PDOException $e) {
        // Handle database connection error
        echo json_encode([]);
    }
} else {
    // Invalid request
    echo json_encode([]);
}
?>
