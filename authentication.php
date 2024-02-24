<?php
session_start();

// Check if the user is already logged in
if(isset($_SESSION["username"])) {
    header("Location: index.php"); // Redirect to index.php
    exit();
}

// Function to sanitize input data
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Function to check if a username already exists
function usernameExists($username) {
    $users = file("users.txt", FILE_IGNORE_NEW_LINES);
    foreach ($users as $user) {
        list($stored_username, $hashed_password) = explode(":", $user);
        if ($username === $stored_username) {
            return true;
        }
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["login"])) {
        $username = sanitize($_POST["username"]);
        $password = sanitize($_POST["password"]);

        // Check credentials
        $users = file("users.txt", FILE_IGNORE_NEW_LINES);
        foreach ($users as $user) {
            list($stored_username, $hashed_password) = explode(":", $user);
            if ($username === $stored_username && password_verify($password, $hashed_password)) {
                // Store username in session to indicate user is logged in
                $_SESSION["username"] = $username;
                // Redirect to index.php
                header("Location: index.php");
                exit();
            }
        }
        $error_message = "Invalid username or password.";
    } elseif (isset($_POST["register"])) {
        $username = sanitize($_POST["username"]);
        $password = sanitize($_POST["password"]);

        // Check if username already exists
        if (usernameExists($username)) {
            $error_message = "Username already exists.";
        } else {
            // Store the username and hashed password in the text file
            $file = fopen("users.txt", "a");
            fwrite($file, $username . ":" . password_hash($password, PASSWORD_DEFAULT) . "\n");
            fclose($file);
            echo "User registered successfully.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Authentication</title>
</head>
<body>

<h2>Login</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    Username: <input type="text" name="username"><br>
    Password: <input type="password" name="password"><br>
    <input type="submit" name="login" value="Login">
</form>

<h2>Create Account</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    Username: <input type="text" name="username"><br>
    Password: <input type="password" name="password"><br>
    <input type="submit" name="register" value="Create Account">
</form>

<?php
if (isset($error_message)) {
    echo "<p>$error_message</p>";
}
?>

</body>
</html>
