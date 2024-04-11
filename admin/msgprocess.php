<?php


$mysqli = require __DIR__ . "/connect.php";
$sql = "INSERT INTO messages (author,title,message,admin_time)
        VALUES (?,?,?,?)";
$stmt = $mysqli -> stmt_init();

$stmt->prepare($sql);

if ( ! $stmt->prepare($sql)){
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("ssss",$_POST["author"],
                        $_POST["title"],
                       $_POST["message"],
                       $_POST["admin_time"]);
                         




try{
    $stmt->execute();
    echo json_encode(array("success" => true));
}
catch(mysqli_sql_exception $e){
    echo json_encode(array("success" => false));
}
    

?>