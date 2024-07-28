<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Read the JSON file
$json = file_get_contents('verses.json');
$verses = json_decode($json, true);

// Check if verses were successfully decoded
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["error" => "Failed to decode JSON"]);
    exit();
}

// Function to get a random verse
function getRandomVerse($verses) {
    $min = 0;
    $max = count($verses) - 1;

    // Ensure $max is not less than $min
    if ($max < $min) {
        return null; // or handle the error as appropriate
    }

    // Get a random index using betterRandom
    $randomIndex = BetterRandom($min, $max, 10000);
    return $verses[$randomIndex];
}

// Function to get a verse by ID
function getVerseById($verses, $id) {
    foreach ($verses as $verse) {
        if ($verse['id'] == $id) {
            return $verse;
        }
    }
    return null;
}

// Function to get a verse by book, chapter, and verse
function getVerseByReference($verses, $book, $chapter, $verseNumber) {
    foreach ($verses as $verse) {
        if ($verse['book'] === $book && $verse['chapter'] == $chapter && $verse['verse'] == $verseNumber) {
            return $verse;
        }
    }
    return null;
}

// Check for all verses request
if (isset($_GET['all']) && $_GET['all'] === 'true') {
    echo json_encode($verses);
    exit();
}

// Determine which function to call based on query parameters
if (isset($_GET['format']) && $_GET['format'] === 'plain') {
    // Plain text format
    if (isset($_GET['id'])) {
        // Get verse by ID
        $id = intval($_GET['id']);
        $verse = getVerseById($verses, $id);
        if ($verse) {
            echo $verse['id'] . PHP_EOL .
                 $verse['book'] . PHP_EOL .
                 $verse['chapter'] . PHP_EOL .
                 $verse['verse'] . PHP_EOL .
                 $verse['text'];
        } else {
            echo "Verse with ID $id not found";
        }
    } elseif (isset($_GET['book']) && isset($_GET['chapter']) && isset($_GET['verse'])) {
        // Get verse by book, chapter, and verse
        $book = $_GET['book'];
        $chapter = intval($_GET['chapter']);
        $verseNumber = intval($_GET['verse']);
        $verse = getVerseByReference($verses, $book, $chapter, $verseNumber);
        if ($verse) {
            echo $verse['id'] . PHP_EOL .
                 $verse['book'] . PHP_EOL .
                 $verse['chapter'] . PHP_EOL .
                 $verse['verse'] . PHP_EOL .
                 $verse['text'];
        } else {
            echo "Verse not found for $book $chapter:$verseNumber";
        }
    } else {
        // Get random verse
        $verse = getRandomVerse($verses);
        echo $verse['id'] . PHP_EOL .
             $verse['book'] . PHP_EOL .
             $verse['chapter'] . PHP_EOL .
             $verse['verse'] . PHP_EOL .
             $verse['text'];
    }
} else {
    // Default to JSON format
    if (isset($_GET['id'])) {
        // Get verse by ID
        $id = intval($_GET['id']);
        $verse = getVerseById($verses, $id);
        if ($verse) {
            echo json_encode($verse);
        } else {
            echo json_encode(["error" => "Verse with ID $id not found"]);
        }
    } elseif (isset($_GET['book']) && isset($_GET['chapter']) && isset($_GET['verse'])) {
        // Get verse by book, chapter, and verse
        $book = $_GET['book'];
        $chapter = intval($_GET['chapter']);
        $verseNumber = intval($_GET['verse']);
        $verse = getVerseByReference($verses, $book, $chapter, $verseNumber);
        if ($verse) {
            echo json_encode($verse);
        } else {
            echo json_encode(["error" => "Verse not found for $book $chapter:$verseNumber"]);
        }
    } else {
        // Get random verse
        echo json_encode(getRandomVerse($verses));
    }
}

function cryptoRandomNumberInRange($min, $max) {
    $range = $max - $min + 1;
    $randomBytes = random_bytes(4); // Get 4 bytes of random data
    $randomNumber = unpack('L', $randomBytes)[1]; // Unpack as an unsigned long integer
    $randomNumber = $randomNumber / (0xFFFFFFFF + 1); // Normalize to [0, 1) range
    return floor($randomNumber * $range) + $min;
}

function betterRandom($min, $max, $depth = 1000) {
    $accumulator = [];

    for ($i = 0; $i < $depth; $i++) {
        $accumulator[] = cryptoRandomNumberInRange($min, $max);
    }

    $randomIndex = cryptoRandomNumberInRange(0, count($accumulator) - 1);
    return $accumulator[$randomIndex];
}

?>
