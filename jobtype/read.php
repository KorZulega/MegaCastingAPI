<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

if (isset($_GET['id']) && $_GET['id'] != "") {
    include("../config/dbwin.php");


    $id = $_GET['id'];
    $sql = "SELECT Identifier, Name FROM JobType WHERE Identifier=?";
    $params = array($id);

    $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
    $result = sqlsrv_query($conn, $sql, $params, $options);

    if (sqlsrv_has_rows($result) === false) {
        header("HTTP/1.0 400 Bad Request");
    }

    $row_count = sqlsrv_has_rows($result);


    if ($row_count === true) {

        while ($row = sqlsrv_fetch_array($result)) {
            $identifier = $row['Identifier'];
            $name = $row['Name'];

            response($identifier, $name);
        }
        sqlsrv_close($conn);
    }

    response(NULL, NULL);
}

function response($identifier, $name)
{
    $response['Identifier'] = $identifier;
    $response['Name'] = $name;


    $json_response = json_encode($response);
    echo $json_response;
}
