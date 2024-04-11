<?php


$mysqli = require __DIR__ . "/connect.php";
$sql = "INSERT INTO calldata (participants,caller,callee,startTime,endTime,user_id,user_firstname,user_lastname,user_email,category,user_time)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)";
$stmt = $mysqli -> stmt_init();


$stmt->prepare($sql);

if ( ! $stmt->prepare($sql)){
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sssssssssss",$_POST["participants"],
                       $_POST["caller"],
                       $_POST["callee"],
                       $_POST["startTime"],
                       $_POST["endTime"],
                       $_POST["user_id"],
                       $_POST["user_firstname"],
                       $_POST["user_lastname"],
                       $_POST["user_email"],
                       $_POST["category"],
                       $_POST["user_time"],);
                         



$stmt->execute();
header("Location: report.php");