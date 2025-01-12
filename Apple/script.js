document.getElementById('signupForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (password !== confirmPassword) {
        alert("Passwords do not match!");
    } else {
        alert("Form submitted successfully!");
        // Here, you can proceed with form submission or API calls
    }
});