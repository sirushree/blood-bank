<?php
include 'conn.php';

// Check if query_id is set in the URL
if(isset($_GET['id'])){
    $que_id = $_GET['id'];

    // Prepare the SQL statement
    $sql = "DELETE FROM contact_query WHERE query_id=?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the query_id parameter
    mysqli_stmt_bind_param($stmt, "i", $que_id);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Close the statement
    mysqli_stmt_close($stmt);

    // Close the connection
    mysqli_close($conn);

    // Redirect back to the previous page
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}
?>