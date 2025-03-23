<?php 

function dbConnect() {

    $host = "localhost";
    $db_name = "PrevisionBudget";
    $username = "root";
    $password = "";
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $exception) {
        die("Erreur de connexion : " .$exception->getMessage());
    }

}

?>