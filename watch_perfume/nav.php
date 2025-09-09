<?php
include 'connection.php';

// ✅ Handle Contact Form Submission
if (isset($_POST['send_message'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = mysqli_prepare($conn_contact, "INSERT INTO contact (`name`, `email`, `message`) VALUES (?, ?, ?)");
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message);
            
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Message sent successfully!');</script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($conn_contact) . "');</script>";
            }
            
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('Error: Prepare failed. " . mysqli_error($conn_contact) . "');</script>";
        }
    } else {
        echo "<script>alert('Please fill all fields');</script>";
    }
}
// ✅ Handle Product Search & Filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$price = isset($_GET['price']) ? $_GET['price'] : 'all';

// Price condition
$priceCondition = '';
if ($price != 'all') {
    if ($price == '0-50') {
        $priceCondition = " AND price BETWEEN 0 AND 50";
    } elseif ($price == '51-100') {
        $priceCondition = " AND price BETWEEN 51 AND 100";
    } elseif ($price == '101-500') {
        $priceCondition = " AND price BETWEEN 101 AND 500";
    } elseif ($price == '500plus') {
        $priceCondition = " AND price > 500";
    }
}

// Search condition
$searchCondition = '';
if (!empty($search)) {
    $searchSafe = mysqli_real_escape_string($conn_product, $search);
    $searchCondition = " AND (title LIKE '%$searchSafe%' OR description LIKE '%$searchSafe%')";
}

// ✅ Product Queries
$queryWatch = "SELECT * FROM products WHERE category='watch' $priceCondition $searchCondition";
$queryPerfume = "SELECT * FROM products WHERE category='perfume' $priceCondition $searchCondition";

$resultWatch = mysqli_query($conn_product, $queryWatch);
$resultPerfume = mysqli_query($conn_product, $queryPerfume);

