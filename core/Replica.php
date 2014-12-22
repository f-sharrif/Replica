<?php

/*
 * ----------------------------------------------------------------------------------------------------------------
 * # PACKAGE INFORMATION
 * ----------------------------------------------------------------------------------------------------------------
 *
 * @package: Replica
 * @author: Abdikadir Adan (Sharif)  -url [http://sharif.co] -Email [hello@sharif.co]
 * @url: http://replica.sharif.co
 * @author: -Github [sp01010011]
 * @filesource: core/Replica.php
 *
 * ----------------------------------------------------------------------------------------------------------------
 * # ABOUT THE PACKAGE
 * ----------------------------------------------------------------------------------------------------------------
 *
 * Replica is single class php based templating engine developed with designers that
 * works on smaller web  projects in mind. Replica allows the designer to
 * quickly develop a functional file based dynamic website. Most Importantly, Replica
 * is built with flexibility, customization  and ease of use being the priority, therefore, designer / developer
 * decides what data to show, how to show it and where to show it. Your design not only frontend
 * but also the data structure and how that data is ported to your design.
 *
 * All configurations and customizations are done in the index.php, and throw the Replica class in the
 * mix and you're ready building a sophisticated website for your clients.
 *
 */


/**
 * Class Replica
 */
class Replica
{

    /*
    |--------------------------------------------------------------------------
    | Class properties : PRIVATE
    |--------------------------------------------------------------------------
    |
    | @var $_page: holds default page to load if no page is requested
    | @var $_template: holds the current template directory for the page
    | @var $_template_data => collect data assigned via magic __set() method
    | @var $_request_exists => flags whether the request resource exists or not
    | @var $_scan_dir_excludes => directories to exclude from scan dir result
    | @var $_module_list => store list of modules
    | @var $_active_modules => list of active modules
    |
    */

    private

        //Default page to load if no request is made
        $_page = 'index',

        //Custom page template
        $_template = "",

        //List of template data assigned via magic method set
        $_template_data = [],

        //set flag of resource existence
        $_request_exists = false,

        //List of available modules
        $_module_list =[],

        //list of active modules
        $_active_modules =[];

    private static

        //list of directories to exclude from scan dir result
        $_scan_dir_excludes =['.','..'];

    /*
    |--------------------------------------------------------------------------
    | Class Properties: Public
    |--------------------------------------------------------------------------
    |
    | @var $theme_config : the current theme configuration file
    |
    */

    public

        //theme config file
        $theme_config= null;


    ############################################################################
    #                             INIT THE APP                                 #
    ############################################################################
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

    public  function __construct()
    {
        //Register the Exception handler
        set_exception_handler([$this, "replica_exceptions_handler"]);

    }

