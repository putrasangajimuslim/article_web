<?php
session_start();
require_once("config.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "DELETE FROM users WHERE id = :userid";
    $stmt = $db->prepare($sql);

    $stmt->bindParam(":userid", $_POST["user_id"]);

    // Eksekusi statement
    if ($stmt->execute()) {
        // Jika penghapusan berhasil, kirimkan respons berhasil
        echo json_encode(["success" => true, "message" => "Berhasil menghapus user."]);
    } else {
        // Jika terjadi kesalahan saat menghapus, kirimkan respons gagal
        echo json_encode(["success" => false, "message" => "Gagal menghapus user."]);
    }
}
