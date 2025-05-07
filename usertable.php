<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="blog.css">
    <title>Üye Listesi</title>
</head>
<body>
<?php
session_start(); // Oturumu başlat
include "navbar.php"; // Navbarı dahil et

// Kullanıcı oturum kontrolü
$oturum = isset($_SESSION['oturum']) && $_SESSION['oturum'] === true;
if (isset($_GET['message'])) {
    echo "<script>alert('Kullanıcı başarıyla silindi.');</script>"; // Eğer bir mesaj varsa, kullanıcı silme işlemi başarıyla yapıldığına dair bir uyarı göster
}
if($oturum){
    $userRole = $_SESSION['userRole']; // Kullanıcının rolünü al
}else{
    $userRole = null;
}

// Eğer kullanıcı yönetici ya da admin değilse ana sayfaya yönlendir
if ($userRole != 'yönetici' && $userRole != 'admin') {
    header("Location:index.php");
    exit;
}
?>

<div class="container mt-5">
    <!-- Kullanıcı Bilgileri Tablosu -->
    <div class="row">
        <div class="col-lg-12">
            <h3 class="mb-4">Kullanıcı Tablosu</h3>
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Kullanıcı Adı</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Yorum Sayısı</th>
                    <th scope="col">Açtığı Başlık Sayısı</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                require_once 'User.php'; // Kullanıcı sınıfı dahil et
                require_once 'Admin.php'; // Admin sınıfı dahil et
                $admin = new Admin(); // Admin nesnesi oluştur
                $row = $admin->showUserList(); // Kullanıcı listesini getir

                // Kullanıcı bilgilerini tabloya yazdır
                foreach ($row as $userin){
                    $userid = $userin['userid']; // Kullanıcı ID'sini al
                    echo "<tr>";
                    echo "<td><a href='aktivite.php?userid=$userid'>" . $userin['nickname']. "</a></td>"; // Kullanıcı adını ve kullanıcı aktivitesine giden bağlantıyı ekle
                    echo "<td>" . $userin['userrole']. "</td>"; // Kullanıcı rolünü ekle
                    echo "<td>" . $userin['usercomsay']. "</td>"; // Kullanıcının yorum sayısını ekle
                    echo "<td>" . $userin['usertopicsay'] . "</td>"; // Kullanıcının açtığı başlık sayısını ekle
                    echo "<td style='width: 25%'>"; // İşlemler sütunu

                    // Eğer kullanıcı yönetici ise butonları göster
                    if ($_SESSION['userRole'] === 'yönetici') {
                        ?>
                        <div align="center">
                            <button type="button" class="btn btn-info" style="width: 50%" data-bs-toggle="modal" data-bs-target="#userInfoModal-<?php echo $userin['userid']; ?>">
                                Kullanıcı Bilgisi
                            </button>
                            <?php include "userinfoModal.php";?> <!-- Kullanıcı bilgi modalini dahil et -->
                        </div>
                        <?php
                    }
                    // Eğer kullanıcı admin ise ek olarak "Kullanıcıyı Sil" butonunu da göster
                    elseif ($_SESSION['userRole'] === 'admin') {
                        ?>
                        <div align="center">
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#userInfoModal-<?php echo $userin['userid']; ?>">
                                Kullanıcı Bilgisi
                            </button>
                            <?php include "userinfoModal.php";?> <!-- Kullanıcı bilgi modalini dahil et -->
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal-<?php echo $userin['userid']; ?>">
                                Kullanıcıyı Sil
                            </button>
                            <?php include "userDeleteModal.php";?> <!-- Kullanıcı silme modalini dahil et -->
                        </div>
                        <?php
                    }
                    echo "</td>"; // İşlemler sütununu kapat
                    echo "</tr>"; // Satırı kapat
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
include "footer.php"; // Footerı dahil et
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