    public function run()
    {


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
        |    Define list of core constants that are needed to interconnect
        |    the system
        |
        */

        //If this Constant is not defined the system will not render pages, do not remove
        if(!defined('REPLICA'))         define('REPLICA', self::get_system('system_state'));

        /*
        |--------------------------------------------------------------------------
        | DEBUG MODE
        |--------------------------------------------------------------------------
        |
        |    If the debug mode is set to true, get all possible errors shown
        |
        */
        if (self::get_system('debug_mode'))
        {
            ob_start();

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

            //Show debug bar
            echo "<div style='width:100%;  overflow: hidden; color: #fff; height: 25px; padding: 5px; margin-bottom: 45px; background-color:#e74c3c; position: absolute; top:0; left: 0;'> <strong style='color: #fff; padding-right: 5px; border-right: 1px solid #fff;'> DEBUG MODE</strong>";

            //Show link to system configuration
            if(self::input_get('debug')!="show_system_config_settings")
            {
                echo "<a href='?debug=show_system_config_settings' style='text-decoration: none; color:#fff; font-size: 12px;  padding: 4px 45px 4px 4px; float: right;'> Show System Configuration Settings</a>";
            }

            echo "</div>";

            //Show system configuration
            if(!is_null(self::input_get('debug')) && self::input_get('debug')=='show_system_config_settings')
            {
                //instantiate new replica reflection
                $r_d = new ReflectionClass('Replica');

                //format the output
                echo  "<div style='width::70%; padding: 15px; margin: 0 auto; border: 2px solid #d35400; border-radius: 5px; background-color: #f39c12'; color: #fff; font-size: 1.3em;'><h1> Replica Diagnosics</h1><hr> <pre>";

                //Start the system configuration
                echo "<h2>Replica Configuration</h2>";

                //Dump detailed system configuration
                var_dump(self::_system_configuration_settings());

                //Start the class reflection
                echo "<h2>Replica Methods</h2>";

                //dump detailed class information
                var_dump($r_d->getMethods());

                //end process
                echo "</pre></div>";

            }

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

    ############################################################################
    #               EXCLUSIVE REPLICA PRIVATE INTERNAL METHODS                 #
    ############################################################################


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
        $this->title = self::get_system('meta_title');

        //Set the default page meta description if no meta description is defined in the data
        $this->meta_description = self::get_system('meta_desc');

        //Set default page keywords is no meta keywords are defined in the data
        $this->meta_keywords = self::get_system('meta_tags');


        $data = self::_include_file($this->route() . self::get_system('ext'));

        //check to see if the page uses special template

        $custom_page_template = isset($data['template']) ? $data['template'] : null;


        //Send each variable in the page to the template
        foreach ($data as $data_key => $data_value)
        {
            //assign the variable name to is own key
            $this->$data_key = $data_value;
        }

        //Generate the view

        return $this->make($custom_page_template);

    }

    /*
    |--------------------------------------------------------------------------
    | Route();
    |--------------------------------------------------------------------------
    |
    | The route method is perhaps on of the most important method in Replica, it
    | analyzes the uri collections, verifies the existence of the requested resources,
    | and throwing in error when no resources in found with the requested uri
    |
    */

    /**
     * @return bool|string
     */
    private function route()
    {
        //assign the page location to its default setting
        $this->_page = self::get_system('path_to_pages_dir').$this->_page;

        // Uri collections

        $ur =$this->_parse_uri_collections();

        //Check to see if we have at least one uri other than index is requested
        if(count($ur)>=1 && reset($ur)!="")
        {

            //Declare empty variable to assign the processed request bits
            $request='';

            //Loop through everything in the $uri collection
            for($i=0; $i<count($ur); $i++)
            {

                //Check to see if the current iterant is the last in the collection
                if($i+1 == count($ur))
                {

                    //Since this is the last in the collection, we know its a page ans there is possibility it might have extension
                    $page_eval = explode('.', self::_whitespace_slashes($ur[$i]));

                    //lets check if has exploded into name and extension
                    if(count($page_eval)==2)
                    {
                        //Evaluate to see if the extension is the same as the Replica Page Extension configured in the settings
                        if(strtolower(end($page_eval))===self::get_system('page_extension'))
                        {
                            //If it evaluated correctly process the file according to this
                            $request.=DS.prev($page_eval);
                        }

                    }else
                    {
                        //Otherwise, there was no extension therefore just process as is
                        $request.=DS.$ur[$i];
                    }

                }elseif($i==0)
                {
                    //If the request collections are more than one, get the first one and process separately
                    $request.=$ur[$i];
                }else
                {
                    //Process everything else with proper os directory separator in between
                    $request.=DS.$ur[$i];
                }

            }

            //At this point we have our request file set and its time to evaluate for its existence
            if(self::_check_file(self::get_system('path_to_pages_dir').$request.self::get_system('ext')))
            {
                //Now it exists, lets turn on the flag that we have found our request
                $this->_request_exists = true;

                //Assign the page to the page
                $this->_page = self::get_system('path_to_pages_dir').$request;
            }


            //Verify that the flag for page found is turned on and if not
            if(!$this->_request_exists && $request!='')
            {
                //Check to see if this request a subdirectory of the root directory
                if(self::_is_dir(self::get_system('path_to_root_dir').self::_whitespace_slashes($request)))
                {
                    //If it is directory, then set a global flag * for assets to load properly via auto_dumper
                    define('AT_403_ON_DIR', true);

                    //Redirect user to 403 error and update http headers
                    return self::redirect_to(403);
                }

                //Check to see if default data page has been set in place
                if(self::_check_file(self::get_system('path_to_pages_dir').$request.DS.self::get_system('data_default').self::get_system('ext')))
                {

                    //If default data page is set in place then return that default data page before 404 error
                    $this->_page=self::get_system('path_to_pages_dir').$request.DS.self::get_system('data_default');

                    //otherwise redirect to 404 error

                }else
                {
                    //check to see if this is publicly accessible module

                    $module = self::scan_for('dir', self::get_system('path_to_modules_dir'), self::$_scan_dir_excludes);

                    if(in_array($ur[0], $module))
                    {
                        //module found
                        $module = self::get_system('path_to_modules_dir').$ur[0];

                        //parse module configuration file
                        $config = self::parse_json($module.DS.self::get_system('modules_config'));

                        //double check that the module is enabled
                        if($config['status'] == self::get_system('status_enabled'))
                        {

                            //verify the module is standalone application
                            if($config['type']==self::get_system('module_type_app'))
                            {
                                //try to include the module class
                                if($this->_include_file($module.DS.$config['class'].self::get_system('ext')))
                                {
                                    //create new class from the application
                                    new $config['class']();

                                    //kill the application from loading anything else
                                    exit;

                                }

                            }
                        }

                    }else
                    {

                        //If the request is not directory, it's now obvious that the resource doesn't exist so Redirect to 404
                        return self::redirect_to(404);

                    }
                }

            } //end of checking file not found error

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
     | $this->_parse_uri_collections()
     |--------------------------------------------------------------------------
     |
     | Parses the uri for the route method
     |
     */


    /**
     * @param string $query
     * @return array
     */
    private function _parse_uri_collections($query='')
    {
        //prep the query to get
        $q = !empty($query) ? $query : 'replica_uri';

        return explode('/', rtrim(filter_var(self::input_get($q,'get'), FILTER_SANITIZE_URL),'/'));
    }

    /*
    |--------------------------------------------------------------------------
    | _module_get()
    |--------------------------------------------------------------------------
    |
    | fetch module configuration
    |
    */

    /**
     * @param $path
     * @param $action
     * @return bool
     */
    private function _module_get($path, $action)
    {
        if($module = self::parse_json($path))
        {
            return $module[$action];
        }
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | self::_query_string()
    |--------------------------------------------------------------------------
    |
    | Returns the uri query string, this method is needed to properly load
    | the assets into their relative path. Modification of this method can
    | break the linkage to stylesheet and javascript
    | if assets are loaded via Replica::asset_load() aka assets auto_dumper
    |
    */

    /**
     * @return array
     */
    private static function _query_string()
    {
        //Get the query string
        $url = filter_var($_SERVER['QUERY_STRING'], FILTER_SANITIZE_URL);

        //Explode the query string at the equal sign
        $qs = explode('=', $url);

        //If exploded properly there should me more than one in $qs count
        if (count($qs) > 1)
            //now explode the second one by the slash as we only need the second one and the rest will be discarded
            return explode('/', next($qs));
        else
            //if there aren't any then just return empty array
            return [];
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
                //The theme must have default page as defined in index.php

                self::get_system('theme_index'),       // REPLICA_THEME_DEFAULT_INDEX

                //CSS directory must be present as defined in index.php

                self::get_system('theme_css_dir'),     //  REPLICA_THEME_CSS_DIR

                //JS directory must be present as defined in index.php

                self::get_system('theme_js_dir'),     //  REPLICA_THEME_JS_DIR

                //Images directory must be present as defined in index.php

                self::get_system('theme_img_dir'),   //  REPLICA_THEME_JS_DIR

                //Main stylesheet must be present as defined in index.php

                self::get_system('theme_main_css'),   //  REPLICA_THEME_DEFAULT_CSS_FILE

                //Errors template must be present as defined in index.php

                self::get_system('theme_errors_tpl'),  //  REPLICA_THEME_ERRORS_TEMPLATE

                //Partial directory must be present as defined in index.php

                self::get_system('theme_partial')     //  REPLICA_THEME_PARTIAL_DIR
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


    ############################################################################
    #               PROTECTED METHODS AVAILABLE INTERNALLY TO CHILD CLASSES    #
    ############################################################################



    /*
    |--------------------------------------------------------------------------
    | _include_file()
    |--------------------------------------------------------------------------
    |
    | Require in the file if it exists and is
    | readable
    |
    | ** CHANGES **
    | 12/22 - changed from private to protected method - sh
    |
    */

    /**
     * @param $path
     * @return mixed|null
     */
    protected static function _include_file($path)
    {
        if (self::_check_file($path)) {
            return require_once $path;
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Replica::_whitespace_slashes()
    |--------------------------------------------------------------------------
    | Trims slashes and spaces from right and left
    |
    | ** CHANGES **
    | 12/22 - changed from private to protected method
    |
    */

    /**
     * @param $var
     * @return string
     */
    protected static function _whitespace_slashes($var)
    {
        return trim($var, "\x00..\x20/");
    }



    /*
    |--------------------------------------------------------------------------
    | $this->_get_uri_collections()
    |--------------------------------------------------------------------------
    |
    | get specified queries from http get
    |
    */

    /**
     * @param $q
     * @return array
     */
    protected  function _get_uri_collections($q)
    {
        /** @var  $this */
        return $this->_parse_uri_collections($q);
    }

    # REPLICA NON STATIC

    /*
   |--------------------------------------------------------------------------
   | $obj->get_theme_config();
   |--------------------------------------------------------------------------
   |
   | Returns current theme configurations setting
   |
   */

    /**
     * @param $format
     * @return array|mixed|null
     */
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

        return [];
    }

    /*
   |--------------------------------------------------------------------------
   | $obj->tcong($f);
   |--------------------------------------------------------------------------
   |
   | Alias to $obj->get_theme_config($format);
   |
   */

    /**
     * @param $f : format
     * @return array|mixed|null
     */
    public function tconf($f)
    {
        return $this->get_theme_config($f);
    }


    ############################################################################
    #               PUBLICLY ACCESSIBLE OBJECT METHODS                         #
    ############################################################################

    /*
    |--------------------------------------------------------------------------
    | $this->replica_exceptions_handler()
    |--------------------------------------------------------------------------
    |
    | Handles custom exceptions
    |
    |
    */
    /**
     * @param Exception $e
     * @return bool
     */
    public function replica_exceptions_handler(Exception $e)
   {
       //extract the error code from the exception
       $code = (is_numeric($e->getCode())) ? $e->getCode() : 500;

       //Send it over to Redirect method to handle the view controll
       return self::redirect_to($code,$e);

   }

    /*
    |--------------------------------------------------------------------------
    | $this->make('template_path');
    |--------------------------------------------------------------------------
    |
    | A method responsible for validating and generating request view template
    | from the current theme. This method is the second most important method
    | and the reason behind developing Replica.
    |
    |
    */
    /**
     * @param null $path
     * @return mixed
     */
    public function make($path = null)
    {

        //Initiate the template directory
        $template_dir = $this->_is_theme_dir(self::get_system('path_to_assets_dir') . self::get_system('theme').DS) ? self::get_system('theme') : self::get_system('default_theme');

        //template to be used

        $this->_template = self::get_system('path_to_assets_dir') . $template_dir . DS;

        //Theme name used to grab only the name
        define('CURRENT_THEME_NAME', $template_dir);

        //Theme dir used for internal only
        define('CURRENT_THEME_DIR', $this->_template);

        // Theme configuration file

        if ($theme_config = self::_check_file(CURRENT_THEME_DIR . CURRENT_THEME_NAME . '.json'))
        {
            $this->$theme_config = $theme_config;
        }

        // Collect all the various assigned variables to the view or template and make it available

        foreach ($this->_template_data as $data => $value)
        {
            //Assign the data variable to itself (** DO NOT REMOVE THE SECOND "$" FROM $$DATA ***)
            $$data = $value;
        }

        //Check to see if specific template is requested
        if ($path) {

            //If the request is for specific template, check to see if it exists
            if (self::_check_file($this->_template . $path . self::get_system('ext'))) {

                //If the requested template exist render that view and complete the task
                return require_once $this->_template . $path .self::get_system('ext');
            }
        }

        //If the requested template doesn't exist or there was no template requested than
        //render the default theme template by default.

        return require_once $this->_template . self::get_system('theme_index');

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






    ############################################################################
    #               PUBLICLY ACCESSIBLE STATIC METHODS                         #
    ############################################################################


    /*
    |--------------------------------------------------------------------------
    | _check_file()
    |--------------------------------------------------------------------------
    |
    | Checks if given file exists and is readable
    |
    | ** CHANGES **
    | 12/22 changed from private to public method, underscore (_) will be removed
    | in future version
    |
    */

    /**
     * @param $path
     * @return bool
     */
    public static function _check_file($path)
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
    | ** CHANGES **
    | 12/22 changed from private to public method, underscore (_) will be removed
    | in the future version
    |
    */
    /**
     * @param $path
     * @return bool
     */
    public static function _is_dir($path)
    {
        if (is_dir($path) && !is_file($path) && is_readable($path)) {
            return true;
        }

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | Replica::parse_json()
    |--------------------------------------------------------------------------
    |
    | process json file
    |
    */


    /**
     * @param $path
     * @return bool|mixed
     */
    public static function parse_json($path)
    {
        //see if the configuration file is available
        if(self::_check_file($path))
        {
            //get the contents of the config file
            $config = file_get_contents($path);

            //return the configuration file for the module
            return json_decode($config, true);
        }

        //there is nothing to return
        return false;
    }

    /*
   |--------------------------------------------------------------------------
   | Replica::p2a(p)
   |--------------------------------------------------------------------------
   |
   | Alias to Replica::parse_json(), parses json file and returns array
   |
   */

    /**
     * @param $p
     * @return bool|mixed
     */
    public static function p2a($p)
    {
        //return parsed json
        return self::parse_json($p);
    }



    /*
    |--------------------------------------------------------------------------
    | scan_for_dirs($type, $path,$custom_excludes)
    |--------------------------------------------------------------------------
    |
    |  multi purpose directory scanner, fetches all the data in the directory,
    |  or only directories or only non directories
    |
    |  Originally written as private method, later made to public static method
    |  for its usefulness in and out of Replica class.
    |
    */


    /**
     * @param $type
     * @param $path
     * @param array $custom_excludes
     * @return array
     */

    public static function scan_for($type,$path, $custom_excludes=[])
    {

        //Normalize the type to lower case
        $type = strtolower($type);

        //determine exclude list, since method is now public user can set their own custom excludes are param.
        $excludes = count($custom_excludes) ? $custom_excludes : self::$_scan_dir_excludes;

        //get the result of the scan different to excludes
        $result_all = array_diff(scandir($path), $excludes);

        //Initialize a variable to collect only what is requested
        $result_request_only =[];

        //loop through all the results
        foreach($result_all as $result)
        {

            //if the request is only for directory skip any non dirs
            if(in_array($type, self::get_system('scan_for_dirs')))
            {
                if(!self::_is_dir($path.DS.$result)) continue;
            }

            //If the request is for non dirs, skip the dirs
            if(in_array($type, self::get_system('scan_for_non_dirs')))
            {
                if(self::_is_dir($path.DS.$result)) continue;
            }

            //assign the result to variable ** IF NO TYPE IS DEFINED ALL MIXED RESULT RETURNED
            $result_request_only[]= $result;

        }

        //Return list of the directories
        return $result_request_only;

    }

    /*
    |--------------------------------------------------------------------------
    | Replica::sf('t','p',[])
    |--------------------------------------------------------------------------
    | Shorter alias for Replica::scan_for());
    |
    */

    /**
     * @param $t : type
     * @param $p : path
     * @param array $ce  :custom exclusions list
     * @return array
     */
    public static function sf($t, $p, $ce=[])
    {
        return self::scan_for($t, $p, $ce);
    }

    /*
    |--------------------------------------------------------------------------
    | Replica::widget_load('nav','main');
    |--------------------------------------------------------------------------
    |
    | Get the replica nav and widgets
    |
    */


    /**
     * @param $type
     * @param $path
     * @return array|mixed|null
     */
    public static function widget_load($type,$path)
    {

        //type
        $type = strtolower($type);

        //Check to see if nav widget is requested under varies names
        if(in_array($type, self::get_system('widget_load_navigation_system')))
        {
            //if the request exist than return array
            if(self::_check_file(self::get_system('path_to_nav_dir').$path.self::get_system('ext')))

                return self::_include_file(self::get_system('path_to_nav_dir').$path.self::get_system('ext'));
        }

        //check to see if the request is to load widget
        elseif(in_array($type, self::get_system('widget_load_widget')))
        {
            //If the request exists return in array format
            if(self::_check_file(self::get_system('path_to_widgets_dir').$path.self::get_system('ext')))


                return self::_include_file(self::get_system('path_to_widgets_dir').$path.self::get_system('ext'));
        }

        //if there is nothing just return empty array that foreach doesn't return error
        return  [];
    }


    /*
    |--------------------------------------------------------------------------
    | Replica::wl('t','p')
    |--------------------------------------------------------------------------
    | Shorter alias for Replica::widget_load();
    |
    */

    /**
     * @param $t : type
     * @param $p : path
     * @return array|mixed|null
     */
    public static function wl($t, $p)
    {
        return self::widget_load($t, $p);
    }

    /*
    |--------------------------------------------------------------------------
    | Replica::redirect_to(404); function
    |--------------------------------------------------------------------------
    | Redirects throughout the application and
    | renders error pages
    |
    */

    /**
     * @param null $location
     * @param Exception $e
     * @return bool
     */
    public static function redirect_to($location=null, Exception $e=null)
    {

        //by default location is null so check is location is passed in
        if ($location)
        {

            //check to see if location is a number
            if (is_numeric($location))
            {

                //Instantiate the replica class
                $replica = new Replica();

                //if location is a number evaluate against these predefined errors
                switch ($location)
                {

                    //Check if is 404
                    case '404':

                        //Force http header to send real error
                        header('HTTP/1.0 404 File Not Found');

                        //set a custom title of the error
                        $replica->replica_exceptions_error_title = self::get_system('redirect_to_404_title');

                        //set custom header for the error page
                        $replica->replica_exceptions_error_header = self::get_system('redirect_to_404_heading');

                        //set custom message for the error
                        $replica->replica_exceptions_error_body = self::get_system('redirect_to_404_message');

                        //make the display for the errors page
                        $replica->make(self::get_system('redirect_to_error_tpl'));

                        //terminate the process
                        exit;

                    //check for 403
                    case '403':

                        //force http header to send real 403 error
                        header('HTTP/1.0 403 Forbidden');

                        //set custom title for the 403 error page
                        $replica->replica_exceptions_error_title = self::get_system('redirect_to_403_title');

                        //set custom error header
                        $replica->replica_exceptions_error_header = self::get_system('redirect_to_403_heading');

                        //set custom error message
                        $replica->replica_exceptions_error_body = self::get_system('redirect_to_403_message');

                        //generate the error page
                        $replica->make(self::get_system('redirect_to_error_tpl'));

                        //Exist the process
                        exit;

                    default:
                        $http_header_message ="";

                        # REPLICA SPECIFIC ERROR CODES

                        # DEFAULT HTTP ERROR CODES

                        //set to Bad Request
                        if($e->getCode()==400)
                            $http_header_message="400 Bad Request";

                        //set to unauthorized
                        if($e->getCode()==401)
                            $http_header_message="400 Unauthorized";

                        //set to Internal Server Error
                        if($e->getCode()==500)
                            $http_header_message ="500 Internal Server Error";

                        //set to Bad Gateway
                        if($e->getCode()==502)
                            $http_header_message ="502 Bad Gateway";

                        //set to Service Unavailable
                        if($e->getCode()==503)
                            $http_header_message="503 Service Unavailable";

                        //set to Gateway Timeout
                        if($e->getCode()==504)
                            $http_header_message="504 Gateway Timeout";


                        header("HTTP/1.1 {$http_header_message}");

                        //set the title of the error
                        $replica->replica_exceptions_error_title = "Error ".$e->getCode()." occurred";

                        //set the view heading
                        $replica->replica_exceptions_error_header = $e->getCode();

                        //set the view message
                        $replica->replica_exceptions_error_body = $e->getMessage();

                        //set the exception line number
                        $replica->replica_exceptions_error_line = $e->getLine();

                        //set the exception file
                        $replica->replica_exceptions_error_file = $e->getFile();

                        //set the trace
                        $replica->replica_exceptions_error_trace = $e->getTrace();

                        //set the trace string
                        $replica->replica_exceptions_error_traceString = $e->getTraceAsString();

                        $replica->make(self::get_system('redirect_to_error_tpl'));

                        exit;
                }


            } else
            {
                //if it is not a number is must be url so try to redirect
                header("location: {$location}");

                //exit
                exit;
            }
        }

        //otherwise return false
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Replica::rt(404)
    |--------------------------------------------------------------------------
    | Shorter alias for Replica::redirect_to();
    |
    */

    /**
     * @param $l : location
     * @return bool
     */
    public static function rt($l)
    {
        return self::redirect_to($l);
    }


    /*
     |--------------------------------------------------------------------------
     | Replica::include_partial('header')
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
     * @param $type
     * @param $partial
     * @param array $params
     * @return mixed|null
     */
    public static function include_partial($type="", $partial, $params = [])
    {
        //Convert partial to all lower so we can type match
        $partial = strtolower($partial);
        $type = strtolower($type);

        //Request require file
        $request = null;

        //Prepare the default
        $default =(self::_check_file(CURRENT_THEME_DIR.self::get_system('theme_partial').DS.$partial.self::get_system('ext'))) ? CURRENT_THEME_DIR.self::get_system('theme_partial').DS.$partial.self::get_system('ext') : null;

        #TEST TO SEE IF SPECIFIC PART IS REQUESTED
        if (in_array($type, self::get_system('include_partial_header')))
        {

            $title = isset($params['title']) ? $params['title'] : self::get_system('meta_title');
            $meta_description = isset($params['meta_description']) ? $params['meta_description'] : self::get_system('meta_desc');
            $meta_keywords = isset($params['meta_keywords']) ? $params['meta_keywords'] : self::get_system('meta_tags');

            $request = $default;
            #HEADER HAS BEEN REQUESTED

        } elseif (in_array($type, self::get_system('include_partial_sidebar')))
        {

            //Since the designer can choose to put the sidebar in different directory lets look at that dir
            $request=(self::_check_file(CURRENT_THEME_DIR.self::get_system('theme_sidebars').DS.$partial.self::get_system('ext'))) ? CURRENT_THEME_DIR.self::get_system('theme_sidebars').DS.$partial.self::get_system('ext') : null;

            #SIDEBAR HAS BEEN REQUESTED

        } elseif (in_array($type, self::get_system('include_partial_widget')))
        {

            //Now since widgets can also have their own custom directories lets check the widget directory
            $request=(self::_check_file(CURRENT_THEME_DIR.self::get_system('theme_widgets').DS.$partial.self::get_system('ext'))) ? CURRENT_THEME_DIR.self::get_system('theme_widgets').DS.$partial.self::get_system('ext') : null;

            #WIDGETS HAS BEEN REQUESTED
        }else
        {
            //If none of the above partials are defined, return the partials directory as default
            $request= $default;
        }

        //Check to see to make sure there is value within the result
        if(!is_null($request))

            //if there is a result then return the data of the request file
            return require_once $request;

        //otherwise return empty result
        return $request;
    }

    /*
    |--------------------------------------------------------------------------
    | Replica::ip('t','p',[])
    |--------------------------------------------------------------------------
    | Shorter alias for Replica::include_partial();
    |
    */

    /**
     * @param $t :type
     * @param $p : partial
     * @param array $pr : parameters
     * @return mixed|null
     */
    public static function ip($t, $p, $pr=[])
    {
        return self::include_partial($t, $p, $pr);
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
    public static function assets_load($type,$assets=[])
    {
        //Convert type to lowercase for case matching
        $type = strtolower($type);

        //Initialize @$auto_dumper variable to collect information
        $auto_dumper="<!--".self::get_system('system_name')." ".self::get_system('system_version')." assets  auto-dump: {$type} //-->".PHP_EOL;

        //Check make sure there is at lease one request in the array before stating any work
        if(count($assets) >=1)
        {

            //Loop through each requested resource
            foreach($assets as $asset)
            {
                //Verify that the resource does exist before we continue with any thing
                if(self::_check_file(CURRENT_THEME_DIR.$asset))
                {

                    //define default separator
                    $url_separator ="";

                    //Count the counter page query string count to determine number of separators needed
                    for($i=self::get_system('assets_load_counter_start'); $i<count(self::_query_string()); $i++)
                    {
                        //for every count add this to separator
                        $url_separator.="../";
                    }

                    //determine the type of requested resource
                    if(in_array($type,self::get_system('assets_load_css')))
                    {
                        //If the request type is stylesheet only deal with css and assign all of the to the dumper
                        $auto_dumper.='<link rel="stylesheet" href="' . $url_separator . self::get_system('assets_dir_name') . '/' . CURRENT_THEME_NAME . '/' . $asset .'">' . PHP_EOL;

                    }elseif(in_array($type,self::get_system('assets_load_js')))
                    {
                        //Or if the request type is javascript assign all verified javascript files to the dumper
                        $auto_dumper.='<script src="' . $url_separator . self::get_system('assets_dir_name') . '/' . CURRENT_THEME_NAME . '/' . $asset .'"> </script>' . PHP_EOL;
                    }
                }
            }


            //Dump everything to the page
            return $auto_dumper;
        }

        return null;

    }


    /*
    |--------------------------------------------------------------------------
    | Replica::al('t',[])
    |--------------------------------------------------------------------------
    | Shorter alias for Replica::asset_load('type',[assets])
    |
    */


    /**
     * @param $t : type
     * @param array $a : assets
     * @return null|string
     */
    public static  function al($t, $a=[])
    {
        //assets load
        return self::assets_load($t, $a);
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
    | Replica::uri();
    |--------------------------------------------------------------------------
    | Shorter alias for Replica::get_base_uri('data')
    |
    */

    /**
     * @return mixed
     */
    public static  function uri()
    {
        return self::get_base_uri();
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
    | Replica::e('data')
    |--------------------------------------------------------------------------
    | Shorter alias for Replica::escape('data')
    |
    */

    /**
     * @param $v : variable to escape
     * @return string
     */
    public static function esc($v)
    {
        //return the scape method
        return self::escape($v);
    }


    /*
   |--------------------------------------------------------------------------
   | Replica::input_get($var);
   |--------------------------------------------------------------------------
   | Gets the available variable from either post or get
   |
   */


    /**
     * @param $var
     * @return string
     */
    public static function input_get($var)
    {
        //Check to see if post get is set
        if(isset($_POST[$var]))
        {
            //if it is return post var
            return $_POST[$var];
        }

        //check to see if get var is set
        elseif(isset($_GET[$var]))
        {
            //return get var
            return $_GET[$var];
        }

        //return empty string by default
        return '';
    }

    /*
   |--------------------------------------------------------------------------
   | Replica::in($var);
   |--------------------------------------------------------------------------
   | Alias to Replica::input_get()
   |
   */


    /**
     * @param $v
     * @return string
     */
    public static function in($v)
    {
        //return the input_get() method
        return self::input_get($v);
    }


    /*
    |--------------------------------------------------------------------------
    | Replica::input_exists();
    |--------------------------------------------------------------------------
    | returns $_POST or $_GET  on request variable if exist or
    | returns false
    |
    */

    /**
     * @param string $type
     * @return bool
     */
    public static function input_exists($type='post')
    {

        //switch the type
        switch(strtolower($type))
        {
            //if the case being evaluated is post
            case self::get_system('input_exists_case_post'):

                //return true or false based on the existence of the request
                return (!empty($_POST)) ? true : false;

            //if the case is to be evaluated is get
            case self::get_system('input_exists_case_get'):

                //return true or false based on the result of get request
                return (!empty($_GET)) ? true : false;

            default:
                //by default return false
                return false;

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

        echo "<div><pre>";
        var_dump($var);
        echo "</pre></div>";

    }

    /*
    |--------------------------------------------------------------------------
    | Replica::get_system('configuration')
    |--------------------------------------------------------------------------
    | Method to parse system configuration settings
    |
    */


    /**
     * @param $request
     * @return array
     */
    public static function get_system($request=[])
    {

        if(count($request)) {
            //Test to see if the array key exists in the system configuration
            while ($config = array_key_exists($request, self::_system_configuration_settings())) {
                //if the result of the test comes true
                if ($config)

                    //return the value for that key
                    return self::_system_configuration_settings()[$request];
            }

            //otherwise if the key doesn't exist than return an empty array.
            return [];

        }else
        {
            //publicly access the entire system configuration setting at once.
            return self::_system_configuration_settings();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Replica::sg('config')
    |--------------------------------------------------------------------------
    | Shorter alias for Replica::get_system('configuration')
    |
    */

    /**
     * @param $r : request
     * @return array
     */
    public static  function gs($r)
    {
        //get system information
        return self::get_system($r);
    }


    /*
    |--------------------------------------------------------------------------
    | Replica::menu_generate(array)
    |--------------------------------------------------------------------------
    | Generates up to three label dropdown menu from single to multi dimensional
    | array data
    |
    */


    /**
     * @param array $data
     * @return bool|string
     */
    public static function menu_generate($data=[])
    {

        //Make sure there is at least one item in the array
        if(count($data)>=1)
        {

            //Initialize the nav var with ul
            $nav ="<ul>";

            //loop through the data
            foreach($data as $label=>$menu)
            {

                //check to see if the current item is not array
                if(!is_array($menu))
                {

                    //If it is not an array than it must just a link so add to link
                    $nav.='<li><a href="'.$menu.'">'.$label.'</a></li>';


                    //double check what was not added to link list is array
                }elseif(is_array($menu))
                {
                    $url = "#";
                    $get_label_url = explode('@', $label);

                    if(count($get_label_url)==2)
                    {
                        $url = end($get_label_url);
                        $label = prev($get_label_url);

                    }


                    //Now prepare nav for sub ul ul>li>ul
                    $nav.='<li><a href="'.$url.'">'.$label.'</a> <ul>';

                    //Loop through
                    foreach($menu as $clabel=>$cmenu)
                    {

                        //Verify the founded is not an array
                        if(!is_array($cmenu))
                        {
                            //add to link collections
                            $nav.='<li><a href="'.$cmenu.'">'.$clabel.'</a></li>';


                            //Verify again for the third label
                        }elseif(is_array($cmenu))
                        {

                            $url = "#";
                            $get_label_url = explode('@', $clabel);

                            if(count($get_label_url)==2)
                            {
                                $url = end($get_label_url);
                                $clabel = prev($get_label_url);

                            }


                            //initilize a collection sub ul ul>li>ul>li
                            $nav.='<li><a href="'.$url.'">'.$clabel.'</a> <ul>';


                            //loop through the gran children
                            foreach($cmenu as $glabel=>$gmenu)
                            {
                                //just before adding to collection verify it is not array
                                if(!is_array($gmenu))
                                {

                                    //Add to the collection
                                    $nav.='<li><a href="'.$gmenu.'">'.$glabel.'</a></li>';
                                }
                            }

                            //Close the grand children
                            $nav.="</ul></li>";
                        }

                        //Unset the grandchild

                        unset($gmenu);

                        //unset the child menu

                        unset($cmenu);
                    }

                    //Close children
                    $nav.="</ul></li>";
                }

                //unset the menu
                unset($menu);
            }

            //Close parent
            $nav.="</ul>";

            //return the result
            return $nav;
        }

        //If there is nothing passed don't waste resource
        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Replica::mgen(array)
    |--------------------------------------------------------------------------
    |
    | Alias to Replica::menu_generate([]);
    |
    */

    /**
     * @param array $d : data
     * @return bool|string
     */
    public static function mgen($d=[])
    {
        //get menu generator system
        return self::menu_generate($d);
    }


    /*
    |--------------------------------------------------------------------------
    | Replica::token('action', $token);
    |--------------------------------------------------------------------------
    |
    | Generates 32bit token key to secure forms and other things
    |
    */

    /**
     * @param $action
     * @param $token
     * @return bool|string
     */
    public static function token($action, $token)
    {
        switch(strtolower($action))
        {
            //If action to generate new toke
            case self::get_system('token_case_generate'):

                //Return new token
                return $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));

            //Check is to verify the existing token
            case self::get_system('token_case_check'):

                //if the existing token is equal to the in question then return true and delete
                if($token == $_SESSION['token'])
                {
                    //Unset the current toke
                    unset($_SESSION['token']);

                    //return true
                    return true;
                }
        }

        //Otherwise return false
        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Replica::tk('action', 'token');
    |--------------------------------------------------------------------------
    |
    | Alias to Replica::token();
    |
    */


    /**
     * @param $a : action
     * @param $t : token
     * @return bool|string
     */
    public static function tk($a, $t)
    {
        //return the token method
        return self::token($a, $t);
    }


    /*
    |--------------------------------------------------------------------------
    | Replica::session('action',[])
    |--------------------------------------------------------------------------
    | sessions method that will handle the session creation, destruction,  and
    | flashing messages
    |
    */


    /**
     * @param $action
     * @param array $param
     * @return bool|string
     */
    public static function session($action, $param=[])
    {
        //Switch on the action
        switch(strtolower($action))
        {

            //put session
            case self::get_system('session_case_put'):

                //put new session
                return $_SESSION[$param['name']]=$param['value'];

            //Check to see if the session exists
            case self::get_system('session_case_exists'):

                //if session exists return true or false if doesn't
                return (isset($_SESSION[$param['name']])) ? true: false;

            //get session
            case self::get_system('session_case_get'):

                //get session
                return $_SESSION[$param['name']];

            //delete session
            case self::get_system('session_case_delete'):

                //check it exists before deleting
                if(self::session('exists',[$param['name']]))
                {
                    //unset the session
                    unset($_SESSION[$param['name']]);
                }

                break;

            //flash message
            case self::get_system('session_case_flash'):

                //Check to see if the session exists
                if(self::session('exists',$param['name']))
                {
                    //assign the current content from session to variable
                    $session = self::session('get', $param['name']);

                    //delete the session key
                    self::session('delete',$param['name']);

                    //return the data stored in the variable
                    return $session;

                }elseif(isset($param['name']) && isset($param['content']))
                {
                    //check to see if name and contents are are if so set them to new flash
                    return self::session('put', ['name'=>$param['name'],'value'=>$param['content']]);
                }else
                {
                    //otherwise just return empty
                    return '';
                }

            //destroy all set sessions
            case self::get_system('session_case_destroy'):

                foreach($_SESSION as $key_to_destroy)
                {
                    unset($key_to_destroy);
                }

                return session_destroy();

        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Replica::ses()
    |--------------------------------------------------------------------------
    |
    | Alias to Replica::session()
    |
    */

    /**
     * @param $a : action
     * @param array $p : parameters
     * @return bool|string
     */
    public static function ses($a, $p=[])
    {
        //return self session method
        return self::session($a, $p);
    }



    /*
    |--------------------------------------------------------------------------
    | Replica::simple_auth()
    |--------------------------------------------------------------------------
    |
    | Very basic authentication  handler system if the developer or designer
    | wants to incorporate simple authentication system, this could handle
    | it pretty well.
    |
    |
    |# I WOULD NOT RECOMMEND SUCH FILE BASED AUTHENTICATION SYSTEM
    | FOR MORE THAN A DOZEN USERS AT MOST, OTHERWISE PLEASE CONSIDER DATABASE
    | IMPLEMENTATION, I HAVE AN EASY TO USE MYSQL PDO DATABASE WRAPPER I RELEASED
    | AS FREE AND OPEN SOURCE AND CAN EASILY BE IMPLEMENTED WITH REPLICA. PLEASE
    | GET IT FROM MY GITHUB ACCOUNT @ HTTP://GITHUB.COM/SP01010011
    |
    | IF YOU NEED ASSISTANCE INTEGRATING THE DATABASE SYSTEM FEEL FREE TO CONTACT
    | ME AT ANYTIME AT HELLO@SHARIF.CO
    |
    |
    */


    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public static function simple_auth($username, $password)
    {
        //Load list of the users
        $load_users_list = self::_include_file(self::get_system('user_authorized_list'));

        if(is_array($load_users_list))
        {
            //if the user list is properly loaded, loop through

            foreach($load_users_list as $user_id=>$user_data)
            {
                //skip if the username do not match the result
                if($user_data['username']!=$username) continue;

                //skip if the status of the account is set to disabled
                if($user_data['status']==self::get_system('status_disabled')) continue;

                // compare the username and password for the user
                if($user_data['username']==$username && $user_data['password']==$password)
                {

                    //If found matching username and password log user in

                    self::session('put', ['name'=>'id', 'value'=>$user_id]);

                    //Save current time stamp to logged in time
                    self::session('put',['name'=>self::get_system('user_session_loggedin_at'), 'value'=>time()]);

                    //set the initial stamp to last activity key
                    self::session('put',['name'=>self::get_system('user_session_last_activity'),'value'=>time()]);


                    //Assign each key aside from password to session key

                    foreach($user_data as $k=>$v)
                    {
                        //skip the password key
                        if($k=="password") continue;

                        //assign every key to session
                        self::session('put', ['name'=>$k, 'value'=>$v]);

                    }
                    // terminate the the loop

                    return true;
                }


            }
        }

        // the array list not loaded properly

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Replica::user()
    |--------------------------------------------------------------------------
    |
    | Method that will manage and manipulates Replica Simple Auth
    |
    */


    /**
     * @param $action
     * @param $data
     * @return bool
     */
    public static function user($action, $data=[])
    {
        //Normalize the action
        switch(strtolower($action))
        {
            //case login attempt to login user
            case self::get_system('user_case_login'):

                //call simple_auth() method and login
                return self::simple_auth($data['username'], $data['password']);

            //case logout attempt to logout user
            case self::get_system('user_case_logout'):

                //destroy all the session
                self::session('destroy');

                //setup a flash message
                self::session('flash',['name'=>self::get_system('user_logout_flash_label'), 'content'=>self::get_system('user_logout_flash_message')]);

                //redirection url check to see if user set a custom redirect url
                $redirect_location = (isset($data['redirect_to'])) ? $data['redirect_to'] : self::get_system('user_logout_url');

                //redirect to the logout url
                return self::redirect_to($redirect_location);

            //test to see if the user session has expired on it is still good
            case self::get_system('user_case_session_expired'):

                //calculate the session time
                if(time()-self::session('get',self::get_system('user_session_last_activity')) > self::get_system('user_session_expiry_time'))
                {
                    //if it expired destroy the session
                    self::session('destroy');

                    //notify user about the the time expiration
                    self::session('flash',['name'=>self::get_system('user_session_expired_flash_label'),'content'=>self::get_system('user_session_expired_flash_message')]);

                    //send uer back to login page
                    return self::redirect_to(self::get_system('user_login_failed_url'));
                }else
                {
                    //continue with user activity and update the last activity time to now
                    self::session('put',['name'=>self::get_system('user_session_last_activity'),'value'=>time()]);
                    return  false;
                }

        }

        //return false if no action is specified
        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Replica::find_simple_auth_user()
    |--------------------------------------------------------------------------
    |
    | Looks up and find the user data from  Replica SimpleAuth data
    |
    */

    /**
     * @param $id
     * @return array
     */
    public static function find_simple_auth_user($id)
    {
        //Load the data from the list of authorized users
        $list = self::_include_file(self::get_system('user_authorized_list'));

        //Double check to make sure there is some data in the list
        if(count($list)>=1)
        {
            //loop through the data
            foreach($list as $user_id=>$user_data)
            {
                //skip any key that is not the same as the user
                if($user_id!=$id) continue;

                //if found a match with the requested user
                if($user_id===$id)
                {
                    //return the user data
                    return $user_data;
                }
            }
        }

        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | Replica::find()
    |--------------------------------------------------------------------------
    |
    | Alias to Replica::find_simple_auth_user();
    |
    */

    /**
     * @param $i : ID
     * @return array
     */
    public static function find($i)
    {
        //return the data from find_simple_auth_user() method
        return self::find_simple_auth_user($i);
    }


    /*
  |--------------------------------------------------------------------------
  | Replica::all_simple_auth_users();
  |--------------------------------------------------------------------------
  |
  | Returns list of all simple auth users with option to include or exclude
  | disabled account list.
  |
  */


    /**
     * @param bool $get_disabled
     * @return array|mixed|null
     */
    public static function all_simple_auth_users($get_disabled=false)
    {
        //Get list of users
        $list = self::_include_file(self::get_system('user_authorized_list'));

        //initialize an array to collect user data
        $all =[];

        //confirm there is at least one data in the list
        if(count($list)>=1)
        {

            //by default this method will not return disabled account
            if(!$get_disabled)
            {
                //loop through the accounts
                foreach($list as $user_id=>$user_data)
                {
                    //if there is disabled account skip it
                    if($user_data['status']==self::get_system('status_disabled')) continue;

                    //confirm the current looping account is not disabled
                    if($user_data['status']==self::get_system('status_active'))
                    {
                        //add to data collection
                        $all[$user_id] =$user_data;
                    }
                }

                //return the result
                return $all;

                //check to see if the requester explicitly request to include disabled accounts as well
            }elseif($get_disabled)
            {
                //if this is the case just return the list
                return $list;
            }
        }

        //if the above conditions are not met just return an empty array
        return [];

    }


    /*
   |--------------------------------------------------------------------------
   | Replica::all()
   |--------------------------------------------------------------------------
   |
   | Alias to Replica::all_simple_auth_users();
   |
   */


    /**
     * @param $gd : get disabled data :option true or false
     * @return array|mixed|null
     */
    public static function all($gd)
    {
        //return the data from all_simple_auth_users() method
        return self::all_simple_auth_users($gd);
    }




    ############################################################################
    #                   SYSTEM CONFIGURATION SETTINGS                          #
    ############################################################################


    /*
   |--------------------------------------------------------------------------
   | self::_system_configuration_settings()
   |--------------------------------------------------------------------------
   |
   | Holds array of the system configuration, formats user configurations e.g.
   | removes whitespaces, slashes, sets to correct casings acts as single point
   | to manage the overhaul system configuration options.
   |
   */

    /**
     * @return array
     */
    private static function _system_configuration_settings()
    {
        return
            [

                #GLOBAL SYSTEM SETTINGS

                //default configuration

                'timezone'                      =>  self::_whitespace_slashes(REPLICA_DEFAULT_TIME_ZONE),
                'debug_mode'                    =>  is_bool(REPLICA_DEBUG_MODE) ? REPLICA_DEBUG_MODE : false,
                'ext'                           =>  '.'.self::_whitespace_slashes(EXT),
                'default_theme'                 =>  'default',
                'data_default'                  =>  'main',

                //global module settings

                'modules_config'                =>  'config.json',
                'modules_type_extension'        =>  'extension', //modules that can be used  to extend functionality of the application
                'module_type_app'               =>  'app',  // standalone modules - that need to be routed independently of the application

                //General settings

                'status_enabled'                => 'enabled',
                'status_disabled'               => 'disabled',
                'status_active'                 => 'active',
                'status'                        => 'status',
                'type'                          => 'type',
                'version'                       => 'version',
                'author'                        => 'author',
                'url'                           => 'url',
                'full_name'                     => 'full_name',

                //Version Information

                'system_name'                   => 'Replica',
                'system_state'                  =>  true,
                'system_version'                =>  0.01,           //do not manually change this
                'system_release_date'           =>  '12/08/2014',
                'system_url'                    =>  'http://replica.sharif.co',

                #USER CUSTOMIZATION

                //Custom directory names ** NOT PATH JUST NAMES **

                'core_dir_name'                 => self::_whitespace_slashes(REPLICA_CUSTOM_CORE_DIR),
                'modules_dir_name'              => self::_whitespace_slashes(REPLICA_CUSTOM_MODULES_DIR),
                'assets_dir_name'               => self::_whitespace_slashes(REPLICA_CUSTOM_ASSETS_DIR),
                'data_dir_name'                 => self::_whitespace_slashes(REPLICA_CUSTOM_DATA_DIR),

                'nav_dir_name'                  => self::_whitespace_slashes(REPLICA_CUSTOM_DATA_NAV_DIR),
                'pages_dir_name'                => self::_whitespace_slashes(REPLICA_CUSTOM_DATA_PAGES_DIR),
                'widgets_dir_name'              => self::_whitespace_slashes(REPLICA_CUSTOM_DATA_WIDGETS_DIR),


                # DIRECTORIES

                //System

                'path_to_root_dir'              => REPLICA_ROOT_DIR,
                'path_to_core_dir'              => REPLICA_ROOT_DIR . self::_whitespace_slashes(REPLICA_CUSTOM_CORE_DIR) . DS,
                'path_to_assets_dir'            => REPLICA_ROOT_DIR . self::_whitespace_slashes(REPLICA_CUSTOM_ASSETS_DIR) . DS,
                'path_to_data_dir'              => REPLICA_ROOT_DIR . self::_whitespace_slashes(REPLICA_CUSTOM_DATA_DIR) . DS,
                'path_to_modules_dir'           => REPLICA_ROOT_DIR.  self::_whitespace_slashes(REPLICA_CUSTOM_CORE_DIR).DS.self::_whitespace_slashes(REPLICA_CUSTOM_MODULES_DIR) . DS,


                //Data

                'path_to_pages_dir'             => REPLICA_ROOT_DIR . self::_whitespace_slashes(REPLICA_CUSTOM_DATA_DIR) . DS . self::_whitespace_slashes(REPLICA_CUSTOM_DATA_PAGES_DIR) . DS,
                'path_to_nav_dir'               => REPLICA_ROOT_DIR . self::_whitespace_slashes(REPLICA_CUSTOM_DATA_DIR) . DS. self::_whitespace_slashes(REPLICA_CUSTOM_DATA_NAV_DIR) . DS,
                'path_to_widgets_dir'           => REPLICA_ROOT_DIR . self::_whitespace_slashes(REPLICA_CUSTOM_DATA_DIR) . DS . self::_whitespace_slashes(REPLICA_CUSTOM_DATA_WIDGETS_DIR) . DS,

                //Theme

                'theme'                         => self::_whitespace_slashes(REPLICA_THEME),
                'theme_partial'                 => self::_whitespace_slashes(REPLICA_THEME_PARTIAL_DIR),
                'theme_index'                   => self::_whitespace_slashes(REPLICA_THEME_DEFAULT_INDEX).'.'.EXT,
                'theme_css_dir'                 => self::_whitespace_slashes(REPLICA_THEME_CSS_DIR),
                'theme_js_dir'                  => self::_whitespace_slashes(REPLICA_THEME_JS_DIR),
                'theme_img_dir'                 => self::_whitespace_slashes(REPLICA_THEME_IMG_DIR),
                'theme_main_css'                => self::_whitespace_slashes(REPLICA_THEME_CSS_DIR).DS.self::_whitespace_slashes(REPLICA_THEME_DEFAULT_CSS_FILE).'.css',
                'theme_errors_tpl'              => self::_whitespace_slashes(REPLICA_THEME_ERRORS_TEMPLATE).'.'.EXT,
                'page_extension'                => self::_whitespace_slashes(strtolower(REPLICA_PAGE_EXTENSION)),
                'theme_sidebars'                => self::_whitespace_slashes(REPLICA_THEME_SIDEBARS_DIR),
                'theme_widgets'                 => self::_whitespace_slashes(REPLICA_THEME_WIDGETS_DIR),

                //Default settings

                'meta_title'                    => REPLICA_DEFAULT_SITE_NAME,
                'meta_desc'                     => REPLICA_DEFAULT_SITE_DESCRIPTION,
                'meta_tags'                     => REPLICA_DEFAULT_SITE_KEYWORDS,

                #CORE SYSTEM CONFIG : METHOD HELPERS


                //Replica::redirect_to()

                'redirect_to_error_tpl'        => self::_whitespace_slashes(REPLICA_THEME_ERRORS_TEMPLATE),
                'redirect_to_404_title'        => self::_whitespace_slashes(REPLICA_404_CUSTOM_ERROR_TITLE),
                'redirect_to_404_heading'      => self::_whitespace_slashes(REPLICA_404_CUSTOM_ERROR_HEADING),
                'redirect_to_404_message'      => self::_whitespace_slashes(REPLICA_404_CUSTOM_ERROR_MESSAGE),

                'redirect_to_403_title'        => self::_whitespace_slashes(REPLICA_403_CUSTOM_ERROR_TITLE),
                'redirect_to_403_heading'      => self::_whitespace_slashes(REPLICA_403_CUSTOM_ERROR_HEADING),
                'redirect_to_403_message'      => self::_whitespace_slashes(REPLICA_403_CUSTOM_ERROR_MESSAGE),

                'redirect_to_case_403'         => 403,
                'redirect_to_case_404'         => 404,
                'redirect_to_case_500'         => 500,

                //Replica::assets_load()

                'assets_load_css'               => ['css','stylesheet','style','styles','c'],
                'assets_load_js'                => ['js','javascript','script','scripts','j'],
                'assets_load_counter_start'     =>  defined('AT_403_ON_DIR') ? 1 : 1, // determines the counter start point for dynamic separators

                //Replica::widget_load()

                'widget_load_navigation_system'  => ['nav','navigation','menu','mainmenu'],
                'widget_load_widget'             => ['widget','widgets','addon','extension'],


                //Replica::include_partial()

                'include_partial_header'         => ['header','head','top'],
                'include_partial_footer'         => ['footer','bottom'],
                'include_partial_widget'         => ['widgets','widget','addon','addin'],
                'include_partial_sidebar'        => ['sidebar','aside','left_sidebar','right_sidebar','justify_sidebar','top_sidebar','bottom_sidebar','middle_sidebar'],

                //Replica::scan_for()

                'scan_for_dirs'                  => ['dir','dirs','directory','directories','folders','folder','non-file'],
                'scan_for_non_dirs'              => ['non-dirs','files','file','document','documents','docs'],

                //Replica::Token()
                'token_case_generate'            => 'generate',
                'token_case_check'               => 'check',

                //Replica::session()

                'session_case_put'               => 'put',
                'session_case_get'               => 'get',
                'session_case_exists'            => 'exists',
                'session_case_delete'            => 'delete',
                'session_case_destroy'           => 'destroy',

                //Replica::simple_auth() // Replica::user() and all related helper methods to SimpleAuth

                /*
                |--------------------------------------------------------------------------
                | WHY SIMPLE AUTH CONFIGURATIONS AND METHODS IN REPLICA CLASS?
                |--------------------------------------------------------------------------
                |
                | Normally, simpleAuth configurations and methods would not be part of Replica
                | ,however, since simpleAuth is technically part of Replica, it
                | would not be an ideal to extract simpleAuth configs and methods into its own
                | class. Ideally however, for any additional module must have its own class and
                | configuration file contained within itself in own directory in the modules
                | directory. Now, i've decided, simpleAuth is not standard module therefore
                | decided to bake it into the Replica class.
                |
                |
                */
                'user_authorized_list'         =>  REPLICA_ROOT_DIR.  self::_whitespace_slashes(REPLICA_CUSTOM_CORE_DIR).DS.self::_whitespace_slashes(REPLICA_CUSTOM_MODULES_DIR).DS.self::_whitespace_slashes(REPLICA_CUSTOM_MODULES_SIMPLEAUTH_DIR).DS.self::_whitespace_slashes(REPLICA_CUSTOM_MODULES_SIMPLEAUTH_FILE_DB).'.'.EXT,

                'user_login_success_url'       =>  self::get_base_uri().'user/profile.html',
                'user_login_failed_url'        =>  self::get_base_uri().'user/login.html?failed=true',
                'user_logout_url'              =>  self::get_base_uri().'user/logout.html?success=true',

                'user_login_failed_flash_label'      =>  'login_failed',
                'user_login_success_flash_label'     =>  'login_success',
                'user_logout_flash_label'            =>  'logout_success',

                'user_login_failed_flash_message'    =>  'Hello, sorry unable to log you into the system',
                'user_login_success_flash_message'   =>  'Hello %s, you have been successfully logged in',
                'user_logout_flash_message'          =>  'You have been successfully logged out of the system',

                'user_account_username'        => 'username',
                'user_account_password'        => 'password',
                'user_account_role'            => 'role',
                'user_account_created_at'      => 'created_at',
                'user_account_updated_at'      => 'updated_at',
                'user_account_email'           => 'email',

                'user_session_expiry_time'              => 300,
                'user_session_loggedin_at'              => 'loggedin_at',
                'user_session_last_activity'            => 'last_activity',
                'user_session_expired_flash_label'      => 'session_expired',
                'user_session_expired_flash_message'    => 'Your session has expired for inactivity, you must login again!',

                'user_case_login'                       => 'login',
                'user_case_session_expired'             => 'session',
                'user_case_logout'                      => 'logout',


                //Replica::input_exists()

                'input_exists_case_post'                => 'post',
                'input_exists_case_get'                 => 'get',
            ];
    }


}


//Start session
session_start();

//Instantiate the replica class
$app = new Replica();

