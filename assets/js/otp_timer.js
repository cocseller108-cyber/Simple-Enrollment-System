let seconds = remainingTime;

const timer = document.getElementById('timer');
const resendBtn = document.getElementById('resendBtn');

function updateTimer() {

    if (!timer || !resendBtn) return;

    if (seconds > 0) {

        timer.innerHTML = "Resend available in " + seconds + " seconds";
        resendBtn.disabled = true;

        seconds--;

    } else {

        timer.innerHTML = "You can now resend OTP.";
        resendBtn.disabled = false;
    }
}

updateTimer();
setInterval(updateTimer, 1000);
