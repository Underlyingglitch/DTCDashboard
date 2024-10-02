import './bootstrap';
// import './datatables';\

import "@fortawesome/fontawesome-free/scss/fontawesome.scss";
import "@fortawesome/fontawesome-free/scss/solid.scss";
import "@fortawesome/fontawesome-free/scss/brands.scss";
import "@fortawesome/fontawesome-free/scss/regular.scss";

// Subscribe to a user notification channel
window.Echo.private(`App.Models.User.${window.userId}`).notification((e) => {
    notification(e.style, e.title, e.message);
});

document.addEventListener('livewire:init', () => {
    Livewire.on('notification', (event) => {
        notification(event[2], event[0], event[1]);
    });
});

function notification(style, title, message) {
    switch (style) {
        case 'success':
            window.toastr.success(message, title);
            break;
        case 'warning':
            window.toastr.warning(message, title);
            break;
        case 'error':
            window.toastr.error(message, title);
            break;
        default:
            window.toastr.info(message, title);
            break;
    }
}

$(function () {
    "use strict"; // Start of use strict

    if (localStorage.getItem('toggled') != 'true') {
        $("body").removeClass("sidebar-toggled");
        $(".sidebar").removeClass("toggled");
    }

    // Toggle the side navigation
    $("#sidebarToggle, #sidebarToggleTop").on('click', function (e) {
        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
        if ($(".sidebar").hasClass("toggled")) {
            $('.sidebar .collapse').collapse('hide');
            localStorage.setItem('toggled', 'true');
        } else {
            localStorage.removeItem('toggled');
        }
    });

    // Close any open menu accordions when window is resized below 768px
    $(window).resize(function () {
        if ($(window).width() < 768) {
            $('.sidebar .collapse').collapse('hide');
        };

        // Toggle the side navigation when window is resized below 480px
        if ($(window).width() < 480 && !$(".sidebar").hasClass("toggled")) {
            $("body").addClass("sidebar-toggled");
            $(".sidebar").addClass("toggled");
            $('.sidebar .collapse').collapse('hide');
        };
    });

    // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
    $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function (e) {
        if ($(window).width() > 768) {
            var e0 = e.originalEvent,
                delta = e0.wheelDelta || -e0.detail;
            this.scrollTop += (delta < 0 ? 1 : -1) * 30;
            e.preventDefault();
        }
    });

    // Scroll to top button appear
    $(document).on('scroll', function () {
        var scrollDistance = $(this).scrollTop();
        if (scrollDistance > 100) {
            $('.scroll-to-top').fadeIn();
        } else {
            $('.scroll-to-top').fadeOut();
        }
    });

    // Smooth scrolling using jQuery easing
    $(document).on('click', 'a.scroll-to-top', function (e) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top)
        }, 1000, 'easeInOutExpo');
        e.preventDefault();
    });

    $('[data-toggle="tooltip"]').tooltip()

    $('[data-action="toggleelement"').on('change', function () {
        let id = $(this).attr('data-action-id');
        if ($(this).prop('checked')) {
            $('#' + id).show();
        } else {
            $('#' + id).hide();
        }
    });

    $('[data-toggledby').each(function () {
        let id = $(this).attr('data-toggledby');

        if ($('#' + id).prop('checked')) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });

    if (null ?? true) {
        $('#oldBrowserWarning').hide();
    }

}); // End of use strict
