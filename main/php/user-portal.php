<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OCC Lost and Found System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/user-portal.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="top-navigation">
        <div class="nav-logo">
            <img src="../img/LAF-LOGO-2.png" alt="OCC Logo">
        </div>
        <div class="nav-actions">
            <button class="chat-btn" title="Open Chat" onclick="location.href='chat-user.php'">
                <i class="fas fa-comment-dots"></i>
            </button>
            <button class="logout-btn" title="Logout" onclick="location.href='login-user.php'">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>
    </nav>

    <div class="hero-banner">
        <div class="hero-overlay"></div>
        <div class="hero-content animate-fade-in">
            <h1>OCC LOST AND FOUND SYSTEM</h1>

            <!-- Update the search container in the existing HTML -->
                <div class="search-container">
                    <div class="search-box">
                        <input type="text" id="searchBar" placeholder="Search for lost items..."
                            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="button" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="search-suggestions" id="searchSuggestions">
                        <!-- Suggestions will be dynamically populated -->
                    </div>
                </div>

        </div>
    </div>

    <div class="table-container animate-fade-up">
        <table class="items-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Date Found</th>
                    <th>Reported By</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="userLostItemsTable">
                <!-- Table content will be loaded dynamically -->
            </tbody>
        </table>
    </div>

    <!-- Modal for image preview -->
        <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imagePreviewModalLabel">Image Preview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="previewImage" src="" alt="Preview" style="max-width: 100%; height: auto;">
                    </div>
                </div>
            </div>
        </div>

    <script>
        $(document).ready(function () {
    // Function to fetch items
    function fetchUserItems(searchQuery = '') {
        $.ajax({
            url: "fetch-items.php",
            type: "GET",
            data: { search: searchQuery },
            success: function (response) {
                $("#userLostItemsTable").html(response);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching items:", error);
            }
        });
    }

     // Function to handle image preview
     $(document).ready(function() {
        $(document).on('click', '.preview-image', function() {
            const imageSrc = $(this).attr('src');
            $('#previewImage').attr('src', imageSrc);
            new bootstrap.Modal(document.getElementById('imagePreviewModal')).show();
        });
    });

    // Debounce function to limit API calls
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Debounced search function
    const debouncedFetch = debounce(function(value) {
        fetchUserItems(value);
    }, 300);

    // Trigger fetch on search bar input with debounce
    $("#searchBar").on("input", function () {
        debouncedFetch($(this).val());
    });

    // Real-time polling mechanism
    function startPolling() {
        setInterval(function () {
            const searchQuery = $("#searchBar").val().trim();
            fetchUserItems(searchQuery);
        }, 7000); // Adjust the interval as needed (e.g., every 5 seconds)
    }

    // Initial fetch to load items
    fetchUserItems();
    startPolling();

});


        //This function is for customized search bar
        $(document).ready(function () {
            const $searchBar = $("#searchBar");
            const $searchSuggestions = $("#searchSuggestions");
            const $searchResetBtn = $('<button>', {
                class: 'search-reset-btn',
                html: '&times;',
                click: function() {
                    $searchBar.val('');
                    $(this).hide();
                    fetchUserItems();
                }
            });

            // Append reset button to search box
            $('.search-box').append($searchResetBtn);

            // Search suggestion data
            const searchSuggestionData = [
                'Wallet', 'Keys', 'Backpack', 'Laptop', 
                'Phone', 'Water Bottle', 'Calculator', 'USB Drive'
            ];

    // Typing animation function
    function typeSearchPlaceholder() {
        let currentIndex = 0;
        const typingSpeed = 100;
        const pauseBetween = 2000;

        function type() {
            const placeholder = searchSuggestionData[currentIndex];
            let charIndex = 0;

            function typeChar() {
                $searchBar.attr('placeholder', placeholder.slice(0, charIndex + 1));
                charIndex++;

                if (charIndex < placeholder.length) {
                    setTimeout(typeChar, typingSpeed);
                } else {
                    // Wait before deleting
                    setTimeout(deleteText, pauseBetween);
                }
            }

            function deleteText() {
                function removeChar() {
                    $searchBar.attr('placeholder', placeholder.slice(0, charIndex - 1));
                    charIndex--;

                    if (charIndex > 0) {
                        setTimeout(removeChar, typingSpeed / 2);
                    } else {
                        // Move to next suggestion
                        currentIndex = (currentIndex + 1) % searchSuggestionData.length;
                        setTimeout(type, typingSpeed);
                    }
                }
                removeChar();
            }

            typeChar();
        }

        type();
    }

    // Start typing animation
    typeSearchPlaceholder();

    // Show/hide reset button based on input
    $searchBar.on('input', function() {
        const hasValue = $(this).val().trim() !== '';
        $('.search-reset-btn').toggle(hasValue);
    });

    // Rest of your existing search suggestion code...
    function showSearchSuggestions(query) {
        $searchSuggestions.empty();
        
        const filteredSuggestions = searchSuggestionData.filter(item => 
            item.toLowerCase().includes(query.toLowerCase())
        );

        if (filteredSuggestions.length > 0) {
            filteredSuggestions.forEach(suggestion => {
                $('<div>')
                    .text(suggestion)
                    .on('click', function() {
                        $searchBar.val(suggestion);
                        $searchSuggestions.removeClass('visible');
                        $('.search-reset-btn').show();
                        fetchUserItems(suggestion);
                    })
                    .appendTo($searchSuggestions);
            });
            
            setTimeout(() => {
                $searchSuggestions.addClass('visible');
            }, 50);
        } else {
            $searchSuggestions.removeClass('visible');
        }
    }

    // Event listener for search input
    $searchBar.on('input', function() {
        const query = $(this).val();
        if (query.length > 1) {
            showSearchSuggestions(query);
        } else {
            $searchSuggestions.removeClass('visible');
        }
    });

    // Close suggestions when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-container').length) {
            $searchSuggestions.removeClass('visible');
        }
    });

    // Prevent form submission
    $searchBar.closest('form').on('submit', function(e) {
        e.preventDefault();
    });
});
    </script>
</body>
</html>

