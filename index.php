<?php
$db = new PDO("mysql:host=localhost;dbname=b18_sharqo", "root", "");

/**
 * 只读取最新 4 件商品 → 首页 product section 固定一排 4 个
 * ORDER BY product_id DESC → 最新加入的排在最前面
 */
$productQuery = "SELECT product_id, product_name, brand, price, category, image
                 FROM products
                 ORDER BY product_id DESC
                 LIMIT 4";
$productStmt = $db->prepare($productQuery);
$productStmt->execute();
$products = $productStmt->fetchAll();

/**
 * 从数据库统计每个潮牌有多少件商品
 * 用在 Featured Brands section（参考 Double 7 首页）
 */
$brandQuery = "SELECT brand, COUNT(*) AS product_count
               FROM products
               GROUP BY brand
               ORDER BY product_count DESC";
$brandStmt = $db->prepare($brandQuery);
$brandStmt->execute();
$dbBrands = $brandStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHARQO</title>
    <!-- import CSS -->
    <link rel="stylesheet" href="./css/index.css" />
    <link rel="stylesheet" href="./css/googleFonts.css" />
    <!-- import Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <!-- import Bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <!-- import Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bitcount+Grid+Double+Ink:wght@100..900&family=Bungee&family=Emblema+One&family=Kaushan+Script&family=Kavoon&family=Knewave&family=Luckiest+Guy&family=Oranienbaum&family=Permanent+Marker&family=Racing+Sans+One&display=swap" rel="stylesheet">

</head>

