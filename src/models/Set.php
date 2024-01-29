<?php

class Set {
    private string $name;
    private string $image;
    private string $word_count;


    public function __construct($name, $image, $word_count) {
        $this->name = $name;
        $this->image = $image;
        $this->word_count = $word_count;
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

}

?>