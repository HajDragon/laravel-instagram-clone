export function initializeTheme() {
    // Check for saved theme preference or use the system preference
    const darkMode = localStorage.getItem('darkMode');
    
    if (darkMode === 'true' || 
        (darkMode === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

export function toggleTheme() {
    const isDark = document.documentElement.classList.contains('dark');
    
    if (isDark) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('darkMode', 'false');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('darkMode', 'true');
    }
}