<?php
include "DB.php";
session_start();
require_once 'User.php';
require_once 'Admin.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user = new User();
    $admin = new Admin();

    if (isset($_POST['infoform'])){
        $form1userId = $_POST['infoform'];
        $ad = $_POST['ad'];
        $soyad = $_POST['soyad'];
        $mail = $_POST['mail'];
        $password = $_POST['sifre'];
        $nickname = $_POST['nickname'];
        $user->updateinfo($nickname, $password,$ad,$soyad,$mail,$form1userId);
    }
    if (isset($_POST['roleform'])) {
        $form2userId = $_POST['roleform'];
        $newRole = $_POST['role'];

        $admin->updateUserRole($form2userId, $newRole);
        if($form2userId==$_SESSION['user_ID']){
            $_SESSION['userRole']=$newRole;
        }

            echo "<p class='text-success mt-3'>Rol başarıyla güncellendi.</p>";
    }
    if (isset($_POST['yetkiform'])) {
        $form3userId = $_POST['yetkiform'];
        $privilege1 = isset($_POST['whois']) ? 1 : 0;
        $privilege2 = isset($_POST['dns']) ? 1 : 0;

        $admin->updatePrivileges($form3userId, $privilege1, $privilege2);

            echo "<p class='text-success mt-3'>Yetki başarıyla güncellendi.</p>";
    }
}
?>