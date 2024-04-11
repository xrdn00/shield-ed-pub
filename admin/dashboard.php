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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <title>Shield-Ed+: Safety and Prevention App</title>
</head>

<body>
    

    <?php if (isset($admins)): ?>

    <?php endif; ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" class="logo">
            <img src="images/logo.png" alt="" style="width: 2.5em; height: 2.5em;">
            <div class="logo-name"><span>Shield-</span>Ed+</div>
        </a>
        <ul class="side-menu">
            <li class="active"><a href="dashboard.php"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li><a href="reports.php"><i class='bx bxs-report'></i>Reports</a></li>
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
                                Analytics
                            </a></li>

                    </ul>
                </div>

            </div>

            <!-- Insights -->
            <ul class="insights">
                <li>
                    <i class="bx bx-check-circle"></i>
                    <span class="info">
                        <h3>
                            <?php
                                $conn = require __DIR__ . "/connect.php";
                                $sql = "SELECT COUNT(*) as userCount FROM users";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                // Output data of each row
                                while($row = $result->fetch_assoc()) {
                                    echo $row["userCount"]. "<br>";
                                }
                                } else {
                                echo "0";
                                }
                                $conn->close();
                            ?>
                        </h3>
                        <p>Registered Users</p>
                    </span>
                </li>
                <li><i class='bx bxs-report'></i>
                    <span class="info">
                        <h3>
                            <?php
                                $category = require __DIR__ . "/connect.php";
                                $categorycount = "SELECT category, COUNT(*) as count FROM calldata WHERE category != 'admin called.' AND category != '' ";
                                $result = $category->query($categorycount);

                                if ($result->num_rows > 0) {
                                    // output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        echo $row["count"];
                                    }
                                } else {
                                    echo "0 results";
                                }
                                $category->close();
                            ?>
                        </h3>
                        <p>Reports</p>
                    </span>
                </li>
                
            </ul>
            <!-- End of Insights -->

            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-receipt'></i>
                        <h3>Recent Reports</h3>

                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Date Reported</th>
                                <th>Category</th>
                            </tr>
                        </thead>
                        <tbody style="height: 30vh;">
                        <?php
                            $conn = require __DIR__ . "/connect.php";
                            $mysql = "SELECT user_firstname, user_lastname,startTime, category FROM calldata ORDER BY id desc";
                            $res = $conn->query($mysql);

                            if ($res->num_rows > 0) {
                                // Output data of each row
                                while($row = $res->fetch_assoc()) {
                                    echo '<tr>
                                        <td>
                                            <p>' . $row["user_firstname"] . ' ' . $row["user_lastname"] . '</p>
                                        </td>
                                        <td>'. $row["startTime"].'</td>
                                        <td><span>' . $row["category"] . '</span></td>
                                    </tr>';
                                }
                            } else {
                                echo "<td>"."0 results"."</td>";
                            }
                            $conn->close();
                        ?>
                        </tbody>
                    </table>
                </div>

                <!-- Reminders -->
                <div class="reminders">
                    <div id="editModal" class="modal">
                        <form action = "editprocess.php" id="messageForm" method="post">
                        
                            <input type="hidden" id="idtarget" name="idtarget">
                            <h4>Title</h4>
                            <div></div>
                            <input type="text" id="editTitle" name="title">
                            <h4>Edit Announcement</h4>
                            <div></div>
                            <textarea class="form-control" id="editMessage" rows="20" cols="100" name="message" style="resize:none;"></textarea>
                            <div></div>
                            <button id="saveButton" style="width:5em;">Save</button>
                        </form>
                        <button id="cancelbtn" style="width:5em;">Cancel</button>
                    </div>
                    
                    <div id="options" class="modal">
                        <div class="modal-content">
                            <button id="editButton">Edit</button>
                            <button id="deleteButton">Delete</button>
                        </div>
                    </div>

                    <div id="deleteModal" class="modal">
                        <form action="deleteprocess.php" id="deleteForm" method="post">
                            <span>Are you sure you want to delete this announcement?</span>
                            <div></div>
                            <input type="hidden" id="idelete" name="idelete">
                            <button id="deleteData">Delete</button>
                        </form>
                        <button id="cancelDelete">Cancel</button>
                        
                    </div>
                    <div class="header">
                        <i class='bx bx-note'></i>
                        <h3>Announcements</h3>

                    </div>
                    <ul class="task-list" style="height: 500px; overflow-y: scroll;">
                    <?php
                        $titlesqli = require __DIR__ . "/connect.php";
                        $title = "SELECT id,title,message FROM messages ORDER BY id desc";
                        $titleres = mysqli_query($titlesqli,$title);
                        if(mysqli_num_rows($titleres) > 0){
                            while($rows=mysqli_fetch_assoc($titleres)){
                                echo "<li class='completed' data-id='".$rows['id']."'> ";
                                echo "<div class='task-title'>";
                                echo "<i class='bx bx-check-circle'></i>";
                                echo "<p>".$rows['title']."</p>";
                                echo "</div>";
                                echo "<i class='bx bx-dots-vertical-rounded'></i>";
                                echo "</li>";

                            }
                            
                        }
                        else{
                            echo "No messages.";
                        }
                        
                    ?>

                    </ul>
                </div>

                <!-- End of Reminders-->

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

        <div class="modal-ov" id="modalOverlay">
            <div class="modal1">
                <div class="modal1-content">
                    <div class="modal1-header">
                        <h5 class="modal1-title">Announcement Released</h5>
                        <button class="close-btn" id="closeBtn">&times;</button>
                    </div>
                    <div class="modal1-body">
                        <p>Announcement has been edited.</p>
                    </div>
                </div>
            </div>
	    </div>
        <div class="modal-ov" id="deleteNotif">
            <div class="modal2">
                <div class="modal2-content">
                    <div class="modal2-header">
                        <h5 class="modal2-title">Announcement Deleted</h5>
                        <button class="close-btn2" id="closeBtn2">&times;</button>
                    </div>
                    <div class="modal2-body">
                        <p>Announcement has been deleted</p>
                    </div>
                </div>
            </div>
	    </div>


        <div>
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

        </div>

    </div>
    <script src = "https://unpkg.com/peerjs@1.5.2/dist/peerjs.min.js"></script>

    <script src="index.js"></script>
    <script>
      init(<?= htmlspecialchars($admins["id"])?>);
    </script>

