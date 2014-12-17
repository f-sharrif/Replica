<?php
if(!defined('REPLICA')) {die('Sorry direct access to this file not allowed');}

Replica::ip('top','header',[
    'title' => $title,
    'meta_description'  => $meta_description,
    'meta_keywords'     => $meta_keywords
]);



if(isset($_POST['username']))
{
    Replica::user($_POST['username'], $_POST['password']);
}


?>

<div class="justify-text">

    <?php
        if(!Replica::session('exists',['name'=>'id'])):
    ?>
    <h2> Login to your account</h2>
    <hr>
    <form action="" method="post">

        <label for="username"> Username</label>
        <input type="text" name="username" id="username" placeholder="Enter your username">
        <br>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password">

        <input type="submit" value="login">
    </form>

    <?php endif;

    if(Replica::session('exists', ['name'=>'id'])):
    ?>

        <h2> Hello <?=Replica::session('get',['name'=>'username']);?>, welcome to your profile</h2>
        <a href="?logout=true">Logout</a>

        <?php


        //Replica::in() = Replica::input()

        if(Replica::in('get','logout')=='true' )
            {
                Replica::session('delete',['name'=>'id']);

                Replica::session('destroy');

                //Replica::rt() = Replica::redirect_to()
                //Replica::uri() = Replica::get_base_uri()

                return Replica::rt(Replica::uri());
            }

        ?>


    <?php endif;?>

</div>



<?php

//Replica::ip() = Replica::include_partial();
Replica::ip('footer','footer');

?>

