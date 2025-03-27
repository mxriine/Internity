const menu = document.querySelector(".search-form");
const menuPosition = menu.offsetTop;

function toggleMenuSticky() {
    if (window.pageYOffset >= menuPosition) {
        menu.classList.add("sticky");
    } else {
        menu.classList.remove("sticky");
    }
}

window.addEventListener("scroll", toggleMenuSticky);