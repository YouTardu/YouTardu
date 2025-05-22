// Base URL for the backend API (replace with your actual backend URL)
const API_BASE_URL = 'https://your-backend-api.com/api';

// Function to show error/success messages (using alert for simplicity)
function showMessage(message, isError = false) {
  alert(message); // Replace with a better UI (e.g., a div or toast notification)
  console.log(isError ? 'Error:' : 'Success:', message);
}

// Toggle between login and signup forms
function showLogin() {
  document.getElementById('loginForm').classList.remove('hidden');
  document.getElementById('signupForm').classList.add('hidden');
}

function showSignup() {
  document.getElementById('signupForm').classList.add('hidden');
  document.getElementById('loginForm').classList.remove('hidden');
}

// Handle login form submission
async function handleLogin(event) {
  event.preventDefault();
  const email = document.getElementById('loginEmail').value;
  const password = document.getElementById('loginPassword').value;
  
  try {
    const response = await fetch(`${API_BASE_URL}/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ email, password }),
    });
    
    const data = await response.json();
    
    if (response.ok && data.success) {
      // Store token (if provided) and redirect or update UI
      if (data.token) {
        localStorage.setItem('token', data.token); // Store JWT
      }
      showMessage('Login successful! Redirecting...');
      // Redirect to dashboard or homepage (replace with your URL)
      setTimeout(() => {
        window.location.href = '/dashboard';
      }, 1000);
    } else {
      showMessage(data.message || 'Invalid credentials', true);
    }
  } catch (error) {
    showMessage('Network error. Please try again later.', true);
    console.error('Login error:', error);
  }
}

// Handle signup form submission
async function handleSignup(event) {
  event.preventDefault();
  const name = document.getElementById('signupName').value;
  const email = document.getElementById('signupEmail').value;
  const password = document.getElementById('signupPassword').value;
  
  try {
    const response = await fetch(`${API_BASE_URL}/signup`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ name, email, password }),
    });
    
    const data = await response.json();
    
    if (response.ok && data.success) {
      showMessage('Signup successful! Please log in.');
      showLogin(); // Switch to login form
      document.getElementById('signup').reset(); // Clear signup form
    } else {
      showMessage(data.message || 'Signup failed', true);
    }
  } catch (error) {
    showMessage('Network error. Please try again later.', true);
    console.error('Signup error:', error);
  }
}

// Handle forgotten password
async function handleForgotPassword() {
  const email = prompt('Enter your email address:'); // Simple prompt for email
  if (!email) {
    showMessage('Email is required.', true);
    return;
  }
  
  try {
    const response = await fetch(`${API_BASE_URL}/forgot-password`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ email }),
    });
    
    const data = await response.json();
    
    if (response.ok && data.success) {
      showMessage(data.message || 'Password reset email sent. Check your inbox.');
    } else {
      showMessage(data.message || 'Email not found', true);
    }
  } catch (error) {
    showMessage('Network error. Please try again later.', true);
    console.error('Forgot password error:', error);
  }
}

// Initially hide signup form
document.getElementById('signupForm').classList.add('hidden');

function showMessage(message, isError = false) {
  const msgDiv = document.createElement('div');
  msgDiv.style.position = 'fixed';
  msgDiv.style.top = '20px';
  msgDiv.style.right = '20px';
  msgDiv.style.padding = '10px 20px';
  msgDiv.style.background = isError ? '#ff4444' : '#44ff44';
  msgDiv.style.color = '#fff';
  msgDiv.style.borderRadius = '4px';
  msgDiv.textContent = message;
  document.body.appendChild(msgDiv);
  setTimeout(() => msgDiv.remove(), 3000);
}
function handleForgotPassword() {
  document.getElementById('forgotPasswordForm').classList.remove('hidden');
}

async function submitForgotPassword() {
  const email = document.getElementById('forgotEmail').value;
  if (!email) {
    showMessage('Email is required.', true);
    return;
  }
  // ... same fetch logic as handleForgotPassword ...
  document.getElementById('forgotPasswordForm').classList.add('hidden');
}