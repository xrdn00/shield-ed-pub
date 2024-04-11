<?php
    if(isset($_POST['id'])){
        $id = $_POST['id'];
        $conn = require __DIR__ . "/connect.php";
        $sql = "SELECT id,title, message FROM messages WHERE id = $id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        echo json_encode($row);
    }
?>
