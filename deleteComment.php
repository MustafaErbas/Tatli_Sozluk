<?php
include "DB.php";
if (isset($_POST['delete_comment'])) {
    $commentId = $_POST['comment_id'];
    global $blogdb;

    // Yorum silme sorgusu
    $deleteCommentQuery = "DELETE FROM comtable WHERE com_ID = ?";
    $stmt = $blogdb->prepare($deleteCommentQuery);
    $stmt->bind_param("s", $commentId);
    $stmt->execute();

    // Başarı mesajı (PHP alert ile)
    echo "<script>alert('Yorum başarıyla silindi.'); window.location.href='usertable.php';</script>";
    exit;
}
?>
