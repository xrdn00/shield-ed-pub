<?php
    $mysqli = require __DIR__ . "/connect.php";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
         // The id of the row you want to delete
        $id_to_delete = $_POST['idelete'];

        // Prepare an SQL statement
        $stmt = $mysqli->prepare("DELETE FROM messages WHERE id = ?");

        // Bind parameters
        $stmt->bind_param("s", $id_to_delete);

        // Execute the prepared statement
        try{
            $stmt->execute();
            echo json_encode(array("success" => true));
        }
        catch(mysqli_sql_exception $e){
            echo json_encode(array("success" => false));
        }
            
        // Close statement and connection
        $stmt->close();

    }



?>
