<?php
include "DB.php";

class Admin extends  User{
    public function showUserList(){
        //user_ID,user_name,user_surname,nickname,user_mail,user_password,userRole,create_date
        global $blogdb;
        $show = $blogdb->prepare("SELECT * FROM userlog");
        $show->execute();
        $result = $show->get_result();

        $userinfos = [];
        while ($row = $result->fetch_assoc()) {
            $usercomcount= $this->comCount($row['user_ID']);
            $usertopiccount= $this->topicCount($row['user_ID']);
            $userinfos[] = [
                'userid' => $row['user_ID'],
                'userad' => $row['user_name'],
                'usersoyad' => $row['user_surname'],
                'nickname' => $row['nickname'],
                'usermail' => $row['user_mail'],
                'userrole' => $row['userRole'],
                'userdate' => $row['create_date'],
                'usercomsay' =>  $usercomcount,
                'usertopicsay' => $usertopiccount,
            ];
        }
        return $userinfos;
    }
    public function updateUserRole($userId, $role): void
    {
        global $blogdb;  // Veritabanı bağlantısı

        $stmt = $blogdb->prepare("UPDATE userlog SET userRole = ? WHERE user_ID = ?");
        $stmt->bind_param("si", $role, $userId);

        if (!$stmt->execute()) {
            echo "Hata: " . $stmt->error; // Hata durumunda mesaj
        }
    }

    public function updatePrivileges($userId, $privilege1, $privilege2): void{
        global $blogdb;

        // Privilege değerlerini kontrol et
        $privilege1 = isset($privilege1) ? $privilege1 : 0;
        $privilege2 = isset($privilege2) ? $privilege2 : 0;

        // Hazırlanmış SQL sorgusu
        $sql = "UPDATE userlog SET whois = ?, dns = ? WHERE user_ID = ?";
        $stmt = $blogdb->prepare($sql);

        // Parametreleri bağlama (i: integer)
        $stmt->bind_param('iii', $privilege1, $privilege2, $userId);

        // Sorguyu çalıştır
        $stmt->execute();
    }

}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateRole'])) {
    $newRole = $_POST['role'];

    if ($admin->updateUserRole($userId, $newRole)) {
        echo "<p class='text-success mt-3'>Rol başarıyla güncellendi.</p>";
    } else {
        echo "<p class='text-danger mt-3'>Rol güncellenirken bir hata oluştu.</p>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updatePrivileges'])) {
    $privilege1 = isset($_POST['whois']) ? 1 : 0;
    $privilege2 = isset($_POST['dns']) ? 1 : 0;

    $admin->updatePrivileges($userId, $privilege1, $privilege2);

    echo "<p class='text-success mt-3'>Yetki başarıyla güncellendi.</p>";
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['updateBtn'])){
        $ad = $_POST["ad"];
        $soyad = $_POST["soyad"];
        $mail = $_POST["mail"];
        $password = $_POST["sifre"];
        $nickname = $_POST["nickname"];
        $userbyadmin->updateinfo($nickname, $password,$ad,$soyad,$mail,$userId);
    }
}
