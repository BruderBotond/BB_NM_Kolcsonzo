document.addEventListener("DOMContentLoaded", function() {
    fetch("./navbar_index.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("navbar").innerHTML = data;
        });
  
    fetch("./footer_index.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("footer").innerHTML = data;
        });
  });