// ✅ Scroll target logic
$scrollTarget = '';
if (!empty($search)) {
    if ($resultWatch && mysqli_num_rows($resultWatch) > 0) {
        $scrollTarget = 'watch';
    } elseif ($resultPerfume && mysqli_num_rows($resultPerfume) > 0) {
        $scrollTarget = 'perfume';
    }
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://fonts.googleapis.com/css2?family=Bubblegum+Sans&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&family=Signika+Negative:wght@300..700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" integrity="sha512-UTNP5BXLIptsaj5WdKFrkFov94lDx+eBvbKyoe1YAfjeRPC+gT5kyZ10kOHCfNZqEui1sxmqvodNUx3KbuYI/A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="style.css">






  <style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    height: auto;
    width: 100%;
    background: linear-gradient(135deg, #07121e, #122b40); 
    background-attachment: fixed;
    color: #fff;
    font-family: 'Poppins', sans-serif;
    overflow:auto;

}

/* Navbar Section */
.Nav nav {
    background: linear-gradient(135deg, #07121e, #122b40); 
    /* padding: 15px 20px; */
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    font-family: 'Poppins', sans-serif;
    width: 100%;
}

/* Logo */
.navbar-brand {
    font-size: 1.5rem;
    font-weight: bold;
    color: #fff !important;
    display: flex;
    align-items: center;
    gap: 10px;
}

.navbar-brand img {
    border-radius: 50%;
   
}

/* Nav Links */
.navbar-nav .nav-link {
    color: #ddd !important;
    font-size: 1rem;
    padding: 8px 15px;
    position: relative;
    transition: 0.3s;
}

.navbar-nav .nav-link:hover {
    color: #ffce00 !important; /* Gold hover effect */
}

.navbar-nav .nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background: #ffce00;
    transition: 0.3s;
    transform: translateX(-50%);
}

.navbar-nav .nav-link:hover::after {
    width: 50%;
}

/* Search Form */
form.d-flex {
    display: flex;
    gap: 10px;
}

form.d-flex input {
    border-radius: 30px;
    border: 1px solid #ccc;
    padding: 8px 15px;
    font-size: 0.9rem;
    outline: none;
    transition: 0.3s;
}

form.d-flex input:focus {
    border-color: #ffce00;
    box-shadow: 0 0 8px rgba(255, 206, 0, 0.6);
}

form.d-flex select {
    border-radius: 30px;
    border: 1px solid #ccc;
    padding: 8px 15px;
    background-color: #fff;
    font-size: 0.9rem;
    outline: none;
}

form.d-flex button {
    border-radius: 30px;
    background: #ffce00;
    color: #000;
    font-weight: bold;
    border: none;
    padding: 8px 20px;
    cursor: pointer;
    transition: 0.3s;
}

form.d-flex button:hover {
    background: #fff;
    color: #ffce00;
    border: 1px solid #ffce00;
}

/* Mobile */
@media (max-width: 991px) {
    .navbar-nav {
        text-align: center;
    }
    form.d-flex {
        flex-direction: column;
        gap: 10px;
    }
}

/* General Section Styling */

/* General Section Styling */
.one {
 background: linear-gradient(135deg, #07121e, #122b40); 
  /* padding: 20px 20px; */
  font-family: 'Poppins', sans-serif;
  
}

/* Container */
.two {
  max-width: 1450px;
  margin: 0 auto;
}

/* Carousel Items */
.carousel-item {
  transition: transform 0.8s ease-in-out, opacity 0.8s ease-in-out;
  padding: 30px 0;
}

.carousel-item.active {
  opacity: 1;
  transform: scale(1);
}

.carousel-item:not(.active) {
  opacity: 0.5;
  transform: scale(0.95);
}

/* Text Styling */
.carousel-item p.display-3 {
  font-weight: 800;
  font-size: 3.2rem;
  color: #f9f6f6ff;
  line-height: 1.2;
  text-transform: uppercase;
}

.carousel-item .fs-5 {
  color: #f9f6f6ff;
  line-height: 1.6;
  font-size: 1.1rem;
  text-align: justify;
}

/* Buttons */
.carousel-item button {
  background: linear-gradient(45deg, #ff6f61, #ff4757);
  border: none;
  color: #fff;
  font-weight: bold;
  font-size: 1.1rem;
  transition: 0.4s ease;
}

.carousel-item button:hover {
  background: linear-gradient(45deg, #ff4757, #ff6f61);
  transform: translateY(-3px);
  box-shadow: 0 8px 15px rgba(255, 71, 87, 0.4);
}

/* Image Styling */
.carousel-item img {
  border-radius: 15px;
  /* box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); */
  transition: transform 0.5s ease;
  height:600px;
  object-fit:contain;
}

.carousel-item img:hover {
  transform: scale(1.05);
}

/* Carousel Controls */
.carousel-control-prev-icon,
.carousel-control-next-icon {
  background-color: #333;
  border-radius: 50%;
  padding: 15px;
}

.carousel-control-prev-icon:hover,
.carousel-control-next-icon:hover {
  background-color: #ff4757;
}

/* Responsive */
@media (max-width: 768px) {
  .carousel-item p.display-3 {
    font-size: 2rem;
    text-align: center;
  }

  .carousel-item .fs-5 {
    text-align: center;
  }

  .carousel-item img {
    margin-top: 20px;
  }
}
/* ===============================
   RESPONSIVE MEDIA QUERIES
================================= */

/* Extra Large Devices (≥1200px) */
@media (max-width: 1400px) {
  .carousel-item p.display-3 {
    font-size: 2.8rem;
  }
  .carousel-item .fs-5 {
    font-size: 1rem;
  }
  .carousel-item img {
    height: 500px;
  }
}

/* Large Devices (≥992px and <1200px) */
@media (max-width: 1200px) {
  .carousel-item p.display-3 {
    font-size: 2.6rem;
  }
  .carousel-item .fs-5 {
    font-size: 0.95rem;
  }
  .carousel-item img {
    height: 450px;
  }
}

/* Medium Devices (≥768px and <992px) */
@media (max-width: 992px) {
  .carousel-item p.display-3 {
    font-size: 2.2rem;
    text-align: center;
  }
  .carousel-item .fs-5 {
    font-size: 0.9rem;
    text-align: center;
    padding: 0 15px;
  }
  .carousel-item img {
    height: 400px;
    display: block;
    margin: 0 auto;
  }
  .carousel-item button {
    font-size: 1rem;
    padding: 10px 20px;
  }
}

/* Small Devices (≥576px and <768px) */
@media (max-width: 768px) {
  .carousel-item p.display-3 {
    font-size: 1.8rem;
  }
  .carousel-item .fs-5 {
    font-size: 0.85rem;
  }
  .carousel-item img {
    height: 350px;
  }
  .carousel-item button {
    font-size: 0.95rem;
    padding: 8px 18px;
  }
}

/* Extra Small Devices (<576px) */
@media (max-width: 576px) {
  .carousel-item p.display-3 {
    font-size: 1.5rem;
    line-height: 1.3;
    text-align:center;
    margin:0%;
    padding:0%;
   
  }
  .carousel-item .fs-5 {
    font-size: 0.8rem;
    padding: 0 10px;
  }
  .carousel-item img {
    height: 250px;
    margin-top: 15px;
  }
  .carousel-item button {
    font-size: 0.9rem;
    padding: 8px 15px;
  }
}

/* Watches & Perfumes Sections */
section.Products {
padding: 40px;
margin-top:20px;
  

  
 
}

section.Products h1 {
  font-size: 2.8rem;
  font-weight: 700;
  text-transform: uppercase;
  color: #f1ebebff;
  text-align: center;
  position: relative;
  margin-bottom: 40px;
  
}

section.Products h1::after {
  content: '';
  width: 100px;
  height: 4px;
  background: #007bff;
  display: block;
  margin: 10px auto 0;
  border-radius: 2px;
}

/* Row and Columns */
.Products .row {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
 
}

/* Card Styling */
.Products .card {
  border: none;
  border-radius: 15px;
  overflow: hidden;
background: linear-gradient(135deg, #07121e, #122b40); 
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease-in-out;
  text-align: center;
  margin-top:30px;
  /* border:1px solid white; */
}

.Products .card:hover {
  transform: translateY(-10px);
  box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
}

/* Card Image */
.Products .card img {
  width: 100%;
  height: 250px;
  object-fit: cover;
  transition: transform 0.4s ease-in-out;
}

.Products .card:hover img {
  transform: scale(1.1);
}

/* Card Body */
.Products .card .card-body {
  padding: 20px;
}

.Products .card .card-title {
  font-size: 1.3rem;
  font-weight: 600;
  color: #f5f4f4ff;
  margin-bottom: 10px;
}

.Products .card .card-text {
  font-size: 0.95rem;
  color: #fefbfbff;
  margin-bottom: 10px;
  height: 45px;
  overflow: hidden;
}

/* Price Text */
.Products .card .text-success {
  font-size: 1.2rem;
  font-weight: 700;
  color: #28a745 !important;
  margin-bottom: 15px;
}

/* Add to Cart Button */
.Products .btn.cart {
  background: #0d3ff6ff;
  color: #fff;
  font-size: 0.9rem;
  font-weight: 600;
  border-radius: 25px;
  padding: 10px 20px;
  transition: background 0.3s ease;
}

.Products .btn.cart:hover {
  background: #10205bff;
}

/* Responsive */
@media (max-width: 768px) {
  section.Products h1 {
    font-size: 2rem;
  }
  .Products .card img {
    height: 200px;
  }
}





/*about*/


/* ABOUT US SECTION */
.about-us {
    background: linear-gradient(135deg, #0b1a2a, #17293eff);
    color: #fdfdfd;
    padding: 80px 20px;
    font-family: 'Poppins', sans-serif;
}

.about-title {
    font-size: 3rem;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 15px;
    position: relative;
    display: inline-block;
}

.about-title span {
    color: #ffce00;
}

.about-title::after {
    content: '';
    width: 80px;
    height: 4px;
    background: #ffce00;
    display: block;
    margin: 10px auto 0;
    border-radius: 2px;
}

.about-intro {
    font-size: 1.2rem;
    color: #ccc;
    max-width: 900px;
    margin: auto;
    line-height: 1.8;
}

.about-card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    padding: 40px 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.about-card:hover {
    background: rgba(255, 206, 0, 0.1);
    transform: translateY(-10px);
}

.about-card::before {
    content: '';
    position: absolute;
    width: 0;
    height: 100%;
    top: 0;
    left: 0;
    background: rgba(255, 206, 0, 0.05);
    z-index: 0;
    transition: width 0.5s;
}

.about-card:hover::before {
    width: 100%;
}

.about-card p, .about-card h3 {
    position: relative;
    z-index: 1;
}

.about-icon {
    font-size: 3rem;
    color: #ffce00;
    margin-bottom: 15px;
}

.about-heading {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.our-story h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 20px;
}

.story-text {
    max-width: 800px;
    margin: auto;
    color: #ddd;
    font-size: 1.1rem;
    line-height: 1.7;
}

.our-story .btn {
    background: #ffce00;
    color: #000;
    font-weight: 600;
    padding: 10px 30px;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.our-story .btn:hover {
    background: #fff;
    color: #ffce00;
    transform: translateY(-3px);
}


/* Contact Us Section */

/* Contact Us Section */
.contact-us {
  background: linear-gradient(135deg, #0b1a2a, #1e2a39ff);
    padding: 80px 20px;
    font-family: 'Poppins', sans-serif;
   
}

.contact-us h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #ffffffff;
    margin-bottom: 50px;
    position: relative;
}

.contact-us h2::after {
    content: "";
    width: 60px;
    height: 4px;
    
    background: #ffb400;
    display: block;
    margin: 10px auto 0;
    border-radius: 2px;
}

/* Container Grid */
.contact-us .container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    max-width: 1100px;
    margin: 0 auto;
box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
    padding: 40px;
    border-radius: 20px;
  
}

/* Contact Form */
.contact-form {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.contact-form form input,
.contact-form form textarea {
    width: 100%;
    padding: 14px 18px;
    margin-bottom: 20px;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 10px;
    outline: none;
    transition: 0.3s ease;
    background: #fafafa;
}

.contact-form form input:focus,
.contact-form form textarea:focus {
    border-color: #ffb400;
    box-shadow: 0 0 8px rgba(255, 180, 0, 0.3);
    background: #fff;
}

/* Button */
.contact-form button {
    padding: 14px;
    background: #111;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 600;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: 0.3s ease;
}

.contact-form button:hover {
    background: #ffb400;
    color: #111;
}

/* Contact Info */
.contact-info {
    background: #111;
    color: #fff;
    border-radius: 15px;
    padding: 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.contact-info h3 {
    font-size: 1.8rem;
    margin-bottom: 20px;
    color: #ffb400;
}

.contact-info p {
    margin-bottom: 15px;
    font-size: 1rem;
    display: flex;
    align-items: center;
}

.contact-info i {
    color: #ffb400;
    font-size: 1.2rem;
    margin-right: 10px;
}

/* Responsive Design */
@media screen and (max-width: 992px) {
    .contact-us .container {
        grid-template-columns: 1fr;
        padding: 30px;
    }

    .contact-info {
        margin-top: 30px;
    }

    .contact-us h2 {
        font-size: 2rem;
    }
}


  </style>
</head>


<body>
  <section class="Nav" id="Nav">
    <nav class="navbar navbar-expand-lg navbar-light ">
  <div class="container-fluid ">
    <a class="navbar-brand" href="#"><img src="images/logos.png" height="60px" width="60px" alt="">Time&Essence</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link " aria-current="page" href="#Nav">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#about">About Us</a>
        </li>
        

       
        <li class="nav-item">
          <a class="nav-link  " href="#watch" tabindex="-1" aria-disabled="true">Watches</a>
        </li>
         <li class="nav-item">
          <a class="nav-link  " href="#perfume" tabindex="-1" aria-disabled="true">Perfumes</a>
        </li>
        
        
        <li class="nav-item">
          <a class="nav-link " href="#contact" tabindex="-1" aria-disabled="true">Contact Us</a>
        </li>
         <li class="nav-item">
          <a class="nav-link" href="register.php">Account</a>
        </li>
      </ul>
<form class="d-flex" method="GET" action="">
  <input id="searchBox" name="search" class="form-control me-2" type="search" placeholder="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
  
  <select id="priceFilter" name="price" class="px-3 py-2 rounded ms-2">
    <option value="all" <?php if(isset($_GET['price']) && $_GET['price']=='all') echo 'selected'; ?>>All Prices</option>
    <option value="0-50" <?php if(isset($_GET['price']) && $_GET['price']=='0-50') echo 'selected'; ?>>Below $50</option>
    <option value="51-100" <?php if(isset($_GET['price']) && $_GET['price']=='51-100') echo 'selected'; ?>>$51 - $100</option>
    <option value="101-500" <?php if(isset($_GET['price']) && $_GET['price']=='101-500') echo 'selected'; ?>>$101 - $500</option>
    <option value="500plus" <?php if(isset($_GET['price']) && $_GET['price']=='500plus') echo 'selected'; ?>>Above $500</option>
  </select>
  
  <button type="submit" class="px-4 py-2 ms-2 rounded bg-success text-white">Search</button>
</form>
    </div>
  </div>
</nav>
  </section>



<section class="one">
  <div class="two ">
    <div id="perfumeCarousel" class="carousel slide" data-bs-ride="carousel">

      <!-- Carousel Inner -->
      <div class="carousel-inner">

        <!-- Slide 1 -->
        <div class="carousel-item active ms-4">
          <div class="row d-flex align-items-center">
            <div class="col-md-6 text-center text-md-start">
              <p class="fw-bold display-3 text-center text-md-start">Perfumes</p>
              <p class="mt-3 fs-5">
                Perfume is more than a fragrance—it is an unspoken language that lingers in the air, telling stories of elegance, passion, and identity. Each drop is a delicate composition of nature’s most precious essences, where fresh citrus sparkles like morning sunlight, florals bloom with grace, and warm woods embrace like a comforting memory. Subtle spices weave intrigue, while whispers of musk and amber leave a lingering trail that captures hearts long after you’ve passed. It is not just a scent, but an experience—an invisible accessory that defines your mood, enhances your presence, and transforms every moment into something unforgettable.
              </p>
              <button class="btn fs-4  rounded-pill mt-3 px-5 py-2 text-white fw-bold" >Shop Now</button>
            </div>
            <div class="col-md-6">
              <img src="images/i7.png" class="d-block w-100" alt="Perfume 1">
            </div>
          </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item ms-4">
          <div class="row d-flex align-items-center">
            <div class="col-md-6 text-center text-md-start">
              <p class="fw-bold display-3 text-center text-md-start">Men's Perfumes</p>
              <p class="mt-3 fs-5">
                A man’s fragrance is more than just a scent—it is a statement of presence, character, and charm. Imagine the deep warmth of cedarwood and sandalwood blending seamlessly with earthy patchouli, leaving a trail of confidence in every step. Fresh aquatic notes mingle with zesty citrus and a hint of mint, evoking the crisp air of an ocean breeze. For evenings, a seductive touch of cardamom, cinnamon, and leather creates an aura of mystery and sophistication. From energetic citrus bursts for daytime adventures to smoky tobacco and rich oud for formal moments, every note is crafted to embody strength, elegance, and timeless masculinity.
              </p>
              <button class="btn fs-4  rounded-pill mt-3 px-5 py-2 text-white fw-bold ">Shop Now</button>
            </div>
            <div class="col-md-6">
              <img src="images/i2.png" class="d-block w-100" alt="Perfume 2">
            </div>
          </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item ms-4">
          <div class="row d-flex align-items-center">
            <div class="col-md-6 text-center text-md-start">
              <p class="fw-bold display-3 text-center text-md-start">Women's Perfumes</p>
              <p class="mt-3 fs-5">
                A woman’s perfume is a whisper of elegance, a memory wrapped in fragrance, and a reflection of her soul. A delicate bouquet of rose, jasmine, and peony blooms in harmony, leaving behind a romantic and graceful trail. For those with a playful spirit, sweet notes of ripe peaches, juicy berries, and creamy vanilla dance together in irresistible charm. On warmer days, crisp cucumber, green tea, and lily-of-the-valley create a refreshing and airy embrace, while exotic amber, sandalwood, and soft spices weave an intoxicating evening aura. From powdery iris and violet to warm musk, every drop tells a story of beauty, confidence, and allure.
              </p>
              <button class="btn fs-4 rounded-pill mt-3 px-5 py-2 text-white fw-bold ">Shop Now</button>
            </div>
            <div class="col-md-6">
              <img src="images/i3.png" class="d-block w-100" alt="Perfume 3">
            </div>
          </div>
        </div>

        <!-- Slide 4 -->
        <div class="carousel-item ms-4">
          <div class="row d-flex align-items-center">
            <div class="col-md-6 text-center text-md-start">
              <p class="fw-bold display-3 text-center text-md-start">Watches</p>
              <p class="mt-3 fs-5">
                A watch is more than a timekeeper—it is a reflection of personal style, craftsmanship, and precision. Every tick tells a story, blending functionality with elegance. From the glint of polished metal to the intricate movement hidden beneath its case, a watch embodies the art of engineering and design. Smooth, sweeping hands trace the hours over a dial that can be bold and modern or classic and understated. Whether adorned with fine leather straps, durable stainless steel, or contemporary mesh, it becomes a trusted companion for both everyday wear and special occasions. A well-crafted watch is not just worn—it is experienced, carrying with it moments, memories, and a timeless charm that never fades.
              </p>
              <button class="btn fs-4   rounded-pill mt-3 px-5 py-2 text-white fw-bold ">Shop Now</button>
            </div>
            <div class="col-md-6">
              <img src="images/w4.png" class="d-block w-100" alt="Watch 1">
            </div>
          </div>
        </div>

        <!-- Slide 5 -->
        <div class="carousel-item ms-4">
          <div class="row d-flex align-items-center">
            <div class="col-md-6 text-center text-md-start">
              <p class="fw-bold display-3 text-center text-md-start">Men's Watches</p>
              <p class="mt-3 fs-5">
                A men’s watch is a symbol of sophistication, strength, and precision—an accessory that speaks before words are ever exchanged. Crafted with meticulous attention to detail, it combines durability with timeless style. The sturdy stainless steel case, resilient sapphire crystal, and finely engineered movement ensure accuracy and reliability, while the bold dial and refined strap reflect a man’s personal taste. Whether it’s a classic leather band for a formal setting, a rugged chronograph for adventure, or a sleek metal bracelet for everyday wear, a men’s watch is more than a tool—it’s a statement of confidence, discipline, and enduring elegance that stands the test of time.
              </p>
              <button class="btn fs-4  rounded-pill mt-3 px-5 py-2 text-white fw-bold ">Shop Now</button>
            </div>
            <div class="col-md-6">
              <img src="images/w5.png" class="d-block w-100" alt="Men's Watch">
            </div>
          </div>
        </div>

        <!-- Slide 6 -->
        <div class="carousel-item ms-4">
          <div class="row d-flex align-items-center">
            <div class="col-md-6 text-center text-md-start">
              <p class="fw-bold display-3 text-center text-md-start">Women's Watches</p>
              <p class="mt-3 fs-5">
                A women’s watch is the perfect blend of elegance, grace, and functionality—an accessory that enhances every moment with timeless charm. Delicately crafted with precision, it reflects a balance between beauty and performance. The shimmering dial, adorned with subtle details, catches the light with every movement, while the refined strap—whether in soft leather, polished metal, or delicate mesh—adds a touch of sophistication to any attire. More than a timepiece, it is a piece of jewelry, a style statement, and a symbol of poise. From casual days to formal evenings, a women’s watch gracefully accompanies her through life’s moments, marking time with elegance that never fades.
              </p>
              <button class="btn fs-4 rounded-pill mt-3 px-5 py-2 text-white fw-bold">Shop Now</button>
            </div>
            <div class="col-md-6">
              <img src="images/w6.png" class="d-block w-100" alt="Women's Watch">
            </div>
          </div>
        </div>

      </div>

      <!-- Controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#perfumeCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#perfumeCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>

    </div>
  </div>
</section>


<!--watch section-->
<section class="Products" id="watch">
  <div class="row">
    <div class="col-12">
      <h1 class="text-center mt-3">Watches</h1>
    </div>

    <?php
    if ($resultWatch && mysqli_num_rows($resultWatch) > 0) {
        while ($row = mysqli_fetch_assoc($resultWatch)) {
            $title = htmlspecialchars($row['title'], ENT_QUOTES);
            $description = htmlspecialchars($row['description'], ENT_QUOTES);
            $price = htmlspecialchars($row['price'], ENT_QUOTES);
            $image = htmlspecialchars($row['image'], ENT_QUOTES);

            echo "
            <div class='col-lg-3 col-md-4 col-sm-6 mb-4'>
                <div class='card h-100'>
                    <img src='{$image}' class='card-img-top' alt='{$title}'>
                    <div class='card-body text-center'>
                        <h5 class='card-title'>{$title}</h5>
                        <p class='card-text'>{$description}</p>
                        <h6 class='text-success'>\${$price}</h6>
                        <form action='cart.php' method='post'>
                            <input type='hidden' name='id' value='{$row['p_id']}'>
                            <input type='hidden' name='name' value='{$title}'>
                            <input type='hidden' name='price' value='{$price}'>
                            <input type='hidden' name='image' value='{$image}'>
                            <input type='hidden' name='description' value='{$description}'>
                            <button type='submit' name='add_to_cart' class='btn btn-primary'>Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
            ";
        }
    } else {
        echo '<p class="text-center text-warning">No watches found.</p>';
    }
    ?>
  </div>
</section>



      <!-- Perfumes Section -->
   <section class="Products" id="perfume">
  <div class="row">
    <div class="col-12">
      <h1 class="text-center">Perfumes</h1>
    </div>

    <?php
    if ($resultPerfume && mysqli_num_rows($resultPerfume) > 0) {
        while ($row = mysqli_fetch_assoc($resultPerfume)) {
            $title = htmlspecialchars($row['title'], ENT_QUOTES);
            $description = htmlspecialchars($row['description'], ENT_QUOTES);
            $price = htmlspecialchars($row['price'], ENT_QUOTES);
            $image = htmlspecialchars($row['image'], ENT_QUOTES);

            echo "
            <div class='col-lg-3 col-md-4 col-sm-6 mb-4'>
                <div class='card h-100'>
                    <img src='{$image}' class='card-img-top' alt='{$title}'>
                    <div class='card-body text-center'>
                        <h5 class='card-title'>{$title}</h5>
                        <p class='card-text'>{$description}</p>
                        <h6 class='text-success'>\${$price}</h6>
                        <form action='cart.php' method='post'>
                            <input type='hidden' name='id' value='{$row['p_id']}'>
                            <input type='hidden' name='name' value='{$title}'>
                            <input type='hidden' name='price' value='{$price}'>
                            <input type='hidden' name='image' value='{$image}'>
                            <input type='hidden' name='description' value='{$description}'>
                            <button type='submit' name='add_to_cart' class='btn btn-primary'>Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
            ";
        }
    } else {
        echo '<p class="text-center text-warning">No perfumes found.</p>';
    }
    ?>
  </div>
</section>








<!--perfume section ends-->


<!-- ABOUT US SECTION -->
<!-- ABOUT US SECTION -->
<section class="about-us py-5" id="about">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="about-title">About <span>Time & Essence</span></h1>
            <p class="about-intro mx-auto">
                At <strong>Time & Essence</strong>, we create timeless elegance. Our exquisite <span>watches</span> and captivating <span>perfumes</span> are crafted for those who appreciate luxury, style, and individuality. Every product tells a story—yours.
            </p>
        </div>

        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="about-card">
                    <i class="ri-eye-line about-icon"></i>
                    <h3 class="about-heading">Our Vision</h3>
                    <p>To redefine luxury by offering sophisticated watches and enchanting fragrances that reflect elegance and individuality.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="about-card">
                    <i class="ri-target-line about-icon"></i>
                    <h3 class="about-heading">Our Mission</h3>
                    <p>To deliver premium-quality products that blend precise engineering with luxurious scents, enhancing your lifestyle and confidence.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="about-card">
                    <i class="ri-star-line about-icon"></i>
                    <h3 class="about-heading">Our Values</h3>
                    <p>Integrity, craftsmanship, and innovation guide us in creating products that stand the test of time and style.</p>
                </div>
            </div>
        </div>

        <div class="our-story mt-5 text-center">
            <h2>Our Story</h2>
            <p class="story-text">
                Time & Essence was born from a passion for elegance. We combine artistry with quality to create watches that mark your moments and perfumes that leave lasting impressions. Every item we craft is a celebration of style, sophistication, and personal expression.
            </p>
            <a href="#watch" class="btn btn-primary mt-3">Explore Products</a>
        </div>
    </div>
</section>




<!-- Contact Us Section -->
<section class="contact-us" id="contact">
  <h2 class="fw-bold  text-center">Contact Us</h2>
    <div class="container">
        <!-- Contact Form -->
        <div class="contact-form" >
            
          
            <!-- Contact Form -->
            <form action="" method="POST" class="mt-4">
                <div class="mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                </div>
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <textarea name="message" class="form-control" rows="4" placeholder="Message" required></textarea>
                </div>
                <button type="submit" name="send_message" class="btn w-100" style="background:#111; color:#fff;">SEND</button>
            </form>
        </div>

        <!-- Contact Info -->
        <div class="contact-info" >
            <h3 class="mb-4">Info</h3>
            <p><i class="ri-mail-line me-2"></i> Time&essence@gmail.com</p>
            <p><i class="ri-phone-line me-2"></i> +91 76984 70422</p>
            <p><i class="ri-map-pin-line me-2"></i> 14 Greenroad,London,UK .</p>
            <p><i class="ri-time-line me-2"></i> 09:00 - 9:00</p>
        </div>
    </div>
</section>



<!-- Footer Section -->
<footer class="text-center text-lg-start text-white" style="background: linear-gradient(135deg, #0b1a2a, #1e2a39ff);;">
    <div class="container p-4">
        <div class="row">
            <!-- Brand and Description -->
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="fw-bold"> <img src="images/logos.png" height="60px" width="60px" alt="">Time & Essence</h5>
                <p>
                    Timeless elegance, premium watches, and enchanting perfumes. Experience luxury with every detail.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                <h5 class="fw-bold mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="#Nav" class="text-white text-decoration-none">Home</a></li>
                    <li><a href="#about" class="text-white text-decoration-none">About</a></li>
                    <li><a href="#watch" class="text-white text-decoration-none">Watches</a></li>
                    <li><a href="#perfume" class="text-white text-decoration-none">Perfumes</a></li>
                    <li><a href="#contact" class="text-white text-decoration-none">Contact</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="fw-bold mb-3">Contact</h5>
                <p><i class="ri-mail-line me-2"></i>Time&essence@gmail.com</p>
                <p><i class="ri-phone-line me-2"></i> +91 76984 70422</p>
                <p><i class="ri-map-pin-line me-2"></i> 14 Greenroad,London,UK .</p>
            </div>

            <!-- Social Media -->
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="fw-bold mb-3">Follow Us</h5>
                <a href="#" class="text-white me-3 fs-4"><i class="ri-facebook-fill"></i></a>
                <a href="#" class="text-white me-3 fs-4"><i class="ri-instagram-fill"></i></a>
                <a href="#" class="text-white me-3 fs-4"><i class="ri-twitter-fill"></i></a>
                <a href="#" class="text-white fs-4"><i class="ri-youtube-fill"></i></a>
            </div>
        </div>
    </div>
    <div class="text-center p-3" style="background-color:#0b1a2a;">
        © <?php echo date("Y"); ?> Time & Essence | All Rights Reserved
    </div>
</footer>



<script>
  document.addEventListener("DOMContentLoaded", function () {
    const scrollTarget = "<?php echo $scrollTarget; ?>";
    console.log("Scroll Target:", scrollTarget);

    if (scrollTarget) {
        const tryScroll = () => {
            const el = document.getElementById(scrollTarget);
            if (el) {
                el.scrollIntoView({ behavior: "smooth", block: "start" });
            } else {
                setTimeout(tryScroll, 200); // Retry until element exists
            }
        };
        setTimeout(tryScroll, 300);
    }
});
</script>





</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- <script src="main.js"></script> -->
<script src="script.js"></script>
<!-- <script src="script1.js"></script> -->



</html>