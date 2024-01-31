<?php

class Word {
    private $word_en;
    private $word_pl;


    public function __construct($word_en, $word_pl) {
        $this->word_en = $word_en;
        $this->word_pl = $word_pl;
    }


    public function getEn() {
        return $this->word_en;
    }

    public function setEn($word_en) {
        $this->word_en = $word_en;
    }


    public function getPl() {
        return $this->word_pl;
    }

    public function setPl($word_pl) {
        $this->word_pl = $word_pl;
    }

}

?>