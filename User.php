<?php
include "DB.php";

class User {

    public function login($nickname, $password): void
    {
        // Kullanıcı adını ve şifreyi kullanarak sorgu hazırla
        global $blogdb;
        $giris = $blogdb->prepare("SELECT * FROM userlog WHERE nickname='$nickname' AND user_password='$password'");
        $giris->execute();
        $sonuc = $giris->get_result();

        // Kullanıcı bulundu mu?
        if ($sonuc->num_rows === 1) {
            $row = $sonuc->fetch_assoc();
            $_SESSION['oturum'] = true;
            $_SESSION['nickname'] = $row['nickname'];
            $_SESSION['user_ID'] = $row['user_ID'];
            $_SESSION['userRole'] = $row['userRole'];
            $_SESSION['whois'] = $row['whois'];
            $_SESSION['dns'] = $row['dns'];
            header('Location:index.php'); // Ana sayfaya yönlendir
            exit;
            }
         else {
            // Kullanıcı bulunamadı
            echo "<div class='text-black'>Kullanıcı adı veya şifre hatalı</div>";
        }
    }

    public function register($nickname, $password,$ad,$soyad,$mail): void{
        global $blogdb;
        $tarih = date('Y-m-d');
        if (!empty($nickname) && !empty($password) && !empty($mail)) {
            if (!empty($nickname)){
                $query = "SELECT * FROM userlog WHERE nickname = '$nickname'";
                $stmt = $blogdb->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0){
                    echo "<script>alert('Bu Kullanıcı Adı Kullanılıyor.');</script>";
                    $stmt->close();
                    $blogdb->close();
                    exit;
                }
            }
            if(!empty($mail)){
                $query = "SELECT * FROM userlog WHERE user_mail = '$mail'";
                $stmt = $blogdb->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0){
                    echo "<script>alert('Bu E-Mail Kullanılıyor.');</script>";
                    $stmt->close();
                    $blogdb->close();
                    exit;
                }
            }
            $stmt = $blogdb->prepare("INSERT INTO userlog (`user_name`, `user_surname`, `nickname`, `user_mail`, `user_password`, `userRole`, `create_date`) VALUES ( ?, ?, ?, ?, ?, 'yazar', '$tarih')");
            $stmt->bind_param("sssss", $ad, $soyad, $nickname, $mail,$password);

            if ($stmt->execute()) {
                echo "<h4 class='text-success'>Kayıt Başarılı Bir Şekilde Oluşturuldu.</h4>";
            } else {
                echo "<h4 class='text-danger'>Kayıt Sırasında Hata Oluştu!! </h4>" . $stmt->error;
            }
            $stmt->close();
            $blogdb->close();
        }
    }
    public function showinfo($ID){
        global $blogdb;
        $show = $blogdb->prepare("SELECT * FROM userlog WHERE user_ID = ?");
        $show->bind_param("s", $ID);
        $show->execute();
        $result = $show->get_result();
        $row = $result->fetch_assoc();
        return $row;
    }
    public function updateinfo($nickname, $password,$ad,$soyad,$mail,$ID): void
    {
        global $blogdb;
        $row=$this->showinfo($ID);
        if ($ad==$row["user_name"] && $soyad==$row["user_surname"] && $mail==$row["user_mail"] &&$password==$row["user_password"] && $nickname==$row["nickname"]) {
            echo "<script>alert('Girdiğiniz Bütün Veriler Aynı');</script>";
        }
        elseif ($row["nickname"]!= $nickname) {
            $query = "SELECT * FROM userlog WHERE nickname = '$nickname'";
            $stmt = $blogdb->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0){
                echo "<script>alert('Girdiğiniz Kullanıcı Adı Kullanılıyor.');</script>";
                $stmt->close();
            }else {
                $update = $blogdb->prepare("UPDATE userlog SET user_password=?,user_name=?, user_surname=?, user_mail=?,nickname=? WHERE user_ID=?");
                $update->bind_param("sssssi", $password, $ad, $soyad, $mail, $nickname, $ID);
                $update->execute();
                $_SESSION['nickname'] = $nickname;
                if ($update->execute()) {
                    echo "<script>alert('Profil bilgileri başarılı bir şekilde güncellendi.');</script>";
                } else {
                    echo "<script>alert('Bir hata oluştu. Profil bilgileri güncellenemedi.');</script>";
                }

            }
        }
        elseif ($row["user_mail"] != $mail) {
            $query = "SELECT * FROM userlog WHERE user_mail = '$mail'";
            $stmt = $blogdb->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0){
                echo "<script>alert('Bu E-Mail Kullanılıyor.');</script>";
                $stmt->close();
            }else {
                $update = $blogdb->prepare("UPDATE userlog SET user_password=?,user_name=?, user_surname=?, user_mail=?,nickname=? WHERE user_ID=?");
                $update->bind_param("sssssi", $password, $ad, $soyad, $mail, $nickname, $ID);
                $update->execute();
                $_SESSION['nickname'] = $nickname;

                if ($update->execute()) {
                    echo "<script>alert('Profil bilgileri başarılı bir şekilde güncellendi.');</script>";
                } else {
                    echo "<script>alert('Bir hata oluştu. Profil bilgileri güncellenemedi.');</script>";
                }
            }
        }
        else {
            $update = $blogdb->prepare("UPDATE userlog SET user_password=?,user_name=?, user_surname=?, user_mail=?,nickname=? WHERE user_ID=?");
            $update->bind_param("sssssi", $password, $ad, $soyad, $mail, $nickname, $ID);
            $update->execute();
            $_SESSION['nickname'] = $nickname;
            if ($update->execute()) {
                echo "<script>alert('Profil bilgileri başarılı bir şekilde güncellendi.');</script>";
            } else {
                echo "<script>alert('Bir hata oluştu. Profil bilgileri güncellenemedi.');</script>";
            }
        }

    }
    public function logout(): void{
        session_destroy();
        header('Location:index.php'); // Ana sayfaya yönlendir

    }
    public function comCount($userid){
        global $blogdb;

        // SQL sorgusu
        $query = "SELECT COUNT(com_ID) AS commentcount FROM comtable WHERE user_ID =$userid";

        $stmt = $blogdb->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['commentcount'];;
    }
    public function topicCount($userid){
        global $blogdb;

        // SQL sorgusu
        $query = "SELECT COUNT(Topic_ID) AS topiccount FROM topictable WHERE user_ID =$userid";

        $stmt = $blogdb->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['topiccount'];
    }
}
