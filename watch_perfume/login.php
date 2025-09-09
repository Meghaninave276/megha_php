<?php
session_start();

include("connection.php");

$msg = '';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $select1 = "SELECT * FROM `users` WHERE email = '$email' AND password = '$password'";
    $select_user = mysqli_query($conn, $select1);

    if (mysqli_num_rows($select_user) > 0) {
        $row1 = mysqli_fetch_assoc($select_user);

        if ($row1['user_type'] == 'user') {
            $_SESSION['user'] = $row1['email'];
            $_SESSION['id'] = $row1['id'];
            header('Location: user.php');
            exit;
        } elseif ($row1['user_type'] == 'admin') {
            $_SESSION['admin'] = $row1['email'];
            $_SESSION['id'] = $row1['id'];
            header('Location: admin.php');
            exit;
        } else {
            $msg = "Invalid user type!";
        }
     } 
    //  else {
    //     $msg = "Incorrect email or password!";
    // }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
       
body {
    margin: 0;
    padding: 0;
    font-family: "Poppins", sans-serif;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
     background: linear-gradient(135deg, #07121e, #122b40); 
}


.form {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border-radius: 18px;
    padding: 40px 35px;
    width: 450px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.15);
    animation: floatUp 1.5s ease-out;
}


.form h2 {
    text-align: center;
    font-weight: 600;
    color: #e0e0e0;
    margin-bottom: 20px;
}


.msg {
    text-align: center;
    font-size: 0.9rem;
    margin-bottom: 15px;
    color: #ff6b6b;
}

.form .form-control {
    background: rgba(255, 255, 255, 0.12);
    border: none;
    color: #ffffff;
    border-radius: 10px;
    padding: 12px;
    margin-bottom: 15px;
    transition: 0.3s ease;
}

.form .form-control::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.form .form-control:focus {
    background: rgba(255, 255, 255, 0.2);
    outline: none;
    box-shadow: none;
}


.form button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    background: linear-gradient(135deg, #00c6ff, #0072ff); /* Cyan gradient */
    color: #ffffff;
    transition: 0.3s ease-in-out;
}

.form button:hover {
    background: linear-gradient(135deg, #0072ff, #00c6ff);
    transform: translateY(-2px);
}


.form p {
    text-align: center;
    margin-top: 15px;
    color: #ccc;
}

.form a {
    color: #00e6e6;
    text-decoration: none;
}

.form a:hover {
    text-decoration: underline;
}


@keyframes floatUp {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

    </style>



</head>
<body>
    <div class="form">
        <form action="" method="post">
            <h2>Login Page</h2>
            <p class="msg"><?php echo $msg; ?></p>

            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Enter Your Email" required>
            </div>

            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Enter Your Password" required>
            </div>

            <button class="btn fw-bold btn-primary" name="submit" ><a href="nav.php" class="text-decoration-none text-white">Login Now</a></button>
            <p>Don't Have An Account? <a href="register.php">Register Now</a></p>
        </form>
    </div>
</body>
</html>
