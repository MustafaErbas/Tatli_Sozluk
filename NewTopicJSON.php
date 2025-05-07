<?php
include "DB.php";
require_once 'TopicClass.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $blogdb;

    // Kullanıcı kimliği kontrolü
    if (!isset($_SESSION['user_ID'])) {
        echo "Hata: Oturum açmadınız.";
        exit;
    }

    $topiccla = new TopicClass();
    $topicname = trim($_POST['baslik']);
    $comment = trim($_POST['yorum']);
    $user_id = $_SESSION['user_ID'];

    // Boş giriş kontrolü
    if (empty($topicname) || empty($comment)) {
        echo "Hata: Başlık ve yorum boş olamaz.";
        exit;
    }

    // Aynı topicname'in olup olmadığını kontrol et
    $normalized_topicname = strtolower($topicname);
    $checkQuery = "SELECT COUNT(*) FROM topictable WHERE LOWER(Topic_name) = LOWER(?)";
    $stmtCheck = $blogdb->prepare($checkQuery);
    $stmtCheck->bind_param("s", $normalized_topicname);
    $stmtCheck->execute();
    $stmtCheck->bind_result($count);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($count > 0) {
        // Aynı başlık varsa hata mesajı döndür
        echo "Hata: Aynı konu birden fazla kez açılamaz.";
    } else {
        // Veritabanına kaydetme işlemi
        $result = $topiccla->addTopic($topicname, $comment, $user_id);
        if ($result) {
            echo "Başlık başarıyla eklendi!";
        } else {
            echo "Bir hata oluştu!";
        }
    }
}
?>
