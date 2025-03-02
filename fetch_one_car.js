async function fetchCarData(carModel) {
    try {
        const response = await fetch(`../fetch_one_car.php?model=${encodeURIComponent(carModel)}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const car = await response.json();

        if (car.error) {
            throw new Error(car.error);
        }

        // Ellenőrizd, hogy az image_url létezik és nem üres
        if (!car.image_url) {
            throw new Error("image_url is missing or empty in the response");
        }

        // Konzolba írd ki a választ hibakereséshez
        console.log("Server response:", car);

        document.querySelector('.car-title').textContent = `${car.model}`;

        const imageUrls = car.image_url.split(', '); 

        // Ellenőrizd, hogy van-e második URL, ha nincs, használj alapértelmezett képet
        const secondImageUrl = imageUrls.length >= 2 ? imageUrls[1].replace(/"/g, '') : 'default-image.jpg';

        const carImage = document.querySelector('.car-image');
        carImage.src = secondImageUrl;

        const specs = document.querySelectorAll('.spec-value');
        specs[1].textContent = car.drive; 
        specs[2].textContent = `${car.top_speed} km/h`; 
        specs[3].textContent = `${car.hp}hp`; 
        specs[4].textContent = car.engine; 
        specs[5].textContent = `${car.acceleration}s`; 
        specs[6].textContent = `${car.gears} gear`; 
    } catch (error) {
        console.error(`Error fetching car data:`, error);
        const carShowcase = document.querySelector('.car-showcase');
        carShowcase.innerHTML = `<div class="error">Error loading car data: ${error.message}</div>`;
    }
}

const carModel = document.querySelector('.car-title').textContent.trim().split(' ').slice(1).join(' ');
fetchCarData(carModel);