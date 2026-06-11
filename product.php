<?php
// session_start()
/**
 * product.php — 商品详情页
 */
$db = new PDO("mysql:host=localhost;dbname=b18_sharqo", "root", "");

require_once __DIR__ . '/dry/init.php';


// 如果自己在url 后面加 id多少多少 , 如果不是的话就 踢回index.php
$productId = (int) ($_GET['id'] ?? 0);
if ($productId <= 0) {
    header('Location: ./index.php');
    exit;
}

$stmt = $db->prepare('SELECT * FROM products WHERE product_id = :id');
$stmt->execute([':id' => $productId]);
$product = $stmt->fetch();

// 如果不是 product 就踢去 Index.php
if (!$product) {
    header('Location: ./index.php');
    exit;
}

$sizes = getProductSizes($db, $productId);
$totalStock = getProductTotalStock($db, $productId);

// You might also like — 同 category，排除自己
$relatedStmt = $db->prepare('SELECT product_id, product_name, brand, price, image
    FROM products WHERE category = :cat AND product_id != :id ORDER BY RAND() LIMIT 4');
$relatedStmt->execute([':cat' => $product['category'], ':id' => $productId]);
$related = $relatedStmt->fetchAll();

$pageTitle = $product['product_name'];
// require __DIR__ . '/includes/header.php';
?>

<div class="product-detail">
    <!-- 左栏：商品信息 -->
    <div>
        <a href="./catalog.php" class="product-detail-back"><i class="bi bi-arrow-left"></i> Back</a>
        <p class="small text-uppercase text-muted mb-2"><?php echo e($product['brand']); ?></p>
        <h1 class="product-detail-title"><?php echo e($product['product_name']); ?></h1>
        <div class="product-detail-meta">
            <p><strong>Category:</strong> <?php echo e($product['category']); ?></p>
            <p><strong>Brand:</strong> <?php echo e($product['brand']); ?></p>
            <p><strong>Total Stock:</strong> <?php echo $totalStock; ?> pcs</p>
        </div>

        <?php if (!empty($product['description'])): ?>
            <div class="mt-3 small text-muted">
                <?php echo nl2br(e($product['description'])); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- 中栏：商品图片 -->
    <div class="product-detail-image">
        <img src="<?php echo e($product['image']); ?>" alt="<?php echo e($product['product_name']); ?>">
    </div>

    <!-- 右栏：购买操作 -->
    <div>
        <p class="product-detail-price"><?php echo formatPrice($product['price']); ?></p>

        <form method="POST" action="./checkout.php" id="checkoutForm">
            <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
            <input type="hidden" name="size_id" id="sizeIdInput" value="">

            <p class="size-label">Size</p>
            <div class="size-buttons" id="sizeButtons">
                <?php foreach ($sizes as $s): ?>
                    <button
                        type="button"
                        class="size-btn"
                        data-size-id="<?php echo (int) $s['size_id']; ?>"
                        data-stock="<?php echo (int) $s['stock']; ?>"
                        <?php echo $s['stock'] <= 0 ? 'disabled' : ''; ?>><?php echo e($s['size']); ?></button>
                <?php endforeach; ?>
            </div>

            <p class="stock-info" id="stockInfo">Select a size to see stock.</p>

            <label class="form-label size-label">Quantity</label>
            <input type="number" name="quantity" id="qtyInput" class="form-control qty-input" value="1" min="1" max="1">

            <button type="submit" class="btn-checkout" id="checkoutBtn" disabled>
                <i class="bi bi-bag"></i> Check Out
            </button>
        </form>

        <div class="accordion-section">
            <details>
                <summary>Details</summary>
                <div class="detail-content">
                    <?php echo !empty($product['description']) ? nl2br(e($product['description'])) : 'No description available.'; ?>
                </div>
            </details>
            <details>
                <summary>Shipping</summary>
                <div class="detail-content">
                    Standard delivery within Malaysia. Shipping fee RM 10.00 per order.
                    <a href="./shipping-policy.php">Read shipping policy</a>.
                </div>
            </details>
            <details>
                <summary>Washing Instructions</summary>
                <div class="detail-content">
                    <?php echo !empty($product['washing_instruction']) ? nl2br(e($product['washing_instruction'])) : 'Machine wash cold. Do not bleach. Tumble dry low.'; ?>
                </div>
            </details>
            <details>
                <summary>Size Guide</summary>
                <div class="detail-content">
                    <?php if (!empty($product['size_guide'])): ?>
                        <?php echo $product['size_guide']; ?>
                    <?php else: ?>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Size</th>
                                    <th>Chest (cm)</th>
                                    <th>Length (cm)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>S</td>
                                    <td>96-100</td>
                                    <td>68</td>
                                </tr>
                                <tr>
                                    <td>M</td>
                                    <td>100-104</td>
                                    <td>70</td>
                                </tr>
                                <tr>
                                    <td>L</td>
                                    <td>104-108</td>
                                    <td>72</td>
                                </tr>
                                <tr>
                                    <td>XL</td>
                                    <td>108-112</td>
                                    <td>74</td>
                                </tr>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </details>
        </div>
    </div>
</div>

<?php if (count($related) > 0): ?>
    <section class="related-section">
        <h2 class="mb-3" style="font-family:Bungee,sans-serif; font-size:1rem; letter-spacing:.1em;">YOU MIGHT ALSO LIKE</h2>
        <div class="related-grid">
            <?php foreach ($related as $r): ?>
                <a href="./product.php?id=<?php echo (int) $r['product_id']; ?>" class="related-card">
                    <img src="<?php echo e($r['image']); ?>" alt="<?php echo e($r['product_name']); ?>" loading="lazy">
                    <p class="small mb-0"><?php echo e($r['product_name']); ?></p>
                    <p class="small text-muted"><?php echo formatPrice($r['price']); ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<script>
    (function() {
        var sizeBtns = document.querySelectorAll('.size-btn:not(:disabled)');
        var sizeInput = document.getElementById('sizeIdInput');
        var stockInfo = document.getElementById('stockInfo');
        var qtyInput = document.getElementById('qtyInput');
        var checkoutBtn = document.getElementById('checkoutBtn');

        sizeBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                sizeBtns.forEach(function(b) {
                    b.classList.remove('active');
                });
                btn.classList.add('active');
                var stock = parseInt(btn.dataset.stock, 10);
                sizeInput.value = btn.dataset.sizeId;
                stockInfo.textContent = stock + ' in stock';
                qtyInput.max = stock;
                if (parseInt(qtyInput.value, 10) > stock) qtyInput.value = stock;
                checkoutBtn.disabled = false;
            });
        });
    })();
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>