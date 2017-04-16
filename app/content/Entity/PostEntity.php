<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 07/09/15
 * Time: 17:23
 */

namespace Content\Entity;


use Core\Entity\Entity;
use Core\Helper\Html;

class PostEntity extends Entity {


    // Renvoie les 120 premiers caractères de la description du post
    public function getIntro() {
        return substr($this->description, 0 , 120);
    }


    public function getUrl() {
        return Html::link("posts/show/{$this->id}");
        //return " index.php?p=posts.show&id={$this->id}";
    }

}