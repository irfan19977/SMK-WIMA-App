<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'SMK WIMA') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="{{ auth()->user()->theme_mode ?? 'light' }}-mode">
    
    <!-- Settings Panel -->
    @auth
    <div id="settingsPanel" class="settings-panel" style="display: none;">
        <div class="settings-content">
            <div class="settings-header">
                <h5>Settings</h5>
                <button type="button" class="btn-close" onclick="toggleSettingsPanel()">&times;</button>
            </div>
            
            <div class="settings-body">
                <!-- Theme Settings -->
                <div class="setting-group">
                    <label class="setting-label">Choose Layouts</label>
                    <div class="theme-options">
                        <button 
                            type="button" 
                            class="theme-btn {{ (auth()->user()->theme_mode ?? 'light') === 'light' ? 'active' : '' }}"
                            data-theme="light"
                            data-theme-toggle
                            onclick="setTheme('light')"
                        >
                            <i class="fas fa-sun"></i> Light Mode
                        </button>
                        <button 
                            type="button" 
                            class="theme-btn {{ (auth()->user()->theme_mode ?? 'light') === 'dark' ? 'active' : '' }}"
                            data-theme="dark"
                            data-theme-toggle
                            onclick="setTheme('dark')"
                        >
                            <i class="fas fa-moon"></i> Dark Mode
                        </button>
                    </div>
                </div>
                
                <!-- Language Settings -->
                <div class="setting-group">
                    <label class="setting-label">Language</label>
                    <select 
                        class="form-select language-select"
                        data-language-select
                        onchange="setLanguage(this.value)"
                    >
                        <option value="id" {{ (auth()->user()->language ?? 'id') === 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                        <option value="en" {{ (auth()->user()->language ?? 'id') === 'en' ? 'selected' : '' }}>English</option>
                        <option value="es" {{ (auth()->user()->language ?? 'id') === 'es' ? 'selected' : '' }}>Español</option>
                        <option value="de" {{ (auth()->user()->language ?? 'id') === 'de' ? 'selected' : '' }}>Deutsch</option>
                        <option value="it" {{ (auth()->user()->language ?? 'id') === 'it' ? 'selected' : '' }}>Italiano</option>
                        <option value="ru" {{ (auth()->user()->language ?? 'id') === 'ru' ? 'selected' : '' }}>Русский</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    @endauth
    
    <!-- Main Content -->
    <div class="main-container">
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Content Area -->
        <div class="content-area">
            <!-- Top Bar -->
            @include('layouts.topbar')
            
            <!-- Page Content -->
            <main class="page-content">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Settings Toggle Button -->
    @auth
    <button 
        type="button" 
        class="settings-toggle-btn"
        onclick="toggleSettingsPanel()"
        title="Settings"
    >
        <i class="fas fa-cog"></i>
    </button>
    @endauth
    
    <!-- User Preferences Script -->
    <script src="{{ asset('js/user-preferences.js') }}"></script>
    
    @stack('scripts')
    
    <style>
        .settings-panel {
            position: fixed;
            top: 0;
            right: -350px;
            width: 350px;
            height: 100vh;
            background: white;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            z-index: 9999;
            transition: right 0.3s ease;
        }
        
        .settings-panel.show {
            right: 0;
        }
        
        .settings-content {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .settings-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .settings-header h5 {
            margin: 0;
            font-weight: 600;
        }
        
        .btn-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6b7280;
        }
        
        .settings-body {
            padding: 20px;
            flex: 1;
            overflow-y: auto;
        }
        
        .setting-group {
            margin-bottom: 25px;
        }
        
        .setting-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: #374151;
        }
        
        .theme-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .theme-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: left;
        }
        
        .theme-btn:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }
        
        .theme-btn.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        .language-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: white;
        }
        
        .settings-toggle-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: all 0.2s ease;
        }
        
        .settings-toggle-btn:hover {
            background: #2563eb;
            transform: scale(1.05);
        }
        
        .dark-mode .settings-panel {
            background: #1f2937;
            color: #f9fafb;
        }
        
        .dark-mode .settings-header {
            border-bottom-color: #374151;
        }
        
        .dark-mode .settings-header h5 {
            color: #f9fafb;
        }
        
        .dark-mode .btn-close {
            color: #d1d5db;
        }
        
        .dark-mode .setting-label {
            color: #f9fafb;
        }
        
        .dark-mode .theme-btn {
            background: #374151;
            color: #f9fafb;
            border-color: #4b5563;
        }
        
        .dark-mode .theme-btn:hover {
            background: #4b5563;
            border-color: #6b7280;
        }
        
        .dark-mode .language-select {
            background: #374151;
            color: #f9fafb;
            border-color: #4b5563;
        }
    </style>
    
    <script>
        function toggleSettingsPanel() {
            const panel = document.getElementById('settingsPanel');
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
            setTimeout(() => {
                panel.classList.toggle('show');
            }, 10);
        }
        
        // These functions will be overridden by UserPreferences class
        function setTheme(theme) {
            if (window.userPreferences) {
                window.userPreferences.preferences.theme_mode = theme;
                window.userPreferences.applyTheme(theme);
                window.userPreferences.savePreferences();
            }
        }
        
        function setLanguage(language) {
            if (window.userPreferences) {
                window.userPreferences.preferences.language = language;
                window.userPreferences.applyLanguage(language);
                window.userPreferences.savePreferences();
            }
        }
    </script>
</body>
</html>
