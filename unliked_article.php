<?php
session_start();
require_once("config.php");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "DELETE FROM likes WHERE user_id = :user_id AND article_id = :article_id";
    $stmt = $db->prepare($sql);

    $stmt->bindParam(':user_id', $_SESSION['user']['id']);
    $stmt->bindParam(':article_id', $_GET["article_id"]);

    // Eksekusi statement
    if ($stmt->execute()) {
        // Jika penghapusan berhasil, kirimkan respons berhasil
        echo json_encode(["success" => true, "message" => "Berhasil unlike article."]);
    } else {
        // Jika terjadi kesalahan saat menghapus, kirimkan respons gagal
        echo json_encode(["success" => false, "message" => "Gagal unlike article."]);
    }
}

