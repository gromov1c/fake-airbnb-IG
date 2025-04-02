<?php
header('Content-Type: application/json');

include_once 'functions.php';

if (isset($_GET['listing_id']) && !empty($_GET['listing_id'])) {
    $listing_id = intval($_GET['listing_id']);

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
