<?php
session_start();

// Check if the user is logged in
if(!isset($_SESSION["username"])) {
    header("Location: authentication.php"); // Redirect to authentication.php if not logged in
    exit();
}

// Retrieve username of logged-in user
$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Finder</title>
</head>
<body>

<h2>Welcome, <?php echo $username; ?></h2>
<p>This is your personalized dashboard. You are logged in as <?php echo $username; ?>.</p>

<!-- Button to open the upload job form -->
<button onclick="window.location.href='upload_job.php'">Upload Job</button>

<h2>Search Jobs</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
    Location: <input type="text" name="location"><br>
    Category:
    <select name="category">
        <option value="music">Music Job</option>
        <option value="craft">Craft Job</option>
        <option value="chores">Basic Chores</option>
    </select><br>
    Visibility:
    <input type="radio" name="visibility" value="public" checked> Public
    <input type="radio" name="visibility" value="private"> Private<br>
    Payment: <input type="text" name="payment"><br>
    <input type="submit" value="Search Jobs">
</form>

<?php
// Handle search
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET["location"])) {
        $location = $_GET["location"];
        $category = $_GET["category"];
        $visibility = $_GET["visibility"];
        $payment = $_GET["payment"];

        // Perform search based on filters
        // Example: Query database for matching jobs
        $servername = "localhost";
        $username = "username";
        $password = "password";
        $dbname = "job_finder";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM jobs WHERE location = '$location' AND category = '$category' AND visibility = '$visibility' AND payment = '$payment'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "Name: " . $row["name"]. " - Description: " . $row["description"]. " - Location: " . $row["location"]. " - Payment: " . $row["payment"]. "<br>";
            }
        } else {
            echo "0 results found";
        }

        $conn->close();
    }
}
?>

</body>
</html>
