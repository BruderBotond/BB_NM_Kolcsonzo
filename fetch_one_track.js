async function fetchTrackData(trackName) {
    try {
        const response = await fetch(`../fetch_one_track.php?track=${encodeURIComponent(trackName)}`);
        
        // Check if the response is OK
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const track = await response.json();

        // Check if the response contains an error
        if (track.error) {
            throw new Error(track.error);
        }

        // Update the track map image
        const trackMapImage = document.querySelector('.map-image');
        if (trackMapImage) {
            trackMapImage.src = track.image_url.replace(/"/g, '');
        }

        // Update gallery images
        if (track.gallery && track.gallery.length > 0) {
            updateGalleryImages(track.gallery);
        }
    } catch (error) {
        console.error(`Error fetching track data:`, error);
        // Display error message to the user
        const trackInfo = document.querySelector('.track-info');
        if (trackInfo) {
            trackInfo.innerHTML = `<div class="error">Error loading track data: ${error.message}</div>`;
        }
    }
}

// Function to update gallery images
function updateGalleryImages(galleryUrls) {
    const carouselInner = document.querySelector('#carouselExampleFade .carousel-inner');
    
    if (!carouselInner) {
        console.error('Gallery carousel not found');
        return;
    }
    
    // Clear existing items
    carouselInner.innerHTML = '';
    
    // Add gallery images to carousel
    galleryUrls.forEach((url, index) => {
        const isActive = index === 0 ? 'active' : '';
        const item = document.createElement('div');
        item.className = `carousel-item ${isActive}`;
        
        const img = document.createElement('img');
        img.src = url;
        img.className = 'd-block w-100';
        img.alt = `Gallery Image ${index + 1}`;
        
        item.appendChild(img);
        carouselInner.appendChild(item);
    });
}

// Get the track name from the page title or a data attribute
const trackName = document.querySelector('.main-title').textContent.trim();

// Call the function to fetch and display track data
fetchTrackData(trackName);