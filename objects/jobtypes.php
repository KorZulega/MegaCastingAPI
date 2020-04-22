<?php
header("Content-Type: application/json");
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        readJobTypes($id);
    } else {
        readJobTypesAll();
    }
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
            createJobTypes();
        
    } else {

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            if (isset($_GET['id'])) {
                updateJobTypes($id);
            } else {

                header("HTTP/1.0 400 Bad Request");
            }
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                if (isset($_GET['id'])) {
                    deleteJobTypes($id);
                } else {

                    header("HTTP/1.0 400 Bad Request");
                }
            } else {

                header("HTTP/1.0 400 Method Not Allowed");
            }
        }
    }
}





function readJobTypes($id)
{
    include("../config/dbwin.php");

    $sql = "SELECT Identifier, Name FROM JobType WHERE Identifier=?";
    $params = array($id);
    $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);

    $result = sqlsrv_query($conn, $sql, $params, $options);

    if (sqlsrv_has_rows($result) === false) {
        header("HTTP/1.0 400 Bad Request");
    }

    while ($row = sqlsrv_fetch_array($result)) {
        $response['Identifier'] = $row['Identifier'];
        $response['Name'] = $row['Name'];

        echo json_encode($response);
    }
}

function readJobTypesAll()
{
    include("../config/dbwin.php");

    $sql = "SELECT Identifier, Name FROM JobType";
    $params = array();
    $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);

    $result = sqlsrv_query($conn, $sql, $params, $options);

    if (sqlsrv_has_rows($result) === false) {
        header("HTTP/1.0 400 Bad Request");
    }

    while ($row = sqlsrv_fetch_array($result)) {
        $response['Identifier'] = $row['Identifier'];
        $response['Name'] = $row['Name'];

        echo json_encode($response);
    }
}

function createJobTypes()
{
    include("../config/dbwin.php");


    $data = json_decode(file_get_contents("php://input"));
    if (empty($data->Name)) {
        header("HTTP/1.0 400 Bad Request");
    } else {

        $sql = "INSERT INTO JobType (Name) VALUES (?)";
        $params = array($data->Name);


        $result = sqlsrv_query($conn, $sql, $params);

        if ($result === false) {
            if (($errors = sqlsrv_errors()) != null) {
                foreach ($errors as $error) {
                    echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />";
                    echo "code: " . $error['code'] . "<br />";
                    echo "message: " . $error['message'] . "<br />";
                }
            }
        }

        if (sqlsrv_rows_affected($result) === 0) {

            header("HTTP/1.0 400 Bad Request");
        } else {
            header("HTTP/1.0 201 Created");
        }
    }
}

function updateJobTypes($id)
{

    include("../config/dbwin.php");
    $data = json_decode(file_get_contents("php://input"));
    if (empty($data->Name)) {
        header("HTTP/1.0 400 Bad Request");
    } else {




        $sql = "UPDATE JobType SET Name = ? WHERE Identifier= ?";
        $params = array($data->Name, $id);


        $result = sqlsrv_query($conn, $sql, $params);

        if (sqlsrv_rows_affected($result) === 0) {

            header("HTTP/1.0 400 Bad Request");
        } else {
            header("HTTP/1.0 202 Accepted");
        }
    }
}


function deleteJobTypes($id)
{
    include("../config/dbwin.php");

    
    $sql = "DELETE FROM JobType WHERE Identifier=?";
    $params = array($id);


    $result = sqlsrv_query($conn, $sql, $params);

    if (sqlsrv_rows_affected($result) === 0) {

        header("HTTP/1.0 400 Bad Request");
    } else {
        header("HTTP/1.0 202 Accepted");
    }
}
