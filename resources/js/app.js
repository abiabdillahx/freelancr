import './bootstrap';

// Initialize global utilities
(function() {
    // Theme Toggle Logic
    const applyTheme = (theme) => {
        document.documentElement.setAttribute('data-theme', theme);
        const label = document.querySelector('[data-theme-label]');
        const button = document.querySelector('[data-theme-toggle]');
        if (label) label.textContent = theme === 'dark' ? 'Dark' : 'Light';
        if (button) button.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
    };

    const initTheme = () => {
        const stored = typeof localStorage !== 'undefined' ? localStorage.getItem('freelancr-theme') : null;
        if (stored === 'light' || stored === 'dark') {
            applyTheme(stored);
            return;
        }
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        applyTheme(prefersDark ? 'dark' : 'light');
    };

    initTheme();

    document.addEventListener('click', (e) => {
        if (e.target.closest('[data-theme-toggle]')) {
            const next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            applyTheme(next);
            if (typeof localStorage !== 'undefined') {
                localStorage.setItem('freelancr-theme', next);
            }
        }
    });

    // Axios Interceptor for JWT
    if (window.axios) {
        window.axios.interceptors.request.use(config => {
            const token = localStorage.getItem('freelancr_token');
            if (token) {
                config.headers.Authorization = `Bearer ${token}`;
            }
            return config;
        });

        window.axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response && error.response.status === 401) {
                    // Only redirect if we were actually trying to use a token
                    if (localStorage.getItem('freelancr_token')) {
                        localStorage.removeItem('freelancr_token');
                        localStorage.removeItem('freelancr_user');
                        window.location.href = '/login';
                    }
                }
                return Promise.reject(error);
            }
        );
    }
})();
