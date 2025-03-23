<?php 

include('inc/connection.php');
$conn = dbConnect();
if($conn) {
    echo 'Connexion reussie';
}

?>