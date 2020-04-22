<?php
header("Content-Type: application/json ; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

if (isset($_GET['name']) && $_GET['name'] != "") {
    include("../config/dbwin.php");

    $name = $_GET['name'];
    $sql = "INSERT INTO JobType (Name) VALUES (?)";
    $params = array($name);

    
    $result = sqlsrv_query($conn, $sql, $params);

    if (sqlsrv_rows_affected($result)===0){
        
            header("HTTP/1.0 400 Bad Request");
        
    }
    else{
        header("HTTP/1.0 201 Created");
    }
} 


