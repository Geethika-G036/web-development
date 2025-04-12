// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get the button element
    const alertButton = document.getElementById('alertButton');
    
    // Add click event listener to the button
    alertButton.addEventListener('click', function() {
        // Show an alert when the button is clicked
        alert('Thank you for visiting our webpage! We appreciate your interest.');
        
        // Optional: Change button text after click
        this.textContent = 'Message Sent!';
        
        // Optional: Disable the button after click
        this.disabled = true;
        
        // Optional: Re-enable the button after 3 seconds
        setTimeout(() => {
            this.textContent = 'Click for Important Message';
            this.disabled = false;
        }, 3000);
    });
    
    // Bonus: Add hover effect to images via JavaScript
    const images = document.querySelectorAll('.image-container img');
    images.forEach(img => {
        img.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.2)';
        });
        
        img.addEventListener('mouseleave', function() {
            this.style.boxShadow = '0 3px 6px rgba(0,0,0,0.1)';
        });
    });
});