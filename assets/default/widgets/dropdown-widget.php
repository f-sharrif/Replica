<?php
if(!defined('REPLICA')) {die('Sorry direct access to this file not allowed');}


//Get the menu

//print_r(Replica::wl('nav','dropdown-menu'));

echo "ul>";

    foreach(Replica::wl('nav','dropdown-menu') as $l=>$menu)
    {




       if(!is_array($menu))
       {
           echo "<li> <a href='{$menu}'>{$l}</a></li>";

       }elseif(is_array($menu))
       {


           echo "<li> <a href='#'>{$l}</a><ul>";

            foreach($menu as $cl=>$cmenu)
            {

                if(!is_array($cmenu))
                {
                    echo "<li>  <a href='{$cmenu}'> {$cl} </a></li>";

                }elseif(is_array($cmenu))
                {
                    echo "<li> <a href='#'>{$cl}</a><ul>";

                        foreach($cmenu as $gl=>$gmenu)
                        {
                            echo "<li>  <a href='{$gmenu}'> {$gl} </a></li>";
                        }

                    echo "</ul></li>";
                }

            }

           echo "</ul> </li>";
       }

    }

echo "</ul>";


?>