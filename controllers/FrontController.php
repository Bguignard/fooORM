<?php
/**
 * Created by Bruno Guignard
 */

class FrontController
{
    protected $page = 'home';

    public function __construct()
    {
        $this->page = isset($_GET['page']) ? $_GET['page'] : 'home';
        echo $this->includePage()->display();
    }

    public function includePage()
    {
        switch ($this->page){
            case 'home':
                return new Page('Welcome to FooOrm', './views/v_home.php', ['js/s_home.js']);
                break;
            default:
                return new Page('Welcome to FooOrm', './views/v_home.php', ['js/s_home.js']);
                break;
        }
    }

    public static function getScripts($page)
    {


    }
}