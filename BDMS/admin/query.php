<?php
include 'conn.php';
include 'session.php';

// Check if the user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        #sidebar { position: relative; margin-top: -20px; }
        #content { position: relative; margin-left: 210px; }
        @media screen and (max-width: 600px) {
            #content { position: relative; margin-left: auto; margin-right: auto; }
        }
        #he { font-size: 14px; font-weight: 600; text-transform: uppercase; padding: 3px 7px; color: #fff; text-decoration: none; border-radius: 3px; align: center; }
    </style>
</head>
<body style="color: black;">
<div id="header">
    <?php include 'header.php'; ?>
</div>
<div id="sidebar">
    <?php $active = "query"; include 'sidebar.php'; ?>
</div>
<div id="content">
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-title">User Query</h1>
                </div>
            </div>
            <hr>
            <?php
            $limit = 10;
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $limit;
            $count = $offset + 1;

            $sql = "SELECT * FROM contact_query LIMIT {$offset}, {$limit}";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
            ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Name</th>
                                <th>Email Id</th>
                                <th>Mobile Number</th>
                                <th>Message</th>
                                <th>Posting Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo htmlspecialchars($row['query_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['query_mail']); ?></td>
                                    <td><?php echo htmlspecialchars($row['query_number']); ?></td>
                                    <td><?php echo htmlspecialchars($row['query_message']); ?></td>
                                    <td><?php echo htmlspecialchars($row['query_date']); ?></td>
                                    <td>
                                        <?php if ($row['query_status'] == 1): ?>
                                            Read
                                        <?php else: ?>
                                            <a href="#" onclick="updateStatus(<?php echo $row['query_id']; ?>, this)">Pending</a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a style="background-color: aqua;" href="delete_query.php?id=<?php echo $row['query_id']; ?>">Delete</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php
                $sql1 = "SELECT COUNT(*) AS total FROM contact_query";
                $result1 = mysqli_query($conn, $sql1);
                $row = mysqli_fetch_assoc($result1);
                $total_records = $row['total'];
                $total_page = ceil($total_records / $limit);

                echo '<ul class="pagination">';
                if ($page > 1) {
                    echo '<li><a href="query.php?page=' . ($page - 1) . '">Prev</a></li>';
                }
                for ($i = 1; $i <= $total_page; $i++) {
                    $active = ($i == $page) ? "active" : "";
                    echo '<li class="' . $active . '"><a href="query.php?page=' . $i . '">' . $i . '</a></li>';
                }
                if ($total_page > $page) {
                    echo '<li><a href="query.php?page=' . ($page + 1) . '">Next</a></li>';
                }
                echo '</ul>';
            } else {
                echo '<div class="alert alert-info">No queries found.</div>';
            }
            ?>
        </div>
    </div>
</div>

<script>
function updateStatus(id, element) {
    if (confirm("Do you really want to mark this query as read?")) {
        $(element).addClass('disabled').text('Processing...'); // Disable the link and change text
        $.ajax({
            type: "POST",
            url: "update_status.php",
            data: { id: id },
            success: function(response) {
                if (response == 'success') {
                    alert("Status updated successfully.");
                    window.location.reload(); // Refresh the page
                } else {
                    alert("Failed to update status.");
                    $(element).removeClass('disabled').text('Pending'); // Enable the link and revert text
                }
            }
        });
    }
}
</script>

</body>
</html>
<?php
} else {
    echo '<div class="alert alert-danger">Please login first to access the admin portal.</div>';
}
?>