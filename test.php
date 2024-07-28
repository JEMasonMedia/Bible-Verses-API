<?php

// Function to fetch a random verse from the API
function fetchRandomVerse() {
    // $apiUrl = 'https://joelemason.com/bible_verses/Random_Verses_api.php?plain=true';
    $apiUrl = 'http://localhost:3000/verse';

    // Use file_get_contents to fetch the API response
    $response = file_get_contents($apiUrl);

    // Check if the response is false, indicating an error
    if ($response === false) {
        echo "Error: Unable to fetch the random verse.";
        return;
    }

    // Output the plain text response
    echo $response;
}

// Call the function to fetch and display the random verse
fetchRandomVerse();

?>
