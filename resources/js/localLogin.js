let device_id = getCookie('device_id')
let id_element = document.getElementById('device_code')

window.axios.post('/api/internal/ping', {
    device_id: device_id,
    loaded_page: window.location.pathname
})

if (window.location.pathname == '/auth/local') {
    if (device_id == "") {
        device_id = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15)
        setCookie('device_id', device_id)
    }

    window.axios.post('/api/internal/register', {
        device_id: device_id
    }).then((r) => {
        if (r.data.page) {
            window.location.pathname = r.data.page
        }
        if (id_element) {
            id_element.innerHTML = r.data.code
        } else {
            console.log('No element found, code:', r.data.code)
        }
    }).catch((error) => {
        console.log(error)
    })
}

window.Echo.channel(`monitor.${device_id}`).listen('.DeviceUpdated', (e) => {
    console.log('Device updated', e)
    if (e.loaded_page != window.location.pathname) {
        window.location.pathname = e.loaded_page
        window.Echo.leaveChannel(`monitor.${device_id}`)
    }
})

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