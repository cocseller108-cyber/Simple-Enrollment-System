let seconds = remainingTime;

const timer = document.getElementById('timer');

function updateTimer() {

    if (!timer) return;

    if (seconds > 0) {

        timer.innerHTML = "OTP expires in " + seconds + " seconds";
        seconds--;

    } else {

        timer.innerHTML = "OTP expired. Please request a new OTP.";
    }
}

updateTimer();
setInterval(updateTimer, 1000);
