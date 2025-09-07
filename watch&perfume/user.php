<?php
include('connection.php');
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    background: linear-gradient(135deg, #2c003e, #0f2027); /* Deep purple to dark teal */
}

/* Glassmorphism style for user page */
.user_page {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border-radius: 18px;
    padding: 40px 35px;
    width: 450px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.15);
    animation: floatUp 1.5s ease-out;
    text-align: center;
}

/* Heading */
.user_page h2 {
    font-weight: 600;
    color: #e0e0e0;
    margin-bottom: 20px;
}

/* Admin label */
.user_page p {
    color: #ccc;
    font-size: 1.1rem;
    margin-bottom: 20px;
}

.user_page p span {
    color: #00e6e6;
    font-weight: bold;
}

/* Logout button */
.user_page button {
    padding: 12px 20px;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    background: linear-gradient(135deg, #00c6ff, #0072ff);
    color: #ffffff;
    transition: 0.3s ease-in-out;
}

.user_page button:hover {
    background: linear-gradient(135deg, #0072ff, #00c6ff);
    transform: translateY(-2px);
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
    <div class="user_page">
        <h2>Welcome to User Page!</h2>
      <p>User: <span>
    <?php echo isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']) : 'Guest'; ?>
</span></p>      <a href="logout.php"><button class="btn fw-bold">Logout</button></a>
    </div>
    
</body>
</html>