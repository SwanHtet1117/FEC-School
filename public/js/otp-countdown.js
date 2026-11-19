document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-otp-countdown]').forEach((countdown) => {
        const label = countdown.querySelector('[data-otp-countdown-label]');
        const display = countdown.querySelector('[data-otp-countdown-display]');
        const initialSeconds = Number.parseInt(countdown.dataset.otpCountdownSeconds, 10);

        if (!label || !display || !Number.isFinite(initialSeconds)) {
            console.error('OTP countdown is missing its label, display, or duration.');

            return;
        }

        const startedAt = performance.now();
        let intervalId = null;

        const update = () => {
            const remainingSeconds = Math.max(
                0,
                initialSeconds - Math.floor((performance.now() - startedAt) / 1000)
            );

            if (remainingSeconds === 0) {
                label.textContent = 'Code expired. Request a new code.';
                display.hidden = true;
                countdown.classList.add('is-expired');
                countdown.setAttribute('role', 'status');
                countdown.setAttribute('aria-live', 'polite');
                document.querySelector('[data-otp-verification-form]')?.querySelectorAll(
                    'input:not([type="hidden"]), button[type="submit"]'
                ).forEach((control) => {
                    control.disabled = true;
                });
                if (intervalId !== null) {
                    window.clearInterval(intervalId);
                }

                return;
            }

            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            display.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        };

        update();
        if (initialSeconds > 0) {
            intervalId = window.setInterval(update, 1000);
        }
    });
});
