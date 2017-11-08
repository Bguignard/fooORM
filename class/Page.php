<?php

class Page
{
    protected $pathOfContent = '';
    protected $title = '';
    protected $wrapper = '';

    public function __construct($title, $pathOfContent)
    {
        $this->title = $title;
        $this->pathOfContent = $pathOfContent;
    }
    public function includeStart()
    {
        return '
        <!doctype html>
            <html lang="fr">
            <!-- Head -->
            <head>
                <!-- Encodage-->
                <meta charset="UTF-8" />
            
                <!-- Bootstrap compatibility-->
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1" />
            
                <!-- Bootstrap CSS -->
                <link rel="stylesheet" href="./lib/bootstrap/css/bootstrap.min.css">
                <link rel="stylesheet" type="text/css" href="../css/styles.css">
            
                <!--  Titre-->
                <title>' . $this->title . ' </title>
                
                <!-- Favicon-->
                <link rel="shortcut icon" href="./image/favicon.jpg" />
            </head>
            
            <!--body-->
            <body data-spy="scroll" data-target=".navbar" data-offset="50">
                <div class="container" id="content">
        ';
    }


    public function includeNav()
    {
        return '';
    }

    public function includeContent()
    {

        return '            <h1>' . $this->title . '</h1>
                    ' . file_get_contents($this->pathOfContent);

    }

    public function includeEnd()
    {
        return'
                     <!-- footer -->
                    <footer id="footer">
                        Created by Bruno Guignard 2017
                    </footer>
                </div>
    
    
        <!-- Scripts -->
            <!-- Jquery -->
            <script type="text/javascript" src="./lib/jquery/jquery.js"></script>
            <!-- Latest compiled and minified Bootstrap JavaScript -->
            <script src="./lib/bootstrap/js/bootstrap.js" ></script>
        </body>
        </html>
    ';
    }

    public function display()
    {



        return $this->includeStart() . $this->includeNav() . $this->includeContent() . $this->includeEnd();

    }

}