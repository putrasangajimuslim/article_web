<?php
require_once("config.php");
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi jika password & password_confirmation sama
    if ($_POST['password'] != $_POST['password_confirmation']) {
        $_SESSION['error'] = 'Password yang Anda masukkan tidak sama dengan konfirmasi password.';
        header("Location: register.php");
        exit();
    }

    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $sql_check = "SELECT COUNT(*) AS count FROM users WHERE username = :username OR email = :email";
    $stmt_check = $db->prepare($sql_check);
    $stmt_check->bindParam(':username', $_POST["username"]);
    $stmt_check->bindParam(':email', $_POST['email']);
    $stmt_check->execute();
    $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        // Jika username atau email sudah ada, kembalikan pesan kesalahan
        $_SESSION['error'] = "Username atau email sudah ada dalam sistem";
        // Redirect ke halaman lain atau lakukan tindakan yang sesuai
        header("Location: register.php");
        exit; // Pastikan untuk menghentikan eksekusi skrip setelah redirect
    } else {
        $sql = "INSERT INTO users (username, nama_depan, nama_belakang, birthday, email, role, password) 
                VALUES (:username, :nama_depan, :nama_belakang, :birthday, :email, :role, :password)";
        $stmt = $db->prepare($sql);

        $params = array(
            ":username" => $_POST["username"],
            ":nama_depan" => $_POST["nama_dpn"],
            ":nama_belakang" => $_POST["nama_belakang"],
            ":email" => $_POST['email'],
            ":birthday" => $_POST['birthday'],
            ":role" => 'user',
            ":password" => $password
        );

        // eksekusi query untuk menyimpan ke database
        $saved = $stmt->execute($params);

        // jika query simpan berhasil, maka user sudah terdaftar
        // maka alihkan ke halaman login
        if ($saved) $_SESSION['message'] = "Berhasil Register";
        if ($saved) header("Location: register.php");
    }
}
