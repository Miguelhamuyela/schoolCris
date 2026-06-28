document.addEventListener('DOMContentLoaded', function() {
    // Check for saved theme in localStorage
    const savedTheme = localStorage.getItem('theme');
    const darkModeToggle = document.getElementById('darkModeToggle');
    const iconElement = darkModeToggle.querySelector('i');
    
    // Apply the saved theme or default to light mode
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        iconElement.classList.remove('fa-moon');
        iconElement.classList.add('fa-sun');
    }
    
    // Toggle dark mode when button is clicked
    darkModeToggle.addEventListener('click', function() {
        // Toggle body class
        document.body.classList.toggle('dark-mode');
        
        // Toggle icon
        if (document.body.classList.contains('dark-mode')) {
            iconElement.classList.remove('fa-moon');
            iconElement.classList.add('fa-sun');
            localStorage.setItem('theme', 'dark');
        } else {
            iconElement.classList.remove('fa-sun');
            iconElement.classList.add('fa-moon');
            localStorage.setItem('theme', 'light');
        }
    });
}); 