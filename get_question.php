<?php
include("config.php");

header("Content-Type: application/json; charset=UTF-8");

$sql = "SELECT id, question, option_a, option_b, option_c, option_d, correct_option FROM quiz_questions";
$result = $conn->query($sql);

$questions = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
    echo json_encode($questions);
} else {
    echo json_encode(array("message" => "No questions found"));
}

$conn->close();
?>
