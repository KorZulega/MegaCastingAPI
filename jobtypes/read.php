<?php
header("Content-Type: application/json; charset=UTF-8");
include("../config/dbwin.php");

$sql = "SELECT Identifier, Name FROM JobType";
$params = array();

$options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
$result = sqlsrv_query($conn, $sql, $params, $options);

if ($result === false) {
    if (($errors = sqlsrv_errors()) != null) {
        foreach ($errors as $error) {
            echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />";
            echo "code: " . $error['code'] . "<br />";
            echo "message: " . $error['message'] . "<br />";
        }
    }
}

$row_count = sqlsrv_has_rows($result);
$results = array();

if ($row_count === true) {

    while ($row = sqlsrv_fetch_object($result)) {
        // $identifier = $row['Identifier'];
        // $results['Identifier'] = $identifier;
        // $name = $row['Name'];
        // $results['Name'] = $name;

        $results[] = array($row);
        
        

        
    }
    echo json_encode($results);
    
    sqlsrv_close($conn);
} else {

    response(NULL, NULL);
}





