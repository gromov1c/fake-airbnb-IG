$(document).ready(function () {
    $('.viewListing').click(function () {
      var listingId = $(this).attr('id');
  
      $.ajax({
        url: 'src/ajax.php',
        type: 'GET',
        data: { listing_id: listingId },
        dataType: 'json',
        success: function (response) {
          // Check for an error in the response
          if (response.error) {
            console.error('Error: ' + response.error);
            $('#modal-title').text("Error");
            $('#modal-body').html("<p>Unable to load listing details.</p>");
            return;
          }
  
          $('#modal-title').text(response.name);
  
          var modalContent = `
            <div class="container-fluid">
              <div class="row">
                <div class="col-12">
                  <img src="${response.image_url}" class="img-fluid mb-3" alt="${response.name}">
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <p><strong>Neighborhood:</strong> ${response.neighborhood}</p>
                  <p><strong>Room Type:</strong> ${response.roomType}</p>
                  <p><strong>Accommodates:</strong> ${response.accommodates}</p>
                  <p><strong>Rating:</strong> ${response.rating}</p>
                  <p><strong>Price:</strong> $${response.price}</p>
                  ${response.description ? `<p><strong>Description:</strong> ${response.description}</p>` : ''}
                </div>
              </div>
            </div>
          `;
  
          $('#modal-body').html(modalContent);
        },
        error: function (xhr, status, error) {
          console.error('AJAX Error: ' + status + ', ' + error);
        }
      });
    });
  });
  