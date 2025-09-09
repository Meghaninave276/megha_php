<?php
include("connection.php");
$msg = '';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn_login, $_POST['name']);
    $email = mysqli_real_escape_string($conn_login, $_POST['email']);
    $password = mysqli_real_escape_string($conn_login, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn_login, $_POST['cpassword']);
    $user_type = mysqli_real_escape_string($conn_login, $_POST['user_type']);

    if ($password !== $cpassword) {
        $msg = "Passwords do not match!";
    } else {
        $select1 = "SELECT * FROM `users` WHERE email = '$email' AND password = '$password' ";
        $select_user = mysqli_query($conn_login, $select1);

        if (mysqli_num_rows($select_user) > 0) {
            $msg = "User already exists!";
        } else {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert1 = "INSERT INTO `users`(`name`, `email`, `password`, `user_type`) 
                        VALUES ('$name','$email','$hashed_password','$user_type')";
            mysqli_query($conn_login, $insert1);
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="style.css">
    
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


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
            <h2>Registration Page</h2>
            <p class="msg"><?php echo $msg; ?></p>

            <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Enter Your Name" required>
            </div>

            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Enter Your Email" required>
            </div>

            <div class="form-group">
                <select name="user_type" class="form-control" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Enter Your Password" required>
            </div>

            <div class="form-group">
                <input type="password" class="form-control" name="cpassword" placeholder="Enter Your Confirm Password" required>
            </div>

            <button class="btn btn-primary fw-bold" name="submit">Register Now</button>
            <p>Already Have An Account? <a href="login.php">Login Now</a></p>
        </form>
    </div>
</body>
</html>
