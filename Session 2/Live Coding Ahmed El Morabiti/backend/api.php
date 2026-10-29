<?php


$filePath = __DIR__ . "\data\games.json";


class API{

    public $filePath;

    public function __construct($fp)
    {
        $this->filePath = $fp;
    }

    public function getData(){
        $content = file_get_contents($this->filePath);
        $data = json_decode($content, true) ?? [];
        return $data;
    }

    public function recieveData(){
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true) ?? [];
        return $data;
    }

    public function appendData(){
        $newData = $this->getData();
        $newData[] = $this->recieveData();
        $jsonified = json_encode($newData);
        file_put_contents($this->filePath, $jsonified);
    }

}


$API = new API($filePath);

$data = $API->getdata();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $API-> appendData();
}



?>