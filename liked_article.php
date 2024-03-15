<?php
session_start();
require_once("config.php");

// Periksa apakah kunci 'logged_in' ada di sesi
if (isset($_SESSION['user']) && isset($_GET['article_id'])) {
    $msg = 'success';

    $sql_check = "SELECT COUNT(*) AS count FROM likes WHERE article_id = :article_id AND user_id = :user_id";
    $stmt_check = $db->prepare($sql_check);
    $stmt_check->bindParam(':article_id', $_GET["article_id"]);
    $stmt_check->bindParam(':user_id', $_SESSION['user']['id']);
    $stmt_check->execute();
    $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        return;
    } else {
        $sql = "INSERT INTO likes (article_id, user_id, jml_like) 
                    VALUES (:article_id, :user_id, :jumlah_like)";
        $stmt = $db->prepare($sql);
    
        $params = array(
            ":article_id" => $_GET["article_id"],
            ":user_id" => $_SESSION['user']['id'],
            ":jumlah_like" => 1,
        );
    }

    // eksekusi query untuk menyimpan ke database
    $saved = $stmt->execute($params);

    header('Content-Type: application/json');
    echo json_encode($msg);
} else {
    $msg = 'error';
    header('Content-Type: application/json');
    echo json_encode($msg);
}
