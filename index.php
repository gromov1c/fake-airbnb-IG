<?php
// 1. Connect to the database (update these parameters as needed)
$servername = "mysqlServer";
$username = "fakeAirbnbUser";
$password = "apples11Million";
$dbname = "fakeAirbnb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Query the neighborhoods table (alphabetical by neighborhood name)
$neighborhood_sql = "SELECT id, neighborhood 
                     FROM neighborhoods 
                     ORDER BY neighborhood ASC";
$neighborhood_result = $conn->query($neighborhood_sql);

// 3. Query the listings table for distinct roomTypeId (if that’s how you store room types)
$roomtype_sql = "SELECT DISTINCT roomTypeId 
                 FROM listings 
                 WHERE roomTypeId IS NOT NULL
                 ORDER BY roomTypeId ASC";
$roomtype_result = $conn->query($roomtype_sql);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fake Airbnb</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
          crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
            <strong>Fake Airbnb</strong>
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                  data-bs-target="#navbarHeader" aria-controls="navbarHeader"
                  aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </div>
    </header>

    <main>
      <div class="album py-5 bg-light">
        <div class="container">
          <h1>Find a Listing</h1>
          <!-- Search Form -->
          <form action="results.php" method="GET">
            
            <!-- Neighborhood (from neighborhoods table) -->
            <div class="mb-3">
              <label for="neighborhood" class="form-label">Neighborhood</label>
              <select class="form-select" id="neighborhood" name="neighborhood_id">
                <option value="">Any</option>
                <?php
                  if ($neighborhood_result && $neighborhood_result->num_rows > 0) {
                    while ($row = $neighborhood_result->fetch_assoc()) {
                      $id   = htmlspecialchars($row['id']);
                      $name = htmlspecialchars($row['neighborhood']);
                      echo "<option value=\"$id\">$name</option>";
                    }
                  } else {
                    echo '<option value="">No neighborhoods available</option>';
                  }
                ?>
              </select>
            </div>

            <!-- Room Type (from listings.roomTypeId) -->
            <div class="mb-3">
              <label for="roomTypeId" class="form-label">Room Type</label>
              <select class="form-select" id="roomTypeId" name="roomTypeId">
                <option value="">Any</option>
                <?php
                  if ($roomtype_result && $roomtype_result->num_rows > 0) {
                    while ($row = $roomtype_result->fetch_assoc()) {
                      $value = htmlspecialchars($row['roomTypeId']);
                      echo "<option value=\"$value\">Room Type #$value</option>";
                    }
                  } else {
                    echo '<option value="">No room types available</option>';
                  }
                ?>
              </select>
            </div>

            <!-- Number of Guests -->
            <div class="mb-3">
              <label for="guests" class="form-label">Number of Guests</label>
              <select class="form-select" id="guests" name="guests">
                <?php
                  // Just show 1-10 in a dropdown
                  foreach (range(1, 10) as $num) {
                    echo "<option value=\"$num\">$num</option>";
                  }
                ?>
              </select>
            </div>

            <button type="submit" class="btn btn-primary">Search Listings</button>
          </form>
        </div><!-- .container-->
      </div><!-- .album-->
    </main>

    <footer class="text-muted py-5">
      <div class="container">
        <p class="mb-1">CS 293, Spring 2025</p>
        <p class="mb-1">Lewis & Clark College</p>
      </div><!-- .container-->
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
            integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="
            crossorigin="anonymous"></script>
  </body>
</html>
<?php
// Close the database connection
$conn->close();
?>
