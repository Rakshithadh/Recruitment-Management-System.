<?php
session_start(); 
include 'db_connect.php';

if (isset($_SESSION["email"]) && isset($_SESSION["name"]) && isset($_SESSION["type"])) {
    $email = $_SESSION["email"];
    $name = $_SESSION["name"];
    $type = $_SESSION["type"];
    
    $search = isset($_POST['search']) ? $_POST['search'] : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'JobRole';

    $sql_select_jobs = "SELECT job.JID, job.JobRole, job.JobType, job.Qualification, job.MinExp, job.Salary, recruitment.CompanyName, recruitment.CompanyLocation
                        FROM job
                        INNER JOIN recruitment ON job.RID = recruitment.RID
                        WHERE job.JID LIKE '%$search%' OR job.JobRole LIKE '%$search%' OR job.JobType LIKE '%$search%' OR job.Qualification LIKE '%$search%' OR job.MinExp LIKE '%$search%' OR  job.Salary LIKE '%$search%' OR recruitment.CompanyName  LIKE '%$search%' OR  recruitment.CompanyLocation LIKE '%$search%'";
    $sql_select_jobs .= " ORDER BY $sort ASC";
    
    $result_jobs = $conn->query($sql_select_jobs);
} else {
    header("Location: Login.php");
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
    <title>Posted Jobs</title>
</head>
<body>
<div class="wel">
        <h1>Welcome ,<?php echo $name; ?></h1>
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
                    <input type="text" name="search" >
                    <input type="submit" value="Search">
            </form>
            <table border="1" style="width:100%">
                <tr>
                    <th><a href="?sort=JID" style="text-decoration: none; color:black;">JID</a></th>
                    <th><a href="?sort=JobRole" style="text-decoration: none; color:black;">Job Role</a></th>
                    <th><a href="?sort=JobType" style="text-decoration: none; color:black;">Job Type</a></th>
                    <th><a href="?sort=Qualification" style="text-decoration: none; color:black;">Qualification</a></th>
                    <th><a href="?sort=MinExp" style="text-decoration: none; color:black;">Experience</a></th>
                    <th><a href="?sort=Salary" style="text-decoration: none; color:black;">Salary</a></th>
                    <th><a href="?sort=CompanyName" style="text-decoration: none; color:black;">Company Name</a></th>
                    <th><a href="?sort=CompanyLocation" style="text-decoration: none; color:black;">Company Location</a></th>
                </tr>
                <?php
                if ($result_jobs->num_rows > 0) {
                    while ($row = $result_jobs->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['JID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['JobRole']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['JobType']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Qualification']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['MinExp']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Salary']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['CompanyName']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['CompanyLocation']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No posted jobs</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>
