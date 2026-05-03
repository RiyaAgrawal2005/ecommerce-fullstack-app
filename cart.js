// let cart = JSON.parse(localStorage.getItem("cart")) || [];

// const cartItemsDiv = document.getElementById("cart-items");
// const totalDiv = document.getElementById("total");
// const checkoutBtn = document.getElementById("checkoutBtn");
// let total = 0;

// // Convert cart into quantity object
// let cartMap = {};

// cart.forEach(id => {
//     cartMap[id] = (cartMap[id] || 0) + 1;
// });

// function displayCart() {
//     cartItemsDiv.innerHTML = "";
//     total = 0;

//    if(Object.keys(cartMap).length === 0){
//     cartItemsDiv.innerHTML = "<p class='empty'>🛒 Your cart is empty</p>";
//     totalDiv.innerText = "";

//     // ❌ Disable button
//     checkoutBtn.disabled = true;
//     checkoutBtn.style.background = "gray";
//     checkoutBtn.style.cursor = "not-allowed";

//     return;
// }

//     Object.keys(cartMap).forEach(id => {
//         const product = products.find(p => p.id == id);
//         let qty = cartMap[id];

//         total += product.price * qty;

//         cartItemsDiv.innerHTML += `
//             <div class="card">
//                 <img src="${product.image}">
//                 <h3>${product.name}</h3>
//                 <p>₹${product.price}</p>

//                 <div>
//                     <button class="qty-btn" onclick="decrease(${id})">-</button>
//                     <span>${qty}</span>
//                     <button class="qty-btn" onclick="increase(${id})">+</button>
//                 </div>

//                 <button onclick="removeItem(${id})">Remove</button>
//             </div>
//         `;
//     });

//     totalDiv.innerText = "Total: ₹" + total;
//     document.getElementById("totalInput").value = total;

//     // ✅ Enable button
// checkoutBtn.disabled = false;
// checkoutBtn.style.background = "#28a745";
// checkoutBtn.style.cursor = "pointer";
// }

// // Increase qty
// function increase(id){
//     cart.push(id);
//     localStorage.setItem("cart", JSON.stringify(cart));
//     location.reload();
// }

// // Decrease qty
// function decrease(id){
//     let index = cart.indexOf(id);
//     if(index > -1){
//         cart.splice(index, 1);
//     }
//     localStorage.setItem("cart", JSON.stringify(cart));
//     location.reload();
// }

// // Remove all
// function removeItem(id){
//     cart = cart.filter(item => item != id);
//     localStorage.setItem("cart", JSON.stringify(cart));
//     location.reload();
// }

// displayCart();


















let cart = JSON.parse(localStorage.getItem("cart")) || [];

const cartItemsDiv = document.getElementById("cart-items");
const totalDiv = document.getElementById("total");
const checkoutBtn = document.getElementById("checkoutBtn");

let total = 0;

function displayCart() {
    console.log("displayCart called");
    cartItemsDiv.innerHTML = "";
    total = 0;

    if(cart.length === 0){
        cartItemsDiv.innerHTML = "<p class='empty'>🛒 Your cart is empty</p>";
        totalDiv.innerText = "";

        checkoutBtn.disabled = true;
        checkoutBtn.style.background = "gray";
        return;
    }

    cart.forEach((item, index) => {

    let productId = (typeof item === "object") ? item.id : item;

    
    const product = products.find(p => Number(p.id) === Number(productId));

   
    console.log("Matching:", productId, product);

    if(!product){
        console.log("❌ Product not found:", item);
        return;
    }

    let qty = item.qty || 1;
    let size = item.size || "N/A";

    let price = Number(product.discount_price || product.price || 0);

    total += price * qty;

    cartItemsDiv.innerHTML += `
        <div class="card">
            <img src="${product.image}">
            <h3>${product.name}</h3>
            <p>₹${price}</p>
            <p><b>Size:</b> ${size}</p>

            <div>
                <button onclick="decrease(${index})">-</button>
                <span>${qty}</span>
                <button onclick="increase(${index})">+</button>
            </div>

            <button onclick="removeItem(${index})">Remove</button>
        </div>
    `;
});

    totalDiv.innerText = "Total: ₹" + total;
    localStorage.setItem("finalTotal", total);

    checkoutBtn.disabled = false;
    checkoutBtn.style.background = "#28a745";
}


// ➕ Increase quantity
function increase(index){

    if(typeof cart[index] === "object"){
        cart[index].qty += 1;
    } else {
        // convert old format → new format
        cart[index] = {
            id: cart[index],
            size: "N/A",
            qty: 2
        };
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    displayCart();
}


// ➖ Decrease quantity
function decrease(index){

    if(typeof cart[index] === "object"){
        if(cart[index].qty > 1){
            cart[index].qty -= 1;
        } else {
            cart.splice(index, 1);
        }
    } else {
        cart.splice(index, 1);
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    displayCart();
}


// ❌ Remove item completely
function removeItem(index){
    cart.splice(index, 1);
    localStorage.setItem("cart", JSON.stringify(cart));
    displayCart();
}


// 🚀 Run on page load
displayCart();

