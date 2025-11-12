 
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
            wifi.style.color = '#4ade80';  
        } else {
            wifi.innerHTML = '<i class="fas fa-wifi-slash"></i>';
            wifi.style.color = '#f87171';  
        }
    }

     
    setInterval(updateDateTime, 1000);
    updateDateTime();

     
    updateWifiStatus();
    window.addEventListener('online', updateWifiStatus);
    window.addEventListener('offline', updateWifiStatus);

});
