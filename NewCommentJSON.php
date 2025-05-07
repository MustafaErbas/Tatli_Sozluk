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

    $user_id = $_POST['user_id'];
    $topic_id = $_POST['topic_id'];
    $comment = trim($_POST['yorum']);

    // Yorumun boş olup olmadığını kontrol et
    if (empty($comment)) {
        echo "Hata: Yorum boş olamaz.";
        exit;
    }

    // Yorum ekleme fonksiyonu çağırılıyor
    $topiccla = new TopicClass();
    $result = $topiccla->addComments($user_id, $topic_id, $comment);

    if ($result) {
        echo "Yorum başarıyla eklendi!";
    } else {
        echo "Hata: Yorum eklenirken bir hata oluştu!";
    }
}
?>
