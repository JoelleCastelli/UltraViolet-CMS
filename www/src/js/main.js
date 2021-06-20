window.addEventListener("load", function(event) {
    document.getElementsByTagName('body')[0].classList.remove("preload");
});

/* ROUTING */
function callRoute(path) {

    let routeJS = "";

    $.ajax({
        type: "POST",
        url: '/admin/routes',
        dataType: 'json',
        async: false,
        data: {
            name: path
        },

        success: function(obj, textstatus) {
            routeJS = obj;
        },
        error: function(obj, textstatus) {
            routeJS = "";
        }
    });

    return routeJS;
}

/* END ROUTING */

const toggleSwitch = (element) => element.classList.toggle("switched-on");

function toggleSidebar() {
    document.querySelector('#sidebar').classList.toggle("wrapped");
    document.querySelector('#main').classList.toggle("sidebarWrapped");
    document.querySelectorAll('#sidebar a').forEach((item, index) => {
        if (item.querySelectorAll(".navLabel").length > 0) {
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
    $("#datatable_filter label").before("<i class='fas fa-search'>");
    let wrapper = $(".dataTables_wrapper");
    wrapper.children().slice(0, 2).wrapAll("<div id='tableHeader'></div>");
    wrapper.children().slice(2, 4).wrapAll("<div id='tableFooter'></div>");

    $("#datatable tbody").on('click', '.bubble-actions', function() {
        $(this).toggleClass("active");
        $('.bubble-actions').not(this).removeClass('active');
    });
});


$(document).ready(function() {

    /******* MODAL ******/

    // Open modal
    $('button[data-toggle="modal"]').click(function() {
        let idModal = $(this).data('target');
        if ($(idModal).length > 0) {
            a = $(idModal)[0];
            $($(idModal)[0]).removeClass('modal-hidden');
            $($(idModal)[0]).addClass('modal-visible');
            $('body').addClass('blurred');

        }
    });

    // Close modal
    $('.modal .btn-close-modal').click(function() {
        let modal = $(this).parents('.modal');

        ($(modal).length > 0)
        {
            $($(modal)[0]).removeClass('modal-visible');
            $($(modal)[0]).addClass('modal-hidden');
            $('body').removeClass('blurred');
        }
    });

    setInterval(function() {
        if ($('.fadeOut').css('opacity') == 0)
            $('.fadeOut').remove();

    }, 3000);
})

/* Message Error and Success */
function successMessageForm(message) {
    return '<p class="success-message-form fadeOut">' +
        '<i class="fas fa-check icon-message-form"></i>' +
        message +
        '</p>';
}

function errorMessageForm(message) {
    return '<p class="error-message-form fadeOut">' +
        '<i class="fas fa-times icon-message-form"></i>' +
        message +
        '</p>';
}

const errorServerJS = "Oops! Un problème est survenue côté serveur, veuillez recommencer s'il vous plaît";
