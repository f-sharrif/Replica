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
        if (self::get_system('debug_mode')) {

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
                if(self::input('get','debug')!="show_system_config_settings")
                {
                    echo "<a href='?debug=show_system_config_settings' style='text-decoration: none; color:#fff; font-size: 12px;  padding: 4px 45px 4px 4px; float: right;'> Show System Configuration Settings</a>";
                }

            echo "</div>";

            //Show system configuration
            if(!is_null(self::input('get','debug')) && self::input('get','debug')=='show_system_config_settings')
            {
                //instantiate new replica reflection
                $r_d = new ReflectionClass('Replica');

                //format the output
                echo  "<div style='width::90%; padding: 15px; margin: 0 auto; border: 2px solid #d35400; border-radius: 5px; background-color: #f39c12'; color: #fff; font-size: 1.3em;'><h1> Replica Diagnosics</h1><hr> <pre>";

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
    | analysis the uri collections, verifies the existence of the requested resources,
    | and throwing in error when no resources in found with the requested uri
    |
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

                //If the request is not directory, it's now obvious that the resource doesn't exist so Redirect to 404
                return self::redirect_to(404);
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
    | $this->_parse_uri_collections()
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
        return explode('/', rtrim(filter_var(self::input('get','replica_uri'), FILTER_SANITIZE_URL),'/'));
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

        return [];
    }

    # REPLICA STATICALLY ACCESSIBLE PUBLIC METHODS




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
     * @param $t
     * @param $p
     * @param array $ce
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
     * @param $t
     * @param $p
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
     * @param $location
     * @return bool
     */
    public static function redirect_to($location=null)
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
                        $replica->title = self::get_system('redirect_to_404_title');

                        //set custom header for the error page
                        $replica->header = self::get_system('redirect_to_404_heading');

                        //set custom message for the error
                        $replica->body = self::get_system('redirect_to_404_message');

                        //make the display for the errors page
                        $replica->make(self::get_system('redirect_to_error_tpl'));

                       //terminate the process
                        exit;

                   //check for 403
                    case '403':

                        //force http header to send real 403 error
                        header('HTTP/1.0 403 Forbidden');

                        //set custom title for the 403 error page
                        $replica->title = self::get_system('redirect_to_403_title');

                        //set custom error header
                        $replica->header = self::get_system('redirect_to_403_heading');

                        //set custom error message
                        $replica->body = self::get_system('redirect_to_403_message');

                        //generate the error page
                        $replica->make(self::get_system('redirect_to_error_tpl'));

                        //Exist the process
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
     * @param $l
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
     * @param $t
     * @param $p
     * @param array $pr
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
                        $auto_dumper.='<link rel="stylesheet" href="' . $url_separator . self::get_system('assets_dir_name') . '/' . CURRENT_THEME_NAME . '/' . $asset . '">' . PHP_EOL;

                    }elseif(in_array($type,self::get_system('assets_load_js')))
                    {
                        //Or if the request type is javascript assign all verified javascript files to the dumper
                        $auto_dumper.='<script src="' . $url_separator . self::get_system('assets_dir_name') . '/' . CURRENT_THEME_NAME . '/' . $asset . '"> </script>' . PHP_EOL;
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
     * @param $t
     * @param array $a
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
     * @param $v
     * @return string
     */
    public static function e($v)
    {
        return self::escape($v);
    }


    /*
    |--------------------------------------------------------------------------
    | Replica::input('get', 'query');
    |--------------------------------------------------------------------------
    | returns $_POST or $_GET  on request variable if exist or
    | returns empty string
    |
    */

    /**
     * @param string $type
     * @param $var
     * @return string
     */
    public static function input($type = 'post', $var)
    {
        //switch the type
        switch (strtolower($type))
        {
            //by default type is post
            case 'post':

                //is the post variable is set then return post var otherwise return empty string
                return isset($_POST[$var]) ? $_POST[$var] : '';

            //If request is for http get
            case 'get':

                //if get variable is set then return get var otherwise return empty array
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
    public static function get_system($request)
    {

        //Test to see if the array key exists in the system configuration
       while($config=array_key_exists($request, self::_system_configuration_settings()))
       {
           //if the result of the test comes true
           if($config)

               //return the value for that key
               return self::_system_configuration_settings()[$request];
       }

        //otherwise if the key doesn't exist than return an empty array.
        return [];
    }

   /*
   |--------------------------------------------------------------------------
   | Replica::sg('config')
   |--------------------------------------------------------------------------
   | Shorter alias for Replica::get_system('configuration')
   |
   */

    /**
     * @param $r
     * @return array
     */
    public static  function gs($r)
    {
        //get system information
        return self::get_system($r);
    }

    /*
   |--------------------------------------------------------------------------
   | self::_system_configuration_settings()
   |--------------------------------------------------------------------------
   | Holds array of the system configuration, formats user configurations e.g.
   | removes whitespaces, slashes, sets to correct casings acts as single place
   | to hold system wide configuration options.
   |
   */

    /**
     * @return array
     */
    private static function _system_configuration_settings()
    {
        return [

            #USER SYSTEM SETTINGS
            'timezone'                      =>  self::_whitespace_slashes(REPLICA_DEFAULT_TIME_ZONE),
            'debug_mode'                    =>  is_bool(REPLICA_DEBUG_MODE) ? REPLICA_DEBUG_MODE : false,
            'ext'                           =>  '.'.self::_whitespace_slashes(EXT),
            'default_theme'                 =>  'default',

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


            //Replica::redirect_to();

            'redirect_to_error_tpl'        => self::_whitespace_slashes(REPLICA_THEME_ERRORS_TEMPLATE),


            'redirect_to_404_title'        => self::_whitespace_slashes(REPLICA_404_CUSTOM_ERROR_TITLE),
            'redirect_to_404_heading'      => self::_whitespace_slashes(REPLICA_404_CUSTOM_ERROR_HEADING),
            'redirect_to_404_message'      => self::_whitespace_slashes(REPLICA_404_CUSTOM_ERROR_MESSAGE),


            'redirect_to_403_title'        => self::_whitespace_slashes(REPLICA_403_CUSTOM_ERROR_TITLE),
            'redirect_to_403_heading'      => self::_whitespace_slashes(REPLICA_403_CUSTOM_ERROR_HEADING),
            'redirect_to_403_message'      => self::_whitespace_slashes(REPLICA_403_CUSTOM_ERROR_MESSAGE),


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

            //Replica::scan_for();

            'scan_for_dirs'                  => ['dir','dirs','directory','directories','folders','folder','non-file'],
            'scan_for_non_dirs'              => ['non-dirs','files','file','document','documents','docs'],


            //Version Information

            'system_name'                    => 'Replica',
            'system_state'                   =>  true,
            'system_version'                 =>  0.01,           //do not manually change this
            'system_release_date'            =>  '12/08/2014',
            'system_url'                     =>  'http://replica.sharif.co'
        ];
    }


}


//Instantiate the replica class
$app = new Replica();