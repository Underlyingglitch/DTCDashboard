let device_id = getCookie('device_id')
if (device_id == "") {
    device_id = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15)
    setCookie('device_id', device_id)
}
console.log('Device ID:', device_id)
setInterval(() => {
    console.log('Sending ping')
    window.axios.post('/api/internal/ping', {
        page: window.location.pathname,
        user_id: null,
        // Get the device id from a cookie
        device_id: device_id
    })
}, 1000 * 5);
window.axios.post('/api/internal/ping', {
    page: window.location.pathname,
    user_id: null,
    device_id: device_id
}).then((data) => {
    let id = data.data.id
    loadPage(data.data.loaded_page)
    window.Echo.channel(`monitor.${id}`).listen('.DeviceUpdated', (e) => {
        loadPage(e.loaded_page)
    })
}).catch((error) => {
    if (error.response.status == 404) {
        // Prompt the user for a number
        let number = prompt('Voer laptopnummer in')
        if (number == null) {
            return
        }
        window.axios.post('/api/internal/register', {
            laptop_number: number,
            device_id: getCookie('device_id')
        }).then((data) => {
            console.log(data)
        }).catch((error) => {
            console.log(error)
        })
    }
})

function loadPage(page) {
    if (page == window.location.pathname) return
    window.location.pathname = page
}

function setCookie(cname, cvalue, exdays = 365) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}