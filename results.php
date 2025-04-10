<?php
$servername = "mysqlServer";
$username   = "fakeAirbnbUser";
$password   = "apples11Million";
$dbname     = "fakeAirbnb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$neighborhood_id = isset($_GET['neighborhood_id']) ? $_GET['neighborhood_id'] : '';
$roomTypeId      = isset($_GET['roomTypeId'])      ? $_GET['roomTypeId']      : '';
$guests          = isset($_GET['guests'])          ? (int)$_GET['guests']     : 1;

$query = "
    SELECT 
        l.*,
        n.neighborhood AS neighborhoodName,
        r.type AS roomTypeName
    FROM listings l
    LEFT JOIN neighborhoods n ON l.neighborhoodId = n.id
    LEFT JOIN roomTypes r     ON l.roomTypeId     = r.id
    WHERE 1 = 1
";

if (!empty($neighborhood_id)) {
    $escaped_nid = $conn->real_escape_string($neighborhood_id);
    $query      .= " AND l.neighborhoodId = '{$escaped_nid}'";
}

if (!empty($roomTypeId)) {
    $escaped_rid = $conn->real_escape_string($roomTypeId);
    $query      .= " AND l.roomTypeId = '{$escaped_rid}'";
}

$query .= " AND l.accommodates >= " . intval($guests);

$query .= " LIMIT 20";

$result = $conn->query($query);
$numResults = ($result) ? $result->num_rows : 0;

if (!empty($neighborhood_id)) {
    $sqlN = "SELECT neighborhood FROM neighborhoods WHERE id = " . (int)$neighborhood_id . " LIMIT 1";
    $resN = $conn->query($sqlN);
    if ($resN && $resN->num_rows > 0) {
        $rowN = $resN->fetch_assoc();
        $displayNeighborhood = htmlspecialchars($rowN['neighborhood']);
    } else {
        $displayNeighborhood = "Any";
    }
} else {
    $displayNeighborhood = "Any";
}

if (!empty($roomTypeId)) {
    $sqlR = "SELECT type FROM roomTypes WHERE id = " . (int)$roomTypeId . " LIMIT 1";
    $resR = $conn->query($sqlR);
    if ($resR && $resR->num_rows > 0) {
        $rowR = $resR->fetch_assoc();
        $displayRoomType = htmlspecialchars($rowR['type']);
    } else {
        $displayRoomType = "Any";
    }
} else {
    $displayRoomType = "Any";
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Fake Airbnb Results</title>
    <!-- Bootstrap + Icons -->
    <link rel="stylesheet" 
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" 
          rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" 
          crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
    <link rel="icon" href="images/house-heart-fill.svg">
    <link rel="mask-icon" href="images/house-heart-fill.svg" color="#000000">   
  </head>
  <body>
    
    <header>
      <div class="collapse bg-dark" id="navbarHeader">
        <div class="container">
          <div class="row">
            <div class="col-sm-8 col-md-7 py-4">
              <h4 class="text-white">About</h4>
              <p class="text-muted">Fake Airbnb. Data c/o http://insideairbnb.com/get-the-data/</p>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
          <a href="index.php" class="navbar-brand d-flex align-items-center">
            <i class="bi bi-house-heart-fill my-2"></i>    
            <strong> Fake Airbnb</strong>
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                  data-bs-target="#navbarHeader" aria-controls="navbarHeader"
                  aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </div>
    </header>

    <main class="py-5">
      <div class="container">
        
        <h1>Results (<?php echo $numResults; ?>)</h1>
        <p><strong>Neighborhood:</strong> <?php echo $displayNeighborhood; ?></p>
        <p><strong>Room Type:</strong> <?php echo $displayRoomType; ?></p>
        <p><strong>Accommodates:</strong> <?php echo $guests; ?></p>

        <?php if ($numResults > 0): ?>
          <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <?php
              $result->data_seek(0);

              while ($row = $result->fetch_assoc()) {
                  $listing_id     = htmlspecialchars($row['id']);
                  $image_url      = htmlspecialchars($row['pictureUrl']);    
                  $name           = htmlspecialchars($row['name']);
                  $price          = htmlspecialchars($row['price']);
                  $accommodates   = htmlspecialchars($row['accommodates']);
                  $rating         = htmlspecialchars($row['rating']);
                  $neighborhood   = htmlspecialchars($row['neighborhoodName']); 
                  $roomType       = htmlspecialchars($row['roomTypeName']);
            ?>
            <div class="col">
              <div class="card shadow-sm">
                <img src="<?php echo $image_url; ?>" class="card-img-top" alt="<?php echo $name; ?>">
                <div class="card-body">
                  <h5 class="card-title"><?php echo $name; ?></h5>
                  <p class="card-text"><?php echo $neighborhood; ?></p>
                  <p class="card-text"><?php echo $roomType; ?></p>
                  <p class="card-text">Accommodates <?php echo $accommodates; ?></p>
                  <p class="card-text">
                    <i class="bi bi-star-fill"></i> <?php echo $rating; ?>
                  </p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                      <button type="button" id="<?php echo $listing_id; ?>" 
                              class="btn btn-sm btn-outline-secondary viewListing" 
                              data-bs-toggle="modal" data-bs-target="#fakeAirbnbnModal">
                        View
                      </button>
                    </div>
                    <small class="text-muted">$<?php echo $price; ?></small>
                  </div>
                </div>
              </div><!-- .card -->
            </div><!-- .col -->
            <?php } // end while ?>
          </div><!-- .row -->

        <?php else: ?>
          <p>Sorry, no results - <a href="index.php">search again</a>.</p>
        <?php endif; ?>

      </div><!-- .container -->
    </main>

    <footer class="text-muted py-5">
      <div class="container">
        <p class="mb-1">CS 293, Spring 2025</p>
        <p class="mb-1">Lewis & Clark College</p>
      </div>
    </footer>

    <div class="modal fade modal-lg" id="fakeAirbnbnModal" tabindex="-1" 
         aria-labelledby="fakeAirbnbnModalLabel" aria-modal="true" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modal-title">Listing Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="modal-body">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS + dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" 
            crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" 
            integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" 
            crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
  </body>
</html>
<?php
$conn->close();
?>
