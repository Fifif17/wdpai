<?php

require_once 'AppController.php';
require_once __DIR__ . './../models/Set.php';
require_once __DIR__ . './../models/Word.php';
require_once __DIR__ . './../repository/SetRepository.php';

class SetController extends AppController {

    const MAX_FILE_SIZE = 1024*1024;
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg', 'image/jpg'];
    const UPLOAD_DIRECTORY = '/../includes/assets/sets/';

    private $message = [];
    private $setRepository;

    public function __construct() {
        parent::__construct();
        $this->setRepository = new SetRepository();
    }


    public function mainPage() {
        $isAdmin = false;
        if ($this->isAdmin()) {
            $isAdmin = true;
        }
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
        
        if ($this->isPost()) {
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
                        echo '<button class="Delete" dataSetId="' . $set->getId() . '">Delete</button>';
                        echo '</div>';
                    }
                    
                    echo '</div>';
                }
            }
            $filteredSets = $this->setRepository->searchSets($filter);
            return $filteredSets;
        }
    }


    public function setPage() {
        $isAdmin = false;
        if ($this->isAdmin()) {
            $isAdmin = true;
        }
        $set_id = $_GET['id'];
        $set = $this->setRepository->getSetbyId($set_id);
        return $this->render('setPage', ['set' => $set]);
    }


    public function addSet() {
        if (!$this->isAdmin()) {
            return $this->redirectTo('mainPage');
        }

        if ($this->isPost() && is_uploaded_file($_FILES['file']['tmp_name']) && $this->validate($_FILES['file'])) {
            move_uploaded_file(
                $_FILES['file']['tmp_name'],
                dirname(__DIR__) . self::UPLOAD_DIRECTORY . $_FILES['file']['name']
            );

            $caught = false;
            try {
                $set = new Set(0, $_POST['setName'], $_FILES['file']['name'], 0);
                $set->setAuthor($_SESSION['uid']);
                $this->setRepository->addSet($set);
            } catch (PDOException $e) {
                $caught = true;
                if ($e->getCode() == '23505') {
                    $this->message[] = "Error: Duplicate key violation. The set with this name already exists.";
                } else {
                    $this->message[] =  "Error: " . $e->getMessage();
                }
            }
            if (!$caught) {
                $this->message[] = "Set Added Successfully :)";
            }

            return $this->redirectTo('mainPage', ['messages' => $this->message]);
            
        }
        
        return $this->render('addSet', ['messages' => $this->message]);
    }


    public function addWord() {
        if (!$this->isAdmin()) {
            return $this->redirectTo('mainPage');
        }

        if ($this->isPost()) {
            $caught = false;
            try {
                $set = new Word($_POST['wordEn'], $_POST['wordPl']);
                $set_id = $_POST['setId'];
                $this->setRepository->addWord($set, $set_id);
            } catch (PDOException $e) {
                $caught = true;
                if ($e->getCode() == '23505') {
                    $this->message[] = "Error: Duplicate key violation.";
                } else {
                    $this->message[] =  "Error: " . $e->getMessage();
                }
            }
            if (!$caught) {
                $this->message[] = "Word Added Successfully (Or already here :D) :)";
            }

            return $this->redirectTo('setPage?id=' . $set_id, ['messages' => $this->message]);
            
        }

        return $this->render('addWord', ['messages' => $this->message]);
    }


    public function removeSet() {
        if (!$this->isAdmin()) {
            return $this->redirectTo('mainPage');
        }

        if ($this->isPost() && isset($_POST['remSetId'])) {
            try {
                $this->setRepository->removeSet($_POST['remSetId']);
            } catch (PDOException $e) {
                $caught = true;
                $this->message[] =  "Error: " . $e->getMessage();
            }
            if (!$caught) {
                $this->message[] = "Deleted succesfully!";
            }

            return $this->redirectTo('mainPage', ['messages' => $this->message]);
        } 
    }


    public function removeWord() {
        if (!$this->isAdmin()) {
            return $this->redirectTo('mainPage');
        }

        if ($this->isPost()  && isset($_POST['wordEn']) && isset($_POST['wordPl'])) {
            try {
                $this->setRepository->removeWord($_POST['wordEn'], $_POST['wordPl']);
            } catch (PDOException $e) {
                $caught = true;
                $this->message[] =  "Error: " . $e->getMessage();
            }
            if (!$caught) {
                $this->message[] = "Deleted succesfully!";
            }

            return $this->redirectTo('mainPage', ['messages' => $this->message]);
        }

        return $this->redirectTo('mainPage');
    }

    
    private function validate(array $file): bool {
        if ($file['size'] > self::MAX_FILE_SIZE) {
            $this->message[] = 'File is too large for destination file system.';
            return false;
        }

        if (!isset($file['type']) || !in_array($file['type'], self::SUPPORTED_TYPES)) {
            $this->message[] = 'File type is not supported.';
            return false;
        }
        return true;
    }
}
