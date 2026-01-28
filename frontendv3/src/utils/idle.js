let idleTimer = null;
let countdownTimer = null;

const IDLE_TIMEOUT = 60 * 60 * 1000; // 1 jam
const COUNTDOWN_SECONDS = 30;       // countdown modal

export function startIdleWatcher(showCountdown, onTimeout) {
    let idleStartTime = null;

    const resetIdle = () => {
        clearTimeout(idleTimer);
        idleStartTime = Date.now();

        idleTimer = setTimeout(() => {
            showCountdown(COUNTDOWN_SECONDS, () => {
                // Jika user tidak gerak selama countdown → logout
                onTimeout();
            });
        }, IDLE_TIMEOUT);
    };

    ["click", "mousemove", "keydown", "scroll", "touchstart"].forEach(evt => {
        window.addEventListener(evt, () => {
            clearInterval(countdownTimer);
            countdownTimer = null;
            resetIdle();
        });
    });

    resetIdle();
}
