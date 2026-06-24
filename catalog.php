<?php

/**
 * catalog.php — show product （all/ categories / brand)
 */
require_once __DIR__ . '/dry/init.php';
/**
 * trim 是可以delete 掉 开头和结尾lebih 的东西
 * 比如 ： 空格 ， /t ， /n
 * example：catalog.php?category= top （top 前面有空格）
 * 不用trim ： 拿到的 value 是 "top" so database 会找不到因为这个有空格
 * 用 trim ： 拿到的value 会自动变成 "top"
 */
/**
 * ??''
 * 是如果user 直接从 url 写...catalog.php （后面没有什么东西）就给他一个value 空空
 */
/**
 * ($_GET['category']??'');
 * - 他会get url 的 categotery 后面的 catalog.php?brand=tntco 
 */

$category = trim($_GET['category'] ?? '');
$brand = trim($_GET['brand'] ?? '');

$sql = 'SELECT product_id, product_name, brand, price, category, image FROM products WHERE 1=1';
// 先给一个 variable 是空的 array先
// 为了给后面装东西
// 比如：
// $params[':category'] = $category;
$params = [];

// 如果 $category 不等于空（url 有category） 就run 下面的code

if ($category !== '') {
    $sql .= ' AND category = :category';
    $params[':category'] = $category;
}
if ($brand !== '') {
    $sql .= ' AND brand = :brand';
    $params[':brand'] = $brand;
}

//  .= 是php 的连接拼接符号
$sql .= ' ORDER BY product_id DESC';

$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// $videoSrc = null; // defaut 不显示 video

// switch ($brand) {
//     case 'tntco':
//         $videoSrc = './video/tntco.mp4';
//         break;
//     case 'stoneCo': // 对应你 header 里的 brand=stoneCo
//         $videoSrc = './image/stoneAds.mp4';
//         break;
//     case 'keynote':
//         $videoSrc = './video/keynote.mp4';
//         break;
//     case 'AgainstLab': // 对应你 header 里的 brand=AgainstLab
//         $videoSrc = './video/againstlab.mp4';
//         break;
//     default:
//         $videoSrc = null; // 如果看的是 All 或者没有选品牌，就不显示视频
//         break;
// }

$pageTitle = $category !== '' ? 'Shop ' . ucfirst($category) : 'Shop All';
require __DIR__ . '/dry/header.php';
?>

<div class="catalog-wrap">
    <h1 class="mb-2" style="font-family:Bungee,sans-serif; letter-spacing:.08em;">
        <!-- syarat ? true : false -->
        SHOP <?php echo $category !== '' ? strtoupper($category) : 'ALL'; ?>
    </h1>
    <p class="text-muted mb-4 small text-uppercase">
        <?php echo count($products); ?> products
        <?php if ($category): ?>
            <!-- 在url 加 dot -->
             · 
        <?php echo ($category); ?>
        <?php endif; ?>
    </p>

    <?php if (count($products) > 0): ?>
        <div class="catalog-grid">
            <?php foreach ($products as $p): ?>
                <a href="./product.php?id=<?php echo (int) $p['product_id']; ?>" class="catalog-card">
                    <p class="catalog-card-brand"><?php echo ($p['brand']); ?></p>
                    <div class="catalog-card-image">
                        <img src="<?php echo ($p['image']); ?>" alt="<?php echo e($p['product_name']); ?>" loading="lazy">
                    </div>
                    <p class="small fw-semibold mb-1"><?php echo ($p['product_name']); ?></p>
                    <p class="small text-muted mb-0"><?php echo formatPrice($p['price']); ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">No products found.</p>
    <?php endif; ?>

    <!-- ?php if ($videoSrc): ?>
        <div class="brand-video-container mb-4 text-center" style="width: 100%; max-width: 1200px; margin: 0 auto; overflow: hidden; border-radius: 8px;">
             HTML5 原生播放器：自动播放(muted controls)、静音、循环、全宽 
            <video src="<" style="width: 100%; max-height: 450px; object-fit: cover;" autoplay muted loop controls playsinline>
                Your browser does not support the video tag.
            </video>
        </div>
    ?php endif; ?> -->
</div>

<?php require __DIR__ . '/dry/footer.php'; ?>