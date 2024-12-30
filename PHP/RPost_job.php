<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION["email"]) || $_SESSION["type"] !== "Recruiter") {
    header("Location: Login.php");
    exit();
}
$email = $_SESSION["email"];
$name = $_SESSION["name"];
$RID = $_SESSION["RID"];


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_SESSION["email"];
        $sql_select = "SELECT RID FROM recruitment WHERE REmail = '$email' LIMIT 1";
        $result_select = $conn->query($sql_select);

        if ($result_select) {
            if ($result_select->num_rows > 0) {
                $row = $result_select->fetch_assoc();
                $RID = $row['RID'];
                $JobRole = $_POST['jobrole'] ?? "";
                $JobType = $_POST['jobtype'] ?? "";
                $Qualification = $_POST['qualification'] ?? "";
                $MinExp = $_POST['minexp'] ?? "";
                $Salary = $_POST['salary'] ?? "";
                $sql_insert = "INSERT INTO job (RID, JobRole, JobType, Qualification, MinExp, Salary) 
                               VALUES ('$RID', '$JobRole', '$JobType', '$Qualification', '$MinExp', '$Salary')";
                if ($conn->query($sql_insert) === TRUE) {
                    header("Location: RPosted_job.php");
                    exit();
                } else {
                    echo "Error inserting record: " . $conn->error;
                }
            } else {
                echo "No data found in the recruitment table for the current recruiter.";
            }
        } else {
            echo "Error retrieving data from the recruitment table: " . $conn->error;
        }
    }

    $conn->close();
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
    <title>Post Job</title>
    <script>
        function showAlert(message) {
            alert(message);
        }
    </script>
</head>

<body class="main-page">
      <div class="wel">
        <h1>Welcome, <?php echo $name; ?></h1> 
      </div>

      <div class="nav-right">
            <a href="RPost_job.php" class="nav-link">Post job</a>
            <a href="RPosted_job.php" class="nav-link">Posted job</a>
            <a href="RApplication.php" class="nav-link">Applications</a>
            <a href="Allposted_job.php" class="nav-link">View jobs</a>
            <a href="Logout.php" class="nav-link">Logout</a>
      </div>

    
    <div class="register-box">
      <div class="register-inbox">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="jobrole"> Job Role:</label>
            <input type="text" name="jobrole" placeholder=" Enter Job Role" class="input-field" required></br>
            
            <label for="jobtype">Job Type:</label>
                <select name="jobtype" class="input-field" required>
                        <option value="Part Time">Part Time</option>
                        <option value="Full Time">Full Time</option>
                        <option value="Internship">Internship</option>
                </select></br>

            <label for="qualification">Qualification:</label>
            <input type="text" name="qualification" placeholder="Enter job Qualification" class="input-field" required></br>
            
            <label for="minexp">Experience:</label>
            <input type="text" name="minexp" placeholder="Enter min experience(years)" class="input-field" required></br>
            
            <label for="salary">Salary:</label>
            <input type="text" name="salary" placeholder="Enter Expected Salary" class="input-field" required></br>
    

            <center><input type="submit" value="SUBMIT" onclick="showAlert('Submitting...')" class="register-btn"> </center> 
            <br>
          </form>
      </div>
    </div>
  </div>
</body>
</html>
