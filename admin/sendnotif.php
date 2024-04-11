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
            <li><a href="search.php"><i class='bx bx-group'></i>Search Student</a></li>
            <li><a href="callstudent.php"><i class='bx bx-microphone'></i>Call Student</a></li>
            <li class="active"><a href="sendnotif.php"><i class='bx bx-notification'></i>Send Notifications</a></li>
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
                                Send Emergency Notifications
                            </a></li>

                    </ul>
                </div>

            </div>
            <ul class="insights">
                <li>
                    <img src="images/construction_e.png" alt="" style="width: 5em; height: 5em;">
                    <span style="color: #ff7f27;" onclick="send1()">Construction Hazard</span>

                </li>
                <li>
                    <img src="images/violence_e.png" alt="" style="width: 5em; height: 5em;">
                    <span style="color: #ff7f27;" onclick="send2()">Violence/Bullying</span>
                </li>
                <li>
                    <img src="images/medical_e.png" alt="" style="width: 5em; height: 5em;">
                    <span style="color: #ff7f27;" onclick="send3()">Medical Attention</span>
                </li>
                <li>
                    <img src="images/fire_e.png" alt="" style="width: 5em; height: 5em;">
                    <span style="color: #ff7f27;" onclick="send4()">Fire/Explosion</span>
                </li>
            </ul>
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
    <script>
 
 
         async function send1() {
             try {
                 const response = await fetch("https://express-server-vnhn.onrender.com/sendtopicnotif", {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json'
                     },
                     body: JSON.stringify({
                         category: "construction"
                     })
 
                 });
 
 
 
                 if (response.ok) {
                     console.log('Notification sent successfully.');
                 } else {
                     console.error('Error sending notification:', response.statusText);
                 }
             } catch (error) {
                 console.error('Error sending notification:', error);
             }
         }
         async function send2() {
             try {
                 const response = await fetch('https://express-server-vnhn.onrender.com/sendtopicnotif', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json'
                     },
                     body: JSON.stringify({
                         category: "violence"
                     })
 
                 });
 
 
 
                 if (response.ok) {
                     console.log('Notification sent successfully.');
                 } else {
                     console.error('Error sending notification:', response.statusText);
                 }
             } catch (error) {
                 console.error('Error sending notification:', error);
             }
         }
         async function send3() {
             try {
                 const response = await fetch('https://express-server-vnhn.onrender.com/sendtopicnotif', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json'
                     },
                     body: JSON.stringify({
                         category: "medical"
                     })
 
                 });
 
 
 
                 if (response.ok) {
                     console.log('Notification sent successfully.');
                 } else {
                     console.error('Error sending notification:', response.statusText);
                 }
             } catch (error) {
                 console.error('Error sending notification:', error);
             }
         }
         async function send4() {
             try {
                 const response = await fetch('https://express-server-vnhn.onrender.com/sendtopicnotif', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json'
                     },
                     body: JSON.stringify({
                         category: "fire"
                     })
 
                 });
 
 
 
                 if (response.ok) {
                     console.log('Notification sent successfully.');
                 } else {
                     console.error('Error sending notification:', response.statusText);
                 }
             } catch (error) {
                 console.error('Error sending notification:', error);
             }
         }
     </script>

    <script src = "https://unpkg.com/peerjs@1.5.2/dist/peerjs.min.js"></script>

    <script src="index.js"></script>
    
    <script>
        init(<?= htmlspecialchars($admins["id"])?>);
    </script>
    
</body>
</body>

</html>