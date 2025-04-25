document.getElementById('contactForm').addEventListener('submit', function (e) {
    e.preventDefault();
    let isValid = true;

    // Reset previous errors
    clearErrors();

    // Validate name (required)
    const name = document.getElementById('name').value.trim();
    if (name === '') {
        showError('nameError', 'Name is required');
        isValid = false;
    } else if (name.length < 2) {
        showError('nameError', 'Name must be at least 2 characters');
        isValid = false;
    }

    // Validate email (required + format)
    const email = document.getElementById('email').value.trim();
    if (email === '') {
        showError('emailError', 'Email is required');
        isValid = false;
    } else if (!isValidEmail(email)) {
        showError('emailError', 'Please enter a valid email address');
        isValid = false;
    }

    // Validate phone (optional but must be valid if provided)
    const phone = document.getElementById('phone').value.trim();
    if (phone !== '' && !isValidPhone(phone)) {
        showError('phoneError', 'Please enter a valid phone number');
        isValid = false;
    }

    // Validate message (required)
    const message = document.getElementById('message').value.trim();
    if (message === '') {
        showError('messageError', 'Message is required');
        isValid = false;
    } else if (message.length < 10) {
        showError('messageError', 'Message must be at least 10 characters');
        isValid = false;
    }

    // If form is valid, submit it
    if (isValid) {
        // Here you would typically send the data to a server
        alert('Form submitted successfully!');
        this.reset();
    }
});

// Helper functions
function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    errorElement.textContent = message;
    errorElement.style.display = 'block';

    // Add error class to input
    const inputId = elementId.replace('Error', '');
    document.getElementById(inputId).classList.add('error');
}

function clearErrors() {
    // Clear all error messages
    document.querySelectorAll('.error-message').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });

    // Remove error classes from inputs
    document.querySelectorAll('input, textarea').forEach(el => {
        el.classList.remove('error');
    });
}

function isValidEmail(email) {
    // More comprehensive email regex
    const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return re.test(String(email).toLowerCase());
}

function isValidPhone(phone) {
    // Allows various phone number formats
    const re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
    return re.test(phone);
}

// Real-time validation for better UX
document.getElementById('name').addEventListener('blur', validateName);
document.getElementById('email').addEventListener('blur', validateEmail);
document.getElementById('phone').addEventListener('blur', validatePhone);
document.getElementById('message').addEventListener('blur', validateMessage);

function validateName() {
    const name = document.getElementById('name').value.trim();
    if (name === '') {
        showError('nameError', 'Name is required');
    } else if (name.length < 2) {
        showError('nameError', 'Name must be at least 2 characters');
    } else {
        document.getElementById('nameError').style.display = 'none';
        document.getElementById('name').classList.remove('error');
        document.getElementById('name').classList.add('valid');
    }
}

function validateEmail() {
    const email = document.getElementById('email').value.trim();
    if (email === '') {
        showError('emailError', 'Email is required');
    } else if (!isValidEmail(email)) {
        showError('emailError', 'Please enter a valid email address');
    } else {
        document.getElementById('emailError').style.display = 'none';
        document.getElementById('email').classList.remove('error');
        document.getElementById('email').classList.add('valid');
    }
}

// Similar validation functions can be created for phone and message