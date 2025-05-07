<?php
include "DB.php";
if (isset($_POST['delete_user'])) {
    $userId = $_POST['user_id'];
    global $blogdb;

    // Kullanıcının yaptığı yorumları sil
    $deleteCommentsQuery = "DELETE FROM comtable WHERE user_ID = ?";
    $stmt = $blogdb->prepare($deleteCommentsQuery);
    $stmt->bind_param("s", $userId);
    $stmt->execute();

    // Kullanıcının açtığı başlıkları ve onların altındaki yorumları sil
    $deleteTopicsQuery = "DELETE FROM topictable WHERE user_ID = ?";
    $stmt = $blogdb->prepare($deleteTopicsQuery);
    $stmt->bind_param("s", $userId);
    $stmt->execute();

    // Başlıkların altındaki yorumları sil
    $deleteTopicCommentsQuery = "DELETE FROM comtable WHERE topic_ID IN (SELECT Topic_ID FROM topictable WHERE user_ID = ?)";
    $stmt = $blogdb->prepare($deleteTopicCommentsQuery);
    $stmt->bind_param("s", $userId);
    $stmt->execute();

    // Kullanıcıyı sil
    $deleteUserQuery = "DELETE FROM userlog WHERE user_ID = ?";
    $stmt = $blogdb->prepare($deleteUserQuery);
    $stmt->bind_param("s", $userId);
    $stmt->execute();

    // Başarılı silme işlemi sonrası yönlendirme
    header("Location: usertable.php?message=silindi.");
    exit;
}
?>
