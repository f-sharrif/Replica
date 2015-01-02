<?php
if(!defined('REPLICA')) {die('Sorry direct access to this file not allowed');}

Replica::inc_part('top','header',[
    'title' => $title,
    'meta_description'  => $meta_description,
    'meta_keywords'     => $meta_keywords,
    'css'               => Replica::assets_load('css',['css/login.css']),
]);



if(Replica::input_exists())
{
    Replica::user('login',['username'=>Replica::in('username'),'password'=>Replica::in('password')]);
}


?>



<div class="justify-text">

    <?php
        if(!Replica::session('exists',['name'=>'id'])):
    ?>
    <div class="login">
        <h2> Login to your account</h2>

        <form action="" method="post">

            <label for="username"> Username</label>
            <input type="text" name="username" id="username" placeholder="Enter your username">
            <br>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter your password">

            <button type="submit" class="submit-btn">Login</button>
            <div class="clearfix"></div>
        </form>
    </div>
    <?php endif;

    if(Replica::session('exists', ['name'=>'id'])):
    ?>

        <h2> Hello <?=Replica::session('get',['name'=>'username']);?>, welcome to your profile</h2>
        <a href="?logout=true">Logout</a>

        <?php


        if(Replica::in('logout','get')=='true' )
            {
                Replica::user('logout',['redirect_to'=>'http://google.com']);

            }

        ?>


    <?php endif;?>

</div>



<?php

Replica::inc_part('footer','footer',['footer-widgets'=>false]);

?>

