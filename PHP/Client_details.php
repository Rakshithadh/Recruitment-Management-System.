<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["email"]) && isset($_SESSION["name"])) {
        $email = $_SESSION["email"];
        $name = $_SESSION["name"];

        $CDay = isset($_POST['CDay']) ? $_POST['CDay'] : "";
        $CMonth = isset($_POST['CMonth']) ? $_POST['CMonth'] : "";
        $CYear = isset($_POST['CYear']) ? $_POST['CYear'] : "";

        if (!empty($CDay) && !empty($CMonth) && !empty($CYear)) {
            $CDOB = date("Y-m-d", strtotime("$CYear-$CMonth-$CDay"));
        } else {
            echo "Error: Please provide a valid date of birth.";
            exit();
        }

        $CLocation = isset($_POST['CLocation']) ? $_POST['CLocation'] : "";
        $CGender = isset($_POST['CGender']) ? $_POST['CGender'] : "";
        $CExp = isset($_POST['CExp']) ? $_POST['CExp'] : "";
        $CSkills = isset($_POST['CSkills']) ? $_POST['CSkills'] : "";
        $CQualification = isset($_POST['CQualification']) ? $_POST['CQualification'] : "";

        $sql_insert = $conn->prepare("INSERT INTO client (CName, CEmail, CDOB, CLocation, CGender, CExp, CSkills, CQualification)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            CName = VALUES(CName), CDOB = VALUES(CDOB), CLocation = VALUES(CLocation),
            CGender = VALUES(CGender), CExp = VALUES(CExp), CSkills = VALUES(CSkills),
            CQualification = VALUES(CQualification)");

        $sql_insert->bind_param("ssssssss", $name, $email, $CDOB, $CLocation, $CGender, $CExp, $CSkills, $CQualification);

        if ($sql_insert->execute()) {
            $sql_insert->close();
            header("Location: Login.php");
            exit();
        } else {
            echo "Error: " . $sql_insert->error;
        }
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
    <title>Client Details</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body class="cdetails-page">
<div class="ccontainer">
    <div class="cbox">
            <center>REGISTER</center>
    </div>
    <div class="text">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Gender:</label>
                <label class="size">
                <input type="radio" value="Male" name="CGender" required> Male
                <input type="radio" value="Female" name="CGender" required> Female</lable></br></br>
        
            <label for="CDOB">Date of Birth:</label>
                <select name="CDay" class="input-field" required>
                    <option value="">Date</option>
                    <?php
                        for ($i = 1; $i <= 31; $i++) {
                            echo "<option value='$i'>$i</option>";
                        }
                    ?>
                </select>
                <select name="CMonth" class="input-field" required>
                    <option value="">Month</option>
                    <?php
                        $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                        foreach ($months as $index => $month) {
                            echo "<option value='".($index + 1)."'>$month</option>";
                        }
                    ?>
                </select>
                <select name="CYear" class="input-field" required>
                    <option value="">Year</option>
                    <?php
                        $currentYear = date("Y");
                        $startYear = $currentYear - 50;
                        for ($year = $currentYear; $year >= $startYear; $year--) {
                            echo "<option value='$year'>$year</option>";
                        }
                    ?>
                </select></br>

                <label for="CLocation">Location:</label>
                <input type="text" name="CLocation" class="input-field" required></br>
               
                <label for="CExp">Experience:</label>
                <input type="text" name="CExp" class="input-field" required></br>

                <label for="CQualification">Qualification:</label>
                <input type="text" name="CQualification" class="input-field" required></br>
               
                <label for="CSkills">Skills:</label>
                <input type="text" name="CSkills" class="input-field" required></br>
                </br>


                <center><input type="submit" value="REGISTER" class="register-btn"></center>
            </form>
        </div>
    </div>
</div>

</body>
</html>
