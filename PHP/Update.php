<?php
session_start();
if (!isset($_SESSION["email"]) || $_SESSION["type"] !== "Recruiter") {
    header("Location: 1login.php");
    exit();
}
$email = $_SESSION["email"];
$name = $_SESSION["name"];
include 'db_connect.php';

if (isset($_SESSION["email"]) && isset($_SESSION["name"]) && isset($_SESSION["type"])) {
    if (isset($_GET['JID'])) {
        $JID = $_GET['JID'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $jobRole = $_POST['jobRole'];
            $jobType = $_POST['jobType'];
            $qualification = $_POST['qualification'];
            $minExp = $_POST['minExp'];
            $salary = $_POST['salary'];

            $sql_update_job = "UPDATE job SET JobRole='$jobRole', JobType='$jobType', Qualification='$qualification', MinExp='$minExp', Salary='$salary' WHERE JID=$JID";

            if ($conn->query($sql_update_job) === TRUE) {
                $_SESSION['success_message'] = "Details successfully updated";
                echo "<script>alert('Details successfully updated');</script>";
                echo "<script>window.location = 'RPosted_job.php';</script>";
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }

        $sql_select_job = "SELECT * FROM job WHERE JID = $JID";
        $result_job = $conn->query($sql_select_job);

        if ($result_job->num_rows == 1) {
            $row = $result_job->fetch_assoc();
            $jobRole = $row['JobRole'];
            $jobType = $row['JobType'];
            $qualification = $row['Qualification'];
            $minExp = $row['MinExp'];
            $salary = $row['Salary'];
        } else {
            echo "Job not found";
            exit();
        }
    } else {
        echo "Invalid Job ID";
        exit();
    }
} else {
    header("Location: Login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
    <title>Posted Job</title>
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
    <form method="post" action="">
        <label for="jobRole">Job Role:</label>
        <input type="text" name="jobRole" value="<?php echo $jobRole; ?>" class="input-field" required></br>
        
        <label for="jobType">Job Type:</label>
            <select name="jobType" class="input-field" required>
                <option value="Part Time" <?php if ($jobType == 'Part Time') echo 'selected'; ?>>Part Time</option>
                <option value="Full Time" <?php if ($jobType == 'Full Time') echo 'selected'; ?>>Full Time</option>
                <option value="Internship" <?php if ($jobType == 'Internship') echo 'selected'; ?>>Internship</option>
            </select></br>
        
        <label for="qualification">Qualification:</label>
        <input type="text" name="qualification" value="<?php echo $qualification; ?>" class="input-field" required></br>
        
        <label for="minExp">Experience:</label>
        <input type="text" name="minExp" value="<?php echo $minExp; ?>"  class="input-field" required></br>
        
        <label for="salary">Salary:</label>
        <input type="text" name="salary" value="<?php echo $salary; ?>" class="input-field" required></br>

        <center><input type="submit" value="UPDATE" class="register-btn"> </center> 
    >
</div>
</div>
    </form>
</center>
</div>

</body>
</html>
