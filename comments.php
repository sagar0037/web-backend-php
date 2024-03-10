<?php

//including the database connection
require_once('conn.php');

//specifying content type
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

//handling POST Request (create new comment)
if ($method === 'POST') {
    //decoding JSON data from request data
    $data = json_decode(file_get_contents('php://input'), true);

    //extracting data from decoded data
    $product = $data['product']; 
    $user = $data['user'];
    $rating = $data['rating'];
    $image = $data['image'];
    $text = $data['text'];

    //inserting comment data
    $sql = "INSERT INTO Comments (product, user, rating, image, text) VALUES ($product, $user, $rating, '$image', '$text')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "New comment created successfully"));
    } else {
        echo json_encode(array("message" => "Error: " . $conn->error));
    }
}

//handling GET Request (retrieve existing Comments)
elseif ($method === 'GET') {
    // fetching Comments
    $sql = "SELECT * FROM Comments";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $comments = array();
        while($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        echo json_encode($comments);
    } else {
        echo json_encode(array());
    }
}

//handling PUT Request (updating data from existing comment)
elseif ($method === 'PUT') {
    //decoding JSON data from request data
    $data = json_decode(file_get_contents("php://input"), true);

    //extracting data from decoded data
    $comment_id = $data['comment_id'];
    $rating = $data['rating'];
    $image = $data['image'];
    $text = $data['text'];

    $sql = "UPDATE Comments SET rating=$rating, image='$image', text='$text' WHERE comment_id=$comment_id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "Comment is updated successfully"));
    } else {
        echo json_encode(array("message" => "Error: " . $conn->error));
    }
}

//handling DELETE Request (deleting a comment)
elseif ($method === 'DELETE') {
    //decoding JSON data from request data
    $data = json_decode(file_get_contents("php://input"), true);

    //extracting comment_id from decoded data
    $comment_id = $data['comment_id'];

    //deleting a comment
    $sql = "DELETE FROM Comments WHERE comment_id=$comment_id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "Comment data is deleted successfully"));
    } else {
        echo json_encode(array("message" => "Error: " . $conn->error));
    }
}

//handling other requests
else{
    http_response_code(405);
    echo json_encode(array("message" => "Unsupported HTTP method"));
    exit;

}

//closing database connection
$conn->close();

?>
