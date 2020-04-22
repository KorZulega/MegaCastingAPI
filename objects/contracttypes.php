<?php
header("Content-Type: application/json");
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        readContractTypes($id);
    } else {
        readContractTypesAll();
    }
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
            createContractTypes();
        
    } else {

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            if (isset($_GET['id'])) {
                updateContractTypes($id);
            } else {

                header("HTTP/1.0 400 Bad Request");
            }
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                if (isset($_GET['id'])) {
                    deleteContractTypes($id);
                } else {

                    header("HTTP/1.0 400 Bad Request");
                }
            } else {

                header("HTTP/1.0 400 Method Not Allowed");
            }
        }
    }
}





function readContractTypes($id)
{
    include("../config/dbwin.php");

    $sql = "SELECT Identifier, Name FROM ContractType WHERE Identifier=?";
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

function readContractTypesAll()
{
    include("../config/dbwin.php");

    $sql = "SELECT Identifier, Name FROM ContractType";
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

function createContractTypes()
{
    include("../config/dbwin.php");


    $data = json_decode(file_get_contents("php://input"));
    if (empty($data->Name)) {
        header("HTTP/1.0 400 Bad Request");
    } else {

        $sql = "INSERT INTO ContractType (Name) VALUES (?)";
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

function updateContractTypes($id)
{

    include("../config/dbwin.php");
    $data = json_decode(file_get_contents("php://input"));
    if (empty($data->Name)) {
        header("HTTP/1.0 400 Bad Request");
    } else {




        $sql = "UPDATE ContractType SET Name = ? WHERE Identifier= ?";
        $params = array($data->Name, $id);


        $result = sqlsrv_query($conn, $sql, $params);

        if (sqlsrv_rows_affected($result) === 0) {

            header("HTTP/1.0 400 Bad Request");
        } else {
            header("HTTP/1.0 202 Accepted");
        }
    }
}


function deleteContractTypes($id)
{
    include("../config/dbwin.php");

    
    $sql = "DELETE FROM ContractType WHERE Identifier=?";
    $params = array($id);


    $result = sqlsrv_query($conn, $sql, $params);

    if (sqlsrv_rows_affected($result) === 0) {

        header("HTTP/1.0 400 Bad Request");
    } else {
        header("HTTP/1.0 202 Accepted");
    }
}
