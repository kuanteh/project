<?php

/**
 * footer.php — 全站页脚
 * 首页 index.php 设 $skipMainClose = true（因为没用 header.php 的 <main>）
 */
if (empty($skipMainClose)):
?>
    </main>
<?php endif; ?>

<style>
    /* Footer */
    .site-footer {
        background: #0a0a0a;
        color: #ffffff;
        padding: 3rem 0 1.5rem;
        margin-top: 3rem;
    }

    .footer-brand {
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
    }

    .footer-text {
        color: #aaaaaa;
        font-size: 0.9rem;
    }

    .footer-heading {
        font-size: 0.8rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links a {
        color: #cccccc;
        text-decoration: none;
        font-size: 0.9rem;
        line-height: 2;
    }

    .footer-links a:hover {
        color: #ffffff;
    }

    .footer-bottom {
        border-top: 1px solid #333;
        margin-top: 2rem;
        padding-top: 1.5rem;
        text-align: center;
        color: #888;
        font-size: 0.8rem;
    }

    .alert-flash {
        max-width: 900px;
        margin: 1rem auto 0;
        padding: 0 1.5rem;
    }

    @media (max-width: 991px) {
        .product-detail {
            grid-template-columns: 1fr;
        }

        .catalog-grid,
        .related-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 575px) {

        .catalog-grid,
        .related-grid {
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }
    }
</style>

<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h3 class="footer-brand bungee-regular">SHARQO</h3>
                <p class="footer-text">Local streetwear, global attitude. Curated fits for the culture.</p>
            </div>
            <div class="col-md-4">
                <h4 class="footer-heading">Customer Care</h4>
                <ul class="footer-links">
                    <li><a href="./shipping-policy.php">Delivery &amp; Shipping</a></li>
                    <li><a href="./privacyPolicy.php">Privacy Policy</a></li>
                    <li><a href="./terms-of-use.php">Terms of Use</a></li>
                    <li><a href="./contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h4 class="footer-heading">About</h4>
                <ul class="footer-links">
                    <li><a href="./about.php">About SHARQO</a></li>
                    <li><a href="./catalog.php">Shop All</a></li>
                    <li><a href="https://www.facebook.com/ChongChengKulimKedah" target="_blank" rel="noopener">Facebook</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> SHARQO. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- 如果放了 nav bar 的dropdown 就不能打开了 , 因为会弄到import 两个 bootstap -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script> -->
