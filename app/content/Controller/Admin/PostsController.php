<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 09/09/15
 * Time: 21:51
 */

namespace Content\Controller\Admin;


use Core\App;
use Core\Database\QueryBuilder;

class PostsController extends AppController {

    public function index() {

        //$posts = $this->Post->all();

        /*$query = new QueryBuilder();
        echo $query->select('id', 'email')
                   ->from('posts')
                   ->where('id = 1')
                   ->where('hash = 123');*/


        //var_dump( App::getInstance()->getDBInstance());
        $this->render('admin.posts.index', compact('posts'));
    }

}
