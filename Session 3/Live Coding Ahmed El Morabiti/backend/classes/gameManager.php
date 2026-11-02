<?php
require __DIR__ . "/games.php";

class gameManager{
    private static string $JSONfile = __DIR__ . "/../data/games.json";


    public function getall(){
        $rawData = file_get_contents(self::$JSONfile);
        $data = $rawData === null ? [] : json_decode($rawData, true);
        $games = [];

        foreach ($data as $game) {
            $games[] = new Game(
                $game['id'],
                $game['name'],
                $game['platform']
            );
        }
        return $games;
    }

    public function save(Game $game){
        $games = $this->getall();
        $games[] = $game;
        file_put_contents(self::$JSONfile, json_encode($games));
    }

    public function toarray(array $games){
        foreach($games as $game){
            $data = [
                $game->id,
                $game->name,
                $game->platform
            ];
            $game = $data;
        }
        return $games;
    }
}
?>