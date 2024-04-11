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




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <title>Shield-Ed+: Safety and Prevention App</title>
    <script>
        $(document).ready(function(){
            var messagecount = 2;
            $("#show").click(function(){
                messagecount = messagecount + 2;
                $("#messages .msg").load("load_user_reports.php", {
                    newcount: messagecount
                });
            });
        });
    </script>
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
            <li class="active"><a href="reports.php"><i class='bx bxs-report'></i>Reports</a></li>
            <li><a href="compose.php"><i class='bx bx-message-square-dots'></i>Compose Announcements</a></li>
            <li><a href="search.php"><i class='bx bx-group'></i>Search Student</a></li>
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
                                Reports
                            </a></li>

                    </ul>
                </div>

            </div>



            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-receipt'></i>
                        <h3>Recent Reports</h3>

                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Case id</th>
                                <th>User</th>
                                <th>Date Reported</th>
                                <th>Category</th>
                                <th>Email</th>
                                <th>Participants</th>
                                <th>Caller</th>
                                <th>Callee</th>
                                <th>Call Started</th>
                                <th>Call Ended</th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php
                                        $sqli = "SELECT participants,caller,callee,startTime,endTime,user_id,user_firstname,user_lastname,user_email,category,id,user_time FROM calldata ORDER BY id desc";
                                        $result = mysqli_query($mysqli,$sqli);
                                        if(mysqli_num_rows($result) > 0){
                                            while($rows=mysqli_fetch_assoc($result)){
                                                echo "<tr>";
                                                echo "<td>".$rows["id"]."</td>";
                                                echo "<td>";
                                                echo "<p>".$rows['user_firstname']." ".$rows['user_lastname']."</p>";
                                                echo "</td>";
                                                echo "<td>".$rows['user_time']."</td>";
                                                echo "<td>".$rows['category']."</td>";
                                                echo "<td>".$rows['user_email']."</td>";
                                                echo "<td>".$rows['participants']."</td>";
                                                echo "<td>".$rows['caller']."</td>";
                                                echo "<td>".$rows['callee']."</td>";
                                                echo "<td>".$rows['startTime']."</td>";
                                                echo "<td>".$rows['endTime']."</td>";
                                                
                                                echo "</tr>";
                                            }
                                            
                                        }
                                        else{
                                            echo "<td>"."No messages."."</td>";
                                        }
                                        
                                    ?>
                            

                            

                        </tbody>
                    </table>
                </div>



            </div>

        </main>

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
        <button id="hangupbtn" class="hangupbtn" onclick="hangup()">Hang Up</button>
    </div>

    
    <script src = "https://unpkg.com/peerjs@1.5.2/dist/peerjs.min.js"></script>
    <script src="index.js"></script>
    <script>
    init(<?= htmlspecialchars($admins["id"])?>);
    </script>
</body>

</html>