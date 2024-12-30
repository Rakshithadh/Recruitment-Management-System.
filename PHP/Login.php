<?php
session_start();
include 'db_connect.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password_input = $_POST["password"];
  
    $sql = "SELECT u.email, u.name, u.password, u.type, c.CID 
            FROM user u 
            LEFT JOIN client c ON u.email = c.CEmail 
            WHERE LOWER(u.email) = LOWER('$email')";

    $result = $conn->query($sql);

    if (!$result) {
        die("Error: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row["type"] == "Client" && isset($row["password"]) && password_verify($password_input, $row["password"])) {
            $_SESSION["email"] = $row["email"];
            $_SESSION["name"] = $row["name"];
            $_SESSION["type"] = $row["type"];
            $_SESSION["CID"] = $row["CID"];
            header("Location: http://localhost/Emp/Client_main.php");
            exit();
        } elseif ($row["type"] == "Recruiter" && isset($row["password"]) && password_verify($password_input, $row["password"])) {
            $_SESSION["email"] = $row["email"];
            $_SESSION["name"] = $row["name"];
            $_SESSION["type"] = $row["type"];
            $_SESSION["RID"] = $row["RID"];

            header("Location: http://localhost/Emp/Recruiter_main.php");
            exit();
        } else {
            $message = "Invalid password or user type";
        }
    } else {
        $message = "User not found";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Log In</title>
    <link rel="stylesheet" href="style1.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="login-page">
<div class="login-box">
    <div class="login-inbox">
        <center>LOG-IN</center>
        <div class="login-text">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="email">Email:</label>
                <input type="text" name="email" placeholder="Email Address" class="input-field" required></br>

                <label for="password">Password:</label>
                <input type="password" name="password" placeholder="Password" class="input-field" required></br>
                
                <center><input type="submit" value="LOGIN" class="login-btn"></center>
                <br>
                
                <div class="signup-link">
                    <a href="Register.php">Don't Have Account? Create A New</a>
                </div>
            </form>
            <script>
                var message = "<?php echo $message; ?>";
                if (message !== "") {
                    alert(message);
                }
        </div>
    </div>
</div>
</body>
</html>
