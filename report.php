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
    
    <form id="calldata" action="calldata_process.php" method="post">
        <input type="hidden" name="participants" id="participants">
        <input type="hidden" name="caller" id="caller">
        <input type="hidden" name="callee" id="callee">
        <input type="hidden" name="startTime" id="startTime">
        <input type="hidden" name="endTime" id="endTime">

        <input type="hidden" name="user_id" id="user_id" value="<?= htmlspecialchars($users["id"])?>">
        <input type="hidden" name="user_firstname" id="user_firstname" value=<?=htmlspecialchars($users["firstname"])?>>
        <input type="hidden" name="user_lastname" id="user_lastname" value=<?=htmlspecialchars($users["lastname"])?>>
        <input type="hidden" name="user_email" id="user_email" value=<?=htmlspecialchars($users["email"])?>>
        <input type="hidden" name="category" id="category" value="">
        <input type="hidden" name="user_time" id="user_time" value="">

    </form>



    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" class="logo">
            <img src="admin/images/logo.png" alt="" style="width: 2.5em; height: 2.5em;">
            <div class="logo-name"><span>Shield-</span>Ed+</div>
        </a>
        <ul class="side-menu">
            <li class="active"><a href="report.php"><i class='bx bx-microphone'></i>Emergency</a></li>
            <li><a href="announcements.php"><i class='bx bx-message-square-dots'></i>Announcements</a></li>
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
            <span style="padding-right:10%;">
                <?php if (isset($users)): ?>

                <div style='color:#008000; text-align: center;'>
                    <p>User:

                        <?= htmlspecialchars($users["firstname"])?>

                    </p>
                </div>

                <?php endif; ?>
            </span>
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
                                Report An Incident
                            </a></li>

                    </ul>
                </div>

            </div>
            <ul class="insights">
                <li id="construction">
                    <img src="admin/images/construction_e.png" alt="" style="width: 5em; height: 5em;">
                    <span style="color: #ff7f27;">Construction Hazard</span>

                </li>
                <li id="violence">
                    <img src="admin/images/violence_e.png" alt="" style="width: 5em; height: 5em;">
                    <span style="color: #ff7f27;">Violence/Bullying</span>
                </li>
                <li id="medical">
                    <img src="admin/images/medical_e.png" alt="" style="width: 5em; height: 5em;">
                    <span style="color: #ff7f27;">Medical Attention</span>
                </li>
                <li id="fire">
                    <img src="admin/images/fire_e.png" alt="" style="width: 5em; height: 5em;">
                    <span style="color: #ff7f27;">Fire/Explosion</span>
                </li>
            </ul>
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



            <div id="customModal" class="modal">
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

        </main>

    </div>
    <script src="https://unpkg.com/peerjs@1.5.2/dist/peerjs.min.js"></script>

    <script type="module">
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.2/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.7.2/firebase-analytics.js";
        import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.7.2/firebase-messaging.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
            //generate your own firebase config, the past config has been revoked for security reasons.
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);

        const messaging = getMessaging(app);
        const token = getToken(messaging);
        Notification.requestPermission().then((permission) => {
            if (permission === 'granted') {
                console.log('Notification permission granted.');

                // Retrieve the FCM token
                getToken(messaging)
                    .then((token) => {
                        console.log('FCM token:', token);
                        // Send this token to your server for sending push notifications
                        fetch('save_token.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ token:token, id:<?= htmlspecialchars($users["id"])?> }),
                        });

                        // Subscribe to topics
                        var topics = ['construction', 'violence', 'medical', 'fire'];
                        topics.forEach(function (topic) {
                            fetch('https://express-server-vnhn.onrender.com/subscribeToTopic', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({ token: token, topic: topic }),
                            })
                                .then(response => response.json())
                                .then(data => console.log(data))
                                .catch((error) => {
                                    console.error('Error:', error);
                                });
                        });
                    })
                    .catch((error) => {
                        console.log('Error while retrieving token:', error);
                        location.reload();
                    });
            } else {
                console.log('Notification permission denied.');
            }
        });
        
        


    </script>
    <script>

        const btn1 = document.getElementById("construction");
        const btn2 = document.getElementById("violence");
        const btn3 = document.getElementById("medical");
        const btn4 = document.getElementById("fire");

        btn1.addEventListener('click', function() {
            studentcall1();
        })
        btn2.addEventListener('click', function() {
            studentcall2();
        })
        btn3.addEventListener('click', function() {
            studentcall3();
        })
        btn4.addEventListener('click', function() {
            studentcall4();
        })

    </script>

    <script src="admin/index.js"></script>
    <script>
        init(<?= htmlspecialchars($users["id"]) ?>);
    </script>
</body>

</html>