window.addEventListener("load", function(event) {
    document.getElementsByTagName('body')[0].classList.remove("preload");
});

const toggleSwitch = (element) => element.classList.toggle("switched-on");

function toggleSidebar() {
    document.querySelector('#sidebar').classList.toggle("wrapped");
    document.querySelector('#main').classList.toggle("sidebarWrapped");
    document.querySelectorAll('#sidebar a').forEach((item, index) => {
        if(item.querySelectorAll(".navLabel").length > 0) {
            item.querySelector(".navLabel").classList.toggle("hidden")
        }
    });
}