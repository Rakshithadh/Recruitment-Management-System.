<?php
session_start();

if (isset($_SESSION["email"]) && $_SESSION["type"] === "Recruiter") {
    $email = $_SESSION["email"];
    $name = $_SESSION["name"];
    $RID = $_SESSION["RID"];
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
  <title>Recruiter Main Screen</title>
</head>

<body class="main-page">
        <div class="wel">
            <h1>Welcome, <?php echo htmlspecialchars($name); ?></h1> 
        </div>
    
        <div class="nav-right">
            <a href="RPost_job.php" class="nav-link">Post job</a>
            <a href="RPosted_job.php" class="nav-link">Posted job</a>
            <a href="RApplication.php" class="nav-link">Applications</a>
            <a href="Allposted_job.php" class="nav-link">View jobs</a>
            <a href="Logout.php" class="nav-link">Logout</a>
        </div>
        <div class="content">
            <div class="content-box">
                <p>Hey Recruiter, </p>
                <p>Start hiring clients by posting jobs.</p>
            </div>
        </div>
    
</body>
</html>
