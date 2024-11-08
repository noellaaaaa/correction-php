<?php
session_start();
$baseUri = '/PHP_POLES/01-Cours/01-Cours/08_Site_boutique';

// Fonction pour gérer l'inscription
function handleRegister() {
    global $pdo, $baseUri;
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (empty($username)) {
            $errors[] = "Le nom d'utilisateur est requis.";
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Une adresse e-mail valide est requise.";
        }
        if (empty($password) || strlen($password) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
        }

        if (empty($errors)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            try {
                $query = "INSERT INTO admins (nom_utilisateur, mot_de_passe,email) VALUES (:nom_utilisateur,  :mot_de_passe,:email)";
                $result = $pdo->prepare($query);
                $result->bindParam(':nom_utilisateur', $username);
                $result->bindParam(':email', $email);
                $result->bindParam(':mot_de_passe', $hashedPassword);
                if ($result->execute()) {
                    header("Location: " . $baseUri . "/admin/login");
                    exit();
                }
            } catch (PDOException $e) {
                $errors[] = "Erreur lors de l'inscription : " . $e->getMessage();
            }
        }
    }
    include __DIR__ . '/../views/admin/register.php';
}

// Fonction pour gérer la connexion
function handleLogin() {
    global $pdo, $baseUri;
    $errors = [];
    if (isset($_POST['login']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (empty($email)) {
            $errors[] = "L'email est requis.";
        }
        if (empty($password)) {
            $errors[] = "Le mot de passe est requis.";
        }

        try {
            $query = "SELECT * FROM admins WHERE email = :email";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['mot_de_passe'])) {
                $_SESSION['admin'] = $admin['nom_utilisateur'];
                header('Location: ' . $baseUri . '/admin/dashboard');
                exit();
            } else {
                $errors[] = "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la connexion : " . $e->getMessage();
        }
    }
    include __DIR__ . '/../views/admin/login.php';
}

// Fonction pour afficher le tableau de bord
function dashboard() {
    global $pdo, $baseUri;
    if (!isset($_SESSION['admin'])) {
        header("Location: " . $baseUri . "/admin/login");
        exit();
    }

    try {
        $query = "SELECT * FROM products";
        $stmt = $pdo->query($query);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $errors[] = "Erreur lors de la récupération des produits: " . $e->getMessage();
    }

   // Inclure le fichier dashboard dans le dossier views
   include __DIR__ . '/../views/admin/dashboard.php';
}

// Fonction pour récupérer les commandes
function getOrders() {
   // Requête SQL pour récupérer toutes les commandes
   global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT orders.id AS order_id, orders.client_name, orders.client_email, orders.total_price, orders.quantity, products.nom AS product_name 
            FROM orders
            JOIN products ON orders.product_id = products.id
            ORDER BY orders.id, products.nom");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des commandes : " . $e->getMessage();
        return [];
    }
}

// Fonction pour afficher les commandes
function viewOrders() {
   // Appel de la fonction getOrders
    // Inclure le fichier orders dans le dossier views
    // global $baseUri;
    $orders = getOrders();
    include __DIR__ . '/../views/admin/orders.php'; // Nouvelle vue pour les commandes
}

// Gestion de la déconnexion
function logout() {
//   // Détruire la session et rediriger vers la page de connexion
global $baseUri;
if (isset($_SESSION['admin'])) {
    unset($_SESSION['admin']);
    session_destroy();
}
header("Location: " . $baseUri);
exit();

}


?>