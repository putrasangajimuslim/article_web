<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            /* Warna latar belakang */
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            /* Warna background kontainer */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-control {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"] {
            width: 100%;
            height: 35px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 10px;
        }

        .btn-register {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #0D4ADA;
            /* Warna tombol */
            color: #fff;
            cursor: pointer;
        }

        .btn-register:hover {
            background-color: #6389e3;
            /* Warna tombol saat dihover */
        }

        .btn-back {
            width: 20%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #a6a6a6;
            /* Warna tombol */
            color: #fff;
            cursor: pointer;
            text-decoration: none;
        }

        .alert-warning {
            padding: 10px;
            background-color: #F8D7DA;
            margin-top: 17px;
            color: #9D2E24;
            border-radius: 10px;
        }

        .alert-success {
            padding: 10px;
            background-color: #D4EDDA;
            margin-top: 17px;
            color: #155724;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="index.php" class="btn-back">Back</a>

        <?php
        if (isset($_SESSION['error'])) {
        ?>
            <div class="alert alert-warning" role="alert">
                <?php echo $_SESSION['error'] ?>
            </div>
        <?php
        }
        ?>
        <?php
        if (isset($_SESSION['message'])) {
        ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_SESSION['message'] ?>
            </div>
        <?php
        }
        ?>

        <form action="register_aksi.php" method="post">
            <h2>Register</h2>
            <div class="form-control">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-control">
                <label for="nama_dpn">Nama Depan</label>
                <input type="text" id="nama_dpn" name="nama_dpn" required>
            </div>
            <div class="form-control">
                <label for="nama_belakang">Nama Belakang</label>
                <input type="text" id="nama_belakang" name="nama_belakang" required>
            </div>
            <div class="form-control">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-control">
                <label for="birthday">Tanggal Lahir</label>
                <input type="date" id="birthday" name="birthday" required>
            </div>
            <div class="form-control">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-control">
                <label for="password">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn-register">Register</button>
        </form>
    </div>
</body>

</html>