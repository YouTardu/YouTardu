const express = require('express');
const bcrypt = require('bcrypt');
const cors = require('cors');
const app = express();

app.use(cors());
app.use(express.json());

// Mock database (replace with real database)
const users = [];

// Login
app.post('/api/login', async (req, res) => {
  const { email, password } = req.body;
  const user = users.find(u => u.email === email);
  if (!user) {
    return res.status(401).json({ success: false, message: 'Email not found' });
  }
  const isMatch = await bcrypt.compare(password, user.password);
  if (!isMatch) {
    return res.status(401).json({ success: false, message: 'Invalid password' });
  }
  // Generate JWT or session (simplified here)
  res.json({ success: true, token: 'mock-jwt-token' });
});

// Signup
app.post('/api/signup', async (req, res) => {
  const { name, email, password } = req.body;
  if (users.find(u => u.email === email)) {
    return res.status(400).json({ success: false, message: 'Email already exists' });
  }
  const hashedPassword = await bcrypt.hash(password, 10);
  users.push({ name, email, password: hashedPassword });
  res.json({ success: true, message: 'User created' });
});

// Forgotten Password
app.post('/api/forgot-password', (req, res) => {
  const { email } = req.body;
  const user = users.find(u => u.email === email);
  if (!user) {
    return res.status(404).json({ success: false, message: 'Email not found' });
  }
  // Send reset email (implement with email service)
  console.log(`Sending reset email to ${email}`);
  res.json({ success: true, message: 'Password reset email sent' });
});

app.listen(3000, () => console.log('Server running on port 3000'));


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