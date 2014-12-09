<?php

class Replica
{

    /*
    |--------------------------------------------------------------------------
    | Class properties
    |--------------------------------------------------------------------------
    |
    | @var $_page: holds default page to load if no page is requested
    | @var $_template: holds the current template directory for the page
    | @var $_template_data = collect data assigned via magic __set() method
    |
    |
    */

    private $_page = 'index',
            $_template = "",
            $_template_data = [];



    /*
    |--------------------------------------------------------------------------
    | SET AND GET THEME CONFIG FILE
    |--------------------------------------------------------------------------
    |
    | @var $theme_config : the current theme configuration file
    |
    */
    public $theme_config= null;


    /*
    |--------------------------------------------------------------------------
    | >--------------RUN--->(.) BOOTING APPLICATION...
    |--------------------------------------------------------------------------
    |
    | The run method calls the dispatch() method and load the application
    |
    */

    /**
     * @return mixed
     */
    public function run()
    {
        /*
       |--------------------------------------------------------------------------
       | Bootstrap the Application
       |--------------------------------------------------------------------------
       |
       | Check to make sure no error loading the configuration file into the the
       | system and bootstrap the application
       |
       */

        #< START OF SYSTEM CONFIGURATION

        /*
        |--------------------------------------------------------------------------
        | INTERNAL CONFIGURATION OPTIONS *** DO NOT MODIFY ***
        |--------------------------------------------------------------------------
        |
        | Unless you know what you are doing do not modify any of the following
        | configuration, they are depended on through out the system, one inappropriate
        | change will cause breakdown of the overall system.
        |
        */


        /*
       |--------------------------------------------------------------------------
       | SET SYSTEM DEFAULT TIME ZONE
       |--------------------------------------------------------------------------
       |
       | Set the default system timezone in case php date time functions used
       |
       */

        date_default_timezone_set(self::get_system('timezone'));

       /*
       |--------------------------------------------------------------------------
       | DEFINED ALL THE CORE CONSTANTS
       |--------------------------------------------------------------------------
       |
       |    Define list of core constants that are needed to connect the system
       |
       */

        //Core
        if(!defined('REPLICA'))         define('REPLICA', self::get_system('state'));
        if(!defined('REPLICA_VERSION')) define('REPLICA_VERSION', self::get_system('version'));

        //System
        if(!defined('CORE_DIR'))        define('CORE_DIR', REPLICA_ROOT_DIR . self::_whitespace_slashes(REPLICA_CUSTOM_CORE_DIR) . DS);
        if(!defined('ASSETS_DIR'))      define('ASSETS_DIR', REPLICA_ROOT_DIR . self::_whitespace_slashes(REPLICA_CUSTOM_ASSETS_DIR) . DS);
        if(!defined('DATA_DIR'))        define('DATA_DIR', REPLICA_ROOT_DIR . self::_whitespace_slashes(REPLICA_CUSTOM_DATA_DIR) . DS);
        if(!defined('MODULES_DIR'))     define('MODULES_DIR', REPLICA_ROOT_DIR . self::_whitespace_slashes(REPLICA_CUSTOM_MODULES_DIR) . DS);


        //Data
        if(!defined('PAGES'))           define('PAGES', DATA_DIR . self::_whitespace_slashes(REPLICA_CUSTOM_DATA_PAGES_DIR) . DS);
        if(!defined('NAV'))             define('NAV', DATA_DIR . self::_whitespace_slashes(REPLICA_CUSTOM_DATA_NAV_DIR) . DS);
        if(!defined('WIDGETS'))         define('WIDGETS', DATA_DIR . self::_whitespace_slashes(REPLICA_CUSTOM_DATA_WIDGETS_DIR) . DS);

        //Public
        if(!defined('PUBLIC_ROOT'))     define('PUBLIC_ROOT', self::get_base_uri());
        if(!defined('PUBLIC_ASSETS'))   define('PUBLIC_ASSETS', PUBLIC_ROOT . self::_whitespace_slashes(REPLICA_CUSTOM_ASSETS_DIR) . '/');


      /*
      |--------------------------------------------------------------------------
      | DEBUG MODE
      |--------------------------------------------------------------------------
      |
      |    If the debug mode is set to true, get all possible errors shown
      |
      */
        if (REPLICA_DEBUG_MODE) {

            //Set Error Display
            ini_set('display_errors', 'On');
            ini_set('html_errors', 0);

            //Set Error Reporting
            error_reporting(-1);

            //Shutdown Handler
            function ShutdownHandler()
            {
                if (@is_array($error = @error_get_last())) {
                    return (@call_user_func_array('ErrorHandler', $error));
                }

                return true;
            }



            register_shutdown_function('ShutdownHandler');

            //Error handler

            function ErrorHandler($type, $message, $file, $line)
            {
                $_ERRORS = Array(
                    0x0001 => 'E_ERROR',
                    0x0002 => 'E_WARNING',
                    0x0004 => 'E_PARSE',
                    0x0008 => 'E_NOTICE',
                    0x0010 => 'E_CORE_ERROR',
                    0x0020 => 'E_CORE_WARNING',
                    0x0040 => 'E_COMPILE_ERROR',
                    0x0080 => 'E_COMPILE_WARNING',
                    0x0100 => 'E_USER_ERROR',
                    0x0200 => 'E_USER_WARNING',
                    0x0400 => 'E_USER_NOTICE',
                    0x0800 => 'E_STRICT',
                    0x1000 => 'E_RECOVERABLE_ERROR',
                    0x2000 => 'E_DEPRECATED',
                    0x4000 => 'E_USER_DEPRECATED'
                );

                if (!@is_string($name = @array_search($type, @array_flip($_ERRORS)))) {
                    $name = 'E_UNKNOWN';
                }

                return (print(@sprintf("%s Error in file \xBB%s\xAB at line %d: %s\n", $name, @basename($file), $line, $message)));
            }



            $old_error_handler = set_error_handler("ErrorHandler");


        }

        # END OF SYSTEM CONFIGURATION />

        /*
        |--------------------------------------------------------------------------
        | RUN DISPATCH
        |--------------------------------------------------------------------------
        | Application has been bootstrapped and now its time to run it...
        |
        */

        return $this->dispatch();

    }


