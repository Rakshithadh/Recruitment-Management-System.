<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION["email"]) && isset($_SESSION["name"]) && isset($_SESSION["type"])) {
    $email = $_SESSION["email"];
    $name = $_SESSION["name"];
    $type = $_SESSION["type"];
}

$CID = $_SESSION["CID"];

$search = isset($_POST['search']) ? $_POST['search'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["AID"])) {
    $AID = $_POST["AID"];
    $sql_delete_application = "DELETE FROM application WHERE AID = '$AID'";
    if ($conn->query($sql_delete_application) === TRUE) {
        $_SESSION['delete_success'] = true; 
        header("Location: CMyapplication.php");
        exit();
    } else {
        echo "Error: " . $sql_delete_application . "<br>" . $conn->error;
    }
}

$sql_select_applied_jobs = "SELECT application.AID, job.JobRole, job.JobType, recruitment.CompanyName, recruitment.CompanyLocation, recruitment.RName
                            FROM application
                            JOIN job ON application.JID = job.JID
                            JOIN recruitment ON job.RID = recruitment.RID
                            WHERE application.CID = '$CID'
                            AND (application.AID LIKE '%$search%' OR job.JobRole LIKE '%$search%' OR job.JobType LIKE '%$search%' OR recruitment.CompanyName LIKE '%$search%' OR recruitment.CompanyLocation LIKE '%$search%' OR recruitment.RName LIKE '%$search%')";
$sql_select_applied_jobs .= " ORDER BY application.AID ASC";
$result_applied_jobs = $conn->query($sql_select_applied_jobs);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
    <title>My Applications</title>
</head>
<body>
    <div class="wel">
    <h1>Welcome, <?php echo $name; ?></h1> 
    </div>

    <div class="nav-right">
            <a href="CAvailable_job.php" class="nav-link">Available jobs</a>
            <a href="CMyapplication.php" class="nav-link">My application</a>
            <a href="Logout.php" class="nav-link">Logout</a>
      </div>

        <div class="box">
            <div class="inbox">
                <br>
                <form method="post" action="">
                    <label for="search">Search:</label>
                    <input type="text" name="search" >
                    <input type="submit" value="Search" >
                </form>

                <br>
                <?php if(isset($_SESSION['delete_success']) && $_SESSION['delete_success']) : ?>
                    <script>
                        alert('Application has been successfully deleted.');
                    </script>
                    <?php unset($_SESSION['delete_success']); ?> 
                <?php endif; ?>
                <table border="1" style="width:100%">
                    <tr>
                        <th>AID</th>
                        <th>Recruiter Name</th>
                        <th>Job Role</th>
                        <th>Job Type</th>
                        <th>Company Name</th>
                        <th>Company Location</th>
                        <th>Action</th>
                    </tr>
                    <?php
                     if ($result_applied_jobs->num_rows > 0) {
                         while ($row = $result_applied_jobs->fetch_assoc()) {
                             echo "<tr>";
                             echo "<td>" . htmlspecialchars($row['AID']) . "</td>";
                             echo "<td>" . htmlspecialchars($row['RName']) . "</td>";
                             echo "<td>" . htmlspecialchars($row['JobRole']) . "</td>";
                             echo "<td>" . htmlspecialchars($row['JobType']) . "</td>";
                             echo "<td>" . htmlspecialchars($row['CompanyName']) . "</td>";
                             echo "<td>" . htmlspecialchars($row['CompanyLocation']) . "</td>";
                             echo "<td>
                                       <form method='post' action='' onsubmit='return confirm(\"Are you sure you want to delete this application?\");'>
                                           <input type='hidden' name='AID' value='" . htmlspecialchars($row['AID']) . "'>
                                           <input type='submit' value='Delete'>
                                       </form>
                                   </td>";
                             echo "</tr>";
                         }
                     } else {
                         echo "<tr><td colspan='7'>No applied jobs.</td></tr>";
                     }
                     ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
