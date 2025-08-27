document.addEventListener('DOMContentLoaded', () => {
  const likeButton = document.getElementById('likeButton');
  const likeCounter = document.getElementById('likeCounter');
  let likes = 0;
  
  likeButton.addEventListener('click', (e) => {
    e.stopPropagation(); // Prevent card click event from triggering
    likes++;
    likeButton.classList.toggle('liked');
    likeCounter.textContent = likes;
  });
  
  document.querySelector('.card').addEventListener('click', () => {
    alert('Card clicked!'); // Example action on card click
  });
});