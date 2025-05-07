<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıtf Sayfası</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="blog.css">
    <style>
        a:hover {
            text-decoration: none;
        }
    </style>
</head>
<body>
<?php
include "navbar.php";
?>
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Kayıt Formu</h4>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="form-group">
                            <label for="nickname">Kullanıcı Adı:</label>
                            <input type="text" class="form-control" name="nickname" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Şifre:</label>
                            <input type="password" class="form-control" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,}$" title="En az bir sayı, bir küçük harf ve bir büyük harf içermelidir. Boşluk içeremez." name="password" required>
                            <!-- işaret istenildiğinde böyle (?=.*[!@$%&*]) -->
                            <!-- şifrede boşluk koymayı engeller (?!.*\s) -->
                            <small>Lütfen en az 8 karakter kullanınız!!</small>
                        </div>
                        <div class="form-group">
                            <label for="ad">Ad:</label>
                            <input type="text" class="form-control" name="ad" required>
                        </div>
                        <div class="form-group">
                            <label for="soyad">Soyad:</label>
                            <input type="text" class="form-control" name="soyad" required>
                        </div>
                        <div class="form-group">
                            <label for="mail">Mail:</label>
                            <input type="email" class="form-control" name="mail" required>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <button type="submit" class="btn btn-primary ">Kayıt Ol</button>
                            <a href="login.php"> <button type="button" class="btn btn-success btn-md mr-4">Login Sayfasına Dön</button> </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-lg " align="center">
    <?php
    require_once 'User.php'; // User sınıfını dahil et
    include "DB.php";
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $user = new User();
        $nickname = $_POST['nickname'];
        $password = $_POST['password'];
        $ad       = $_POST['ad'];
        $soyad    = $_POST['soyad'];
        $mail     = $_POST['mail'];

        $user->register($nickname, $password,$ad,$soyad,$mail);
    }
    ?>
</div>

<?php
include "footer.php";
?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

