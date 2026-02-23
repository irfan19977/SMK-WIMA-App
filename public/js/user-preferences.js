class UserPreferences {
    constructor() {
        this.preferences = {
            theme_mode: 'light',
            language: 'id'
        };
        this.init();
    }

    async init() {
        console.log('UserPreferences initializing...');
        await this.loadPreferences();
        this.applyPreferences();
        this.setupEventListeners();
        console.log('UserPreferences initialized with:', this.preferences);
    }

    async loadPreferences() {
        try {
            const response = await fetch('/preferences');
            if (response.ok) {
                const data = await response.json();
                this.preferences = data;
            }
        } catch (error) {
            console.error('Failed to load preferences:', error);
        }
    }

    async savePreferences() {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                this.showNotification('Security token not found', 'error');
                return false;
            }

            console.log('Saving preferences:', this.preferences);
            
            const response = await fetch('/preferences', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                },
                body: JSON.stringify(this.preferences)
            });

            console.log('Response status:', response.status);
            
            if (response.ok) {
                const data = await response.json();
                console.log('Response data:', data);
                this.showNotification('Preferences saved successfully!', 'success');
                return true;
            } else {
                const errorText = await response.text();
                console.error('Server error:', response.status, errorText);
                this.showNotification(`Server error: ${response.status}`, 'error');
            }
        } catch (error) {
            console.error('Failed to save preferences:', error);
            this.showNotification('Failed to save preferences', 'error');
        }
        return false;
    }

    applyPreferences() {
        // Apply theme mode
        this.applyTheme(this.preferences.theme_mode);
        
        // Apply language
        this.applyLanguage(this.preferences.language);
    }

    applyTheme(theme) {
        const body = document.body;
        body.classList.remove('light-mode', 'dark-mode');
        body.classList.add(`${theme}-mode`);
        
        // Update theme toggle buttons
        document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
            btn.classList.remove('active');
            if (btn.getAttribute('data-theme') === theme) {
                btn.classList.add('active');
            }
        });
    }

    applyLanguage(language) {
        // Update language dropdown
        const langSelect = document.querySelector('[data-language-select]');
        if (langSelect) {
            langSelect.value = language;
        }
        
        // Here you can add logic to translate the UI
        // For now, we'll just store the preference
        this.translateUI(language);
    }

    translateUI(language) {
        // Since we're now using Laravel language files, we need to reload the page
        // to get the translated content from the server
        // This is more efficient than maintaining duplicate translations in JS
        console.log('Language changed to:', language);
        
        // Update charts if function exists
        if (typeof window.updateChartsLanguage === 'function') {
            window.updateChartsLanguage();
        }
        
        // You can add AJAX-based translation here if needed in the future
        // For now, the page will be reloaded with the new language from server
    }

    setupEventListeners() {
        // Theme toggle buttons
        document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const theme = btn.getAttribute('data-theme');
                this.preferences.theme_mode = theme;
                this.applyTheme(theme);
                this.savePreferences();
            });
        });

        // Language select
        const langSelect = document.querySelector('[data-language-select]');
        if (langSelect) {
            langSelect.addEventListener('change', (e) => {
                this.preferences.language = e.target.value;
                this.applyLanguage(this.preferences.language);
                this.savePreferences();
            });
        }
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
            color: white;
            border-radius: 6px;
            z-index: 9999;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.3s ease-out;
        `;

        document.body.appendChild(notification);

        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .light-mode {
        --bg-primary: #ffffff;
        --bg-secondary: #f3f4f6;
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --border-color: #e5e7eb;
    }
    
    .dark-mode {
        --bg-primary: #1f2937;
        --bg-secondary: #111827;
        --text-primary: #f9fafb;
        --text-secondary: #d1d5db;
        --border-color: #374151;
    }
    
    [data-theme-toggle] {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    [data-theme-toggle]:hover {
        opacity: 0.8;
    }
    
    [data-theme-toggle].active {
        background-color: #3b82f6;
        color: white;
    }
    
    [data-rtl-toggle] {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    [data-rtl-toggle].active {
        background-color: #3b82f6;
        color: white;
    }
`;
document.head.appendChild(style);

// Initialize the preferences system
document.addEventListener('DOMContentLoaded', () => {
    window.userPreferences = new UserPreferences();
});
