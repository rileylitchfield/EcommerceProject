<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load environment variables
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD'); // Required environment variable

if (!$pass) {
    die('Database password environment variable (DB_PASSWORD) is not set');
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query products from database
    try {
        $stmt = $pdo->query('SELECT id, name, price FROM products');
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die('Error retrieving products: ' . $e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern E-commerce Store</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <style>
        .product-card {
            transition: transform 0.2s;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .cart-badge {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <!-- Shopping cart badge -->
    <div class="cart-badge">
        <button class="btn btn-primary position-relative" onclick="viewCart()">
            🛒 Cart
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count">
                0
            </span>
        </button>
    </div>

    <div class="container py-5">
        <header class="text-center mb-5">
            <h1 class="display-4 mb-3">Welcome to Our Store</h1>
            <p class="lead text-muted">Browse our carefully curated collection of products</p>
        </header>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($products as $product): ?>
            <div class="col">
                <div class="card product-card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text">
                            <strong class="text-primary">$<?= htmlspecialchars($product['price']) ?></strong>
                        </p>
                        <button class="btn btn-outline-primary" 
                                onclick="addToCart(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>', <?= $product['price'] ?>)">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Shopping Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="cartItems"></div>
                    <div class="text-end mt-3">
                        <strong>Total: $<span id="cartTotal">0.00</span></strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="checkout()">Checkout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let cart = [];
        const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));

        function addToCart(id, name, price) {
            const existingItem = cart.find(item => item.id === id);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({ id, name, price, quantity: 1 });
            }
            
            updateCartBadge();
            showToast(`Added ${name} to cart!`);
        }

        function updateCartBadge() {
            const total = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.querySelector('.cart-count').textContent = total;
        }

        function viewCart() {
            const cartItems = document.getElementById('cartItems');
            cartItems.innerHTML = '';
            
            if (cart.length === 0) {
                cartItems.innerHTML = '<p class="text-center">Your cart is empty</p>';
                document.getElementById('cartTotal').textContent = '0.00';
            } else {
                let total = 0;
                cart.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    total += itemTotal;
                    
                    cartItems.innerHTML += `
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-0">${item.name}</h6>
                                <small class="text-muted">$${item.price} x ${item.quantity}</small>
                            </div>
                            <div>
                                <span class="me-2">$${itemTotal.toFixed(2)}</span>
                                <button class="btn btn-sm btn-danger" onclick="removeFromCart(${item.id})">×</button>
                            </div>
                        </div>
                    `;
                });
                
                document.getElementById('cartTotal').textContent = total.toFixed(2);
            }
            
            cartModal.show();
        }

        function removeFromCart(id) {
            cart = cart.filter(item => item.id !== id);
            updateCartBadge();
            viewCart();
        }

        function checkout() {
            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }
            
            // Here you would typically integrate with a payment processor
            alert('Thank you for your purchase! This is where payment processing would occur.');
            cart = [];
            updateCartBadge();
            cartModal.hide();
        }

        function showToast(message) {
            // You could add a nice toast notification here
            // For now, we'll use a simple alert
            // alert(message);
        }
    </script>
</body>
</html>

<?php
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
