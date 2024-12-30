<?php
session_start(); 
include 'db_connect.php';
// Initialize a message variable to store feedback (error or success messages)
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data (email, name, type, password) from the POST request
    $email = $_POST["email"];
    $name = $_POST["name"];
    $type = $_POST["type"];
    $password = $_POST["password"];

    // Check if the email already exists in the 'user' table
    $check_email_sql = "SELECT * FROM user WHERE email = '$email'";
    $result = $conn->query($check_email_sql);

    // If the email already exists, set an error message
    if ($result->num_rows > 0) {
        $message = "Error: Email already exists. Please use a different email.";
    } 
    else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (email, name, type, password) VALUES ('$email', '$name', '$type', '$hashed_password')";
        if ($conn->query($sql) === TRUE) {
            $message = "Registration successful!";
            if ($type == "Recruiter") {
                $_SESSION["email"] = $email;
                $_SESSION["name"] = $name;
                $_SESSION["type"] = $type;
                $_SESSION["RID"] = $row["RID"];
                header("Location: Recruiter_details.php"); 
                exit();
            } 
            elseif ($type == "Client") {
                $_SESSION["email"] = $email;
                $_SESSION["name"] = $name;
                $_SESSION["type"] = $type;
                $_SESSION["CID"] = $row["CID"]; 
                header("Location: Client_details.php"); 
                exit();
            }
        } 
        else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sign Up | Log In</title>
    <link rel="stylesheet" href="style1.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="register-page">
<div class="register-box">
    <div class="register-inbox">
        <center>REGISTER</center>
        <div class="register-text">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="email">Email:</label>
                <input type="email" name="email" class="input-field" required><br/>

                <label for="name">Name:</label>
                <input type="text" name="name" class="input-field" required><br>

                <label>Type: </label>
                <select name="type" class="input-field" required>
                    <option value="Recruiter">Recruiter</option>
                    <option value="Client">Client</option>
                </select><br>

                <label for="password">Password:</label>
                <input type="password" name="password" class="input-field" required><br/>
                
                <center><input type="submit" value="REGISTER" class="register-btn"></center>
                <br>

                <div class="signup-link">
                    <a href="Login.php">Already have an account? Log-in</a>
                </div>
            </form>
            <script>
                var message = "<?php echo $message; ?>";
                if (message !== "") {
                    alert(message);
                }
            </script>
        </div>
    </div>
</div>
</body>
</html>
