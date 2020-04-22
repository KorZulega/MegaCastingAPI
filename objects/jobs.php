<?php
header("Content-Type: application/json");
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        readJobs($id);
    } else {
        readJobsAll();
    }
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
            createJobs();
        
    } else {

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            if (isset($_GET['id'])) {
                updateJobs($id);
            } else {

                header("HTTP/1.0 400 Bad Request");
            }
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                if (isset($_GET['id'])) {
                    deleteJobs($id);
                } else {

                    header("HTTP/1.0 400 Bad Request");
                }
            } else {

                header("HTTP/1.0 400 Method Not Allowed");
            }
        }
    }
}





function readJobs($id)
{
    include("../config/dbwin.php");

    $sql = "SELECT Job.Identifier, Job.Name, JobType.Name AS JobType FROM Job INNER JOIN JobType ON Job.IdentifierJobType = JobType.Identifier  WHERE Job.Identifier=?";
    $params = array($id);
    $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);

    $result = sqlsrv_query($conn, $sql, $params, $options);

    if (sqlsrv_has_rows($result) === false) {
        header("HTTP/1.0 400 Bad Request");
    }

    while ($row = sqlsrv_fetch_array($result)) {
        $response['Identifier'] = $row['Identifier'];
        $response['Name'] = $row['Name'];
        $response['JobType'] = $row['JobType'];

        echo json_encode($response);
    }
}

function readJobsAll()
{
    include("../config/dbwin.php");

    $sql = "SELECT Job.Identifier, Job.Name, JobType.Name AS JobType FROM Job INNER JOIN JobType ON Job.IdentifierJobType = JobType.Identifier";
    $params = array();
    $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);

    $result = sqlsrv_query($conn, $sql, $params, $options);

    if (sqlsrv_has_rows($result) === false) {
        header("HTTP/1.0 400 Bad Request");
    }

    while ($row = sqlsrv_fetch_array($result)) {
        $response['Identifier'] = $row['Identifier'];
        $response['Name'] = $row['Name'];
        $response['JobType'] = $row['JobType'];

        echo json_encode($response);
    }
}

function createJobs()
{
    include("../config/dbwin.php");


    $data = json_decode(file_get_contents("php://input"));
    if (empty($data->Name)&&
    empty($data->IdentifierJobType)) {
        header("HTTP/1.0 400 Bad Request");
    } else {

        $sql = "INSERT INTO Job (Name, IdentifierJobType) VALUES (?,?)";
        $params = array($data->Name, $data->IdentifierJobType);


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

function updateJobs($id)
{

    include("../config/dbwin.php");
    $data = json_decode(file_get_contents("php://input"));
    if (empty($data->Name)) {
        header("HTTP/1.0 400 Bad Request");
    } else {




        $sql = "UPDATE Job SET Name = ? WHERE Identifier= ?";
        $params = array($data->Name, $id);


        $result = sqlsrv_query($conn, $sql, $params);

        if (sqlsrv_rows_affected($result) === 0) {

            header("HTTP/1.0 400 Bad Request");
        } else {
            header("HTTP/1.0 202 Accepted");
        }
    }
}


function deleteJobs($id)
{
    include("../config/dbwin.php");

    
    $sql = "DELETE FROM Job WHERE Identifier=?";
    $params = array($id);


    $result = sqlsrv_query($conn, $sql, $params);

    if (sqlsrv_rows_affected($result) === 0) {

        header("HTTP/1.0 400 Bad Request");
    } else {
        header("HTTP/1.0 202 Accepted");
    }
}
