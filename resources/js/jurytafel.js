let device_id = getCookie('device_id')

window.axios.post('/api/internal/ping', {
    device_id: device_id,
    loaded_page: window.location.pathname
})

window.Echo.channel(`monitor.${device_id}`).listen('.DeviceUpdated', (e) => {
    console.log('Device updated', e)
    if (!e.loaded_page) {
        window.location.reload()
    } else if (e.loaded_page != window.location.pathname) {
        // Redirect to the new page
        window.location.href = e.loaded_page
        // Disconnect from the channel
        window.Echo.leaveChannel(`monitor.${device_id}`)
    }
})

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