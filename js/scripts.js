document.addEventListener('DOMContentLoaded', function() {
    // 1. Auto-dismiss flash messages
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(function(msg) {
        setTimeout(function() {
            msg.style.transition = 'opacity 0.5s ease';
            msg.style.opacity = '0';
            setTimeout(function() {
                msg.remove();
            }, 500);
        }, 5000);
    });

    // 2. Client-side validation for Register and Profile forms
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            let isValid = true;
            let errorMessage = '';

            const usernameInput = form.querySelector('input[name="username"]');
            const emailInput = form.querySelector('input[name="email"]');
            const passwordInput = form.querySelector('input[name="password"]');

            if (usernameInput && usernameInput.value.trim().length < 3) {
                isValid = false;
                errorMessage += 'Username must be at least 3 characters long.\n';
            }

            if (emailInput) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailInput.value.trim())) {
                    isValid = false;
                    errorMessage += 'Please enter a valid email address.\n';
                }
            }

            // Only validate password length if it's required (register) or if it's provided (profile update)
            if (passwordInput && (passwordInput.hasAttribute('required') || passwordInput.value.length > 0)) {
                if (passwordInput.value.length < 6) {
                    isValid = false;
                    errorMessage += 'Password must be at least 6 characters long.\n';
                }
            }

            // 3. File Upload Validation
            const fileInput = form.querySelector('input[type="file"]');
            if (fileInput && fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    isValid = false;
                    errorMessage += 'File is too large. Maximum size is 5MB.\n';
                }
                if (file.type !== 'application/pdf') {
                    isValid = false;
                    errorMessage += 'Only PDF files are allowed.\n';
                }
            }

            if (!isValid) {
                event.preventDefault();
                alert(errorMessage);
            }
        });
    });
});