    /*
    |--------------------------------------------------------------------------
    | Dispatch method
    |--------------------------------------------------------------------------
    |
    | The dispatch method instantiates the the template and put together all
    | inner workings and bootstraps the application
    |
    */
    /**
     * @return mixed
     */
    private function dispatch()
    {

        //Set the default page title if no title is defined in the data
        $this->title = REPLICA_DEFAULT_SITE_NAME;

        //Set the default page meta description if no meta description is defined in the data
        $this->meta_description = REPLICA_DEFAULT_SITE_DESCRIPTION;

        //Set default page keywords is no meta keywords are defined in the data
        $this->meta_keywords = REPLICA_DEFAULT_SITE_KEYWORDS;


        $data = self::_include_file($this->route() . EXT);

        //check to see if the page uses special template

        $user_page_template = isset($data['template']) ? $data['template'] : null;


        //Send each variable in the page to the template
        foreach ($data as $data_key => $data_value) {
            $this->$data_key = $data_value;
        }

        //Generate the view

        return $this->make($user_page_template);

    }

    /*
    |--------------------------------------------------------------------------
    | Route method
    |--------------------------------------------------------------------------
    |
    | Prepares the uri
    |
    */


    /**
     * @return bool|string
     */
    private function route()
    {
        //Default page value

        $this->_page = PAGES . $this->_page;

        //Get the URI Array Values
        $url = $this->_parse_uri_collections();


        //Confirm that there is at array index
        if (count($url) >= 1) {
            //Check to see if the first option set is directory if it is than check second option for being a file
            if (self::_is_dir(PAGES . $url[0])) {

                //Check to see if url one is setup
                if (isset($url[1])) {

                    //If it is setup explode by page extension
                    $eval_page_request = explode('.', self::_whitespace_slashes($url[1]));

                    //Make sure the exploded var is two part file and extension
                    if (count($eval_page_request) == 2) {

                        //Assign parts to a variables
                        $final_page = $eval_page_request[0];
                        $extension = $eval_page_request[1];

                        //Test to see if evaluated extension is equal to one in config
                        if ($extension != REPLICA_PAGE_EXTENSION)

                            //If the extension is not the same, this has happened in error send to 404
                            unset($url[1]);
                        else

                            //If the extension is the same as what is in config while than assign the file to the variable
                            $url[1] = $final_page;
                    }

                }

                //Set up the file to check
                $file = isset($url[0]) && isset($url[1]) ? PAGES . $url[0] . DS . $url[1] : PAGES . $url[0];


                //Since the first Index or the URI array is also a directory check is second index is set
                if (self::_check_file($file . EXT)) {

                    //Tested and confirmed assign the file to default page
                    $this->_page = $file;

                    //Unset the found file from the array list
                    unset($url[1]);

                    //Also since a file is found unset the first array index as well
                    unset($url[0]);
                }

            }


            /*
            |--------------------------------------------------------------------------
            |       ERROR HAS OCCURRED
            |--------------------------------------------------------------------------
            |       THROW ERROR 404
            | THE SHOULD BE NO $URL[1] HERE
            |
            */

            if (isset($url[1]))
                return self::Redirect_to(404);


            //Check to see if the URL[0] is still set

            if (isset($url[0])) {

                //If it is setup explode by page extension
                $eval_page_request = explode('.', self::_whitespace_slashes($url[0]));

                //Make sure the exploded var is two part file and extension
                if (count($eval_page_request) == 2) {

                    //Assign parts to a variables
                    $final_page = $eval_page_request[0];
                    $extension = $eval_page_request[1];

                    //Test to see if evaluated extension is equal to one in config
                    if ($extension == REPLICA_PAGE_EXTENSION) {
                        //If the extension is the same as what is in config while than assign the file to the variable
                        $url[0] = $final_page;
                    }


                }


                //We have learned url index 0 still set, lets check if this is a file
                $file = PAGES . $url[0];

                //Check for a existing and readable file
                if (self::_check_file($file . EXT)) {

                    //If found a result assign the default page index 0
                    $this->_page = $file;

                    //Unset index 0 in case we would like to do more checking in the future
                    unset($url[0]);
                }

            }


            /*
            |--------------------------------------------------------------------------
            |       ERROR HAS OCCURRED
            |--------------------------------------------------------------------------
            |           THROW ERROR 404
            |   THE SHOULD BE NO $URL[0] HERE
            |
            */

            if (isset($url[0])) {

                // Only throw in the 404 error when the $url[0] is not empty
                //  Otherwise continue with default page

                if (!empty($url[0])) {

                    if (self::_is_dir(REPLICA_ROOT_DIR . $url[0])) {
                        define('AT_403_ON_DIR', true);

                        return self::Redirect_to(403);
                    }

                    return self::Redirect_to(404);
                }


                //Terminate the url[0] here and continue with default page
                unset($url[0]);
            }

        }


        /*
        |--------------------------------------------------------------------------
        | RETURN PAGE LOCATION
        |--------------------------------------------------------------------------
        |
        | Request processing completed, return the request without the file
        | extension to the dispatch to be processed.
        |
        */
        return $this->_page;

    }

    



