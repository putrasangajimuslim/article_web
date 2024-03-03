<?php
session_start(); // Mulai sesi pada setiap halaman yang menggunakan sesi

require_once("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username=:username OR email=:email";
    $stmt = $db->prepare($sql);

    // Bind parameter ke query
    $params = array(
        ":username" => $username,
        ":email" => $username
    );

    $stmt->execute($params);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verifikasi password
        if (password_verify($password, $user["password"])) {
            // Buat Session
            $_SESSION["user"] = $user;

            // Hapus pesan kesalahan jika ada
            unset($_SESSION['error']);

            // Login sukses, periksa peran pengguna
            if ($user['role'] == 'admin') {
                // Redirect to admin page
                header("Location: dashboard.php");
                exit; // Pastikan untuk keluar dari skrip setelah melakukan pengalihan
            } else if ($user['role'] == 'writer') {
                // Redirect to writer page
                header("Location: article.php");
                exit; // Pastikan untuk keluar dari skrip setelah melakukan pengalihan
            } else {
                // Redirect to user page
                header("Location: index.php");
                exit; // Pastikan untuk keluar dari skrip setelah melakukan pengalihan
            }
        } else {
            // Jika password tidak sesuai, atur pesan kesalahan
            $_SESSION['error'] = "Gagal, username dan password tidak sesuai";
        }
    } else {
        // Jika tidak ada pengguna dengan username atau email yang diberikan
        $_SESSION['error'] = "Gagal, username atau email tidak ditemukan";
    }

    // Kembalikan ke halaman login
    header("Location: index.php");
    exit; // Pastikan untuk keluar dari skrip setelah melakukan pengalihan
}
