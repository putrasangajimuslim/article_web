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
        input[type="password"] {
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
    </style>
</head>

<body>
    <div class="container">
        <a href="index.php" class="btn-back">Back</a>

        <form action="#" method="post">
            <h2>Register</h2>
            <div class="form-control">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-control">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-control">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-register">Register</button>
        </form>
    </div>
</body>

</html>