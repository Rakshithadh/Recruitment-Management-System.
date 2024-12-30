<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION["email"]) && isset($_SESSION["name"]) && isset($_SESSION["type"])) {
    $email = $_SESSION["email"];
    $name = $_SESSION["name"];
    $type = $_SESSION["type"];
    $RID = $_SESSION["RID"];

    $search = isset($_POST['search']) ? $_POST['search'] : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'JobRole';

    $sql_select_applied_jobs = "SELECT application.AID, job.JobRole, job.JobType, client.CName, client.CEmail, client.CDOB, client.CLocation, client.CSkills, client.CQualification
                                FROM application
                                JOIN job ON application.JID = job.JID
                                JOIN client ON application.CID = client.CID
                                JOIN recruitment ON job.RID = recruitment.RID
                                WHERE recruitment.REmail = '$email'
                                AND (application.AID LIKE '%$search%' OR job.JobRole LIKE '%$search%' OR job.JobType LIKE '%$search%' OR client.CNAME LIKE '%$search%' OR client.CEMAIL LIKE '%$search%' OR client.CDob LIKE '%$search%' OR client.CLocation LIKE '%$search%' OR client.CSkills LIKE '%$search%' OR client.CQualification LIKE '%$search%')";
    $sql_select_applied_jobs .=  " ORDER BY application.AID ASC";

    $result_applied_jobs = $conn->query($sql_select_applied_jobs);
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
    <title>Applications</title>
</head>
<body>

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

<div class="box">
    <div class="inbox">
        <br>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="search">Search:</label>
            <input type="text" name="search">
            <input type="submit" value="Search">
        </form>
        <?php if(isset($_SESSION['delete_success']) && $_SESSION['delete_success']) : ?>
                    <script>
                        alert('Application has been successfully deleted.');
                    </script>
                    <?php unset($_SESSION['delete_success']); ?> 
                <?php endif; ?>
            <table border="1" style="width:100%">
               <tr>
                        <th>AID</th>
                        <th>Job Role</th>
                        <th>Job Type</th>
                        <th>Client Name</th>
                        <th>Client Email</th>
                        <th>Client DOB</th>
                        <th>Client Location</th>
                        <th>Client Skills</th>
                        <th>Client Qualification</th>
                        <th>Action</th>
                </tr>
                <?php 
            if ($result_applied_jobs->num_rows > 0) {
                while ($row = $result_applied_jobs->fetch_assoc()) { 
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['AID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['JobRole']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['JobType']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['CName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['CEmail']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['CDOB']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['CLocation']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['CSkills']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['CQualification']) . "</td>";
                    echo "<td>
                              <form method='post' action='' onsubmit='return confirm(\"Are you sure you want to delete this application?\");'>
                                  <input type='hidden' name='AID' value='" . htmlspecialchars($row['AID']) . "'>
                                  <input type='submit' value='Delete'>
                              </form>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No applied jobs.</td></tr>";
            }
            ?>
       </table>
            
                
    </div>
</div>

</body>
</html>