<body>
    <section>
        <div class="marquee-section bungee-regular">
            <div class="marquee-container p-50">
                <div class="marquee-content">
                    <!-- span 可以让里面的内容变成一块，so 不是一个字一个字去marquee 是一块去marquee -->
                    <span> Limited Stock Available — Grab Yours Today! </span>
                    <span> Stay Sharp. Stay SHARQO </span>
                    <span> Local Streetwear, Global Attitude </span>
                    <span> More Than Fashion, It's A Lifestyle </span>

                    <!--repeat content ，为了让第二个span 内容紧跟着第一个span 内容，第一个span 走完会有一个空白，要补 -->
                    <span> Limited Stock Available — Grab Yours Today! </span>
                    <span> Stay Sharp. Stay SHARQO </span>
                    <span> Local Streetwear, Global Attitude </span>
                    <span> More Than Fashion, It's A Lifestyle </span>
                </div>
            </div>
        </div>
    </section>

    <div id="navbar">
        <nav class="navbar navbar-expand-lg ">
            <div class="container">
                <a class="navbar-brand bungee-regular" href="#">SHARQO</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav oranienbaum-regular">
                        <li class="nav-item dropdown">
                            <!-- 加上 data-bs-toggle="dropdown" ，点击他就就会show option -->
                            <!-- role:button 是给原本 <a> tag 不在是 link的功能 是button  -->
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                Shop
                            </a>
                            <!-- Option -->
                            <!-- dropdowm-menu 会先display none 以下的option,click 了才有反应 -->
                            <ul class="dropdown-menu border-dark">
                                <li><a class="dropdown-item" href="#all">All</a></li>
                                <li><a class="dropdown-item" href="#">Top</a></li>
                                <li><a class="dropdown-item" href="#bottom">Bottom</a></li>
                                <li><a class="dropdown-item" href="#accesories">Accesories</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#new">New</a>
                        </li>
                        <li class="nav-item dropdown">
                            <!-- 加上 data-bs-toggle="dropdown" ，点击他就就会show option -->
                            <!-- role:button 是给原本 <a> tag 不在是 link的功能 是button  -->
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                SHQR Coleection
                            </a>
                            <!-- Option -->
                            <!-- dropdowm-menu 会先display none 以下的option,click 了才有反应 -->
                            <ul class="dropdown-menu border-dark">
                                <li><a class="dropdown-item" href="#keynote">Keynote</a></li>
                                <li><a class="dropdown-item" href="#tntco">TNTCO</a></li>
                                <li><a class="dropdown-item" href="#againtsLab">Againts Lab</a></li>
                                <li><a class="dropdown-item" href="#StoneCo">Stone & Co</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#about">About</a>
                        </li>
                    </ul>
                    <div class="navbar-profile-zone">
                        <?php
                        // 在服务器端用 $_SESSION['user'] 来记录用户登录状态
                        // 如果你想在写前端时先测试效果，可以手动把下面这行改成 if (true) 或 if (false)
                        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
                            // 情况 A：已经有账户/已登录，前往个人主页
                            $profile_url = "./profile.php";
                        } else {
                            // 情况 B：没有账户/未登录，前往登录页面
                            $profile_url = "./login-form.php";
                        }
                        ?>

                        <a href="<?php echo $profile_url; ?>" class="profile-link" title="My Account">
                            <!-- bi-person-circle 是 Bootstrap 官方的高级圆圈人头图标 -->
                            <i class="bi bi-person-circle"></i>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <section>
        <div id="hero-section">
            <div class="hero-content">
                <h1 class="text-center bungee-regular">SHARQO OUTFIT</h1>
                <a href="https://www.facebook.com/ChongChengKulimKedah" class="edu-inbtn text-decoration-none text-center">
                    <button class="btn-hero">Shop Now</button>
                </a> <!-- <p class="text-center fs-3 racing-sans-one-regular">by Chan Ze Yu</p>
                <p class="text-center fs-6 fst-italic tex6t-secondary">Student in Forward College / Coding Beginner / Tech Explorer</p> -->
            </div>
        </div>
    </section>

    <!--  -----------   PRODUCT SECTION  ---------- -->
    <section id="productShow-section" class="home-section">
        <div class="section-container">

            <div class="section-heading">
                <h2 class="section-title">NEW ARRIVALS</h2>
            </div>

            <!-- $products 是一个 array , 如果$products 这个盒子装的商品数量 more than 0 就 run 下面的code -->
            <?php if (count($products) > 0): ?>

                <!-- product-grid-row：CSS 强制 4 列，只显示一排 -->
                <div class="product-grid-row">
                    <!-- foreach - 逐个循环(把 $products array的内容一个一个拿出来)
                        $products as $product - $products 是一个 array 里面装着select 出来的商品, $product 是 临时variable
                    -->
                    <?php foreach ($products as $product): ?>
                        <!-- 每一个商品是一个a tag，点击了就会跳转到product.php 这个页面，同时把product_id 传过去 -->
                        <!-- ./product.php?id= 是他可以跳转网页的同时把id 送过去 , 等于的后面是要拿哪一个 id -->
                        <!-- $product['product_id'] 是从 database 拉出来的当前的商品的 product id -->
                        <a
                            href="./product.php?id=<?php echo (int) $product['product_id']; ?>"
                            class="product-card">
                            <!-- 潮牌brand label -->
                            <span class="product-card-brand">
                                <?php echo htmlspecialchars($product['brand']); ?>
                            </span>

                            <!-- product图片 -->
                            <div class="product-card-image">
                                <img
                                    src="<?php echo htmlspecialchars($product['image']); ?>"
                                    alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                                    loading="lazy">
                                <!-- Hover 时出现的黑色filter 和 fake button (View Product) -->
                                <div class="product-card-overlay">
                                    <span class="product-card-cta">View Product</span>
                                </div>
                            </div>

                            <div class="product-card-info">
                                <p class="product-card-name">
                                    <?php echo htmlspecialchars($product['product_name']); ?>
                                </p>
                                <p class="product-card-price">
                                    RM <?php echo number_format((float) $product['price'], 2); ?>
                                </p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <!-- 如果没有product 出什么: -->
                <!-- <?php else: ?> 
                <div class="product-empty">
                    <p>No products yet.</p>
                    <p class="product-empty-hint">Add items in manage-product-add.php to see them here.</p>
                </div> -->
            <?php endif; ?>
        </div>
    </section>

    <!-- ---- Brand Marquee Section ------ -->
    <section>
        <div class="brand-marquee-section">
            <div class="brand-marquee-container">
                <div class="brand-marquee-content">

                    <span class="brand-logo-box"><img src="./image/logoImage/tntco(1).PNG" alt="tntco"></span>
                    <span class="brand-logo-box"><img src="./image/logoImage/stone(1).PNG" alt="stone"></span>
                    <span class="brand-logo-box"><img src="./image/logoImage/keynote(1).PNG" alt="keynote"></span>
                    <span class="brand-logo-box"><img src="./image/logoImage/against_lab(1).PNG" alt="against_lab"></span>

                    <span class="brand-logo-box"><img src="./image/logoImage/tntco(1).PNG" alt="tntco"></span>
                    <span class="brand-logo-box"><img src="./image/logoImage/stone(1).PNG" alt="stone"></span>
                    <span class="brand-logo-box"><img src="./image/logoImage/keynote(1).PNG" alt="keynote"></span>
                    <span class="brand-logo-box"><img src="./image/logoImage/against_lab(1).PNG" alt="against_lab"></span>

                    <span class="brand-logo-box"><img src="./image/logoImage/tntco(1).PNG" alt="tntco"></span>
                    <span class="brand-logo-box"><img src="./image/logoImage/stone(1).PNG" alt="stone"></span>
                    <span class="brand-logo-box"><img src="./image/logoImage/keynote(1).PNG" alt="keynote"></span>
                    <span class="brand-logo-box"><img src="./image/logoImage/against_lab(1).PNG" alt="against_lab"></span>

                    <span class="brand-logo-box"><img src="./image/logoImage/tntco(1).PNG" alt="tntco"></span>
                    <span class="brand-logo-box"><img src="./image/logoImage/stone(1).PNG" alt="stone"></span>
                    <span class="brand-logo-box"><img src="./image/logoImage/keynote(1).PNG" alt="keynote"></span>
                    <span class="brand-logo-box"><img src="./image/logoImage/against_lab(1).PNG" alt="against_lab"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- scroll 下去会有 navbar 会 transparent -->
    <script>
        (function() {
            var navContainer = document.querySelector("#navbar"); // 方案2 的最外层 div
            var navBar = document.querySelector("#navbar nav.navbar"); // 里面的 nav
            if (!navContainer || !navBar) return;

            var lastScrollY = window.scrollY; // 记录上一次的滚动位置
            var threshold = 48; // 变色的临界点

            function handleScroll() {
                var currentScrollY = window.scrollY;

                // 把当前的滚动距离传给 CSS 变量 --scroll-top
                // 这样 CSS 就能实时计算 top: 120px - currentScrollY，实现紧跟滚动的效果！
                document.documentElement.style.setProperty('--scroll-top', currentScrollY + 'px');

                // 1. 处理变色逻辑
                if (currentScrollY > threshold) {
                    navBar.classList.add("navbar-scrolled");
                } else {
                    navBar.classList.remove("navbar-scrolled");
                }

                // 2. 处理上滑显示 / 下滑隐藏逻辑
                // 只有当向下滚动超过 700px 时才启动隐藏机制，防止在网页最顶部时误切
                if (currentScrollY > 700) {
                    if (currentScrollY > lastScrollY) {
                        // 网页在向下滚动 -> 隐藏导航栏
                        navContainer.classList.add("nav-hidden");
                    } else if (currentScrollY < lastScrollY) {
                        // 网页在向上滚动 -> 瞬间显示导航栏
                        navContainer.classList.remove("nav-hidden");
                    }
                } else {
                    // 如果滚回了网页最顶部，必须确保导航栏显示
                    navContainer.classList.remove("nav-hidden");
                }

                // 更新上一次的滚动位置
                lastScrollY = currentScrollY;
            }

            // 告诉window (browser) 每次 scroll 的时候都 call updateNav 这个 function 来更新 navbar 的状态
            window.addEventListener("scroll", handleScroll);
        })();
    </script>

</body>

</html>