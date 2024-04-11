<?php
$host = 'localhost'; // your host
$db   = 'login_db'; // your database
$user = 'root'; // your username
$pass = ''; // your password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

$data = json_decode(file_get_contents('php://input'), true);
$token = $data['token'];
$id = $data['id']; // assuming you're sending the user's id along with the token

$sql = "UPDATE users SET fcm = ? WHERE id = ?";
$stmt= $pdo->prepare($sql);
$stmt->execute([$token, $id]);
?>
