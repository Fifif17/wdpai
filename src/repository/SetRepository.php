<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/Set.php';
require_once __DIR__.'/../models/Word.php';

class SetRepository extends Repository {

    private $imgDIR = '/includes/assets/sets/';

    public function getSets() {     
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM sets;
        ');
        $stmt->execute();

        $setsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $sets = [];

        foreach ($setsData as $setData) {
            $imgPath = $this->imgDIR . $setData['image'];
            $set = new Set($setData['id_set'], $setData['name'], $imgPath, $setData['word_count']);
            $set->setAuthor($setData['id_author']);

            // $pairedWords = explode(', ', $setData['paired_words']);
            // foreach ($pairedWords as $pairedWord) {
            //     list($word_en, $word_pl) = explode(' ; ', $pairedWord);
            //     $word = new Word($word_en, $word_pl);
            //     $set->addWord($word);
            // }

            $sets[] = $set;
        }

        return $sets;
    }


    public function getSetbyId($id_set) {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM sets WHERE id_set = :id_set;
        ');
        $stmt->bindParam(':id_set', $id_set, PDO::PARAM_INT);
        $stmt->execute();
        $setData = $stmt->fetch(PDO::FETCH_ASSOC);

        $imgPath = $this->imgDIR . $setData['image'];

        $set = new Set(
            $setData['id_set'],
            $setData['name'],
            $imgPath,
            $setData['word_count']
        );
        $set->setAuthor($setData['id_author']);
        // $pairedWords = explode(', ', $setData['paired_words']);
        // foreach ($pairedWords as $pairedWord) {
        //     list($word_en, $word_pl) = explode(' ; ', $pairedWord);
        //     $word = new Word($word_en, $word_pl);
        //     $set->addWord($word);
        // }

        return $set;
    }


    public function getSetHistory($uid) {
        $stmt = $this->database->connect()->prepare('
            SELECT id_set, MAX(timestamp) AS latest_timestamp
            FROM User_history
            WHERE id_user = :uid
            GROUP BY id_set
            ORDER BY latest_timestamp DESC;
        ');

        $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
        $stmt->execute();

        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $sets = [];

        foreach ($history as $hData) {
            $set = $this->getSetById($hData['id_set']);
            $sets[] = $set;
        }

        return $sets;
    }


    public function insertUserHistory($uid, $set_id) {
        $stmt = $this->database->connect()->prepare('
            SELECT insert_user_history(:uid, :set_id)
        ');
        $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
        $stmt->bindParam(':set_id', $set_id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }


    public function searchSets($filter) {     
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM sets WHERE LOWER(name) LIKE :filter;
        ');
        $stmt->bindValue(':filter', '%' . $filter . '%', PDO::PARAM_STR);
        $stmt->execute();
    
        $setsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $sets = [];
        
    
        foreach ($setsData as $setData) {
            $imgPath = $this->imgDIR . $setData['image'];
            $set = new Set($setData['id_set'], $setData['name'], $imgPath, $setData['word_count']);
            $set->setAuthor($setData['id_author']);

            // $pairedWords = explode(', ', $setData['paired_words']);
            // foreach ($pairedWords as $pairedWord) {
            //     list($word_en, $word_pl) = explode(' ; ', $pairedWord);
            //     $word = new Word($word_en, $word_pl);
            //     $set->addWord($word);
            // }
            $sets[] = $set;
        }
        
        return $sets;
    }
    
}

?>