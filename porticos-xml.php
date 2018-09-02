<?php
// If you installed via composer, just use this code to requrie autoloader on the top of your projects.
require 'Medoo.php';
$value=$_GET['value'];

 
// Using Medoo namespace
use Medoo\Medoo;
//if ($_SERVER['HTTP_REFERER']=='http://190.105.239.31/~infoautopista/'){  //validación del servidor
$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

 
// Initialize
$database = new Medoo([
    'database_type' => 'mysql',
    //'database_name' => 'infoauto_db',
    'database_name' => 'infoautopistas',
    'server' => 'localhost',
    'username' => 'root',
    //'username' => 'infoauto_usr',
    'password' => '',
    //'username' => 'infoauto_usr',
    'charset' => 'utf8'
]);
 
// Enjoy
$data = $database->select("porticos",
    [
        "[><]autopistas" => ["autopista_id" => "id", "autopista_tramo" => "tramo"]
    ]
    ,[
        "porticos.id",
        "porticos.nombre",
        "porticos.lat",
        "porticos.long",
        "porticos.sentido",
        "autopistas.nombre (autopista)"
    ]
    ,["autopista_id" => $value]
);
 

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each

for ($i=0; $i < count($data); $i++){
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  //$newnode->setAttribute("id",$data[$i]['id']);
  $newnode->setAttribute("nombre",$data[$i]['nombre']);
  $newnode->setAttribute("autopista", $data[$i]['autopista']);
  $newnode->setAttribute("lat", $data[$i]['lat']);
  $newnode->setAttribute("lng", $data[$i]['long']);
  $newnode->setAttribute("sentido", $data[$i]['sentido']);
  //$newnode->setAttribute("type", "restaurant");

}

echo $dom->saveXML();
//}  //validación del servidor
?>