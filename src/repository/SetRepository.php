<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/Set.php';
require_once __DIR__.'/../models/Word.php';

class SetRepository extends Repository {

    private $imgDIR = '/includes/assets/sets/';

    public function getSets() {     
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM sets_with_words;
        ');
        $stmt->execute();

        $setsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $sets = [];

        foreach ($setsData as $setData) {
            $imgPath = $this->imgDIR . $setData['set_image'];
            $set = new Set($setData['id_set'], $setData['set_name'], $imgPath, $setData['word_count']);
            $set->setAuthor($setData['id_author']);

            if ($setData['paired_words']) {
                $pairedWords = explode(', ', $setData['paired_words']);
                foreach ($pairedWords as $pairedWord) {
                    list($word_en, $word_pl) = explode(' ; ', $pairedWord);
                    $word = new Word($word_en, $word_pl);
                    $set->addWord($word);
                }
            }

            $sets[] = $set;
        }

        return $sets;
    }


    public function getSetbyId($id_set) {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM sets_with_words WHERE id_set = :id_set;
        ');
        $stmt->bindParam(':id_set', $id_set, PDO::PARAM_INT);
        $stmt->execute();
        $setData = $stmt->fetch(PDO::FETCH_ASSOC);

        $imgPath = $this->imgDIR . $setData['set_image'];

        $set = new Set(
            $setData['id_set'],
            $setData['set_name'],
            $imgPath,
            $setData['word_count']
        );
        $set->setAuthor($setData['id_author']);
        if ($setData['paired_words']) {
            $pairedWords = explode(', ', $setData['paired_words']);
            foreach ($pairedWords as $pairedWord) {
                list($word_en, $word_pl) = explode(' ; ', $pairedWord);
                $word = new Word($word_en, $word_pl);
                $set->addWord($word);
            }
        }

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
            SELECT * FROM sets_with_words WHERE LOWER(set_name) LIKE :filter;
        ');
        $stmt->bindValue(':filter', '%' . $filter . '%', PDO::PARAM_STR);
        $stmt->execute();
    
        $setsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $sets = [];
        
    
        foreach ($setsData as $setData) {
            $imgPath = $this->imgDIR . $setData['set_image'];
            $set = new Set(
                $setData['id_set'],
                $setData['set_name'],
                $imgPath,
                $setData['word_count']
            );
            $set->setAuthor($setData['id_author']);

            if ($setData['paired_words']) {
                $pairedWords = explode(', ', $setData['paired_words']);
                foreach ($pairedWords as $pairedWord) {
                    list($word_en, $word_pl) = explode(' ; ', $pairedWord);
                    $word = new Word($word_en, $word_pl);
                    $set->addWord($word);
                }
            }
            $sets[] = $set;
        }
        
        return $sets;
    }
    

    public function addSet(Set $set) {
        $stmt = $this->database->connect()->prepare('
            SELECT insert_into_sets(:set_name, :set_image, 0, :uid);
        ');

        $setName = $set->getName();
        $setImage = $set->getImage();
        $uid = $set->getAuthor();

        $stmt->bindParam(':set_name', $setName, PDO::PARAM_INT);
        $stmt->bindParam(':set_image', $setImage, PDO::PARAM_INT);
        $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);

        $stmt->execute();
    }


    public function addWord(Word $word, $set_id) {
        $stmt = $this->database->connect()->prepare('
            SELECT add_word_to_set(:en_word, :pl_word, :set_id);
        ');

        $en_word = $word->getEn();
        $pl_word = $word->getPl();

        $stmt->bindParam(':en_word', $en_word, PDO::PARAM_INT);
        $stmt->bindParam(':pl_word', $pl_word, PDO::PARAM_INT);
        $stmt->bindParam(':set_id', $set_id, PDO::PARAM_INT);

        $stmt->execute();
    }


    public function removeSet($set_id) {
        $stmt = $this->database->connect()->prepare('
            SELECT remove_set_and_associations(:set_id);
        ');
        $stmt->bindParam(':set_id', $set_id, PDO::PARAM_INT);
        
        $stmt->execute(); 
    }


    public function removeWord($word_en, $word_pl) {
        $stmt = $this->database->connect()->prepare('
            SELECT remove_word_and_associations_by_en_pl(:word_en, :word_pl);
        ');
        $stmt->bindParam(':word_en', $word_en, PDO::PARAM_INT);
        $stmt->bindParam(':word_pl', $word_pl, PDO::PARAM_INT);

        $stmt->execute();
    }
}

?>