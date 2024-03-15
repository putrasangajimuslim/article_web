<?php
require_once("config.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // $data = json_decode(file_get_contents('php://input'), true);

    $sql = "INSERT INTO article (title, content, img_content, published_date, user_id) 
                VALUES (:title, :content, :img_content, :publish_date, :user_id)";
    $stmt = $db->prepare($sql);

    $params = array(
        ":title" => $_POST["title"],
        ":content" => $_POST["content"],
        ":img_content" => $_POST["img_content"],
        ":publish_date" => $_POST["publish_date"],
        ":user_id" => $_POST['user_id'],
    );

    // eksekusi query untuk menyimpan ke database
    $saved = $stmt->execute($params);

    // jika query simpan berhasil, maka user sudah terdaftar
    // maka alihkan ke halaman login
    if ($saved) {
        echo json_encode([
            'error' => false,
            'message' => 'Data berhasil disimpan',
        ]);
    } else {
        // Jika terjadi kesalahan saat mengeksekusi query
        echo json_encode([
            'error' => true,
            'message' => 'Data Gagal disimpan',
        ]);
    }
}
