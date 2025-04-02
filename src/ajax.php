<?php
// Set the header to return JSON
header('Content-Type: application/json');

// Include functions.php which contains the database connection and helper functions
include_once 'functions.php';

// Check if a listing_id is provided
if (isset($_GET['listing_id']) && !empty($_GET['listing_id'])) {
    $listing_id = intval($_GET['listing_id']);

    // Retrieve the listing details (this function should be defined in functions.php)
    $listingDetails = getListingDetails($listing_id);

    if ($listingDetails) {
        echo json_encode($listingDetails);
    } else {
        echo json_encode(['error' => 'Listing not found.']);
    }
} else {
    echo json_encode(['error' => 'No listing id provided.']);
}
?>
