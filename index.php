<?php

/*
 * ----------------------------------------------------------------------------------------------------------------
 * # PACKAGE INFORMATION
 * ----------------------------------------------------------------------------------------------------------------
 *
 * @package: Replica
 * @author: Abdikadir Adan (Sharif)  -url [http://sharif.co] -Email [hello@sharif.co]
 * @url: http://replica.hub.sharif.co
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


//GLOBAL CONFIGURATION
$GLOBALS['config'] = [

    /*
    |--------------------------------------------------------------------------
    | SEND EMAIL WITH REPLICA
    |--------------------------------------------------------------------------
    |
    | configure your email system configuration. Replica will allow for your
    | endusers to send email from your application, to enable this feature
    | of Replica, you must provide the below essential configurations
    |
    |
    */
    'send_email' =>[

        //Your email address where you want your emails sent to
        'address'   => 'hello@sharif.co',

        //Your secondary email where you want a copy of the email sent to
        'cc'        => 'support@replica.hub.sharif.co',

        //The subject for your email
        'subject'   => 'A new message from your Replica powered website'
    ],

    /*
   |--------------------------------------------------------------------------
   | SOCIAL MEDIA
   |--------------------------------------------------------------------------
   |
   | Below are list of currently Replica supported social media sites. Please
   | provide links and username to your social media profile.
   | Please note, these names are also registered with Replica route and they cannot
   | be used as link for internal pages. A visit to any of this names eg.
   | http://yourreplicasite.com/github will automatically redirects to what ever you define
   | as github url even if you have a page called http://yourreplicasite.com/github in
   | Replica Pages, the redirect to social media will take the priority.
   |
   | If you must change this behavior, then the following methods need to be changed in
   | replica class:
   | Route();
   | link_to_social_media();
   |
   | If you need assistance, please feel free to contact me at hello@sharif.co.
   |
   */

    'socialmedia' => [
        /*
        |---------------------------------------------
        | Linkedin - http://linkedin.com
        |----------------------------------------------
        */
        'linkedin' =>
            [
                //your linkedin public url
                'url' => 'https://www.linkedin.com/pub/abdikadir-a/33/975/542',
                //your linked full name
                'handle' => 'linkedin',
            ],

        /*
        |---------------------------------------------
        | Google Plus - https://plus.google.com
        |----------------------------------------------
        */
        'googleplus' => [
            //Your google plus url
            'url' => 'https://google.com/plus',
            //Your google username
            'handle' => '@sharif',
        ],
        /*
        |---------------------------------------------
        | Facebook - http://facebook.com
        |----------------------------------------------
        */
        'facebook' => [
            //Your facebook url
            'url' => 'https://facebook.com/sharif',
            //your facebook display name
            'handle' => 'Sharif',
        ],
        /*
        |---------------------------------------------
        | Twitter - http://twitter.com
        |----------------------------------------------
        */
        'twitter' => [
            //Your twiter url
            'url' => 'https://twitter.com/asharif',
            //your twitter handle
            'handle' => '@sharif1',
        ],
        /*
        |---------------------------------------------
        | Youtube - http://youtube.com
        |----------------------------------------------
        */
        'youtube' => [
            //Your youtube channel url
            'url' => 'http://yout.be/user/sharif',
            //your youtube channel name
            'handle' => 'sharif',
        ],

        /*
       |---------------------------------------------
       | GitHub - http://github.com
       |----------------------------------------------
       */
        'github' => [
            //your github url
            'url' => 'https://github.com/sp01010011',
            //your github username
            'handle' => 'SP01010011',
        ],
        /*
        |---------------------------------------------
        | Skype - http://skype.com
        |----------------------------------------------
        */
        'skype' => [
            //your skype public profile url
            'url' => 'skype',
            //your skype username
            'handle' => 'SP01010011'
        ],
    ] // end of social media configuration
];


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
define('REPLICA_THEME_JS_DIR', 'js');  // default =>'js'

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

if (is_readable(REPLICA_CUSTOM_CORE_DIR . DS . REPLICA_CUSTOM_CLASS_FILE_NAME . '.' . EXT)) {
    require_once REPLICA_CUSTOM_CORE_DIR . DS . REPLICA_CUSTOM_CLASS_FILE_NAME . '.' . EXT;

} else {
    die('<html><head><title>Fatal Error Occurred</title></head><body> <div style="color: #fff; font-family: sans-serif; font-weight: 400; width:760px; margin: 0 auto; padding: 20px; border-radius 10px; background-color:#e74c3c;"><h2>Fatal Error On Loading Replica Class...</h2><ul><li>Sorry, Replica encountered fatal system error while trying to load <i>Replica Class File</i>  <strong>(' . REPLICA_CUSTOM_CLASS_FILE_NAME . EXT . ')</strong>, please verify your config setting in index.php</li></ul></div></body></html>');
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



