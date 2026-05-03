// Load cart
let cart = JSON.parse(localStorage.getItem("cart")) || [];

// Cart badge
const cartCount = document.getElementById("cart-count");
if(cartCount){
    cartCount.innerText = cart.length;
}

// Toast notification
function showToast(msg){
    const toast = document.createElement("div");
    toast.innerText = msg;
    toast.style = `
        position:fixed;
        bottom:20px;
        right:20px;
        background:black;
        color:white;
        padding:10px 15px;
        border-radius:6px;
        z-index:1000;
    `;
    document.body.appendChild(toast);

    setTimeout(()=> toast.remove(), 2000);
}


function viewProduct(id){
    window.location.href = "product.php?id=" + id;
}



// Add to cart
function addToCart(id) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    cart.push(id);

    localStorage.setItem("cart", JSON.stringify(cart));

    // Update badge instantly
    if(cartCount){
        cartCount.innerText = cart.length;
    }

    showToast("Added to cart 🛒");
}