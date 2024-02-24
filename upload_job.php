<!DOCTYPE html>
<html>
<head>
    <title>Job Finder</title>
</head>
<body>

<h2>Upload Job</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    Job Name: <input type="text" name="job_name"><br>
    Job Description: <textarea name="job_description"></textarea><br>
    Category:
    <select name="job_category">
        <option value="music">Music Job</option>
        <option value="craft">Craft Job</option>
        <option value="chores">Basic Chores</option>
    </select><br>
    Public or Private:
    <input type="radio" name="job_visibility" value="public" checked> Public
    <input type="radio" name="job_visibility" value="private"> Private<br>
    Location: <input type="text" name="job_location"><br>
    Payment: <input type="text" name="job_payment"><br>
    <input type="submit" value="Upload Job">
</form>

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
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_name = $_POST["job_name"];
    $job_description = $_POST["job_description"];
    $job_category = $_POST["job_category"];
    $job_visibility = $_POST["job_visibility"];
    $job_location = $_POST["job_location"];
    $job_payment = $_POST["job_payment"];

    // Perform database insertion or file storage, etc.
    // Example: Insert data into MySQL database
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "job_finder";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO jobs (name, description, category, visibility, location, payment)
            VALUES ('$job_name', '$job_description', '$job_category', '$job_visibility', '$job_location', '$job_payment')";

    if ($conn->query($sql) === TRUE) {
        echo "Job uploaded successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

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
