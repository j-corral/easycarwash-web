<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 07/09/15
 * Time: 11:09
 */

namespace Content\Controller;


use Content\Table\TestTable;
use Core\App;
use Core\Database\Database;
use Core\Database\MysqlDatabase;

class StaticsController extends AppController {

    /**
     * StaticsController constructor.
     * Sending $static = true param
     */
    public final function __construct(){
        parent::__construct(true);
    }


    public function home() {
//
//        $test = new TestTable();
//
//
//        var_dump($test->query()->where(['id' => 3])->fetch());

        $this->render('statics.home');
    }


    public function contact() {

        $this->render('statics.contact');
    }


}
