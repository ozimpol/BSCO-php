
window.addEventListener('DOMContentLoaded', function() {
    var sliderContainer = document.getElementById('slider-container');
    var slides = sliderContainer.getElementsByClassName('slider');
    var currentSlideIndex = 0;

    function showSlide(index) {
        for (var i = 0; i < slides.length; i++) {
            if (i === index) {
                slides[i].style.transform = 'translateX(0)';
            } else {
                slides[i].style.transform = 'translateX(100%)';
            }
        }
    }

    function startSlider() {
        setInterval(function() {
            currentSlideIndex = (currentSlideIndex + 1) % slides.length;
            showSlide(currentSlideIndex);
        }, 5000);
    }

    startSlider();
});

// Function to update the cart items and display them
function updateCartItems() {
    const cartItemsElement = document.querySelector('#cart-section .cart-items');
    cartItemsElement.innerHTML = '';
  
    let totalPrice = 0;
  
    for (const item of cartItems) {
      const itemElement = document.createElement('div');
      itemElement.classList.add('cart-item');
  
      // Product image
      const imageElement = document.createElement('img');
      imageElement.src = 'images/' + item.image;
      imageElement.alt = item.name;
      imageElement.style.width = '100px';
      imageElement.style.height = '150px';
      itemElement.appendChild(imageElement);
  
      // Product name
      const nameElement = document.createElement('h3');
      nameElement.textContent = item.name;
      itemElement.appendChild(nameElement);
  
      // Quantity selector
      const quantityElement = document.createElement('select');
      quantityElement.classList.add('quantity-select');
      const maxQuantity = item.type === 'Sweatshirt' ? 2 : 10;
      for (let i = 1; i <= maxQuantity; i++) {
        const optionElement = document.createElement('option');
        optionElement.value = i;
        optionElement.textContent = i;
        quantityElement.appendChild(optionElement);
      }
      quantityElement.value = item.quantity;
      quantityElement.addEventListener('change', quantityChangeHandler); // Add this line
      itemElement.appendChild(quantityElement);
  
      // Item Price
      const itemPriceElement = document.createElement('p');
      itemPriceElement.classList.add('item-price');
      itemPriceElement.textContent = item.price * item.quantity + ' TL';
      itemElement.appendChild(itemPriceElement);
  
      // Delete button
      const deleteButton = document.createElement('button');
      deleteButton.classList.add('delete-button');
      deleteButton.textContent = 'Delete';
      deleteButton.addEventListener('click', deleteButtonClickHandler); // Add this line
      itemElement.appendChild(deleteButton);
  
      // Calculate total price for this item
      const itemPrice = item.price * item.quantity;
      totalPrice += itemPrice;
  
      cartItemsElement.appendChild(itemElement);
    }
  
    // Update total price
    const totalPriceElement = document.querySelector('#cart-section .cart-total');
    totalPriceElement.textContent = 'Total Price: ' + totalPrice + ' TL';
  }
  
  // Add to Cart button click event
  const addToCartButtons = document.getElementsByClassName('add-to-cart');
  for (const button of addToCartButtons) {
    button.addEventListener('click', function () {
      const productName = this.getAttribute('data-product');
  
      // Send an AJAX request to add the product to the cart
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'add_to_cart.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            // Request was successful
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
              // Update the cart items and display them
              cartItems = response.cartItems;
              updateCartItems();
            } else {
              // Display an error message
              console.error(response.message);
            }
          } else {
            // Request failed
            console.error('An error occurred while adding the product to the cart.');
          }
        }
      };
      xhr.send('product=' + encodeURIComponent(productName));
    });
  }
  
  // Delete button click event
  function deleteButtonClickHandler() {
    const itemName = this.parentNode.querySelector('h3').textContent;
  
    // Send an AJAX request to remove the item from the cart
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'remove_from_cart.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          // Request was successful
          const response = JSON.parse(xhr.responseText);
          if (response.success) {
            // Update the cart items and display them
            cartItems = response.cartItems;
            updateCartItems();
          } else {
            // Display an error message
            console.error(response.message);
          }
        } else {
          // Request failed
          console.error('An error occurred while removing the item from the cart.');
        }
      }
    };
    xhr.send('product=' + encodeURIComponent(itemName));
  }
  
  // Quantity change event
  function quantityChangeHandler() {
    const itemName = this.parentNode.querySelector('h3').textContent;
    const quantity = this.value;
  
    // Send an AJAX request to update the quantity of the item in the cart
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_quantity.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          // Request was successful
          const response = JSON.parse(xhr.responseText);
          if (response.success) {
            // Update the cart items and display them
            cartItems = response.cartItems;
            updateCartItems();
          } else {
            // Display an error message
            console.error(response.message);
          }
        } else {
          // Request failed
          console.error('An error occurred while updating the quantity of the item in the cart.');
        }
      }
    };
    xhr.send('product=' + encodeURIComponent(itemName) + '&quantity=' + encodeURIComponent(quantity));
  }

