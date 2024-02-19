<?php
require_once("config.php");
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = isset($_POST['id']) ? $_POST['id'] : null;

    $title = $_POST['title'];
    $content = $_POST['content'];
    $publish_date = $_POST['publish_date'];

    // Jika gambar baru diunggah, proses penggantian gambar
    if ($_FILES['imageUpload']['size'] > 0) {
        $sql_select = "SELECT img_content FROM article WHERE id = :id";
        $stmt_select = $db->prepare($sql_select);
        $stmt_select->bindParam(':id', $id);
        $stmt_select->execute();
        $old_image = $stmt_select->fetchColumn();

        if ($old_image) {
            unlink("assets/uploads/$old_image");
        }

        $target_dir = "assets/uploads/";
        $target_file = $target_dir . basename($_FILES["imageUpload"]["name"]);
        move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $target_file);

        $img_content = basename($_FILES["imageUpload"]["name"]);
        $sql_update = "UPDATE article SET title = :title, content = :content, img_content = :img_content, published_date = :publish_date, user_id = :user_id WHERE id = :id";
        $stmt_update = $db->prepare($sql_update);
        $stmt_update->bindParam(':title', $title);
        $stmt_update->bindParam(':content', $content);
        $stmt_update->bindParam(':img_content', $img_content);
        $stmt_update->bindParam(':publish_date', $publish_date);
        $stmt_update->bindParam(':user_id', $_SESSION['user']['id']);
        $stmt_update->bindParam(':id', $id);
        $saved = $stmt_update->execute();
    } else {
        $sql_update = "UPDATE article SET title = :title, content = :content, published_date = :publish_date, user_id = :user_id WHERE id = :id";
        $stmt_update = $db->prepare($sql_update);
        $stmt_update->bindParam(':title', $title);
        $stmt_update->bindParam(':content', $content);
        $stmt_update->bindParam(':publish_date', $publish_date);
        $stmt_update->bindParam(':user_id', $_SESSION['user']['id']);
        $stmt_update->bindParam(':id', $id);
        $saved = $stmt_update->execute();
    }

    if ($saved) {
        $_SESSION['message'] = "Berhasil update article";
        header("Location: edit_article.php?id=" . $id);
        exit(); // Pastikan Anda keluar setelah mengalihkan
    } else {
        // Jika terjadi kesalahan saat mengeksekusi query
        $_SESSION['error'] =  "Gagal menyimpan article ke database.";
        header("Location: edit_article.php?id=" . $id);
        exit();
    }
}
