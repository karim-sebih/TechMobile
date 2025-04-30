<?php
require __DIR__ . '/config/app.php';

$router = new App\Core\Router();
$content = $router->handleRequest();

// Déterminer la classe de la page en fonction du contrôleur
$pageClass = isset($_GET['c']) ? strtolower($_GET['c']) : 'home'; // Par défaut, c'est "home"
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>MVC Project</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/style.css">
    </head>
    <body>
        <header>
        <style>
/* Custom CSS for enhancements */
.hero-gradient {
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.product-card:hover {
transform: translateY(-5px);
box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0,
0.04);
}
.cart-panel {
transition: all 0.3s ease;
}
.cart-open {
right: 0;
}
.cart-closed {
right: -100%;
}
.brand-logo {
filter: grayscale(100%) brightness(0.5);
transition: all 0.3s ease;
}
.brand-logo:hover {
filter: grayscale(0%) brightness(1);
}
.phone-image {
transition: all 0.3s ease;
}
.phone-image:hover {
transform: scale(1.05);
}
</style>
</head>
<body class="bg-gray-50 font-sans">
<!-- Header/Navigation -->
<header class="bg-white shadow-sm sticky top-0 z-50">
<div class="container mx-auto px-4 py-3 flex justify-between items-center">
<div class="flex items-center space-x-2">

<i class="fas fa-mobile-alt text-2xl text-indigo-600"></i>
<h1 class="text-xl font-bold text-gray-800">TechMobile</h1>
</div>
<nav class="hidden md:flex space-x-8">
<a href="#" class="text-gray-700 hover:text-indigo-600 font-medium">Home</a>
<a href="#products" class="text-gray-700 hover:text-indigo-600
font-medium">Products</a>
<a href="#brands" class="text-gray-700 hover:text-indigo-600
font-medium">Brands</a>
<a href="#features" class="text-gray-700 hover:text-indigo-600
font-medium">Features</a>
<a href="#contact" class="text-gray-700 hover:text-indigo-600
font-medium">Contact</a>
</nav>
<div class="flex items-center space-x-4">
<button id="search-btn" class="text-gray-600 hover:text-indigo-600">
<i class="fas fa-search"></i>
</button>
<button id="cart-btn" class="relative text-gray-600 hover:text-indigo-600">
<i class="fas fa-shopping-cart text-xl"></i>
<span id="cart-count" class="absolute -top-2 -right-2 bg-indigo-600 text-white

text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
</button>
<button class="md:hidden text-gray-600" id="mobile-menu-btn">
<i class="fas fa-bars text-xl"></i>
</button>
</div>
</div>
<!-- Mobile Menu -->
<div id="mobile-menu" class="hidden md:hidden bg-white py-2 px-4 shadow-md">
<a href="#" class="block py-2 text-gray-700 hover:text-indigo-600">Home</a>
<a href="#products" class="block py-2 text-gray-700
hover:text-indigo-600">Products</a>
<a href="#brands" class="block py-2 text-gray-700
hover:text-indigo-600">Brands</a>
<a href="#features" class="block py-2 text-gray-700
hover:text-indigo-600">Features</a>
<a href="#contact" class="block py-2 text-gray-700
hover:text-indigo-600">Contact</a>
</div>
<!-- Search Bar -->
<div id="search-bar" class="hidden bg-white py-3 px-4 shadow-md">
<div class="container mx-auto flex">

<input type="text" placeholder="Search for phones, brands..." class="flex-grow
px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2
focus:ring-indigo-500">
<button class="bg-indigo-600 text-white px-4 py-2 rounded-r-lg
hover:bg-indigo-700">

<i class="fas fa-search"></i>
</button>
</div>
</div>
</header>
<!-- Shopping Cart Sidebar -->
<div id="cart-panel" class="cart-panel fixed top-0 h-full w-full md:w-96 bg-white shadow-xl
z-50 overflow-y-auto cart-closed">
<div class="p-4 border-b flex justify-between items-center">
<h3 class="text-lg font-semibold">Your Cart</h3>
<button id="close-cart" class="text-gray-500 hover:text-gray-700">
<i class="fas fa-times"></i>
</button>
</div>
<div id="cart-items" class="p-4">
<!-- Cart items will be added here dynamically -->
<p class="text-gray-500 text-center py-8">Your cart is empty</p>
</div>
<div class="p-4 border-t">
<div class="flex justify-between mb-4">
<span class="font-medium">Subtotal:</span>
<span id="cart-subtotal" class="font-medium">$0.00</span>
</div>
<button class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700
transition">
Proceed to Checkout
</button>
</div>
</div>
<div id="cart-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>
        </header>
    
        <main class="page-<?php echo htmlspecialchars($pageClass); ?>">
            <?php echo $content; ?>
        </main>

        <footer>
            <p>WhiteCat © 2025</p>
        </footer>
        <script src="./js/"></script>
    </body>
</html>