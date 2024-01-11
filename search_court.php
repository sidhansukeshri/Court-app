<?php
// Your database connection setup here

$host = "localhost";
$port = "5432";
$dbname = "courtapp"; 
$db_username = "postgres";
$db_password = "Spysid@#69";

$conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$db_username;password=$db_password");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["q"])) {
    $search_query = "%" . $_GET["q"] . "%"; // Add wildcards for partial search

    // Prepare and execute a SQL query to search for court names
    $search_sql = "SELECT court_name FROM court_t WHERE court_name ILIKE :search_query AND display = 'Y' LIMIT 5";
    $stmt_search = $conn->prepare($search_sql);
    $stmt_search->bindParam(":search_query", $search_query, PDO::PARAM_STR);
    $stmt_search->execute();

    $results = $stmt_search->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
} else {
    // Invalid request
    echo json_encode([]);
}
?>