    /*
    |--------------------------------------------------------------------------
    | Make() Method
    |--------------------------------------------------------------------------
    |
    | A method responsible for validating and generating request view template
    | from the current theme
    |
    */
    /**
     * @param null $path
     * @return mixed
     */
    public function make($path = null)
    {

        //Initiate the template directory
        $template_dir = $this->_is_theme_dir(ASSETS_DIR . REPLICA_THEME.DS) ? REPLICA_THEME : 'default';

        //template to be used

        $this->_template = ASSETS_DIR . $template_dir . DS;

        //Theme name used to grab only the name
        define('CURRENT_THEME_NAME', $template_dir);

        //Theme dir used for internal only
        define('CURRENT_THEME_DIR', $this->_template);

        // Theme configuration file

        if ($theme_config = self::_check_file(CURRENT_THEME_DIR . CURRENT_THEME_NAME . '.json'))
        {
            $this->$theme_config = $theme_config;
        }

        // Send variable to the template

        foreach ($this->_template_data as $data => $value) {
            $$data = $value;
        }

        if ($path) {

            if (self::_check_file($this->_template . $path . EXT)) {

                return require_once $this->_template . $path . EXT;
            }
        }

        return require_once $this->_template . 'default' . EXT;

    }


    #PRIVATE METHOD ACCESSED ONLY INTERNALLY

