<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Portal - Lost and Found</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Lost Items</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Timestamp Found</th>
                    <th>Reported By</th>
                </tr>
            </thead>
            <tbody id="userLostItemsTable"></tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            function fetchUserItems() {
                $.ajax({
                    url: "fetch-items.php",
                    type: "GET",
                    success: function (response) {
                        $("#userLostItemsTable").html(response);
                    }
                });
            }

            // Fetch items in real-time
            setInterval(fetchUserItems, 2000);
        });
    </script>
</body>
</html>
