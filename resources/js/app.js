// AdminKit (required)
import "./modules/bootstrap";
import "./modules/sidebar";
import "./modules/theme";
import "./modules/feather";

// // Charts
// import "./modules/chartjs";

// // Forms
// import "./modules/flatpickr";

// // Maps
// import "./modules/vector-maps";

// Axios
import axios from 'axios'
window.axios = axios

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// jQuery
import jquery from 'jquery'
window.jQuery = jquery
window.$ = jquery

// Font Awesome
import "@fortawesome/fontawesome-free/scss/fontawesome.scss"
import "@fortawesome/fontawesome-free/scss/solid.scss"
import "@fortawesome/fontawesome-free/scss/brands.scss"
import "@fortawesome/fontawesome-free/scss/regular.scss"

// Toastr
import toastr from 'toastr'
toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}
window.toastr = toastr

// Echo
import Echo from 'laravel-echo'

import Pusher from 'pusher-js'
window.Pusher = Pusher

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    wsPort: import.meta.env.VITE_PUSHER_PORT,
    wssPort: import.meta.env.VITE_PUSHER_PORT,
    cluster: import.meta.env.VITE_PUSHER_CLUSTER,
    forceTLS: import.meta.env.VITE_APP_ENV == 'local' ? false : true,
    encrypted: true,
    disableStats: true,
    enabledTransports: import.meta.env.VITE_APP_ENV == 'local' ? ['ws'] : ['ws', 'wss'],
})

// Notification handler
window.Echo.private(`App.Models.User.${window.userId}`).notification((e) => {
    notification(e.style, e.title, e.message)
})

document.addEventListener('livewire:init', () => {
    Livewire.on('notification', (event) => {
        notification(event[2], event[0], event[1])
    })
})

function notification(style, title, message) {
    switch (style) {
        case 'success':
            window.toastr.success(message, title)
            break
        case 'warning':
            window.toastr.warning(message, title)
            break
        case 'error':
            window.toastr.error(message, title)
            break
        default:
            window.toastr.info(message, title)
            break
    }
}

// App Code
$(() => {
    $('[data-action="toggleelement"').on('change', function () {
        let id = $(this).attr('data-action-id')
        if ($(this).prop('checked')) {
            $('#' + id).show()
        } else {
            $('#' + id).hide()
        }
    })

    $('[data-toggledby').each(function () {
        let id = $(this).attr('data-toggledby')

        if ($('#' + id).prop('checked')) {
            $(this).show()
        } else {
            $(this).hide()
        }
    })

    if (null ?? true) {
        $('#oldBrowserWarning').hide();
    }
})