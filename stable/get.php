<?php

require("db.php");

// XML

$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// Открывает соединение с сервером БД

$connection=mysql_connect ('localhost', $username, $password);
if (!$connection) {  die('Не соединено : ' . mysql_error());}

// Выбирает бд

$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die ('Нельзя использовать БД : ' . mysql_error());
}

// Устанавливаем кодировку
$encode = "SET NAMES 'utf8';";
$encoder = mysql_query($encode);
if (!$encoder) {
  die('Невозможно задать utf8: ' . mysql_error());
}

// Берем все данные

$query = "SELECT * FROM ins_con_places WHERE 1";
$result = mysql_query($query);
if (!$result) {
  die('Неверный запрос: ' . mysql_error());
}

header("Content-type: text/xml");

// Последовательно добавляем данные из строк, вставляя их в XML-элементы

while ($row = @mysql_fetch_assoc($result)){
  // Добавляем с атрибутами
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("title",$row['title']);
  $newnode->setAttribute("mapdesc", $row['mapdesc']);
  $newnode->setAttribute("lat", $row['lat']);
  $newnode->setAttribute("lng", $row['lng']);
  $newnode->setAttribute("category_id", $row['category_id']);
  $newnode->setAttribute("slug", $row['slug']);

}

echo $dom->saveXML();

?>
