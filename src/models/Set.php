<?php

require_once 'Word.php';

class Set {
    public $id_set;
    public $name;
    public $image;
    public $word_count;
    public $id_author;
    public $words;


    public function __construct($id_set, $name, $image, $word_count) {
        $this->id_set = $id_set;
        $this->name = $name;
        $this->image = $image;
        $this->word_count = $word_count;
        $this->words = [];
    }

    public function getId() {
        return $this->id_set;
    }


    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }


    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }


    public function getWordCount() {
        return $this->word_count;
    }

    public function setWordCount($word_count){
        $this->word_count = $word_count;
    }


    public function getAuthor() {
        return $this->id_author;
    }

    public function setAuthor($id_author){
        $this->id_author = $id_author;
    }


    public function getWords() {
        return $this->words;
    }

    public function addWord(Word $word) {
        $this->words[] = $word;
    }
}

?>