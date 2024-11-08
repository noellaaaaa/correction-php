<?php
    include_once __DIR__ . '/../database/db.php';

function getAllProducts() {
  // requetes sql pour chopper tous les produits
  global $pdo;
    try {
        $query = "SELECT * FROM products";
        $result = $pdo->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des produits: " . $e->getMessage();
        return null;
    }
}

function getProductById($id) {
    // requête SQL pour récupérer un produit par son ID
    global $pdo;
    try {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération du produit: " . $e->getMessage();
        return null;
    }
}

function home() {
  // appel de la fonction getAllProducts
  // inclure le fichier index dans le dossier views
    $products = getAllProducts();
    include __DIR__ . '/../views/index.php';
}

function viewProduct() {
  // appel de la fonction getProductById
  // inclure le fichier product dans le dossier views
  if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    include __DIR__ . '/../views/product.php'; // Afficher les détails du produit
}
//     var_dump($id);
//     $product = getProductById($id);
    
//     if (!$product) {
//         include __DIR__ . '/../views/404.php'; // Produit non trouvé
//     } else {
        // include __DIR__ . '/../views/product.php'; // Afficher les détails du produit
//     }
// } else {
//     include __DIR__ . '/../views/404.php'; // ID non fourni
// }
}
?>