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

    // Check apakah user dengan username tersebut sudah ada di tabel users
    $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Jika username sudah ada, kembalikan ke halaman register
    if ($row) {
        $_SESSION['error'] = 'Username: ' . $_POST['username'] . ' sudah ada di database.';
        header("Location: register.php");
        exit();
    } else {
        // Hash password
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Simpan data pengguna ke dalam database
        $insert_query = "INSERT INTO users (username, nama_depan, nama_belakang, birthday, email, role, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($insert_query);
        $stmt->bind_param('sssssss', $_POST['username'], $_POST['nama_dpn'], $_POST['nama_belakang'], $_POST['birthday'], $_POST['email'], 'user', $hashed_password); // Perbaikan disini, melewatkan nilai langsung, bukan referensi
        $stmt->execute();

        // Set pesan sukses dan kembalikan ke halaman register
        $_SESSION['message'] = 'Berhasil register ke dalam sistem. Silakan login dengan username dan password yang sudah dibuat.';
        header("Location: register.php");
        exit();
    }
}
