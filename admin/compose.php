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
$date = date('Y-m-d-g:i:s-a');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <title>Shield-Ed+: Safety and Prevention App</title>
    

</head>

<body>
    <div class="modal-ov" id="modalOverlay">
		<div class="modal1">
			<div class="modal1-content">
				<div class="modal1-header">
					<h5 class="modal1-title">Announcement Released</h5>
					<button class="close-btn" id="closeBtn">&times;</button>
				</div>
				<div class="modal1-body">
					<p>Announcement has been released successfully to users/students.</p>
				</div>
			</div>
		</div>
	</div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" class="logo">
            <img src="images/logo.png" alt="" style="width: 2.5em; height: 2.5em;">
            <div class="logo-name"><span>Shield-</span>Ed+</div>
        </a>
        <ul class="side-menu">
            <li><a href="dashboard.php"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li><a href="reports.php"><i class='bx bxs-report'></i>Reports</a></li>
            <li class="active"><a href="compose.php"><i class='bx bx-message-square-dots'></i>Compose Announcements</a>
            </li>
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
                                Compose Announcements
                            </a></li>

                    </ul>
                </div>

            </div>



            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-message-square-dots'></i>
                        <h3>Compose Announcements</h3>

                    </div>
                    <form action = "msgprocess.php" id="messageForm" method="post">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Announcement Title</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" name="title" placeholder="Title">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Compose Announcement</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="message"
                                placeholder="Compose"></textarea>
                            <input type="hidden" name = "author" id = "author" value = <?= htmlspecialchars($admins["firstname"])?>>
                            <input type="hidden" name = "admin_time" id = "admin_time" value = <?= $date?>>
                            <button class="btn-coreui">Submit</button>
                        </div>

                    </form>


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
    <script>
        $("#messageForm").submit(function(event) {
            event.preventDefault();

            // Perform the AJAX post
            $.post("msgprocess.php", $(this).serialize(), function(data) {
            // Parse the JSON response from the server
            var responseData = JSON.parse(data);

            // If the response indicates a successful sign up
            if (responseData.success) {
                // Show the modal
                $("#modalOverlay").css('display', 'flex');
            } else {
                // Handle sign up failure (you can customize this part)
                alert("Sign up failed: " + responseData.error);
            }
        });
        });
    </script>
    <script>
        closeBtn.addEventListener('click', () => {
        modalOverlay.style.display = 'none';
        });
        modalOverlay.addEventListener('click', (event) => {
            if (event.target === modalOverlay) {
                modalOverlay.style.display = 'none';
            }
            });
            
    </script>

    

</body>

</html>