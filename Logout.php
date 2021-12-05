<?php 
    session_start();
    //load header

    session_destroy();
        header("Location: index.php");
        exit();
?>
