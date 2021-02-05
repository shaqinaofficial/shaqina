<?php

try{
    $pdo = new PDO('mysql:host=localhost;dbname=db_panel','root','');
    //echo 'Connection Successfull';
}catch(PDOException $error){
    echo $error->getmessage();
}


?>