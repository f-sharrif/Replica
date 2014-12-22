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
     * @filesource: index.php
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


    #< START OF USER CONFIGURATION

    /*
    |--------------------------------------------------------------------------
    | Basic Setting
    |--------------------------------------------------------------------------
    |
    | Basic fallback settings
    |
    */

    //Default site name : used if page title not defined
    define('REPLICA_DEFAULT_SITE_NAME', 'Replica PHP Template Engine For Designers and Developer');

    //Set default site description used if page does not contain a description
    define('REPLICA_DEFAULT_SITE_DESCRIPTION', 'Replica allows designers and developers alike to develop small website faster and easier');


    //Default site keywords, used when page does not have keywords
    define('REPLICA_DEFAULT_SITE_KEYWORDS', 'Replica PHP, PHP Templating Engine, PHP Micro Sites');


    /*
    |--------------------------------------------------------------------------
    | Application Time Zone
    |--------------------------------------------------------------------------
    |
    | Provide system application time zone
    |
    */


    define('REPLICA_DEFAULT_TIME_ZONE', 'America/New_York');

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | Turn PHP error reporting on and off, this should be set to false on
    | live website
    |
    */

    //By default its set to true, set it to false once you're ready to deploy
    // your site to live server

    # ***NOTE** Must be boolean value of 'true' or 'false' otherwise debug mode
    // is set to false by default

    define('REPLICA_DEBUG_MODE', true);  //default => true

    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | Provide the default theme to be loaded, themes are located in the assets
    | directory and the must contain at least default.php
    |
    */

    //What is your site theme name
    define('REPLICA_THEME', 'default');  // default => default


    /*
    |--------------------------------------------------------------------------
    | Partial Files Directory
    |--------------------------------------------------------------------------
    |
    | This is the directory in your themes files where you keep
    | header, footer, sidebar and widget. By default Facile uses includes
    | directory as the partial directory, if you do not use partial directory
    | instead have all your files in the root of the theme directory tech is to
    | empty. Do not delete the Constant it will break the system.
    |
    */

    // Custom Partial template file directory relative to themes root

    define('REPLICA_THEME_PARTIAL_DIR', 'includes');  // default =>includes


    //specify your index page in your theme folder (index.php,default.php,
    // main.php, root.php")

    define('REPLICA_THEME_DEFAULT_INDEX', 'default'); // default =>default


    //please specify your 404 or 403 error templates location within the
    // theme directory

    define('REPLICA_THEME_ERRORS_TEMPLATE', 'errors/default');  //default =>errors/default


    /*
    |--------------------------------------------------------------------------
    | EXTREME FILE STRUCTURE CUSTOMIZATION {THEME FOLDER FILES AND DIRECTORIES}
    |--------------------------------------------------------------------------
    |
    | Replica does not restrict user to set of rules, or file name or structures
    | you're welcome to customize your files however your like, that is exactly what
    | this extreme customization section allows you to do.
    |
    | ***NOTE***
    | any of this option can be within any number of subdirectory deep, the only
    | requirement is that, they subdirectories must be within the themes dir.
    |
    */

    //What did you call your css directory name
    define('REPLICA_THEME_CSS_DIR', 'css'); //default => 'css'

    //What is your default images name in your theme directory
    define('REPLICA_THEME_IMG_DIR', 'img');  //default => 'img'

    //What is your default javascript directory
    define('REPLICA_THEME_JS_DIR',  'js');  // default =>'js'

    //What is your default cascading stylesheet file name
    define('REPLICA_THEME_DEFAULT_CSS_FILE', 'styles'); // default=>'styles'

    //What is your default javascript file name
    define('REPLICA_THEME_DEFAULT_JS_FILE', 'scripts');  //default=>'scripts'

    //If there is custom place for sidebars other than the includes partial

    define('REPLICA_THEME_SIDEBARS_DIR', REPLICA_THEME_PARTIAL_DIR); //By default => same as theme partials

    //If you keep your widgets templates anywhere other than where you keep includes partial

    define('REPLICA_THEME_WIDGETS_DIR', 'widgets'); //By default => widgets


    /*
    |--------------------------------------------------------------------------
    | Static Look and Feel Page Extension
    |--------------------------------------------------------------------------
    |
    | If you like static feel and look for your pages setup and extension e.g.
    | html => replica/about-us.html or  replica/services/web-development.html
    |
    |
    */

    //setting this doesn't force every page to be called with extension in fact,
    // if valid page is called without this extension it will render

    ### PLEASE NOTE: do not add dot(.) in front of the extension

    define('REPLICA_PAGE_EXTENSION', 'html');  //default =>html



    /*
    |--------------------------------------------------------------------------
    | DEFAULT ERRORS CUSTOMIZATION
    |--------------------------------------------------------------------------
    | Here are list of error types defined in replica by default,
    | this settings will allow you to customize this output errors
    |
    |
    */

    #CUSTOMIZE 404 AND 403 ERRORS

    //Window title for the 404 error page
    define('REPLICA_404_CUSTOM_ERROR_TITLE', 'Error 404 File Not Found'); //default => Error 404 File Not Found

    //Page header
    define('REPLICA_404_CUSTOM_ERROR_HEADING', '404');  //default => 404

    //Page message
    define('REPLICA_404_CUSTOM_ERROR_MESSAGE',  'Woops, page not found.');  //default => Woops, page not found

    //Window title for the 403 error page
    define('REPLICA_403_CUSTOM_ERROR_TITLE', 'Error 403 Forbidden Access');  //default => Error 403 Forbidden Access

    //Page header
    define('REPLICA_403_CUSTOM_ERROR_HEADING', '403');  // default => 403

    //Page message
    define('REPLICA_403_CUSTOM_ERROR_MESSAGE',  "Woops, you're totally forbidden from here"); // default => Woops, you're totally forbidden from here


    /*
    |--------------------------------------------------------------------------
    | SYSTEM CUSTOMIZATION
    |--------------------------------------------------------------------------
    | The system customization will allow you to define your own data, assets,
    | system directories locations and names.
    |
    */



     /*
     |--------------------------------------------------------------------------
     |              CUSTOM ASSETS DIRECTORY NAME
     |--------------------------------------------------------------------------
     |****** DON'T FORGET TO RENAME THE ACTUAL DIRECTORY ****
     | path relative to root e.g 'path/to/new/asset_dir'
     |
     |
     */

    define('REPLICA_CUSTOM_ASSETS_DIR', 'assets');  //default =>assets

     /*
     |--------------------------------------------------------------------------
     |              CUSTOM DATA DIRECTORY NAME
     |--------------------------------------------------------------------------
     |****** DON'T FORGET TO RENAME THE ACTUAL DIRECTORY ****
     | path relative to root e.g 'path/to/new/dat_dir'
     |
     |
     */

    define('REPLICA_CUSTOM_DATA_DIR', 'data');      // default =>data

    /*
    |--------------------------------------------------------------------------
    |              CUSTOM DATA/PAGES DIRECTORY NAME
    |--------------------------------------------------------------------------
    |****** DON'T FORGET TO RENAME THE ACTUAL DIRECTORY ****
    | path must be a subdir of data_dir, can go several lev-
    | al deep e.g.'data_dir/path/to/new/pages_dir'
    |
    |
    */
    define('REPLICA_CUSTOM_DATA_PAGES_DIR', 'pages'); //Default =>pages

     /*
     |--------------------------------------------------------------------------
     |              CUSTOM DATA/NAV DIRECTORY NAME
     |--------------------------------------------------------------------------
     |****** DON'T FORGET TO RENAME THE ACTUAL DIRECTORY ****
     | path must be a subdir of data_dir, can go several lev-
     | al deep e.g.'data_dir/path/to/new/nav_dir'
     |
     |
     */

    define('REPLICA_CUSTOM_DATA_NAV_DIR', 'nav'); //default =>nav

    /*
    |--------------------------------------------------------------------------
    |              CUSTOM DATA/WIDGETS DIRECTORY NAME
    |--------------------------------------------------------------------------
    |****** DON'T FORGET TO RENAME THE ACTUAL DIRECTORY ****
    | path must be a subdir of data_dir, can go several lev-
    | al deep e.g.'data_dir/path/to/new/widgets_dir'
    |
    |
    */

    define('REPLICA_CUSTOM_DATA_WIDGETS_DIR', 'widgets');  //Default =>widgets

    /*
    |--------------------------------------------------------------------------
    |              BASIC LOGIN SYSTEM : Replica SimpleAuth
    |--------------------------------------------------------------------------
    |****** DON'T FORGET TO RENAME THE ACTUAL DIRECTORY ****
    |
    |   Replica SimpleAuth is basic authentication system that comes with
    |   Replica class as the default authentication system. All data are saved
    |   in flat file, the system can handle multiple users and many different
    |   roles. Defined below are the customizable configuration option for
    |   Replica SimpleAuth module.
    |
    |
    */

    define('REPLICA_CUSTOM_MODULES_SIMPLEAUTH_DIR', 'simpleauth');  //Default =>simpleauth

    //Users list file name
    define('REPLICA_CUSTOM_MODULES_SIMPLEAUTH_FILE_DB', 'auth'); //Default => auth


    /*
    |--------------------------------------------------------------------------
    |              CUSTOM SYSTEM DIRECTORY NAME
    |--------------------------------------------------------------------------
    |****** DON'T FORGET TO RENAME THE ACTUAL DIRECTORY ****
    | path relative to root e.g 'path/to/new/system_dir'
    |
    |
    */


    //Location of system core modules classes must be with your
    // defined core directory

    define('REPLICA_CUSTOM_MODULES_DIR', 'modules'); //default =>core/modules

    //Location of the system core Replica Class
    define('REPLICA_CUSTOM_CORE_DIR', 'core'); //default =>Core


    #   END OF USER CONFIGURATION />


    /*
    |--------------------------------------------------------------------------
    | REPLICA CLASS FILE NAME
    |--------------------------------------------------------------------------
    | Extremely customize the system the way that you want to work with and the
    | way that works for you. Remove all the default namings and directory
    | structure to fit your project.
    |
    */


    //Rename Replica class file
    define('REPLICA_CUSTOM_CLASS_FILE_NAME', 'Replica');        //default =>Replica



     /*
     |-------------------------------------------------------------------------
     | *** DO NOT MODIFY ANYTHING BEYOND THIS POINT ***
     |-------------------------------------------------------------------------
     |
     | PLEASE DO NOT MODIFY ANY OF THE FOLLOWING CONSTANTS, THEY ARE THE
     | BUILDING BLOCK OF THE APPLICATION, ANY MODIFICATION TO THE BELOW SETTINGS
     | CAN AND WILL BREAK THE SYSTEM.
     |
     |      ---------------------------------------------------
     |      ## DEFAULT SETTINGS FOR THIS IMPORTANT CONSTANTS ##
     |      ---------------------------------------------------
     |
     |      #OS Directory separator short name
     |      -----------------------------------
     |      define('DS', DIRECTORY_SEPARATOR);
     |
     |      #system file extension
     |     ------------------------------------
     |      define('EXT','php');
     |
     |     #System root directory
     |     ------------------------------------
     |      define('REPLICA_ROOT_DIR', __DIR__.DS);
     |
     |
     */

    //OS Directory separator short name
    define('DS', DIRECTORY_SEPARATOR);

    ### PLEASE NOTE: Do not add dot(.) in front of the extension

    //System file extension
    define('EXT', 'php');

    /*
    |--------------------------------------------------------------------------
    | APPLICATION ROOT DIRECTORY
    |--------------------------------------------------------------------------
    |                   **DO NOT MODIFY THIS OPTION***
    | Root dir definition moved from bootstrap to index to allow system
    | customization and being able to move any system components anywhere
    | according to users customization without breaking the system.
    |
    */


    ### DO NOT CHANGE THIS, IS THE STARTING POINT FOR THE APP TO WORK ###
    define('REPLICA_ROOT_DIR', __DIR__ . DS);

    /*
    |--------------------------------------------------------------------------
    | Bootstrap the Application
    |--------------------------------------------------------------------------
    |
    | Bootstrap the application, require the core class and prepare for run
    |
    */

    if(is_readable(REPLICA_CUSTOM_CORE_DIR . DS . REPLICA_CUSTOM_CLASS_FILE_NAME .'.'.EXT))
    {
        require_once REPLICA_CUSTOM_CORE_DIR . DS . REPLICA_CUSTOM_CLASS_FILE_NAME .'.'. EXT;

    }else
    {
        die('<html><head><title>Fatal Error Occurred</title></head><body> <div style="color: #fff; font-family: sans-serif; font-weight: 400; width:760px; margin: 0 auto; padding: 20px; border-radius 10px; background-color:#e74c3c;"><h2>Fatal Error On Loading Replica Class...</h2><ul><li>Sorry, Replica encountered fatal system error while trying to load <i>Replica Class File</i>  <strong>('.REPLICA_CUSTOM_CLASS_FILE_NAME.EXT.')</strong>, please verify your config setting in index.php</li></ul></div></body></html>');
    }

    /*
    |--------------------------------------------------------------------------
    | Run the application
    |--------------------------------------------------------------------------
    |
    | Once application is successfully bootstrapped, run the application
    |
    */

    $app->run();

