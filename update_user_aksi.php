<?php
require_once("config.php");
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = isset($_POST['id']) ? $_POST['id'] : null;

    $username = $_POST['username'];
    $nama_d = $_POST['nama_d'];
    $nama_b = $_POST['nama_b'];
    $birthday = $_POST['birthday'];
    $email = $_POST['email'];
    $user_role = $_POST['user_role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($password) && empty($confirm_password)) {
        $sql_update = "UPDATE users SET username = :username, nama_depan = :nama_d, nama_belakang = :nama_b, birthday = :birthday, email = :email, role = :user_role WHERE id = :id";
        $stmt_update = $db->prepare($sql_update);
        $stmt_update->bindParam(':username', $username);
        $stmt_update->bindParam(':nama_d', $nama_d);
        $stmt_update->bindParam(':nama_b', $nama_b);
        $stmt_update->bindParam(':birthday', $birthday);
        $stmt_update->bindParam(':email', $email);
        $stmt_update->bindParam(':user_role', $user_role);
        $stmt_update->bindParam(':id', $id);
        $saved = $stmt_update->execute();

        if ($saved) {
            $_SESSION['message'] = "Berhasil update user";
            header("Location: edit_user.php?id=" . $id);
            exit(); // Pastikan Anda keluar setelah mengalihkan
        } else {
            // Jika terjadi kesalahan saat mengeksekusi query
            $_SESSION['error'] =  "Gagal update data user ke database.";
            header("Location: edit_user.php?id=" . $id);
            exit();
        }

    } else {
        if ($password !== $confirm_password) {
            $_SESSION['error'] =  "Gagal update password dan konfirmasi password tidak sesuai.";
            header("Location: edit_user.php?id=" . $id);
            return;
            exit();
        }

        $sql_update = "UPDATE users SET username = :username, nama_depan = :nama_d, nama_belakang = :nama_b, birthday = :birthday, email = :email, role = :user_role, password = :new_password WHERE id = :id";

        $newpassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt_update = $db->prepare($sql_update);
        $stmt_update->bindParam(':username', $username);
        $stmt_update->bindParam(':nama_d', $nama_d);
        $stmt_update->bindParam(':nama_b', $nama_b);
        $stmt_update->bindParam(':birthday', $birthday);
        $stmt_update->bindParam(':email', $email);
        $stmt_update->bindParam(':user_role', $user_role);
        $stmt_update->bindParam(':new_password', $newpassword);
        $stmt_update->bindParam(':id', $id);
        $saved = $stmt_update->execute();

        if ($saved) {
            $_SESSION['message'] = "Berhasil update user";
            header("Location: edit_user.php?id=" . $id);
            exit(); // Pastikan Anda keluar setelah mengalihkan
        } else {
            // Jika terjadi kesalahan saat mengeksekusi query
            $_SESSION['error'] =  "Gagal update data user ke database.";
            header("Location: edit_user.php?id=" . $id);
            exit();
        }
    }
}
