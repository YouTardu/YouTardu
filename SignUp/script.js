function showSignup() {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('signupForm').style.display = 'block';
        }

        function showLogin() {
            document.getElementById('signupForm').style.display = 'none';
            document.getElementById('loginForm').style.display = 'block';
        }

        function handleLogin(event) {
            event.preventDefault();
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            alert(`Login attempted with Email: ${email}`);
            // Add your login logic here
        }
        function handleForgotPassword() {
    console.log('Forgotten Password clicked');
    // Add your password reset logic here (e.g., redirect to reset page or show a modal)
    alert('Redirecting to password reset page...'); // Placeholder
}

        function handleSignup(event) {
            event.preventDefault();
            const name = document.getElementById('signupName').value;
            const email = document.getElementById('signupEmail').value;
            const password = document.getElementById('signupPassword').value;
            alert(`Signup attempted with Name: ${name}, Email: ${email}`);
            // Add your signup logic here
        }
        
        