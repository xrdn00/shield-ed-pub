<?php
    include "connect.php";
    $newcount = $_POST['newcount'];

    $sqli = "SELECT id,author,title,message,admin_time FROM messages ORDER BY id desc LIMIT $newcount";
    $result = mysqli_query($mysqli,$sqli);
    if(mysqli_num_rows($result) > 0){
        while($rows=mysqli_fetch_assoc($result)){
            echo `  <div class="header">
                        <i class='bx bx-receipt'></i>
                        <h3>Recent Announcements</h3>
                    </div>`;
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
