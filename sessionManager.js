// sessionManager.js
document.addEventListener("DOMContentLoaded", () => {
    let idleTime = 0;

    function timerIncrement() {
        idleTime += 1;
        if (idleTime >= 1) { // 1 menit
            alert("Mohon maaf sesi Anda telah berakhir, silahkan melakukan login ulang.");
            window.location.href = "logout.php"; // Buat halaman logout.php yang mengakhiri sesi
        }
    }

    function resetTimer() {
        idleTime = 0;
    }

    // Increment idle time setiap menit
    setInterval(timerIncrement, 60000); // 1 menit

    // Reset timer saat ada aktivitas pengguna
    window.onmousemove = resetTimer;
    window.onkeypress = resetTimer;
    window.onclick = resetTimer;
    window.onscroll = resetTimer;

    function showAlert() {
        alert("Sesi Anda berlaku selama 1 menit. Selamat berbelanja! Happy shopping!");
    }

    // Tampilkan alert setelah halaman selesai dimuat
    window.onload = showAlert;
});
