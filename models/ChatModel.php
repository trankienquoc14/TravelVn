<?php
class ChatModel {

private $conn;

public function __construct($db){
    $this->conn=$db;
}

public function saveMessage($data){

$sql="
INSERT INTO chat_messages(
session_id,
message,
message_type,
file_url,
latitude,
longitude,
sender_type
)

VALUES(?,?,?,?,?,?,?)
";

$stmt=$this->conn->prepare($sql);

$stmt->execute([

$data['session_id'],
$data['message'],
$data['message_type'],
$data['file_url'],
$data['latitude'],
$data['longitude'],
$data['sender_type']

]);

return $this->conn->lastInsertId();

}

}
?>