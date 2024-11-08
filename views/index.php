<?php include 'inc/header.php'; ?>

<h1>Bienvenue dans notre boutique</h1>
<div class="products">
    <?php if (!empty($products)): ?> <!-- Vérifie si des produits sont disponibles -->
        <?php foreach ($products as $product): ?>
            <div class="product">
                <h2><?php echo htmlspecialchars($product['nom']); ?></h2>
                <img src="<?php echo $baseUri . "/public/img/" . htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" />
                <p>Prix: <?php echo htmlspecialchars($product['prix']); ?> €</p>
                <a href="<?php echo $baseUri; ?>/product?id=<?php echo htmlspecialchars($product['id']); ?>">Voir détails</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun produit à afficher pour le moment.</p> <!-- Message si aucun produit n'est disponible -->
    <?php endif; ?>
</div>

<?php include 'inc/footer.php'; ?>
