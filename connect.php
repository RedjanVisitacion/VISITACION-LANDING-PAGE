<?php
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];


    //Database Connection

    $conn = new mysqli('localhost', 'root','','postgres');
    if($conn->connect_error){
        die('Connection Failed : ' .$conn->connect_error);
    }else{
        $stmt = $conn->prepare("insert into message(email,name,message_text) values(?,?,?)");
        $stmt->bind_param("sssssi", $email,$name,$message_text);
        $stmt->execute();
        
        echo("Submit Succesfully...");
        $stmt->close();
        $conn->close();

    }
?>