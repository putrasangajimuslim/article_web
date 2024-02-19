<?php
session_start();
require_once("config.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql_select = "SELECT img_content FROM article WHERE id = :article_id";
    $stmt_select = $db->prepare($sql_select);
    $stmt_select->bindParam(':article_id', $_POST["article_id"]);
    $stmt_select->execute();
    $old_image = $stmt_select->fetchColumn();

    if ($old_image) {
        unlink("assets/uploads/$old_image");
    }

    $sql = "DELETE FROM article WHERE id = :article_id";
    $stmt = $db->prepare($sql);

    $stmt->bindParam(":article_id", $_POST["article_id"]);

    // Eksekusi statement
    if ($stmt->execute()) {
        // Jika penghapusan berhasil, kirimkan respons berhasil
        echo json_encode(["success" => true, "message" => "Berhasil menghapus article."]);
    } else {
        // Jika terjadi kesalahan saat menghapus, kirimkan respons gagal
        echo json_encode(["success" => false, "message" => "Gagal menghapus article."]);
    }
}
