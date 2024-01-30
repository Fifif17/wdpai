<?php

class AppController {
    private $request;


    public function __construct(){
        $this->request = $_SERVER['REQUEST_METHOD'];
    }
    

    protected function isGet() : bool {
        return $this->request === 'GET';
    }


    protected function isPost() : bool {
        return $this->request === 'POST';
    }


    protected function isLoggedIn() : bool {
        session_start();
        return isset($_SESSION['uid']);
    }


    protected function isAdmin() : bool {
        session_start();
        return isset($_SESSION['admin']);
    }


    protected function render(string $template = null, array $variables = []) {
        $templatePath = 'includes/views/'.$template.'.php';
        $output = 'File not found!';

        if (file_exists($templatePath)) {
            extract($variables);

            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }

        print $output;
    }


    protected function redirectTo(string $url, array $variables = []) {
        $_SESSION['messages'] = $variables;
        header("Location: $url");
        exit;
    }
}