<?php

class Page
{
    protected $pathOfContent = '';
    protected $title = '';
    protected $wrapper = '';
    protected $jsScript = [];

    public function __construct($title, $pathOfContent, $scripts)
    {
        $this->title = $title;
        $this->pathOfContent = $pathOfContent;
        $this->jsScript = $scripts;
    }
    public function includeStart():string
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


    public function includeNav():string
    {
        return '';
    }

    public function includeContent():string
    {

        return '            <h1>' . $this->title . '</h1>
                    ' . file_get_contents($this->pathOfContent);

    }

    public function includeScipts()
    {
        $s = '';
        if(sizeof($this->jsScript)>0){
            foreach ($this->jsScript as $js){
                $s .= '<script type="text/javascript" src="./' . $js . '"></script>';
            }
        }
        return $s;
    }

    public function includeEnd():string
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
            <!-- Other scripts -->
            ' . $this->includeScipts() . '
        </body>
        </html>
    ';
    }

    public function display():string
    {
        return $this->includeStart() . $this->includeNav() . $this->includeContent() . $this->includeEnd();

    }

    /**
     * @return string
     */
    public function getPathOfContent(): string
    {
        return $this->pathOfContent;
    }

    /**
     * @param string $pathOfContent
     */
    public function setPathOfContent(string $pathOfContent)
    {
        $this->pathOfContent = $pathOfContent;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getWrapper(): string
    {
        return $this->wrapper;
    }

    /**
     * @param string $wrapper
     */
    public function setWrapper(string $wrapper)
    {
        $this->wrapper = $wrapper;
    }


}