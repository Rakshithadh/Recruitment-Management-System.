<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_SESSION["email"]) && isset($_SESSION["name"])) {
        $email = $_SESSION["email"];
        $name = $_SESSION["name"];

        $sql_insert = $conn->prepare("INSERT INTO recruitment (REmail, RName, CompanyName, CompanyLocation, RGender)
                                      VALUES (?, ?, ?, ?, ?)
                                      ON DUPLICATE KEY UPDATE
                                      RName = VALUES(RName), CompanyName = VALUES(CompanyName), CompanyLocation = VALUES(CompanyLocation), RGender = VALUES(RGender)");

        $CompanyName = isset($_POST['CompanyName']) ? $_POST['CompanyName'] : "";
        $CompanyLocation = isset($_POST['CompanyLocation']) ? $_POST['CompanyLocation'] : "";
        $RGender = isset($_POST['RGender']) ? $_POST['RGender'] : "";

        $sql_insert->bind_param("sssss", $email, $name, $CompanyName, $CompanyLocation, $RGender);

        if ($sql_insert->execute()) {
            header("Location: Login.php");
            exit();
        } else {
            echo "Error: " . $sql_insert->error;
        }
        $sql_insert->close();
    } else {
        echo "Session variables (email and name) not set.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rec Details</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body class="rdetails-page">
<div class="rcontainer">
    <div class="rbox">
            <center>REGISTER</center>
    </div>
        <div class="text">
            <br>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label>Gender:</label>
                <label class="size">
                <input type="radio" name="RGender" value="Male"  required> Male
                <input type="radio" name="RGender" value="Female"  required> Female
                </lable></br></br>
                
                <label for="CompanyName">Company Name:</label>
                <input type="text" name="CompanyName" class="input-field" required></br>

                <label for="CompanyLocation">Company Location:</label>
                <input type="text" name="CompanyLocation" class="input-field" required></br>
                </br>

                <center><input type="submit" value="REGISTER" class="register-btn"></center>
            </form>
        </div>
    </div>
</div>

</body>
</html>
