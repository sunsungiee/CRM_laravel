const openBurger = document.getElementById('burger_open');
const closeBurger = document.getElementById('burger_close');
const burgerContent = document.getElementById('burger_nav');

openBurger.addEventListener("click", function () {
    openBurger.style.display = "none";
    burgerContent.style.display = "block";
    closeBurger.style.display = "block";
});

closeBurger.addEventListener("click", function () {
    openBurger.style.display = "block";
    burgerContent.style.display = "none";
    closeBurger.style.display = "none";
});
