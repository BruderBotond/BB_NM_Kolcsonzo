document.addEventListener('DOMContentLoaded', function() {  
  // State variables  
  let selectedTrack = null;  
  let selectedLaps = null;
  let selectedDate = null;  
  let lapPrices = {}; // Store lap prices fetched from the database

  // DOM elements  
  const trackOptions = document.querySelectorAll('.track-option');  
  const lapOptions = document.querySelectorAll('.lap-option');  
  const selectedLapsElement = document.querySelector('.selected-laps');  
  const totalPriceElement = document.querySelector('.total-price');  
  const cartButton = document.querySelector('.cart-button');  
  
  // Get car ID from button  
  const carId = cartButton.getAttribute('data-car-id');
  
  // Fetch lap prices from the database
  fetchLapPrices(carId);

  // Create date picker container
  const lapsContainer = document.querySelector('.laps-container');
  const datePickerContainer = document.createElement('div');
  datePickerContainer.className = 'date-picker-container';
  datePickerContainer.innerHTML = `
    <div class="date-title-box">
      <h3 class="date-title">Choose Date</h3>
    </div>
    <div class="calendar-container">
      <div class="calendar-header">
        <button id="prev-month">&lt;</button>
        <div id="current-month"></div>
        <button id="next-month">&gt;</button>
      </div>
      <div class="weekdays">
        <div>Sun</div>
        <div>Mon</div>
        <div>Tue</div>
        <div>Wed</div>
        <div>Thu</div>
        <div>Fri</div>
        <div>Sat</div>
      </div>
      <div id="calendar-days" class="calendar-days"></div>
    </div>
    <div class="selected-date">No date selected</div>
  `;
  
  // Insert the date picker before the laps selector
  lapsContainer.parentNode.insertBefore(datePickerContainer, lapsContainer);

  // Add CSS for date picker
  const style = document.createElement('style');
  style.textContent = `
    .date-picker-container {
      margin-bottom: 20px;
      background-color: rgba(0, 0, 0, 0.7);
      border-radius: 10px;
      padding: 15px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
    
    .date-title-box {
      margin-bottom: 10px;
    }
    
    .date-title {
      font-size: 1.2rem;
      color: white;
      margin: 0;
      text-align: center;
    }
    
    .calendar-container {
      background-color: rgba(30, 30, 30, 0.8);
      border-radius: 8px;
      padding: 10px;
      margin-bottom: 10px;
    }
    
    .calendar-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }
    
    .calendar-header button {
      background-color: #e50000;
      color: white;
      border: none;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      cursor: pointer;
      font-weight: bold;
    }
    
    #current-month {
      color: white;
      font-weight: bold;
    }
    
    .weekdays {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      text-align: center;
      color: #999;
      font-size: 0.8rem;
      margin-bottom: 5px;
    }
    
    .calendar-days {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 5px;
    }
    
    .calendar-day {
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      cursor: pointer;
      color: white;
    }
    
    .calendar-day:hover:not(.inactive) {
      background-color: rgba(229, 0, 0, 0.5);
    }
    
    .inactive {
      color: #555;
      cursor: not-allowed;
    }
    
    .today {
      border: 1px solid #e50000;
    }
    
    .selected {
      background-color: #e50000;
      color: white;
    }
    
    .selected-date {
      text-align: center;
      color: white;
      margin-top: 10px;
      font-weight: bold;
    }
  `;
  document.head.appendChild(style);

  // Calendar functionality
  const calendarDays = document.getElementById('calendar-days');
  const currentMonthElement = document.getElementById('current-month');
  const prevMonthButton = document.getElementById('prev-month');
  const nextMonthButton = document.getElementById('next-month');
  const selectedDateElement = document.querySelector('.selected-date');
  
  let currentDate = new Date();
  let currentMonth = currentDate.getMonth();
  let currentYear = currentDate.getFullYear();

  function generateCalendar(month, year) {
    calendarDays.innerHTML = '';
    
    currentMonthElement.textContent = `${new Date(year, month).toLocaleString('default', { month: 'long' })} ${year}`;
    
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    
    // Previous month's days
    for (let i = 0; i < firstDay; i++) {
      const dayElement = document.createElement('div');
      dayElement.className = 'calendar-day inactive';
      calendarDays.appendChild(dayElement);
    }
    
    // Current month's days
    const today = new Date();
    for (let day = 1; day <= daysInMonth; day++) {
      const dayElement = document.createElement('div');
      dayElement.className = 'calendar-day';
      dayElement.textContent = day;
      
      const dateToCheck = new Date(year, month, day);
      
      // Check if this day is today
      if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
        dayElement.classList.add('today');
      }
      
      // Check if this day is in the past
      if (dateToCheck < new Date(today.getFullYear(), today.getMonth(), today.getDate())) {
        dayElement.classList.add('inactive');
      } else {
        dayElement.addEventListener('click', function() {
          document.querySelectorAll('.calendar-day').forEach(day => {
            day.classList.remove('selected');
          });
          this.classList.add('selected');
          
          selectedDate = new Date(year, month, day);
          selectedDateElement.textContent = selectedDate.toLocaleDateString();
          updateCartButtonState();
        });
      }
      
      calendarDays.appendChild(dayElement);
    }
  }

  prevMonthButton.addEventListener('click', function() {
    if (currentMonth === 0) {
      currentMonth = 11;
      currentYear--;
    } else {
      currentMonth--;
    }
    generateCalendar(currentMonth, currentYear);
  });
  
  nextMonthButton.addEventListener('click', function() {
    if (currentMonth === 11) {
      currentMonth = 0;
      currentYear++;
    } else {
      currentMonth++;
    }
    generateCalendar(currentMonth, currentYear);
  });
  
  // Initialize calendar
  generateCalendar(currentMonth, currentYear);

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
  
  // Function to fetch lap prices from the database
  function fetchLapPrices(carId) {
      fetch(`../get_car_prices.php?car_id=${carId}`)
          .then(response => response.json())
          .then(data => {
              if (data.error) {
                  console.error('Error fetching prices:', data.error);
                  return;
              }
              
              lapPrices = {
                  1: parseFloat(data.lap_price_1),
                  3: parseFloat(data.lap_price_3),
                  5: parseFloat(data.lap_price_5),
                  10: parseFloat(data.lap_price_10)
              };
              
              // Update prices on lap options
              lapOptions.forEach(option => {
                  const laps = parseInt(option.getAttribute('data-laps'));
                  const priceEl = option.querySelector('.lap-price');
                  if (priceEl && lapPrices[laps]) {
                      priceEl.textContent = `${(lapPrices[laps] / 1000).toFixed(0)}K`;
                  }
              });
              
              // Update display if laps are already selected
              if (selectedLaps) {
                  updateLapsDisplay();
              }
          })
          .catch(error => {
              console.error('Error:', error);
          });
  }

  function updateLapsDisplay() {  
      if (selectedLaps) {  
          selectedLapsElement.textContent = `${selectedLaps} ${selectedLaps === 1 ? 'Lap' : 'Laps'}`;  
          totalPriceElement.textContent = `Total: ${calculateTotal()}HUF`;  
      } else {  
          selectedLapsElement.textContent = 'Select laps';  
            
      }  
  }  

  function calculateTotal() {  
      if (selectedLaps === null || !lapPrices[selectedLaps]) return 0;
      // Use the price from the database
      return (lapPrices[selectedLaps]).toFixed(2);
  }  

  function updateCartButtonState() {  
      if (selectedTrack && selectedLaps && selectedDate) {  
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
      if (selectedTrack && selectedLaps && selectedDate) {  
          const formData = new FormData();  
          formData.append('car_id', carId);  
          formData.append('track_id', getTrackId(selectedTrack));  
          
          // Használjunk közvetlenül helyi időzóna szerinti dátumot
          const dateStr = `${selectedDate.getFullYear()}-${String(selectedDate.getMonth() + 1).padStart(2, '0')}-${String(selectedDate.getDate()).padStart(2, '0')}`;
          formData.append('date', dateStr);
          
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
                  selectedDate = null;
                  trackOptions.forEach(opt => opt.classList.remove('selected'));  
                  lapOptions.forEach(opt => opt.classList.remove('selected'));  
                  document.querySelectorAll('.calendar-day').forEach(day => {
                    day.classList.remove('selected');
                  });
                  selectedDateElement.textContent = 'No date selected';
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