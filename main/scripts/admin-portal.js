// Tab Switching JavaScript
$(document).ready(function() {
  $('#dashboard-tab').click(function() {
      $('#dashboard').show();
      $('#lost-items').hide();
      $('#report-log').hide();
      $('.nav-link').removeClass('active');
      $(this).addClass('active');
  });

  $('#lost-items-tab').click(function() {
      $('#dashboard').hide();
      $('#lost-items').show();
      $('#report-log').hide();
      $('.nav-link').removeClass('active');
      $(this).addClass('active');
  });

  $('#report-log-tab').click(function() {
      $('#dashboard').hide();
      $('#lost-items').hide();
      $('#report-log').show();
      $('.nav-link').removeClass('active');
      $(this).addClass('active');
  });
});

  
// Get all claim buttons
    var claimButtons = document.querySelectorAll('button[data-bs-toggle="modal"]');
    claimButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var itemId = this.getAttribute('data-id');
            document.getElementById('itemId').value = itemId;
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('.table img');
        const previewModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
        const previewImage = document.getElementById('previewImage');
    
        images.forEach(img => {
            img.addEventListener('click', function(e) {
                e.preventDefault();
                previewImage.src = this.src;
                previewModal.show();
            });
        });
    });


    
