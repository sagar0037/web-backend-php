<?php

//including the database connection
require_once('conn.php');

//specifying content type
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // fetching comments with username
    $sql = "SELECT username, text, description FROM Users JOIN Comments ON user_id = user JOIN Product ON product_id = product";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $usercomments = array();
        while($row = $result->fetch_assoc()) {
            $usercomments[] = $row;
        }
        echo json_encode($usercomments);
    } else {
        echo json_encode(array());
    }
}
?>