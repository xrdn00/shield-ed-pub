<?php
session_start();

if (isset($_SESSION["users_id"])){
    $mysqli = require __DIR__ . "/connect.php";

    $sql = "SELECT * FROM users
            WHERE id = {$_SESSION["users_id"]}";
    $result = $mysqli->query($sql);

    $users = $result->fetch_assoc();
}
else{
  header("Location: login.php");
}


date_default_timezone_set('Asia/Manila');
$script_tz = date_default_timezone_get();
$date = date('Y-m-d-H:i:s-a')
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="admin/style.css">
    <title>Shield-Ed+: Safety and Prevention App</title>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" class="logo">
            <img src="admin/images/logo.png" alt="" style="width: 2.5em; height: 2.5em;">
            <div class="logo-name"><span>Shield-</span>Ed+</div>
        </a>
        <ul class="side-menu">
            <li><a href="report.php"><i class='bx bx-microphone'></i>Emergency</a></li>
            <li><a href="announcements.php"><i class='bx bx-message-square-dots'></i>Announcements</a></li>
            <li class="active"><a href="admins.php"><i class='bx bx-group'></i>Admin List</a></li>

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
                                Choose an admin to call
                            </a></li>

                    </ul>
                </div>

            </div>
            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-receipt'></i>
                        <h3>Admin List</h3>

                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID no:</th>
                                <th>Admin Name</th>

                            </tr>
                        </thead>
                        <tbody>


                            

                            <?php

                                $sqli = "SELECT id,firstname FROM admins ORDER BY id desc LIMIT 10";
                                $result = mysqli_query($mysqli,$sqli);
                                if(mysqli_num_rows($result) > 0){
                                    while($rows=mysqli_fetch_assoc($result)){
                                        echo "<tr>";
                                        echo "<td>";
                                        echo "<p>";
                                        echo $rows['id'];
                                        echo "</p>";
                                        echo "</td>";
                                        echo "<td>";
                                        echo $rows['firstname'];
                                        echo "</td>";
                                        echo "</tr>";
        
        
                                    }
                                        
                                }
                                else{
                                    echo "No messages.";
                                }
                                
                            ?>


                            





                        </tbody>
                    </table>


                </div>



            </div>
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

        </main>

    </div>
    <script src = "https://unpkg.com/peerjs@1.5.2/dist/peerjs.min.js"></script>
 
    <script src="admin/index.js"></script>
    <script>

        init(<?= htmlspecialchars($users["id"])?>);

    </script>
</body>

</html>