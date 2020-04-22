<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

if (isset($_GET['id']) && $_GET['id'] != "") {
    include("../config/dbwin.php");

    $id = $_GET['id'];
    $sql = "DELETE FROM JobType WHERE Identifier=?";
    $params = array($id);

    
    $result = sqlsrv_query($conn, $sql, $params);

    if (sqlsrv_rows_affected($result)===0){
        
            header("HTTP/1.0 400 Bad Request");
        
    }
    else{
        header("HTTP/1.0 202 Accepted");
    }
} 