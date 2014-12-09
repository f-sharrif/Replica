<?php


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
    define('REPLICA_DEFAULT_SITE_NAME', 'Replica PHP Template Engine For Designers');

    //Set default site description used if page does not contain a description
    define('REPLICA_DEFAULT_SITE_DESCRIPTION', 'Replica allows designers to develop small website faster and easier');


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

    //By default its set to true, set it to false once you're ready to deploy your site to live server
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


    //specify your index page in your theme folder (index.php,default.php, main.php, root.php")
    define('REPLICA_THEME_DEFAULT_INDEX', 'default'); // default =>default


    //please specify your 404 or 403 error templates location within the theme drectory
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

    //setting this doesn't force every page to be called with extension in fact the page will show with and without extension
    define('REPLICA_PAGE_EXTENSION', 'html');  //default =>html


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

    define('REPLICA_CUSTOM_MODULES_DIR', 'core/modules'); //default =>core/modules

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
    |              CUSTOM SYSTEM DIRECTORY NAME
    |--------------------------------------------------------------------------
    |****** DON'T FORGET TO RENAME THE ACTUAL DIRECTORY ****
    | path relative to root e.g 'path/to/new/system_dir'
    |
    |
    */

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
     |
     */

    //OS Directory separator short name
    define('DS', DIRECTORY_SEPARATOR);

    //System file extension
    define('EXT', '.php');

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

    define('REPLICA_ROOT_DIR', __DIR__ . DS);

    /*
    |--------------------------------------------------------------------------
    | Bootstrap the Application
    |--------------------------------------------------------------------------
    |
    | Bootstrap the application, require the core class and prepare for run
    |
    */

    if(is_readable(REPLICA_CUSTOM_CORE_DIR . DS . REPLICA_CUSTOM_CLASS_FILE_NAME . EXT))
    {
        require_once REPLICA_CUSTOM_CORE_DIR . DS . REPLICA_CUSTOM_CLASS_FILE_NAME . EXT;

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

    $Replica->run();