<?php
// Veritabanı bağlantı bilgileri
$host = '212.58.20.68:3306';
$username = 'Mustafa';
$password = 'Me200210!!';
$dbname = 'wp_yhy8g';

// MySQLi ile bağlantı oluşturma
$blogdb = new mysqli($host, $username, $password, $dbname);

// Bağlantı kontrolü
if ($blogdb->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $blogdb->connect_error);
}
?>