<?php

//including the database connection
require_once('conn.php');

//specifying content type
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // fetching user's order details
    $sql = "SELECT username, order_id, status, o.quantities FROM Users JOIN Cart ON user_id = user JOIN Orders o ON cart_id = cart";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $usercart = array();
        while($row = $result->fetch_assoc()) {
            $usercart[] = $row;
        }
        echo json_encode($usercart);
    } else {
        echo json_encode(array());
    }
}
?>