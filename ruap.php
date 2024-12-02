<html>
<head>
 <Title>Registration Form</Title>
 <style type="text/css">
 body {
 background-color: #fff;
 border-top: solid 10px #000;
 color: #333;
 font-size: .85em;
 margin: 20;
 padding: 20;
 font-family: "Segoe UI", Verdana, Helvetica, Sans-Serif;
 }
 h1, h2, h3 {
 color: #000;
 margin-bottom: 0;
 padding-bottom: 0;
 }
 h1 {
 font-size: 2em;
 }
 h2 {
 font-size: 1.75em;
 }
 h3 {
 font-size: 1.2em;
 }
 table {
 margin-top: 0.75em;
 }
 th {
 font-size: 1.2em;
 text-align: left;
 border: none;
 padding-left: 0;
 }
 td {
 padding: 0.25em 2em 0.25em 0em;
 border: 0 none;
 }
 </style>
</head>
<body>
 <h1>Register here!</h1>
 <p>Fill in your name and email address, then click <strong>Submit</strong> to register.</p>
 <form method="post" action="index.php">
  <label for="name">Name:</label><br />
  <input type="text" name="name" id="name" required /><br /><br />
  <label for="email">Email:</label><br />
  <input type="email" name="email" id="email" required /><br /><br />
  <input type="submit" name="submit" value="Submit" />
 </form>

<?php
// DB connection info
$host = "ruap-server";
$user = "xgtshiwape";
$pwd = "hmA4JKI$dE8jHgV1-data";
$db = "ruap-database";

// Connect to the database
$conn = mysqli_connect($host, $user, $pwd, $db);
if (mysqli_connect_errno()) {
    echo "<h3>Failed to connect to MySQL:</h3> " . mysqli_connect_error();
} else {
    // Form processing
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize input
        $name = mysqli_real_escape_string($conn, trim($_POST['name']));
        $email = mysqli_real_escape_string($conn, trim($_POST['email']));

        // Validate email format
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $date = date("Y-m-d");

            // Insert registration info
            $sql_insert = "INSERT INTO registration_tbl (name, email, date) VALUES ('$name', '$email', '$date')";
            if ($conn->query($sql_insert) === TRUE) {
                echo "<h3>You're registered!</h3>";

                // Retrieve and display the registered people
                $sql_select = "SELECT * FROM registration_tbl";
                $registrants = $conn->query($sql_select);
                if ($registrants->num_rows > 0) {
                    echo "<h2>People who are registered:</h2>";
                    echo "<table>";
                    echo "<tr><th>Name</th><th>Email</th><th>Date</th></tr>";
                    while ($registrant = $registrants->fetch_assoc()) {
                        echo "<tr><td>" . htmlspecialchars($registrant['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($registrant['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($registrant['date']) . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<h3>No one is currently registered.</h3>";
                }
            } else {
                echo "<h3>Insert failed. Please try again.</h3>";
            }
        } else {
            echo "<h3>Invalid email format.</h3>";
        }
    }
}

// Close database connection
mysqli_close($conn);
?>
</body>
</html>
