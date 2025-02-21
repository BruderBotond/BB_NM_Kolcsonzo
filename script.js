//Navbar és footer fetch
document.addEventListener("DOMContentLoaded", function() {
  fetch("/nav&footer/navbar.html")
      .then(response => response.text())
      .then(data => {
          document.getElementById("navbar").innerHTML = data;
      });

  fetch("/nav&footer/footer.html")
      .then(response => response.text())
      .then(data => {
          document.getElementById("footer").innerHTML = data;
      });
});

/////////////////////////////////////////////////////////////

//Hover effektus a hero trackhez
const img = document.querySelector('.hover-image');

img.addEventListener('mouseover', () => {
  img.src = '/src/Hero/Tracks/hungaroring_black.png'; 
});

img.addEventListener('mouseout', () => {
  img.src = '/src/Hero/Tracks/hungaroring.png'; 
});