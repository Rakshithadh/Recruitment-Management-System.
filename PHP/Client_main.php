<?php
session_start(); 
if (isset($_SESSION["name"])) {
    $name = $_SESSION["name"];
    $CID = $_SESSION["CID"];
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
  <title>Client Main Screen</title>
</head>
<body class="main-page">
    <div class="wel">
      <h1>Welcome, <?php echo htmlspecialchars($name); ?></h1> 
    </div>

    <div class="nav-right">
      <a href="CAvailable_job.php" class="nav-link">Available Jobs</a>
      <a href="CMyapplication.php" class="nav-link">My Applications</a>
      <a href="Logout.php" class="nav-link">Logout</a>
    </div>

    <div class="content">
    <div class="content-box">
        <p>Hey Client,</p>
        <p>Start applying for your dream job.</p>
    </div>
    </div>


</body>
</html>
