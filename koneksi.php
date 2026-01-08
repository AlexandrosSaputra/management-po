<?php
date_default_timezone_set('Asia/Jakarta');
$host    =  "192.168.1.7";
$dbuser  =  "postgres";
$dbpass  =  "almukmin";
$port    =  "5432";
$dbname  =  "nurul_hayat_new";

$conn = pg_connect("host='$host' port='$port' dbname='$dbname' user='$dbuser' password='$dbpass'"); 

if (!$conn) {
    die("Koneksi gagal: " . pg_last_error());
}

// Fungsi untuk membersihkan input dari serangan injeksi SQL
function anti_injection($input) {
    global $conn;
    $clean_input = pg_escape_string($conn, $input);
    return $clean_input;
}

?>
