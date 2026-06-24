<?php

/**
 * checkout.php — payment page
 */
require_once __DIR__ . '/dry/init.php';
requireLogin();

$shippingFee = 10.00;
$error = null;
// 从session 拿到 success 放进 $success
$success = flashGet('success');

// ── 处理下单 POST ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    // 从 url get id 
    $productId = (int) ($_POST['product_id'] ?? 0);
    $sizeId = (int) ($_POST['size_id'] ?? 0);
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));
    $deliveryAddress = trim($_POST['delivery_address'] ?? '');
    $contactPhone = trim($_POST['contact_phone'] ?? '');
    $paymentMethod = trim($_POST['payment_method'] ?? '');

    if ($deliveryAddress === '' || $contactPhone === '') {
        $error = 'Delivery address and contact phone are required.';
    } elseif (!in_array($paymentMethod, ['Online Banking', "Touch 'n Go"], true)) {
        $error = 'Please select a payment method.';
    } else {
        // verify product & stock
        $pStmt = $db->prepare('SELECT * FROM products WHERE product_id = :id');
        $pStmt->execute([':id' => $productId]);
        $product = $pStmt->fetch();

        $sStmt = $db->prepare('SELECT * FROM product_sizes WHERE size_id = :sid AND product_id = :pid');
        $sStmt->execute([':sid' => $sizeId, ':pid' => $productId]);
        $sizeRow = $sStmt->fetch();

        if (!$product || !$sizeRow) {
            $error = 'Invalid product or size.';
        } elseif ($sizeRow['stock'] < $quantity) {
            $error = 'Not enough stock for selected size.';
        } else {
            // 给product 的price 乘 quantity （小数点）
            $subtotal = (float) $product['price'] * $quantity;
            $totalPrice = $subtotal + $shippingFee;
            $userId = (int) $_SESSION['user']['user_id'];

            try {
                $db->beginTransaction();

                $orderStmt = $db->prepare('INSERT INTO orders (user_id, purchase_date, total_price, status, delivery_address, contact_phone)
                    VALUES (:uid, NOW(), :total, :status, :addr, :phone)');
                $orderStmt->execute([
                    ':uid' => $userId,
                    ':total' => $totalPrice,
                    ':status' => 'Pending',
                    ':addr' => $deliveryAddress,
                    ':phone' => $contactPhone,
                ]);
                $orderId = (int) $db->lastInsertId();

                $opStmt = $db->prepare('INSERT INTO order_products (order_id, product_id, quantity, size_id)
                    VALUES (:oid, :pid, :qty, :sid)');
                $opStmt->execute([
                    // 把刚刚拿到的新的order id 
                    ':oid' => $orderId,
                    ':pid' => $productId,
                    ':qty' => $quantity,
                    ':sid' => $sizeId,
                ]);

                // Update
                $stockStmt = $db->prepare('UPDATE product_sizes SET stock = stock - :qty WHERE size_id = :sid AND stock >= :qty');
                $stockStmt->execute([':qty' => $quantity, ':sid' => $sizeId]);

                if ($stockStmt->rowCount() === 0) {
                    throw new Exception('Stock update failed.');
                }

                $db->commit();
                flashSet('success', 'Order #' . $orderId . ' placed successfully!');
                header('Location: ./profile.php');
                exit;
            } catch (Exception $e) {
                // 如果出事情 repeat(rollback)
                $db->rollBack();
                $error = 'Order failed. Please try again.';
            }
        }
    }
}

// 从 product.php send 来data
$productId = (int) ($_POST['product_id'] ?? $_GET['product_id'] ?? 0);
$sizeId = (int) ($_POST['size_id'] ?? $_GET['size_id'] ?? 0);
$quantity = max(1, (int) ($_POST['quantity'] ?? $_GET['quantity'] ?? 1));

// if user no pick the size
if ($productId <= 0 || $sizeId <= 0) {
    // flashset 在error set 一个 tips “Pls....”
    flashSet('error', 'Please select a product and size first.');
    header('Location: ./catalog.php');
    exit;
}

// sedia bayar product的 detail
$pStmt = $db->prepare('SELECT * FROM products WHERE product_id = :id');
$pStmt->execute([':id' => $productId]);
$product = $pStmt->fetch();

// sedia size
$sStmt = $db->prepare('SELECT ps.* FROM product_sizes ps WHERE ps.size_id = :sid AND ps.product_id = :pid');
$sStmt->execute([':sid' => $sizeId, ':pid' => $productId]);
$sizeRow = $sStmt->fetch();

if (!$product || !$sizeRow) {
    header('Location: ./catalog.php');
    exit;
}

$subtotal = (float) $product['price'] * $quantity;
$totalPrice = $subtotal + $shippingFee;
// 拿buyer 的detail ，set 成 user variable（因为会要写buyer 的detail）
$user = currentUser();

$pageTitle = 'Checkout';
require __DIR__ . '/dry/header.php';
?>

<div class="checkout-wrap">
    <h1 class="mb-4" style="font-family:Bungee,sans-serif; letter-spacing:.08em;">CHECKOUT</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo e($error); ?></div>
    <?php endif; ?>

    <!-- Order Summary -->
    <h2 class="h6 text-uppercase fw-bold mb-3">Order Summary</h2>
    <div class="checkout-summary">
        <img src="<?php echo e($product['image']); ?>" alt="">
        <div>
            <p class="fw-semibold mb-1"><?php echo e($product['product_name']); ?></p>
            <p class="small text-muted mb-1"><?php echo e($product['brand']); ?> · Size <?php echo e($sizeRow['size']); ?> · Qty <?php echo $quantity; ?></p>
            <p class="mb-0"><?php echo formatPrice($product['price']); ?> each</p>
        </div>
    </div>

    <div class="mb-4">
        <div class="checkout-total-row"><span>Subtotal</span><span><?php echo formatPrice($subtotal); ?></span></div>
        <div class="checkout-total-row"><span>Shipping Fee</span><span><?php echo formatPrice($shippingFee); ?></span></div>
        <div class="checkout-total-row grand"><span>Total</span><span><?php echo formatPrice($totalPrice); ?></span></div>
    </div>

    <form method="POST">
        <input type="hidden" name="confirm_order" value="1">
        <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
        <input type="hidden" name="size_id" value="<?php echo $sizeId; ?>">
        <input type="hidden" name="quantity" value="<?php echo $quantity; ?>">

        <h2 class="h6 text-uppercase fw-bold mb-3">Contact &amp; Delivery</h2>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" value="<?php echo e($user['email']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Delivery Address *</label>
            <textarea name="delivery_address" class="form-control" rows="3" required><?php echo e($user['address'] ?? ''); ?></textarea>
        </div>
        <div class="mb-4">
            <label class="form-label">Contact Phone *</label>
            <input type="text" name="contact_phone" class="form-control" value="<?php echo e($user['phone'] ?? ''); ?>" required>
        </div>

        <h2 class="h6 text-uppercase fw-bold mb-3">Payment Method</h2>
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="pay1" value="Online Banking" required>
                <label class="form-check-label" for="pay1">Online Banking</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="pay2" value="Touch 'n Go">
                <label class="form-check-label" for="pay2">Touch 'n Go</label>
            </div>
        </div>

        <button type="submit" class="btn btn-dark w-100 py-3 text-uppercase fw-bold">Place Order</button>
    </form>
</div>

<?php require __DIR__ . '/dry/footer.php'; ?>