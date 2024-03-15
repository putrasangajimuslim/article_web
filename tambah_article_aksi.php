<?php
require_once("config.php");
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["imageUpload"])) {
    $target_dir = "assets/uploads/"; // Folder tempat gambar akan disimpan
    $target_file = $target_dir . basename($_FILES["imageUpload"]["name"]); // Path lengkap ke file
    $fileNames = basename($_FILES["imageUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    // Cek apakah file sudah ada
    if (file_exists($target_file)) {
        $_SESSION['error'] = "Maaf, file sudah ada.";
        $uploadOk = 0;
    }

    // Cek ukuran file
    if ($_FILES["imageUpload"]["size"] > 500000) {
        $_SESSION['error'] =  "Maaf, ukuran file terlalu besar.";
        $uploadOk = 0;
    }

    // Izinkan hanya format gambar tertentu
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        $_SESSION['error'] = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
        $uploadOk = 0;
        header("Location: tambah_article.php");
        return;
        exit();
    }

    // Cek jika $uploadOk bernilai 0
    if ($uploadOk != 0) {  // Jika semuanya baik-baik, coba unggah file
        if (move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $target_file)) {
            $_SESSION['message'] =  "File " . htmlspecialchars(basename($_FILES["imageUpload"]["name"])) . " berhasil diunggah.";
        } else {
            $_SESSION['error'] = "Maaf, terjadi kesalahan saat mengunggah file.";
            header("Location: tambah_article.php");
            return;
            exit();
        }
    }

    $sql = "INSERT INTO article (title, content, img_content, published_date, user_id) 
                VALUES (:title, :content, :img_content, :publish_date, :user_id)";
    $stmt = $db->prepare($sql);

    $params = array(
        ":title" => $_POST["title"],
        ":content" => $_POST["content"],
        ":img_content" => $fileNames,
        ":publish_date" => $_POST["publish_date"],
        ":user_id" => $_SESSION['user']['id'],
    );

    // eksekusi query untuk menyimpan ke database
    $saved = $stmt->execute($params);

    // jika query simpan berhasil, maka user sudah terdaftar
    // maka alihkan ke halaman login
    if ($saved) {
        $_SESSION['message'] = "Berhasil membuat article";
        header("Location: tambah_article.php");
        exit(); // Pastikan Anda keluar setelah mengalihkan
    } else {
        // Jika terjadi kesalahan saat mengeksekusi query
        $_SESSION['error'] =  "Gagal menyimpan article ke database.";
        header("Location: tambah_article.php");
        exit();
    }
}
