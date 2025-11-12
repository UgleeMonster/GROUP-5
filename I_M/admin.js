// Wait for the DOM to fully load
document.addEventListener("DOMContentLoaded", function() {

    function updateDateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString();
        const dateString = now.toLocaleDateString();
        document.getElementById('datetime').textContent = dateString + ' ' + timeString;
    }

    function updateWifiStatus() {
        const wifi = document.getElementById('wifi');
        if (navigator.onLine) {
            wifi.innerHTML = '<i class="fas fa-wifi"></i>';
            wifi.style.color = '#4ade80'; // green
        } else {
            wifi.innerHTML = '<i class="fas fa-wifi-slash"></i>';
            wifi.style.color = '#f87171'; // red
        }
    }

    // Update date/time every second
    setInterval(updateDateTime, 1000);
    updateDateTime();

    // Initial Wi-Fi status and listen for changes
    updateWifiStatus();
    window.addEventListener('online', updateWifiStatus);
    window.addEventListener('offline', updateWifiStatus);

});
