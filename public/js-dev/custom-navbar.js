const navToggler = document.querySelector("#nav-toggle-mobile");
const navbar = document.querySelector('.navbar');
const nav2Toggler = document.querySelector("#toggle-nav-2");

navToggler.addEventListener("click", function() {
    nav2Toggler.click();
});

document.querySelectorAll('.user-dropdown-menu').forEach(menu => {
    menu.addEventListener('click', function(e) {
        e.stopPropagation();
    })
})