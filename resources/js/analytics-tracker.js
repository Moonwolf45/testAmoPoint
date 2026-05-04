/**
 * Analytics Tracker Script
 */
(function () {
    'use strict';

    const TRACKER_ENDPOINT = '/api/track';
    const STORAGE_KEY = 'analytics_visited';
    const STORAGE_DURATION = 30 * 60 * 1000; // 30 минут между повторными трекингами

    function shouldTrack() {
        try {
            const lastVisit = localStorage.getItem(STORAGE_KEY);
            if (lastVisit) {
                const elapsed = Date.now() - parseInt(lastVisit, 10);
                if (elapsed < STORAGE_DURATION) {
                    return false;
                }
            }

            return true;
        } catch (e) {
            return true;
        }
    }

    function collectData() {
        const data = {
            user_agent: navigator.userAgent,
            page_url: window.location.href,
            screen_resolution: window.screen.width + 'x' + window.screen.height,
        };

        const ua = navigator.userAgent;
        if (/Mobi|Android/i.test(ua)) {
            data.device = 'Mobile';
        } else if (/Tablet|iPad/i.test(ua)) {
            data.device = 'Tablet';
        } else {
            data.device = 'Desktop';
        }

        return data;
    }

    function track() {
        if (!shouldTrack()) return;

        try {
            const data = collectData();

            fetch(TRACKER_ENDPOINT, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(data),
                keepalive: true,
            }).then(function (response) {
                if (response.ok) {
                    localStorage.setItem(STORAGE_KEY, Date.now().toString());
                }
            }).catch(function () {
                if (navigator.sendBeacon) {
                    navigator.sendBeacon(TRACKER_ENDPOINT, JSON.stringify(data));
                }
            });
        } catch (e) {
            // Тихо игнорируем ошибки
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', track);
    } else {
        track();
    }

    // Трекинг при изменении SPA-маршрута (для Vue/React)
    window.addEventListener('popstate', track);

    // Трекинг при отправке формы
    document.addEventListener('submit', function () {
        setTimeout(track, 100);
    });
})();
