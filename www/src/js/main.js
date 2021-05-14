const toggleSwitch = (element) => element.classList.toggle("switched-on");

function toggleSidebar() {
    document.querySelector('#sidebar').classList.toggle("wrapped");
    document.querySelectorAll('#sidebar a').forEach((item, index) => {
        if(item.querySelectorAll(".navLabel").length > 0) {
            item.querySelector(".navLabel").classList.toggle("hidden")
        }
    });
}