<script>
    // Get the modal
    var options = document.getElementById("options");

    // Function to open modal at specific position
    function openModal(event) {
        options.style.display = "block";
        options.style.left = (event.clientX - 50) + "px"; // Adjust 50px as needed
        options.style.top = (event.clientY + 10) + "px"; // Adjust 10px as needed
    }

    // Function to close modal
    function closeModal() {
        options.style.display = "none";
    }

    // Attach click event to bx-dots-vertical-rounded icons
    var dotsIcons = document.querySelectorAll('.bx-dots-vertical-rounded');
    dotsIcons.forEach(function(icon) {
        icon.addEventListener('click', function(event) {
            openModal(event); // Open modal on icon click
        });
    });

// Close the modal when the user clicks anywhere on the screen
document.body.addEventListener('click', function(event) {
    // Check if the clicked element is not part of the modal or the icons
    var isIcon = Array.from(dotsIcons).some(icon => icon === event.target);
    if (!options.contains(event.target) && !isIcon) {
        closeModal();
    }
});


document.getElementById("editButton").addEventListener("click", function() {
    // Get the id of the selected announcement
    var id = options.getAttribute('data-id');

    //AJAX request to fetch the title and message
    $.ajax({
        url: 'fetch_announcement.php',
        type: 'post',
        data: {id: id},
        success: function(response){
            // Parse the JSON response
            var res = JSON.parse(response);

            // Set the title and message in the edit modal
            document.getElementById('idtarget').value = res.id;
            document.getElementById('editTitle').value = res.title;
            document.getElementById('editMessage').value = res.message;

            // Show the edit modal
            document.getElementById('editModal').style.display = 'block';
        }
    });
});


document.getElementById("deleteButton").addEventListener("click", function() {
    // Get the id of the selected announcement
    var id = options.getAttribute('data-id');

    //AJAX request to fetch the title and message
    $.ajax({
        url: 'fetch_announcement.php',
        type: 'post',
        data: {id: id},
        success: function(response){
            // Parse the JSON response
            var res = JSON.parse(response);

            // Set the title and message in the edit modal
            document.getElementById('idelete').value = res.id;


            // Show the edit modal
            document.getElementById('deleteModal').style.display = 'block';
        }
    });
});
    dotsIcons.forEach(function(icon) {
    icon.addEventListener('click', function(event) {
        // Get the id of the announcement
        var id = event.target.parentElement.getAttribute('data-id');
        
        // Set the data-id attribute for the options modal
        options.setAttribute('data-id', id);
        
        openModal(event); // Open modal on icon click
    });
});
$("#messageForm").submit(function(event) {
    event.preventDefault();

    var formData = $(this).serializeArray();
    formData.push({name: "id", value: $(this).data("id")});


    $.post("editprocess.php", formData, function(data) {

        var responseData = JSON.parse(data);


        if (responseData.success) {

            $("#modalOverlay").css('display', 'flex');
        } else {

            alert("Update Failed: " + responseData.error);
        }
    });
});
$("#deleteForm").submit(function(event) {
    event.preventDefault();

    var formData = $(this).serializeArray();
    formData.push({name: "id", value: $(this).data("id")});


    $.post("deleteprocess.php", formData, function(data) {

        var responseData = JSON.parse(data);


        if (responseData.success) {

            $("#deleteNotif").css('display', 'flex');
        } else {

            alert("Delete Failed: " + responseData.error);
        }
    });
});


</script>
<script>
document.getElementById("cancelDelete").addEventListener("click", function() {
    document.getElementById('deleteModal').style.display = 'none';
});

document.getElementById("cancelbtn").addEventListener("click", function() {
    document.getElementById('editModal').style.display = 'none';
});
    closeBtn.addEventListener('click', () => {
    modalOverlay.style.display = 'none';
    location.reload();
    });
    modalOverlay.addEventListener('click', (event) => {
        if (event.target === modalOverlay) {
            modalOverlay.style.display = 'none';
            location.reload();
        }
        });
    closeBtn2.addEventListener('click', () => {
    deleteNotif.style.display = 'none';
    location.reload();
    });
    deleteNotif.addEventListener('click', (event) => {
        if (event.target === deleteNotif) {
            deleteNotif.style.display = 'none';
            location.reload();
        }
        });
            
</script>


</body>

</html>