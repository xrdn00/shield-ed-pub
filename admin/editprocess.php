<?php
$mysqli = require __DIR__ . "/connect.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["title"]) && isset($_POST["message"]) && isset($_POST["id"])) {
        $title = $_POST["title"];
        $message = $_POST["message"];
        $id = $_POST["idtarget"];

        $sql = "UPDATE messages SET title=?, message=? WHERE id=?";
        $stmt = $mysqli->prepare($sql);


        $stmt->bind_param("ssi", $title, $message, $id);

        if ($stmt->execute()) {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false, "error" => "Failed to update message."));
        }
    } else {
        echo json_encode(array("success" => false, "error" => "Required fields are missing."));
    }
} else {

    echo json_encode(array("success" => false, "error" => "Invalid request method."));
}

?>
