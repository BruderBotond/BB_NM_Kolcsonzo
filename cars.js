document.addEventListener("DOMContentLoaded", function () {
    fetch("get_cars.php")  // 🔹 PHP API hívás
        .then(response => response.json())
        .then(cars => {
            const carList = document.querySelector(".car-list");
            carList.innerHTML = ""; // 🔹 Ürítjük a meglévő tartalmat

            cars.forEach(car => {
                const carCard = document.createElement("div");
                carCard.classList.add("car-card");

                carCard.innerHTML = `
                    <div class="specs">
                        <div class="spec-item">${car.hp}</div>
                        <div class="spec-item">${car.engine}</div>
                        <div class="spec-item">${car.top_speed}</div>
                        <div class="spec-item">${car.gears} gears</div>
                        <div class="spec-item">${car.drive}</div>
                        <div class="spec-item">0-100: ${car.acceleration}</div>
                    </div>
                    <div class="car-image">
                        <img src="${car.image_url}" alt="${car.model}">
                        <h2>${car.brand} ${car.model}</h2>
                    </div>
                    <div class="pricing">
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
                    </div>
                `;

                carList.appendChild(carCard);
            });
        })
        .catch(error => console.error("Error loading cars:", error));
});
