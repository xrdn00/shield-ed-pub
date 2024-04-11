<?php
session_start();


 
if (isset($_SESSION["admins_id"])){
    $mysqli = require __DIR__ . "/connect.php";

    $sql = "SELECT * FROM admins
            WHERE id = {$_SESSION["admins_id"]}";
    $result = $mysqli->query($sql);

    $admins = $result->fetch_assoc();
}
else{
  header("Location: login.php");
}

date_default_timezone_set('Asia/Manila');
$script_tz = date_default_timezone_get();
$date = date('Y-m-d-H:i:s-a');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Shield-Ed+: Safety and Prevention App</title>
</head>

<body>
    <form id="calldata" name="calldata" action="../calldata_process_admin.php" method="post">

    <input type="hidden" name="participants" id="participants">
    <input type="hidden" name="caller" id="caller">
    <input type="hidden" name="callee" id="callee">
    <input type="hidden" name="startTime" id="startTime">
    <input type="hidden" name="endTime" id="endTime">

    <input type="hidden" name="user_id" id="user_id">
    <input type="hidden" name="user_firstname" id="user_firstname">
    <input type="hidden" name="user_lastname" id="user_lastname">
    <input type="hidden" name="user_email" id="user_email">
    <input type="hidden" name="category" id="category">
    <input type="hidden" name="user_time" id="user_time">

    </form>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" class="logo">
            <img src="images/logo.png" alt="" style="width: 2.5em; height: 2.5em;">
            <div class="logo-name"><span>Shield-</span>Ed+</div>
        </a>
        <ul class="side-menu">
            <li><a href="dashboard.php"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li><a href="reports.php"><i class='bx bxs-report'></i>Reports</a></li>
            <li><a href="compose.php"><i class='bx bx-message-square-dots'></i>Compose Announcements</a></li>
            <li class="active"><a href="search.php"><i class='bx bx-group'></i>Search Student</a></li>
            <li><a href="callstudent.php"><i class='bx bx-microphone'></i>Call Student</a></li>
            <li><a href="sendnotif.php"><i class='bx bx-notification'></i>Send Notifications</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>

            <span style="color: green;">Dark/Light Mode:</span>
            <input type="checkbox" id="theme-toggle" hidden>
            <label for="theme-toggle" class="theme-toggle"></label>

        </nav>

        <!-- End of Navbar -->

        <main>
            <div class="header">
                <div class="left">
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li><a href="#" class="active">
                                Search Student
                            </a></li>

                    </ul>
                </div>

            </div>



            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-group'></i>
                        <h3>Search Student</h3>

                    </div>
                    <form method="get">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Enter First Name or Last Name</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1"
                                placeholder="Student First Name or Last Name" name="search">
                        </div>
                        <button class="btn-coreui">Search</button>
                    </form>


                </div>



            </div>
            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-group'></i>
                        <h3>Results</h3>
                        <table>
                            
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                </tr>

                            </thead>
                            <tbody style="height: 30vh;">
                            <?php
          // Check if the search query parameter is set
          if(isset($_GET['search']) && !empty($_GET['search'])) {
            // Get the search query from the form
            $search_query = $_GET['search'];
        
            // Perform any necessary sanitization or validation of the search query
        
            // Connect to your database (assuming MySQL in this example)
            $db_host = 'localhost';
            $db_username = 'root';
            $db_password = '';
            $db_name = 'login_db';
            
            $conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);
        
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
        
            // Construct your prepared statement
            $sql = "SELECT id, firstname, lastname, email FROM users WHERE firstname LIKE ? OR lastname LIKE ?";
            
            // Prepare the statement
            $stmt = mysqli_prepare($conn, $sql);
        
            // Bind parameters
            $search_param = "%" . $search_query . "%"; // Adjusting search parameter to match partial matches
            mysqli_stmt_bind_param($stmt, "ss", $search_param, $search_param);
        
            // Execute the prepared statement
            mysqli_stmt_execute($stmt);
        
            // Get result set
            $result = mysqli_stmt_get_result($stmt);
        
            // Check if there are any results
            if (mysqli_num_rows($result) > 0) {
                // Output the results
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>";
                    echo $row["id"];
                    echo "</td>";
                    echo "<td>";
                    echo $row["firstname"];
                    echo "</td>";
                    echo "<td>";
                    echo $row["lastname"];
                    echo "</td>";
                    echo "<td>";
                    echo $row["email"];
                    echo "</td>";
                    echo "</tr>";

                }
            } else {
                echo "<td>";
                echo "No results found";
                echo "</td>";
            }
        
            // Close the statement
            mysqli_stmt_close($stmt);
        
            // Close the connection
            mysqli_close($conn);
          }
          else{
            echo "<td>";
            echo "Please enter a search query.";
            echo "</td>";
          } 
        ?>

                               

                            </tbody>

                        </table>


                    </div>


                </div>



            </div>

        </main>
        <div id="customModal" class="modal">
            <div id="callInfo"></div>
            <button id="acceptButton">Accept</button>
            <button id="declineButton">Decline</button>
        </div>

        <div id="admincall" class="adminCall">
            <div id="callInfo">Call a Student</div>
            <input type="text" name="studentid" id="studentid">
            <button id="callbutton" onclick="sendMessage()">Call</button>
            <button id="cancelbutton">Cancel</button>
        </div>

        <div id="userDecline" class="userDecline">
            <div id="declineInfo">Call Declined</div>
            <button id="okbutton" onclick="">OK</button>
        </div>

        <div class="vcallmodal" id="vcallmodal">
            <div class="video">
            <div class="primary-video" id="remoteVideo"></div>
            <div class="secondary-video" id="localVideo"></div>
            </div>
            <button id="hangupbtn" class="hangupbtn">Hang Up</button>
        </div>

    </div>

    <script src = "https://unpkg.com/peerjs@1.5.2/dist/peerjs.min.js"></script>
    <script src="index.js"></script>
    <script>
    init(<?= htmlspecialchars($admins["id"])?>);
    </script>
</body>
</body>

</html>