const img = document.querySelector('.hover-image');

img.addEventListener('mouseover', () => {
  img.src = 'ssrc/Hero/Tracks/hungaroring_black.png'; 
});

img.addEventListener('mouseout', () => {
  img.src = 'src/Hero/Tracks/hungaroring.png'; 
});