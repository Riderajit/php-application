<?php include "db.inc.php"; ?>

<html>
<body>
<h1>Sample Page</h1>

<?php

/* Connect to MySQL and select the database. */
$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

/* Ensure that the EMPLOYEES table exists. */
VerifyEmployeesTable($connection);

/* Check if the form is submitted */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_name = htmlentities($_POST['NAME']);
    $employee_address = htmlentities($_POST['ADDRESS']);

    if (strlen($employee_name) > 0 && strlen($employee_address) > 0) {
        AddEmployee($connection, $employee_name, $employee_address);
    } else {
        echo "<p>Please provide both a name and an address.</p>";
    }
}

// Add predefined employee data directly
AddEmployee($connection, 'ajith', 'kalamboli');
AddEmployee($connection, 'santosh', 'chennai');
AddEmployee($connection, 'vignesh', 'malad');
AddEmployee($connection, 'karthik', 'kalamboli');
?>

<!-- Input form -->
<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">
  <table border="0">
    <tr>
      <td>NAME</td>
      <td>ADDRESS</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="NAME" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="ADDRESS" maxlength="90" size="60" />
      </td>
      <td>
        <input type="submit" value="Add Data" />
      </td>
    </tr>
  </table>
</form>

<!-- Display table data -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>NAME</td>
    <td>ADDRESS</td>
  </tr>

<?php
$result = mysqli_query($connection, "SELECT * FROM EMPLOYEES");

while ($query_data = mysqli_fetch_row($result)) {
    echo "<tr>";
    echo "<td>", $query_data[0], "</td>",
         "<td>", $query_data[1], "</td>",
         "<td>", $query_data[2], "</td>";
    echo "</tr>";
}
?>

</table>

<!-- Clean up -->
<?php
mysqli_free_result($result);
mysqli_close($connection);
?>

</body>
</html>

<?php

/* Add an employee to the table */
function AddEmployee($connection, $name, $address) {
    $n = mysqli_real_escape_string($connection, $name);
    $a = mysqli_real_escape_string($connection, $address);

    $query = "INSERT INTO EMPLOYEES (NAME, ADDRESS) VALUES ('$n', '$a')";

    if (!mysqli_query($connection, $query)) {
        echo "<p>Error adding employee data: " . mysqli_error($connection) . "</p>";
    } else {
        echo "<p>Employee $n added successfully.</p>";
    }
}

/* Check whether the table exists and, if not, create it */
function VerifyEmployeesTable($connection) {
    if (!TableExists("EMPLOYEES", $connection)) {
        $query = "CREATE TABLE EMPLOYEES (
            ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(45),
            ADDRESS VARCHAR(90)
        )";

        if (!mysqli_query($connection, $query)) {
            echo "<p>Error creating table: " . mysqli_error($connection) . "</p>";
        }
    }
}

/* Check for the existence of a table */
function TableExists($tableName, $connection) {
    $t = mysqli_real_escape_string($connection, $tableName);
    $checktable = mysqli_query($connection,
        "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '" . DB_DATABASE . "'");

    return mysqli_num_rows($checktable) > 0;
}
?>

 
 


