function updateClock() {
    var now = new Date();
    
    var hours = String(now.getHours()).padStart(2, '0');
    var minutes = String(now.getMinutes()).padStart(2, '0');
    var seconds = String(now.getSeconds()).padStart(2, '0');
    
    var clockElement = document.getElementById('liveClock');
    
    if (clockElement) {
        clockElement.innerHTML = hours + ":" + minutes + ":" + seconds + " WIB";
    }
}

setInterval(updateClock, 1000);

updateClock();