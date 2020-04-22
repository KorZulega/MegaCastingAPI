<?php
header("Content-Type: application/json; charset=French_CI_AS");
header("Access-Control-Allow-Methods: POST");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_GET['id']) && $_GET['id'] != "" && isset($_GET['name']) && $_GET['name'] != "") {
        include("../config/dbwin.php");

        $name = $_GET['name'];
        $id = $_GET['id'];
        $sql = "UPDATE JobType SET Name = ? WHERE Identifier= ?";
        $params = array($name, $id);


        $result = sqlsrv_query($conn, $sql, $params);

        if (sqlsrv_rows_affected($result) === 0) {

            header("HTTP/1.0 400 Bad Request");
        } else {
            header("HTTP/1.0 202 Accepted");
        }
    }
}
else{
    header("HTTP/1.0 405 Method Not Allowed");
}
