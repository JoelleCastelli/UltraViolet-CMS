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

var waitForElement = function(selector, callback) {
    if (jQuery(selector).length) {
        callback();
    } else {
        setTimeout(function() {
            waitForElement(selector, callback);
        }, 100);
    }
};

waitForElement("#datatable", function() {
    $("#datatable_filter label").before( "<i class='fas fa-search'>" );
    let wrapper = $(".dataTables_wrapper");
    wrapper.children().slice(0,2).wrapAll("<div id='tableHeader'></div>");
    wrapper.children().slice(2,4).wrapAll("<div id='tableFooter'></div>");
});
