
const wrapDatatablesFooter = () => {
    const toAdd = document.querySelectorAll("#datatable_info, #datatable_paginate");
    
    const newFooter = document.createElement("div");
    newFooter.classList.add("table-footer");

    toAdd.forEach((element) => newFooter.appendChild(element));
    document.querySelector('#datatable').after(newFooter);
}

$(document).ready( function () {

    $("#datatable_filter label").before( "<i class='fas fa-search'>" );
    $(".dataTables_wrapper").children().slice(0,2).wrapAll( "<div id='tableHeader'></div>" );
    wrapDatatablesFooter();
} );
