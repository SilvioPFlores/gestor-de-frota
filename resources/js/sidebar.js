import $ from 'jquery';

$(function () {
    // 1. Restaura o estado salvo no navegador ao carregar a página
    if (localStorage.getItem("sidebarState") === "collapsed") {
        $("#sidebar").addClass("collapsed");
    }

    // 2. Alterna o estado ao clicar no botão
    $("#sidebarToggle").on("click", function () {
        $("#sidebar").toggleClass("collapsed");
        console.log("apertou");

        if ($("#sidebar").hasClass("collapsed")) {
            localStorage.setItem("sidebarState", "collapsed");
        } else {
            localStorage.setItem("sidebarState", "expanded");
        }
    });
});