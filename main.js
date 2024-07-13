/*=============== SHOW MENU ===============*/
const navMenu = document.getElementById('nav-menu');
const navToggle = document.getElementById('nav-toggle');
const navClose = document.getElementById('nav-close');

if (navToggle) {
  navToggle.addEventListener('click', () => {
    navMenu.classList.add('show-menu');
  });
}

if (navClose) {
  navClose.addEventListener('click', () => {
    navMenu.classList.remove('show-menu');
  });
}

/*=============== IMAGE GALLERY ===============*/
function imgGallery() {
  const mainImg = document.querySelector(".details__img");
  const smallImgs = document.querySelectorAll(".details__small-img");

  smallImgs.forEach((img) => {
    img.addEventListener("click", function () {
      mainImg.src = this.src;
    });
  });
}
imgGallery();

/*=============== SWIPER CATEGORIES ===============*/
var swiperCategories = new Swiper(".categories__container", {
  spaceBetween: 24,
  loop: true,
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  breakpoints: {
    350: {
      slidesPerView: 2,
      spaceBetween: 24,
    },
    768: {
      slidesPerView: 3,
      spaceBetween: 24,
    },
    992: {
      slidesPerView: 4,
      spaceBetween: 24,
    },
    1200: {
      slidesPerView: 5,
      spaceBetween: 24,
    },
    1400: {
      slidesPerView: 6,
      spaceBetween: 24,
    },
  },
});

/*=============== SWIPER PRODUCTS ===============*/
var swiperProducts = new Swiper(".new__container", {
  spaceBetween: 24,
  loop: true,
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  breakpoints: {
    768: {
      slidesPerView: 2,
      spaceBetween: 24,
    },
    992: {
      slidesPerView: 3,
      spaceBetween: 44,
    },
    1400: {
      slidesPerView: 4,
      spaceBetween: 24,
    },
  },
});

/*=============== PRODUCTS TABS ===============*/
const tabs = document.querySelectorAll("[data-target]");
const tabContents = document.querySelectorAll("[content]");

tabs.forEach((tab) => {
  tab.addEventListener("click", () => {
    const target = document.querySelector(tab.dataset.target);
    tabContents.forEach((tabContent) => {
      tabContent.classList.remove("active-tab");
    });

    target.classList.add("active-tab");

    tabs.forEach((tab) => {
      tab.classList.remove("active-tab");
    });

    tab.classList.add("active-tab");
  });
});

/*=============== QUANTITY INPUT ===============*/
document.querySelectorAll('.quantity').forEach(input => {
  input.addEventListener('input', updateSubtotal);
});

function updateSubtotal(event) {
  const input = event.target;
  const price = parseFloat(input.getAttribute('data-price'));
  const quantity = parseInt(input.value);
  const subtotalElement = input.closest('tr').querySelector('.table__subtotal');
  const newSubtotal = price * quantity;
  subtotalElement.textContent = `$${newSubtotal.toFixed(2)}`;
  updateCartTotal();
}

function updateCartTotal() {
  let total = 0;
  document.querySelectorAll('.table__subtotal').forEach(subtotalElement => {
    total += parseFloat(subtotalElement.textContent.replace('$', ''));
  });
  document.querySelector('.cart__total-price').textContent = `$${total.toFixed(2)}`;
}

/*=============== TOGGLE PASSWORD VISIBILITY ===============*/
function togglePassword() {
  var passwords = Array.from(arguments); // Convert arguments to an array
  passwords.forEach(function (id) {
    var passwordField = document.getElementById(id);
    if (passwordField.type === "password") {
      passwordField.type = "text";
    } else {
      passwordField.type = "password";
    }
  });
}

/*=============== HANDLE CART OPERATIONS ===============*/
function addToCart(name, price, img) {
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  const product = {
    id: Date.now(), // unique ID for the product
    name: name,
    price: parseFloat(price),
    image: img,
    quantity: 1
  };

  const index = cart.findIndex(item => item.name === name);
  if (index !== -1) {
    cart[index].quantity += 1;
  } else {
    cart.push(product);
  }

  try {
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCart();
  } catch (error) {
    console.error('Error saving to localStorage:', error);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const cartBtns = document.querySelectorAll('.add-to-cart-btn');
  cartBtns.forEach(btn => {
    btn.addEventListener('click', (event) => {
      event.preventDefault();
      const name = btn.getAttribute('data-name');
      const price = btn.getAttribute('data-price');
      const img = btn.getAttribute('data-img');
      addToCart(name, price, img);
    });
  });

  const quantities = document.querySelectorAll('.quantity');
  quantities.forEach(quantity => {
    quantity.addEventListener('input', updateCart);
  });
  updateCart();
});

/*=============== LOGIN FUNCTION ===============*/
function login() {
  var email = document.getElementById("email-login");
  var password = document.getElementById("password-login");

  event.preventDefault();

  sendDataToServer('/login.php', { email: email.value, password: password.value }, function (response) {
    if (response.success) {
      alert("Login berhasil!");
      // Handle successful login
    } else {
      alert("Email atau password salah.");
    }
  });
}

/*=============== REGISTER FUNCTION ===============*/
function register() {
  var username = document.getElementById("username");
  var email = document.getElementById("email-register");
  var password = document.getElementById("password-register");
  var confirmPassword = document.getElementById("confirm-password");
  var registrationAlert = document.getElementById("registration-alert");

  if (!/^\S+@\S+\.\S+$/.test(email.value)) {
    registrationAlert.innerHTML = "Format email salah.";
    registrationAlert.style.display = "block";
    return false;
  }

  if (password.value !== confirmPassword.value) {
    registrationAlert.innerHTML = "Password tidak cocok.";
    registrationAlert.style.display = "block";
    return false;
  } else if (password.value.length < 8) {
    registrationAlert.innerHTML = "Password minimal 8 karakter.";
    registrationAlert.style.display = "block";
    return false;
  } else {
    sendDataToServer('/register.php', { username: username.value, email: email.value, password: password.value }, function (response) {
      if (response.success) {
        alert("Registrasi berhasil!");
        // Handle successful registration
      } else {
        registrationAlert.innerHTML = response.message || "Registrasi gagal.";
        registrationAlert.style.display = "block";
      }
    });
  }
}

/*=============== SHOW NOTIFICATION IF ANY ===============*/
var urlParams = new URLSearchParams(window.location.search);
var message = urlParams.get('message');
var messageType = urlParams.get('messageType');
if (message && messageType) {
  showNotification(message, messageType);
}

function showNotification(message, type) {
  alert(`${type}: ${message}`);
}
