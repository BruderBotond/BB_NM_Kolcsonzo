// Fetch brands data from the PHP API
async function fetchBrands() {
    try {
        const response = await fetch('../fetch_brands.php');
        const brands = await response.json();

        
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            const brandName = card.getAttribute('data-brand');
            const brand = brands.find(b => b.name === brandName);

            if (brand) {
                const imgElement = card.querySelector('.card-img-top');
                imgElement.src = brand.logo_url.replace(); // Remove extra quotes
            }
        });
    } catch (error) {
        console.error('Error fetching brands:', error);
    }
}


fetchBrands();



// Function to fetch cars by brand
async function fetchCarsByBrand(brandName) {
    try {
        const response = await fetch(`../fetch_cars_by_brand.php?brand=${encodeURIComponent(brandName)}`);
        
        // Check if the response is OK
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        // Check if the response contains an error
        if (data.error) {
            throw new Error(data.error);
        }

        // Get the car list container
        const carList = document.querySelector('.car-list');

        // Clear existing content
        carList.innerHTML = '';

        // Loop through the cars and create HTML elements
        data.forEach(car => {
            const carCard = document.createElement('div');
            carCard.className = 'car-card';

            // Car specifications
            const specs = document.createElement('div');
            specs.className = 'specs';
            specs.innerHTML = `
                <div class="spec-item">${car.hp}hp</div>
                <div class="spec-item">${car.engine}</div>
                <div class="spec-item">${car.top_speed}km/h</div>
                <div class="spec-item">${car.gears} gears</div>
                <div class="spec-item">${car.drive}</div>
                <div class="spec-item">0-100: ${car.acceleration}s</div>
            `;

            // Car image and name
            const carImage = document.createElement('div');
            carImage.className = 'car-image';
            const formattedModel = car.model.replace(/\s+/g, '-'); // Szóközök cseréje kötőjelekre
            carImage.innerHTML = `
                <a href="../HTML/${formattedModel}.html">
                    <img src="${car.image_url.replace('-')}" alt="${car.model}">
                </a>
                <h2>${brandName} ${car.model}</h2>
            `;

            // Pricing
            const pricing = document.createElement('div');
            pricing.className = 'pricing';
            pricing.innerHTML = `
                <h3>Prices:</h3>
                <div class="price-item">
                    <span>Rent:</span>
                    <span>From ${car.rent_price} HUF/day</span>
                </div>
                <div class="price-item">
                    <span>Track use:</span>
                    <span>${car.lap_price_1} HUF/1 lap</span>
                    <span>${car.lap_price_3} HUF/3 laps</span>
                    <span>${car.lap_price_5} HUF/5 laps</span>
                    <span>${car.lap_price_10} HUF/10 laps</span>
                </div>
            `;

            // Append elements to the car card
            carCard.appendChild(specs);
            carCard.appendChild(carImage);
            carCard.appendChild(pricing);

            // Append the car card to the car list
            carList.appendChild(carCard);
        });
    } catch (error) {
        console.error(`Error fetching ${brandName} cars:`, error);
        // Display error message to the user
        const carList = document.querySelector('.car-list');
        carList.innerHTML = `<div class="error">Error loading cars: ${error.message}</div>`;
    }
}

// Get the brand name from the page title or a data attribute
const brandName = document.querySelector('.brand-title').textContent.trim();

// Call the function to fetch and display cars for the specified brand
fetchCarsByBrand(brandName);