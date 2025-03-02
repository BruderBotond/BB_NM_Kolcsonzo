document.addEventListener('DOMContentLoaded', function() {  
  // State variables  
  let selectedTrack = null;  
  let selectedLaps = null;  
  const pricePerLap = 150; // Price per lap in dollars  

  // DOM elements  
  const trackOptions = document.querySelectorAll('.track-option');  
  const lapOptions = document.querySelectorAll('.lap-option');  
  const selectedLapsElement = document.querySelector('.selected-laps');  
  const totalPriceElement = document.querySelector('.total-price');  
  const cartButton = document.querySelector('.cart-button');  
  
  // Get car ID from button  
  const carId = cartButton.getAttribute('data-car-id');  

  // Track selection  
  trackOptions.forEach(option => {  
      option.addEventListener('click', function() {  
          trackOptions.forEach(opt => opt.classList.remove('selected'));  
          this.classList.add('selected');  
          selectedTrack = this.getAttribute('data-track');  
          updateCartButtonState();  
      });  
  });  

  // Lap selection  
  lapOptions.forEach(option => {  
      option.addEventListener('click', function() {  
          lapOptions.forEach(opt => opt.classList.remove('selected'));  
          this.classList.add('selected');  
          selectedLaps = parseInt(this.getAttribute('data-laps'));  
          updateLapsDisplay();  
          updateCartButtonState();  
      });  
  });  

  function updateLapsDisplay() {  
      if (selectedLaps) {  
          selectedLapsElement.textContent = `${selectedLaps} ${selectedLaps === 1 ? 'Lap' : 'Laps'}`;  
          totalPriceElement.textContent = `Total: ${calculateTotal()}$`;  
      } else {  
          selectedLapsElement.textContent = 'Select laps';  
          totalPriceElement.textContent = 'Total: 0$';  
      }  
  }  

  function calculateTotal() {  
      if (selectedLaps === null) return 0;  
      return selectedLaps * pricePerLap;  
  }  

  function updateCartButtonState() {  
      if (selectedTrack && selectedLaps) {  
          cartButton.disabled = false;  
      } else {  
          cartButton.disabled = true;  
      }  
  }  

  function getTrackId(trackName) {  
      const trackMap = {  
          'hungaroring': 1,  
          'balaton': 2,  
          'mring': 3,  
          'drx': 4  
      };  
      return trackMap[trackName] || 1;  
  }  

  function showMessage(message, isSuccess) {  
      const oldMessage = document.querySelector('.booking-message');  
      if (oldMessage) {  
          oldMessage.remove();  
      }  

      const messageDiv = document.createElement('div');  
      messageDiv.className = `booking-message ${isSuccess ? 'success' : 'error'}`;  
      messageDiv.style.padding = '10px';  
      messageDiv.style.marginTop = '10px';  
      messageDiv.style.borderRadius = '5px';  
      messageDiv.style.textAlign = 'center';  
      messageDiv.style.backgroundColor = isSuccess ? '#4CAF50' : '#f44336';  
      messageDiv.style.color = 'white';  
      messageDiv.textContent = message;  

      cartButton.parentNode.insertBefore(messageDiv, cartButton.nextSibling);  

      setTimeout(() => {  
          messageDiv.remove();  
      }, 5000);  
  }  

  cartButton.addEventListener('click', function() {  
      if (selectedTrack && selectedLaps) {  
          const formData = new FormData();  
          formData.append('car_id', carId);  
          formData.append('track_id', getTrackId(selectedTrack));  
          formData.append('date', new Date().toISOString().split('T')[0]);  
          formData.append('laps', selectedLaps);  

          fetch('../booking.php', {  
              method: 'POST',  
              body: formData  
          })  
          .then(response => response.text())  
          .then(result => {  
              if (result.includes('Sikeres')) {  
                  showMessage('Sikeres foglalás!', true);  
                  // Reset form  
                  selectedTrack = null;  
                  selectedLaps = null;  
                  trackOptions.forEach(opt => opt.classList.remove('selected'));  
                  lapOptions.forEach(opt => opt.classList.remove('selected'));  
                  updateLapsDisplay();  
                  updateCartButtonState();  
              } else {  
                  showMessage(result, false);  
              }  
          })  
          .catch(error => {  
              console.error('Error:', error);  
              showMessage('Hiba történt a foglalás során!', false);  
          });  
      }  
  });  
}); 