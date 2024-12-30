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

    if (isset($_GET['JID'])) {
        $JID = $_GET['JID'];

        $sql_delete_job = "DELETE FROM job WHERE JID = $JID";

        if ($conn->query($sql_delete_job) === TRUE) {
            $_SESSION['delete_success'] = true;
            header("Location: RPosted_job.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }

    $sql_select_jobs = "SELECT * FROM job WHERE ( JID LIKE '%$search%' OR RID LIKE '%$search%' OR JobRole LIKE '%$search%' OR JobType LIKE '%$search%' OR Qualification LIKE '%$search%' OR Salary LIKE '%$search%' OR MinExp LIKE '%$search%')
    AND RID = (SELECT RID FROM recruitment WHERE REmail = '$email')";
    $sql_select_jobs .= " ORDER BY $sort ASC";

    $result_jobs = $conn->query($sql_select_jobs);
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
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="search">Search:</label>
                    <input type="text" name="search">
                    <input type="submit" value="Search">
                </form>
                <?php if(isset($_SESSION['delete_success']) && $_SESSION['delete_success']) : ?>
                <script>
                    alert('Record deleted successfully');
                </script>
                <?php unset($_SESSION['delete_success']); ?> 
            <?php endif; ?>
            <table border="1" style="width:100%">
                <tr>
                    <th>JID</th>
                    <th>RID</th>
                    <th><a href="?sort=JobRole" >Job Role</a></th>
                    <th><a href="?sort=JobType">Job Type</a></th>
                    <th><a href="?sort=Qualification" >Qualification</a></th>
                    <th><a href="?sort=MinExp" >Experience</a></th>
                    <th><a href="?sort=Salary">Salary</a></th>
                    <th> Update</th>
                    <th> Delete </th>
                </tr>
                <?php
                if ($result_jobs->num_rows > 0) {
                    while ($row = $result_jobs->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['JID'] . "</td>";
                        echo "<td>" . $row['RID'] . "</td>";
                        echo "<td>" . $row['JobRole'] . "</td>";
                        echo "<td>" . $row['JobType'] . "</td>";
                        echo "<td>" . $row['Qualification'] . "</td>";
                        echo "<td>" . $row['MinExp'] . "</td>";
                        echo "<td>" . $row['Salary'] . "</td>";
                        echo "<td><a href='update.php?JID=" . $row['JID'] . "'>Update</a></td>";
                        echo "<td><a href='?JID=" . $row['JID'] . "' onclick='return confirmDelete()'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No posted jobs</td></tr>";
                }
                ?>
            </table>
            
        </div>
    </div>
</div>

<script>

function confirmDelete() {
    return confirm("Are you sure you want to delete this job?");
}
</script>

</body>
</html>
