/**
 * Custom Forms JavaScript Functions
 * Adds enhanced functionality to textareas and other form elements
 */

// Wait for document to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all textareas with enhanced features
    initializeTextAreas();
});

/**
 * Initialize enhanced features for textareas
 */
function initializeTextAreas() {
    // Get all textareas in the document
    var textareas = document.querySelectorAll('textarea.form-control');
    
    // Add enhancements to each textarea
    textareas.forEach(function(textarea) {
        // Auto resizing based on content
        makeExpandable(textarea);
        
        // Add character count if inside a details-container
        if (textarea.closest('.details-container')) {
            addCharacterCounter(textarea);
        }
        
        // Add focus animation
        addFocusAnimation(textarea);
    });
}

/**
 * Make textarea automatically expandable
 * @param {HTMLElement} textarea - The textarea element to make expandable
 */
function makeExpandable(textarea) {
    textarea.addEventListener('input', function() {
        // Reset height to auto to calculate new height
        this.style.height = 'auto';
        // Set height to scrollHeight to fit content
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Trigger initial resize
    textarea.dispatchEvent(new Event('input'));
}

/**
 * Add character counter to a textarea
 * @param {HTMLElement} textarea - The textarea to add counter to
 */
function addCharacterCounter(textarea) {
    // Create or find character counter element
    var container = textarea.closest('.details-container');
    var counter = container.querySelector('.char-counter');
    
    if (counter) {
        // Update the counter text
        var updateCount = function() {
            var count = textarea.value.length;
            var maxLength = textarea.getAttribute('maxlength') || 0;
            
            // If there's a maximum length, show remaining characters
            if (maxLength > 0) {
                counter.innerHTML = '<i class="fas fa-text-width"></i> ' + 
                    count + ' / ' + maxLength + ' caracteres' +
                    (count > maxLength * 0.8 ? ' <span class="text-warning">(limite próximo)</span>' : '');
            } else {
                // Just show the character count
                counter.innerHTML = '<i class="fas fa-text-width"></i> ' + count + ' caracteres';
            }
        };
        
        // Update on input
        textarea.addEventListener('input', updateCount);
        
        // Initial update
        updateCount();
    }
}

/**
 * Add focus animation to textarea
 * @param {HTMLElement} textarea - The textarea to add animation to
 */
function addFocusAnimation(textarea) {
    textarea.addEventListener('focus', function() {
        var container = this.closest('.details-container');
        if (container) {
            container.classList.add('focused');
        }
    });
    
    textarea.addEventListener('blur', function() {
        var container = this.closest('.details-container');
        if (container) {
            container.classList.remove('focused');
        }
    });
} 