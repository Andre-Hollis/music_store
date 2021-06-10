<?php
define(SERVERNAME, 'localhost');
define(USERNAME, 'root');
define(PASSWORD,'root');
define(DBNAME, 'music_store');

$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);
if (!$conn) {
    die("Error: Could not connect. " . $conn->connect_error);
}
?>