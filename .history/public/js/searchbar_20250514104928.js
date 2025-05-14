/* // Mobile Menu Toggle
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const mobileMenu = document.getElementById('mobile-menu');
mobileMenuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
});
// Search Bar Toggle
const searchBtn = document.getElementById('search-btn');
const searchBar = document.getElementById('search-bar');
searchBtn.addEventListener('click', () => {
    searchBar.classList.toggle('hidden');
});
// Shopping Cart Functionality
const cartBtn = document.getElementById('cart-btn');
const closeCart = document.getElementById('close-cart');
const cartPanel = document.getElementById('cart-panel');
const cartOverlay = document.getElementById('cart-overlay');

const cartCount = document.getElementById('cart-count');
const cartItems = document.getElementById('cart-items');
const cartSubtotal = document.getElementById('cart-subtotal');
let cart = [];
cartBtn.addEventListener('click', () => {
    cartPanel.classList.remove('cart-closed');
    cartPanel.classList.add('cart-open');
    cartOverlay.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
});
closeCart.addEventListener('click', () => {
    cartPanel.classList.remove('cart-open');
    cartPanel.classList.add('cart-closed');
    cartOverlay.classList.add('hidden');
    document.body.style.overflow = 'auto';
});
cartOverlay.addEventListener('click', () => {
    cartPanel.classList.remove('cart-open');
    cartPanel.classList.add('cart-closed');
    cartOverlay.classList.add('hidden');
    document.body.style.overflow = 'auto';
});
// Add to Cart Functionality
const addToCartButtons = document.querySelectorAll('.add-to-cart');
addToCartButtons.forEach(button => {
    button.addEventListener('click', () => {
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const price = parseFloat(button.getAttribute('data-price'));
        const image = button.getAttribute('data-image');
        // Check if item already in cart
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id,
                name,
                price,
                image,

                quantity: 1
            });
        }
        updateCart();
        // Show cart panel
        cartPanel.classList.remove('cart-closed');
        cartPanel.classList.add('cart-open');
        cartOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });
});
function updateCart() {
    // Update cart count
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
    cartCount.textContent = totalItems;
    // Update cart items
    if (cart.length === 0) {
        cartItems.innerHTML = '<p class="text-gray-500 text-center py-8">Your cart is empty</p>';
        cartSubtotal.textContent = '$0.00';
    } else {
        cartItems.innerHTML = '';
        let subtotal = 0;
        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            const cartItemElement = document.createElement('div');
            cartItemElement.className = 'flex py-4 border-b';
            cartItemElement.innerHTML = `
/* faire un template,avec fetch  */
<div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center p-2">

        <img src="${item.image}" alt="${item.name}" class="max-h-full max-w-full">
</div>
<div class="ml-4 flex-grow">
<h4 class="font-medium">${item.name}</h4>
<p class="text-gray-600">$${item.price.toFixed(2)}</p>
<div class="flex items-center mt-2">
<button class="change-quantity px-2 border rounded"

data-id="${item.id}" data-change="-1">

<i class="fas fa-minus text-xs"></i>
</button>

<span class="mx-2">${item.quantity}</span>
<button class="change-quantity px-2 border rounded"

data-id="${item.id}" data-change="1">

<i class="fas fa-plus text-xs"></i>
</button>
</div>
</div>
<div class="flex flex-col items-end">
<span class="font-medium">$${itemTotal.toFixed(2)}</span>
<button class="remove-item text-red-500 mt-2" data-id="${item.id}">
<i class="fas fa-trash"></i>
</button>
</div>
`;
            cartItems.appendChild(cartItemElement);
        });
        cartSubtotal.textContent = `$${subtotal.toFixed(2)}`;
        // Add event listeners to new buttons
        document.querySelectorAll('.change-quantity').forEach(button => {
            button.addEventListener('click', (e) => {
                const id = button.getAttribute('data-id');
                const change = parseInt(button.getAttribute('data-change'));
                const item = cart.find(item => item.id === id);
                if (item) {
                    item.quantity += change;
                    if (item.quantity <= 0) {
                        cart = cart.filter(item => item.id !== id);
                    }
                    updateCart();
                }
            });
        });
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', (e) => {
                const id = button.getAttribute('data-id');
                cart = cart.filter(item => item.id !== id);
                updateCart();
            });
        });
    }
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: 'smooth'
            });
            // Close mobile menu if open
            mobileMenu.classList.add('hidden');
        }
    });
}); */