    /*
    |--------------------------------------------------------------------------
    | _check_file()
    |--------------------------------------------------------------------------
    |
    | Checks if given file exists and is readable
    |
    */

    /**
     * @param $path
     * @return bool
     */
    private static function _check_file($path)
    {
        if (file_exists($path) && !is_dir($path) && is_readable($path)) {
            return true;
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | _is_dir($path)
    |--------------------------------------------------------------------------
    |
    | Check if path is a directory and is not a
    | file and is readable
    |
    */
    /**
     * @param $path
     * @return bool
     */
    private static function _is_dir($path)
    {
        if (is_dir($path) && !is_file($path) && is_readable($path)) {
            return true;
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | _include_file()
    |--------------------------------------------------------------------------
    |
    | Require in the file if it exists and is
    | readable
    |
    */

    /**
     * @param $path
     * @return mixed|null
     */
    private static function _include_file($path)
    {
        if (self::_check_file($path)) {
            return require_once $path;
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | _query_string()
    |--------------------------------------------------------------------------
    |
    | Returns the uri query string
    |
    */

    /**
     * @return array
     */
    private static function _query_string()
    {
        $url = filter_var(rtrim($_SERVER['QUERY_STRING'], '/'), FILTER_SANITIZE_URL);
        $qs = explode('=', $url);
        if (count($qs) == 2)
            return explode('/', $qs[1]);
        else
            return [];
    }


    /*
    |--------------------------------------------------------------------------
    | Replica::_whitespace_slashes()
    |--------------------------------------------------------------------------
    | Trims slashes and spaces from right and left
    |
    */

    /**
     * @param $var
     * @return string
     */
    private static function _whitespace_slashes($var)
    {
        return trim($var, "\x00..\x20/");
    }



    /*
    |--------------------------------------------------------------------------
    | _is_theme_dir();
    |--------------------------------------------------------------------------
    |
    | Checks if given path is a valid replica theme
    | directory and has required theme contents
    |
    */

    /**
     * @param $path
     * @return bool
     */
    private function _is_theme_dir($path)
    {
        $err = 0;

        $replica_required_theme_contents =
            [
                self::_whitespace_slashes(REPLICA_THEME_DEFAULT_INDEX).EXT,
                self::_whitespace_slashes(REPLICA_THEME_CSS_DIR),
                self::_whitespace_slashes(REPLICA_THEME_JS_DIR),
                self::_whitespace_slashes(REPLICA_THEME_IMG_DIR),
                self::_whitespace_slashes(REPLICA_THEME_CSS_DIR).DS.self::_whitespace_slashes(REPLICA_THEME_DEFAULT_CSS_FILE).'.css',
                self::_whitespace_slashes(REPLICA_THEME_ERRORS_TEMPLATE).EXT,
                self::_whitespace_slashes(REPLICA_THEME_PARTIAL_DIR)
            ];

        foreach ($replica_required_theme_contents as $req) {
            if (!is_readable($path . $req)) {
                $err++;
            }

        }

        if ($err == 0) {
            return true;
        }

        return false;


    }


    /*
    |--------------------------------------------------------------------------
    | Magic Setter
    |--------------------------------------------------------------------------
    |
    | PHP magic method to set template variables
    |
    */

    /**
     * @param $k
     * @param $v
     */
    public function __set($k, $v)
    {
        $this->_template_data[$k] = $v;
    }


    /*
    |--------------------------------------------------------------------------
    |  _parse_uri_collections()
    |--------------------------------------------------------------------------
    |
    | Parses the uri for the route method
    |
    */
    /**
     * @return array
     */
    private function _parse_uri_collections()
    {
        return explode('/', filter_var(self::_whitespace_slashes(self::input('get', 'replica_uri')), FILTER_SANITIZE_URL));
    }

    # REPLICA NON STATIC

    public function get_theme_config($format)
    {
        if ($this->theme_config) {
            switch ($format) {
                case 'obj':
                    return json_decode($this->theme_config);
                case 'array':
                    return json_decode($this->theme_config, true);
                case 'json':
                    return $this->theme_config;
            }
        }

        return null;
    }

    # REPLICA STATICALLY ACCESSIBLE PUBLIC METHODS

    /*
    |--------------------------------------------------------------------------
    | Replica::get('nav','main');
    |--------------------------------------------------------------------------
    |
    | Get the replica nav and widgets
    |
    */


    public static function get($type,$path)
    {

        switch(strtolower($type))
        {
            case "nav":
                $nav = NAV . $path . EXT;
                if (self::_check_file($nav))
                    return self::_include_file($nav);
                break;
            case 'widget':
                $widget = WIDGETS.$path.EXT;
                if(self::_check_file($widget))
                    return self::_include_file($widget);
                break;
        }

        return [];
    }


    /*
    |--------------------------------------------------------------------------
    | Replica::Redirect_to(404); function
    |--------------------------------------------------------------------------
    | Redirects throughout the application and
    | renders error pages
    |
    */

    /**
     * @param $location
     * @return bool
     */
    public static function Redirect_to($location)
    {
        if ($location) {
            if (is_numeric($location)) {

                //Instantiate the replica class
                $replica = new Replica();

                switch ($location) {
                    case '404':
                        header('HTTP/1.0 404 File Not Found');
                        $replica->title = "Error 404 File Not Found";
                        $replica->header = "404";
                        $replica->body = "Woops, page not found.";

                        $replica->make('errors/default');

                        exit;

                    case '403':
                        header('HTTP/1.0 403 Forbidden');
                        $replica->title = "Error 403 Forbidden Access";
                        $replica->header = "403";
                        $replica->body = "Woops, you're totally forbidden from here";

                        $replica->make('errors/default');

                        exit;

                }
            } else {
                header("location: {$location}");
                exit;
            }
        }
        return false;
    }

    /*
     |--------------------------------------------------------------------------
     | Replica::include_get('header')
     |--------------------------------------------------------------------------
     |
     | Method that will include view partial e.g.
     | header, footer and other parts,
     | this function can be created in the themes
     | file therefore if that is created than
     | that will be used instead.
     |
     */


    /**
     * @param $partial
     * @param array $params
     * @return mixed|null
     */
    public static function include_get($partial, $params = [])
    {
        //Request require file
        $request = CURRENT_THEME_DIR . self::_whitespace_slashes(REPLICA_THEME_PARTIAL_DIR) . DS . strtolower($partial) . EXT;

        switch ($partial) {
            case $partial:
                if (strtolower($partial) == "header") {
                    $title = isset($params['title']) ? $params['title'] : REPLICA_DEFAULT_SITE_NAME;
                    $meta_description = isset($params['meta_description']) ? $params['meta_description'] : REPLICA_DEFAULT_SITE_DESCRIPTION;
                    $meta_keywords = isset($params['meta_keywords']) ? $params['meta_keywords'] : REPLICA_DEFAULT_SITE_KEYWORDS;
                } elseif (strtolower($partial) == "sidebar") {
                    $sidebar_title = isset($params['sidebar_title']) ? $params['sidebar_title'] : "";
                    $sidebar_content = isset($params['sidebar_content']) ? $params['sidebar_content'] : "";
                } elseif (strtolower($partial) == "widgets") {
                    $widget_title = isset($params['widget_title']) ? $params['widget_title'] : "";
                    $widget_content = isset($params['widget_content']) ? $params['widget_content'] : "";
                }
                return self::_check_file($request) ? include_once $request : null;
        }
        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | Replica::assets_get('css',[css/styles.css]);
    |--------------------------------------------------------------------------
    |
    | Imports assets like stylesheet and javascript
    | into the replica pages.
    |
    */
    /**
     * @param $type
     * @param array $assets
     * @return null|string
     */
    public static function assets_get($type, $assets = [])
    {
        $asset_type = strtolower($type);

        //Where to start the counter for generating automatic directory separators
        $start = defined("AT_403_ON_DIR") ? 0 : 1;

        switch ($asset_type) {

            case 'css':
                $css = "<!--replica assets auto-dump: Stylesheet //-->" . PHP_EOL;
                if (count($assets) >= 1) {
                    foreach ($assets as $asset) {

                        #ONLY INCLUDE ASSET IF EXISTS TO ELIMINATE 404 ERRORS
                        if (self::_check_file(CURRENT_THEME_DIR . $asset)) {
                            //Generate separator tp link asset from relative path from current uri
                            $uri = count(self::_query_string());
                            $separator = "";
                            for ($i = $start; $i < $uri; $i++) {
                                $separator .= "../";
                            }

                            $css .= '<link rel="stylesheet" href="' . $separator . self::_whitespace_slashes(REPLICA_CUSTOM_ASSETS_DIR) . '/' . CURRENT_THEME_NAME . '/' . $asset . '">' . PHP_EOL;
                        }
                    }
                }
                return $css;
            case 'js':
                $js = "<!--replica assets auto-dump: JavaScript  //-->" . PHP_EOL;
                if (count($assets) >= 1) {
                    foreach ($assets as $asset) {
                        if (self::_check_file(CURRENT_THEME_DIR . $asset)) {
                            //Generate separator tp link asset from relative path from current uri
                            $uri = count(self::_query_string());
                            $separator = "";
                            for ($i = $start; $i < $uri; $i++) {
                                $separator .= "../";
                            }
                            $js .= '<script src="' . $separator . self::_whitespace_slashes(REPLICA_CUSTOM_ASSETS_DIR) . '/' . CURRENT_THEME_NAME . '/' . $asset . '"> </script>' . PHP_EOL;
                        }
                    }
                }
                return $js;
        }
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Replica::get_base_uri();
    |--------------------------------------------------------------------------
    |
    | Returns the base URI of the script with
    | right slash is trimmed off.
    |
    */

    /**
     * @return mixed
     */
    public static function get_base_uri()
    {
        return filter_var(rtrim($_SERVER['SCRIPT_NAME'], 'index.php'), FILTER_SANITIZE_URL);
    }


    /*
    |--------------------------------------------------------------------------
    | Replica::escape();
    |--------------------------------------------------------------------------
    | escapes the out put in correct charset
    |
    */

    /**
     * @param $var
     * @return string
     */
    public static function escape($var)
    {
        return htmlentities($var, ENT_QUOTES, 'UTF-8');
    }


    /*
    |--------------------------------------------------------------------------
    | Replica::input('get', 'username');
    |--------------------------------------------------------------------------
    | returns $_POST or $_GET var
    |
    */

    /**
     * @param string $type
     * @param $var
     * @return string
     */
    public static function input($type = 'post', $var)
    {
        switch (strtolower($type)) {
            case 'post':
                return isset($_POST[$var]) ? $_POST[$var] : '';
            case 'get':
                return isset($_GET[$var]) ? $_GET[$var] : '';
        }

    }




    /*
    |--------------------------------------------------------------------------
    | Replica::dd($var);
    |--------------------------------------------------------------------------
    | returns var dumps the detail on the var
    |
    */

    public static function dd($var)
    {
        return var_dump("<pre>",$var,"</pre>");
    }



    /*
    |--------------------------------------------------------------------------
    | Replica::get_system()
    |--------------------------------------------------------------------------
    | returns the current version of the application
    |
    */


    /**
     * @param $request
     * @return null
     */
    public static function get_system($request)
    {
       $replica_info = ['name'=>'Replica','state'=>true,'version'=> 0.01,'release_date' => '12/08/2014','url'=>'http://sharif.co/projects/replica','timezone'=>REPLICA_DEFAULT_TIME_ZONE ];

        foreach($replica_info as $info_key=>$info_value)
        {
            if(strtolower($request)==$info_key)
            {
                return $info_value;
            }
        }
        return null;
    }

}


//Instantiate the replica class
$Replica = new Replica();