<?php require_once 'header.php'; ?>

    <h1>Products</h1>
    Page: <?= $page ?>
    <?php if(isset($products)): ?>
    <div class="products">
        <?php foreach($products as $product): ?>
            <div class="single">
                <img src="<?= $product['imageUrl'] ?>" alt="">
                <a href=""><?= $product['title'] ?></a>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

<?php require_once 'footer.php'; ?>