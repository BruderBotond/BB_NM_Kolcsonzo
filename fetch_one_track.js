// Function to fetch track data
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
        trackMapImage.src = track.image_url.replace(/"/g, '');
    } catch (error) {
        console.error(`Error fetching track data:`, error);
        // Display error message to the user
        const trackInfo = document.querySelector('.track-info');
        trackInfo.innerHTML = `<div class="error">Error loading track data: ${error.message}</div>`;
    }
}

// Get the track name from the page title or a data attribute
const trackName = document.querySelector('.main-title').textContent.trim();

// Call the function to fetch and display track data
fetchTrackData(trackName);