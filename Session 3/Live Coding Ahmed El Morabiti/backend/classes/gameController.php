<?php
require __DIR__ . "/gameManager.php";

class gameController{
    private gameManager $manager;

    public function __construct()
    {
        $this->manager = new gameManager();
    }

    public function handle(){
        if($_SERVER['REQUEST_METHOD'] === "GET"){
            echo json_encode($this->manager->toarray($this->manager->getall()));
        }

        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $rawNew = file_get_contents("php://input");
            $new = json_decode($rawNew, true);
            $new['id'] = $this->manager->getall() === [] ? 1 : max(array_column($this->manager->getall(), "id")) + 1;
            $game = new Game($new['id'], $new['name'], $new['platform']);
            $this->manager->save($game);
        }
    }
}

?>