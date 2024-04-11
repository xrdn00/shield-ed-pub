<?php

include "connect.php";

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



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="admin/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <title>Shield-Ed+: Safety and Prevention App</title>
    <script>
    $(document).ready(function(){
        var messagecount = 2;
        $("button").click(function(){
            messagecount = messagecount + 2;
            $(".bottom-data .orders").load("load.php", {
                newcount: messagecount
            });
        });
    });
    </script>

</head>

<body>
    <form id="calldata" action="calldata_process.php" method="post">
      <input type="hidden" name = "participants" id = "participants">
      <input type="hidden" name = "caller" id = "caller">
      <input type="hidden" name = "callee" id = "callee">
      <input type="hidden" name = "startTime" id = "startTime">
      <input type="hidden" name = "endTime" id = "endTime">
      
      <input type="hidden" name = "user_id" id = "user_id" value = "<?= htmlspecialchars($users["id"])?>">
      <input type="hidden" name = "user_firstname" id = "user_firstname" value = <?= htmlspecialchars($users["firstname"])?>>
      <input type="hidden" name = "user_lastname" id = "user_lastname" value = <?= htmlspecialchars($users["lastname"])?>>
      <input type="hidden" name = "user_email" id = "user_email" value = <?= htmlspecialchars($users["email"])?>>
      <input type="hidden" name = "category" id = "category" value = "">
      <input type="hidden" name = "user_time" id = "user_time" value = "">
        
    </form>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" class="logo">
            <img src="admin/images/logo.png" alt="" style="width: 2.5em; height: 2.5em;">
            <div class="logo-name"><span>Shield-</span>Ed+</div>
        </a>
        <ul class="side-menu">
            <li><a href="report.php"><i class='bx bx-microphone'></i>Emergency</a></li>

            <li class="active"><a href="announcements.php"><i class='bx bx-message-square-dots'></i>Announcements</a></li>
            <li><a href="admins.php"><i class='bx bx-group'></i>Admin List</a></li>

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
                                Announcements
                            </a></li>

                    </ul>
                </div>

            </div>
            <div class="bottom-data">
            <div class="orders" style="height: 500px; overflow-y: scroll;">
                    <div class="header">
                        <i class='bx bx-receipt'></i>
                        <h3>Recent Announcements</h3>

                    </div>
                    <?php
                        $sqli = "SELECT id,author,title,message,admin_time FROM messages ORDER BY id desc LIMIT 2";
                        $result = mysqli_query($mysqli,$sqli);
                        if(mysqli_num_rows($result) > 0){
                            while($rows=mysqli_fetch_assoc($result)){
                                echo "<pre>";
                                echo "<div style ='padding-right:8px;padding-left:15px;color:green'>";
                                echo "<b> Admin: ";
                                echo $rows['author'];
                                echo " Date/Time: ".$rows['admin_time'];
                                echo "</b>";
                                echo "<br>";
                                echo "<br>";
                                echo "<h3>";
                                echo $rows['title'];
                                echo "</h3>";
                                echo "</div>";
                                echo "</pre>";
                                echo "<br>";
                                echo "<div style ='padding-right:8px;padding-left: 15px;color:green'>";
                                echo "<pre>"; 
                                echo $rows['message'];
                                echo "</pre>"; 
                                echo "</div>";
                                echo "<br>";
                                echo "<br>";

                            }
                            
                        }
                        else{
                            echo "No messages.";
                        }
                        
                    ?>

                
                </div>
                



            </div>
            <div class="show">
                <button class="btn-coreui">Show more announcements</button>
            </div>
            

        </main>
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



        <div id="customModal" class="callingmodal">
            <div id="callInfo"></div>
            <button id="acceptButton">Accept</button>
            <button id="declineButton">Decline</button>
        </div>

        <div id="studentcallmodal" class="studentcallmodal">
            <div id="callInfo">Call an admin</div>
            <input type="text" name="adminid" id="adminid">
            <button id="callbutton" onclick="StudentsendMessage()">Call</button>
            <button id="cancelbutton">Cancel</button>
        </div>

    </div>

    <script src = "https://unpkg.com/peerjs@1.5.2/dist/peerjs.min.js"></script>
    <script src="admin/index.js"></script>

    <script>

      init(<?= htmlspecialchars($users["id"])?>);

    </script>

</body>

</html>