<?php
include "DB.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class TopicClass {
    public function addTopic($topicname, $comment, $user_id) {
        global $blogdb;
        $tarih = date('Y-m-d H:i:s');

        // Transaction başlat
        $blogdb->begin_transaction();

        try {
            // topictable'a ekleme
            $querytop = "INSERT INTO topictable (`user_ID`, `Topic_name`, `created_date`) VALUES (?, ?, ?)";
            $stmt = $blogdb->prepare($querytop);
            $stmt->bind_param("iss", $user_id, $topicname, $tarih);
            $stmt->execute();

            // Eklenen başlığın topic_id'sini al
            $last_topic_id = $blogdb->insert_id;

            // comtable'a ekleme
            $querycom = "INSERT INTO comtable (`topic_ID`, `user_ID`, `comment`, `com_date`) VALUES (?, ?, ?, ?)";
            $stmt2 = $blogdb->prepare($querycom);
            $stmt2->bind_param("iiss", $last_topic_id, $user_id, $comment, $tarih);
            $stmt2->execute();

            // Eğer her şey başarılıysa, transaction'ı commit et
            $blogdb->commit();

            // Statement'ları kapat
            $stmt->close();
            $stmt2->close();

            // İşlem başarılı, true döndür
            return true;
        } catch (Exception $e) {
            // Hata durumunda rollback yap
            $blogdb->rollback();
            echo "Hata: " . $e->getMessage();

            // İşlem başarısız, false döndür
            return false;
        } finally {
            // Veritabanı bağlantısını kapat
            $blogdb->close();
        }
    }
    public function topicinfo($topicname){
        global $blogdb;

        $query= "SELECT u.nickname, t.Topic_name, t.created_date, t.Topic_ID FROM topictable t JOIN userlog u WHERE u.user_ID = t.user_ID AND t.Topic_name ='$topicname' ";
        $stmt = $blogdb->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();


        $row = $result->fetch_assoc();
        return $row;

    }
    public function lastTopics(){
        global $blogdb;

        $query= "SELECT Topic_name FROM topictable ORDER BY created_date DESC LIMIT 10";
        $stmt = $blogdb->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $topics=[];
        while ($row = $result->fetch_assoc()) {
            $topics[] = $row['Topic_name'];
        }

        return $topics;

    }
    public function topTopics() {
        global $blogdb;

        // SQL sorgusu
        $query = "
    SELECT t.Topic_name, COUNT(c.com_ID) AS comment_count
    FROM topictable t
    LEFT JOIN comtable c ON t.Topic_ID = c.topic_ID
    GROUP BY t.Topic_name
    ORDER BY comment_count DESC LIMIT 5;
    ";

        $stmt = $blogdb->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $topics = [];
        while ($row = $result->fetch_assoc()) {
            $topics[] = [
                'Topic_name' => $row['Topic_name'],
                'comment_count' => $row['comment_count'],
            ];
        }

        return $topics;
    }
    public function userTopics($id){
        global $blogdb;

        // SQL sorgusu
        $query = "
    SELECT t.Topic_name, COUNT(c.com_ID) AS comment_count
    FROM topictable t
    LEFT JOIN comtable c ON t.Topic_ID = c.topic_ID
    WHERE t.user_ID = $id
    GROUP BY t.Topic_name
    ORDER BY t.created_date DESC;
    ";

        $stmt = $blogdb->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $topics = [];
        while ($row = $result->fetch_assoc()) {
            $topics[] = [
                'Topic_name' => $row['Topic_name'],
                'comment_count' => $row['comment_count'],
            ];
        }

        return $topics;
    }
    public function addComments($userid, $topicid, $comment) {
        global $blogdb;
        $tarih = date('Y-m-d H:i:s');

        $querycom = "INSERT INTO comtable (`topic_ID`, `user_ID`, `comment`, `com_date`) VALUES (?, ?, ?, ?)";
        $stmt2 = $blogdb->prepare($querycom);
        $stmt2->bind_param("iiss", $topicid, $userid, $comment, $tarih);

        if ($stmt2->execute()) {
            return true; // Başarılı ise true döndür
        } else {
            return false; // Başarısızsa false döndür
        }
    }
    public function lastComments(){
        global $blogdb;

        // SQL sorgusu
        $query = "	SELECT t.Topic_name,c.comment,u.nickname,c.com_date
    FROM comtable c
    LEFT JOIN topictable t ON c.topic_ID = t.Topic_ID 
    LEFT JOIN userlog u ON c.user_ID = u.user_ID
    ORDER BY c.com_date DESC LIMIT 8";

        $stmt = $blogdb->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $comments = [];
        while ($row = $result->fetch_assoc()){
            $row['com_date']=date('d-m-Y H:i');
            $comments[] = [
                'Topic_name'=> $row['Topic_name'],
                'Comment'=> $row['comment'],
                'User_nickname'=> $row['nickname'],
                'comdate'=>$row['com_date'],
            ];
        }
        return $comments;
    }
    public function allTopicComments($topicname){
        global $blogdb;

        $query = "SELECT c.comment,u.nickname,c.com_date
    FROM comtable c
    INNER JOIN userlog u ON c.user_ID = u.user_ID
    INNER JOIN topictable t ON t.Topic_ID = c.topic_ID
    WHERE t.Topic_name = '$topicname'
    ORDER BY c.com_date";

        $stmt = $blogdb->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $comments = [];
        while ($row = $result->fetch_assoc()){
            $row['com_date']=date('d-m-Y H:i');
            $comments[] = [
                'Comment'=> $row['comment'],
                'User_nickname'=> $row['nickname'],
                'comdate'=>$row['com_date'],
            ];
        }
        return $comments;
    }
    public function userComments($id){
        global $blogdb;

        // SQL sorgusu
        $query = "	SELECT t.Topic_name,c.comment,u.nickname,c.com_date,c.com_ID
    FROM comtable c
    LEFT JOIN topictable t ON c.topic_ID = t.Topic_ID 
    LEFT JOIN userlog u ON c.user_ID = u.user_ID
    WHERE u.user_ID=$id
    ORDER BY c.com_date DESC";

        $stmt = $blogdb->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $comments = [];
        while ($row = $result->fetch_assoc()){
            $row['com_date']=date('d-m-Y H:i');
            $comments[] = [
                'comid' => $row['com_ID'],
                'Topic_name'=> $row['Topic_name'],
                'Comment'=> $row['comment'],
                'User_nickname'=> $row['nickname'],
                'comdate'=>$row['com_date'],
            ];
        }
        return $comments;
    }
}
