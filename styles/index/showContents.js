const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting){
            entry.target.classList.add('show');
        }
        else{
            entry.target.classList.remove('show');
        }
    });
});
const hiddenElements = document.querySelectorAll('.hidden');
hiddenElements.forEach((el) => observer.observe(el));



function toggleMobileMenu(menu) {
    menu.classList.toggle('open');
}
document.querySelector(".mobile-menu").addEventListener("click", function(event) {
    // Prevent the default behavior of clicks on empty space within the menu
    event.stopPropagation();
});
