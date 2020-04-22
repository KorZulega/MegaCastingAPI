<?php
$serverName = "KorZulega-PC\\sqlexpress"; //serverName\instanceName
$connectionInfo = array( "Database"=>"MegaCasting", "UID"=>"sa", "PWD"=>"Not24get");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

// if( $conn ) {
//      echo "Connexion établie.<br />";
// }else{
//      echo "La connexion n'a pu être établie.<br />";
//      die( print_r( sqlsrv_errors(), true));
// }
?>
