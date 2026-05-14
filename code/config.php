<?php
    session_start();
    $host = getenv('MYSQL_HOST') ?: 'db'; /* Host name */
    $user = getenv('MYSQL_USER') ?: 'logistics-tracking-ayz'; /* User */
    $password = getenv('MYSQL_PASSWORD') ?: '70210f093ae4df7947b8ccfe4a8181a8'; /* Password */
    $dbname = getenv('MYSQL_DATABASE') ?: 'users'; /* Database name */

    $con = mysqli_connect($host, $user, $password, $dbname);
    // Check connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>