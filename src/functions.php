<?php
// functions.php

function getDbConnection() {
    $servername = "mysqlServer";
    $username   = "fakeAirbnbUser";
    $password   = "apples11Million";
    $dbname     = "fakeAirbnb";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function getListingDetails($listing_id) {
    $conn = getDbConnection();

    // Adjust the query and column names based on your actual schema.
    $query = "
      SELECT 
        l.id,
        l.name,
        l.pictureUrl AS image_url, 
        l.price,
        l.accommodates,
        l.rating,
        n.neighborhood,
        r.type AS roomType,
        l.description  -- Optional: if you have a description field
      FROM listings l
      LEFT JOIN neighborhoods n ON l.neighborhoodId = n.id
      LEFT JOIN roomTypes r ON l.roomTypeId = r.id
      WHERE l.id = " . intval($listing_id) . " LIMIT 1";

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $conn->close();
        return $row;
    } else {
        $conn->close();
        return false;
    }
}
?>
