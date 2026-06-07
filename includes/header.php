<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>FitZone Fitness</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="<?=BASE_URL?>assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Barlow:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<header class="site-header">
  <div class="container header-inner">
     <h1 class="logo">
      <a href="<?=BASE_URL?>index.php" class="logo-link">FitZone Fitness</a>
    </h1>
    <div style="display:flex; padding: 0px;">
      <div style="display:flex;flex-direction: column;align-items: center;">
        <nav class="nav">
          <a href="<?=BASE_URL?>index.php">Home</a>
          <a href="<?=BASE_URL?>pages/classes.php">Classes</a>
          <a href="<?=BASE_URL?>pages/trainers.php">Trainers</a>
          <a href="<?=BASE_URL?>pages/memberships.php">Memberships</a>
          <a href="<?=BASE_URL?>pages/blog.php">Blog</a>
          <a href="<?=BASE_URL?>pages/gallery.php">Gallery</a>
          <a href="<?=BASE_URL?>pages/contact.php">Contact</a>
          
          <?php if (!empty($_SESSION['user'])): ?>
            <!-- Show these when user is logged in -->
            <a href="<?=BASE_URL?>dashboards/<?=htmlspecialchars($_SESSION['user']['role'])?>.php">Dashboard</a>
            <a class="btn" href="<?=BASE_URL?>auth/logout.php">Logout</a>
          <?php else: ?>
            <!-- Show these when user is not logged in -->
            <a href="<?=BASE_URL?>auth/login.php">Login</a>
            <a class="btn" href="<?=BASE_URL?>auth/register.php">Register</a>
          <?php endif; ?>
        </nav>
        
        <form action="<?=BASE_URL?>pages/search.php" method="get" class="search-form" style="align-items: baseline;position: absolute;top: 130px;right: 15px;">
          <input type="text" name="q" placeholder="Search classes, trainers, blog..." required>
          <button type="submit"><i class="fas fa-search"></i></button>
        </form>
      </div>
    </div>
  </div>
</header>
<main class="container main">