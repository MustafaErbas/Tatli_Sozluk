<?php
session_start();
if (!isset($_SESSION['oturum'])) {
    header("Location:login.php");
    exit;
}
?>
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
    <title>Profil Sayfası</title>
</head>
<body>
<?php
include "navbar.php";
?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-bg-light overflow-hidden">
                <div class="row card-header text-center ">
                    <h2 style="display: inline-block">Profil Bilgileri</h2>
                </div>
                <div class="card-body">
                    <img src="../profilfotosu.png" class="img-fluid rounded-circle mx-auto d-block mb-3" style="max-width: 150px;">
                    <form method="post">
                        <h2>Kişisel Bilgiler</h2>
                            <?php
                            require_once 'User.php'; // User sınıfını dahil et
                            include "DB.php";
                            $user = new User();

                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                if (isset($_POST['updateBtn'])){
                                    $ad = $_POST["ad"];
                                    $soyad = $_POST["soyad"];
                                    $mail = $_POST["mail"];
                                    $password = $_POST["sifre"];
                                    $nickname = $_POST["nickname"];
                                    $user->updateinfo($nickname, $password,$ad,$soyad,$mail,$_SESSION['user_ID']);
                                }
                                if (isset($_POST['logoutBtn'])) {
                                    $user->logout();
                                }
                            }
                            $row = $user->showinfo($_SESSION['user_ID']);
                            ?>
                        <p><strong>Ad:</strong> <input required style="width: 80%" type="text" name="ad" value="<?php echo $row["user_name"]; ?>"></p>
                        <p><strong>Soyad:</strong> <input required style="width: 80%" type="text" name="soyad" value="<?php echo $row["user_surname"]; ?>"></p>
                        <p><strong>E-Mail:</strong> <input required style="width: 80%" type="email" name="mail" value="<?php echo $row["user_mail"]; ?>"></p>
                        <hr>
                        <h2>Kullanıcı Bilgileri</h2>
                        <p><strong>Kullanıcı Ad:</strong> <input required style="width: 80%" type="text" name="nickname" value="<?php echo $row["nickname"]?>"></p>
                        <p><strong>Şifre:</strong> <input required style="width: 80%" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,}$" title="Lütfen en az 8 karakter kullanınız!! En az bir sayı, bir küçük harf ve bir büyük harf içermelidir. Boşluk içeremez." type="text" name="sifre" value="<?php echo $row["user_password"]; ?>"></p>
                        <div align="right">
                            <button type="submit" name="updateBtn" class="btn btn-primary">Güncelle</button>
                        </div>
                    </form>
                    <form method="post">
                        <div align="right" class="mt-2">
                            <!-- Çıkış Yap butonu -->
                            <button type="submit" name="logoutBtn" class="btn btn-danger">Çıkış Yap</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "footer.php";
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</body>
</html>
