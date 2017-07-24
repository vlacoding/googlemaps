<?php

require("db.php");

// Start XML file, create parent node

$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// Открывает соединение с сервером БД

$connection=mysql_connect ('localhost', $username, $password);
if (!$connection) {  die('Not connected : ' . mysql_error());}

// Выбирает бд

$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysql_error());
}

// Устанавливаем кодировку
$encode = "SET NAMES 'utf8';";
$encoder = mysql_query($encode);
if (!$encoder) {
  die('Cannot encode to utf8: ' . mysql_error());
}

// Берем все данные

$query = "SELECT * FROM markers WHERE 1";
$result = mysql_query($query);
if (!$result) {
  die('Invalid query: ' . mysql_error());
}

header("Content-type: text/xml");

// Последовательно добавляем данные из строк, вставляя их в XML-элементы

while ($row = @mysql_fetch_assoc($result)){
  // Добавляем с атрибутами
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("name",$row['name']);
  $newnode->setAttribute("address", $row['address']);
  $newnode->setAttribute("lat", $row['lat']);
  $newnode->setAttribute("lng", $row['lng']);
  $newnode->setAttribute("type", $row['type']);
  $newnode->setAttribute("content", $row['content']);
}

echo $dom->saveXML();

?>
