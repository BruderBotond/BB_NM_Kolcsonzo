// Fetch tracks data from the PHP API
async function fetchTracks() {
    try {
        const response = await fetch('../fetch_tracks.php');
        const tracks = await response.json();

        
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            const track = tracks[index]; 

            if (track) {
                const imgElement = card.querySelector('.card-img-top');
                imgElement.src = track.image_url.replace(); 
                imgElement.alt = track.name; 
            }
        });
    } catch (error) {
        console.error('Error fetching tracks:', error);
    }
}

// Call the function to fetch and update tracks
fetchTracks();