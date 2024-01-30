<?php

require_once 'AppController.php';
require_once __DIR__ . './../models/Set.php';
require_once __DIR__ . './../models/Word.php';
require_once __DIR__ . './../repository/SetRepository.php';

class SetController extends AppController {

    private $setRepository;

    public function __construct() {
        parent::__construct();
        $this->setRepository = new SetRepository();
    }


    public function mainPage() {
        $sets = $this->setRepository->getSets();
        return $this->render('mainPage', ['sets' => $sets]);
    }


    public function myAccount() {
        if (!$this->isLoggedIn()) {
            return $this->redirectTo('mainPage');
        }
        $sets = $this->setRepository->getSetHistory($_SESSION['uid']);
        return $this->render('myAccount', ['sets' => $sets]);
    }


    public function insertUserHistory() {
        if ($this->isPost() && isset($_POST['uid']) && isset($_POST['set_id'])) {
            $user_id = $_POST['uid'];
            $set_id = $_POST['set_id'];

            $success = $this->setRepository->insertUserHistory($user_id, $set_id);

            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Failed to insert user history']);
            }
        } else {
            echo json_encode(['error' => 'Invalid request']);
        }
    }

    
    public function searchSets() {
        $isAdmin = false;
        if ($this->isAdmin()) {
            $isAdmin = true;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['filter'])) {       
                $filter = $_POST['filter'];
                $filteredSets = $this->setRepository->searchSets($filter);
                
                foreach ($filteredSets as $set) {
                    echo '<div class="wordSet">';
                    echo '<span id="set_id" hidden>', $set->getId(), '</span>';
                    echo '<img src="', $set->getImage(), '">';
                    echo '<h1>', $set->getName(), '</h1>';
                    echo '<span>', $set->getWordCount(), ($set->getWordCount() > 1) ? ' words' : ' word', '</span>';
                    
                    if ($isAdmin) {
                        echo '<div class="delSet">';
                        echo '<button class="Delete">Delete</button>';
                        echo '</div>';
                    }
                    
                    echo '</div>';
                }
            }
            $filteredSets = $this->setRepository->searchSets($filter);
            return $filteredSets;
        }
    }
}
