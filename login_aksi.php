<?php
require_once("config.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username=:username OR email=:email";
    $stmt = $db->prepare($sql);

    // bind parameter ke query
    $params = array(
        ":username" => $username,
        ":email" => $username
    );

    $stmt->execute($params);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // verifikasi password
        if (password_verify($password, $user["password"])) {
            // buat Session
            session_start();
            $_SESSION["user"] = $user;
            // login sukses, periksa peran pengguna
            if ($user['role'] == 'admin') {
                // Redirect to admin page
                header("Location: article.php");
            } else {
                // Redirect to user page
                header("Location: index.php");
            }
        }
    }
}
