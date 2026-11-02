<?php
class Game{
    public int $id;
    public string $name;
    public string $platform;


    public function __construct(int $id, string $name, string $platform){
        $this->id = $id;
        $this->name = $name;
        $this->platform = $platform;
    }
}
?>