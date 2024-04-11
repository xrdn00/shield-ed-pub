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
$date = date('Y-m-d-H:i:s-a')

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

    <div id="admincall" class="adminCall">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modal-results">
                <!-- Display results here -->
                <input type="hidden" name="studentid" id="studentid">
                <p id="idno"></p>
                <p id="firstname"></p>
                <p id="lastname"></p>
                <input type="hidden" name="fcmtoken" id="fcmtoken">
                <button id="callbutton" onclick="sendMessage()">Call</button>
                <button id="cancelbutton">Cancel</button>
            </div>
        </div>
    </div>
    <!--
         <div id="admincall" class="adminCall">
            <div id="callInfo">Call a Student</div>
            <input type="text" name="studentid" id="studentid">
            <button id="callbutton" onclick="sendMessage()">Call</button>
            <button id="cancelbutton">Cancel</button>
        </div>
    -->
   

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
            <li class="active"><a href="callstudent.php"><i class='bx bx-microphone'></i>Call Student</a></li>
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
                        <h3>Call Student</h3>

                    </div>
                    
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Enter ID no.</label>
                        <input type="text" name="search" class="form-control" id="exampleFormControlInput1" placeholder="ID#">
                        <button class="btn-coreui">Confirm</button>
                    </div>
                    

                </div>



            </div>

        </main>
        <div id="customModal" class="modal">
            <div id="callInfo"></div>
            <button id="acceptButton">Accept</button>
            <button id="declineButton">Decline</button>
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
    // JavaScript code
    const confirmButton = document.querySelector('.btn-coreui');
    const modal = document.getElementById('myModal');
    const modalResults = document.getElementById('modal-results');

    confirmButton.addEventListener('click', () => {
    const id = document.querySelector('input[name="search"]').value;

    // Send AJAX request to call.php
    fetch('call.php?id=' + id)
        .then(response => response.json())
        .then(data => {

            document.getElementById('firstname').innerText = data.firstname;
            document.getElementById('lastname').innerText = data.lastname;
            document.getElementById('idno').innerText = data.id;

            // Set the values of input fields
            document.getElementById('studentid').value = data.id;
            document.getElementById('fcmtoken').value = data.fcm;
            document.getElementById('firstname').value = data.firstname;
            document.getElementById('lastname').value = data.lastname;

            // Show the modal
            admincall.style.display = 'block';
    });
    const studentid = document.getElementById("studentid");
    const callbutton = document.getElementById("callbutton");
    const cancelbutton = document.getElementById("cancelbutton");
    callbutton.onclick = () => {
    admincall.style.display = "none";
    vcallmodal.style.display = "block";

    

    const intervalId = setInterval(() => {


      count++;
      if (count > 10) {
        clearInterval(intervalId);
        peerList.push("end");
      }
      if (peerList.length == 0) {
        
        makeCall(studentid.value);
        sendMessage();
        send();
        
        

      }
      if (peerList.length > 0) {
        clearInterval(intervalId);
        adminAskInfo();
      }




    }, 5000);



  }
  hangupbtn.onclick = () => {
    location.reload();
  }
  cancelbutton.onclick = () => {
    admincall.style.display = "none";
  }

});

    // Close modal when user clicks the close button
    const closeButton = document.querySelector('.close');
    closeButton.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    function send() {
        fetch('https://express-server-vnhn.onrender.com/sendusernotif', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ token: document.getElementById("fcmtoken").value }),
        })
            .then(response => response.json())
            .then(data => console.log(data))
            .catch((error) => {
                console.error('Error:', error);
            });

    }



</script>

<script src = "https://unpkg.com/peerjs@1.5.2/dist/peerjs.min.js"></script>
<script src="index.js"></script>

<script>

    init(<?= htmlspecialchars($admins["id"])?>);

</script>


</body>

</html>