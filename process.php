<?php

$password_hash = password_hash($_POST["password"],PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/connect.php";

$sql = "INSERT INTO users (firstname,lastname,email,password_hash,fcm)
        VALUES (?,?,?,?,?)";
$stmt = $mysqli -> stmt_init();

$stmt->prepare($sql);

if ( ! $stmt->prepare($sql)){
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sssss",$_POST["firstname"],
                         $_POST["lastname"],
                         $_POST["email"],
                         $password_hash,
                        $_POST["fcm"]);

try{
    $stmt->execute();
    echo json_encode(array("success" => true));
}
catch(mysqli_sql_exception $e){
    echo json_encode(array("success" => false));
}

?>
