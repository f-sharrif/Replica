<?php
if(!defined('REPLICA')) {die('Sorry direct access to this file not allowed');}

Replica::inc_part('top','header',[
    'title' => $title,
    'meta_description'  => $meta_description,
    'meta_keywords'     => $meta_keywords,
    'style'             => '
<style>
    .contact-us
    {
        width: 700px;
        margin: 80px auto;
        padding: 10px;
        border: 1px solid #efefef;
        text-align: left;
    }
    .contact-us h2{
        padding: 0 10px 10px 10px;
        border-bottom: 1px solid #efefef;
    }
    .contact-us input[type="text"], textarea
    {
        padding: 10px;
        display: block;
        margin: 3px 0 5px 0;
        width: 100%;
    }

    .contact-us .submit-btn
    {
        padding:8px;
        outline: none;
        cursor: hand;
        border: none;
        float: right;
        color: #fff;
        background-color: #27ae60;
    }

    .contact-us .submit-btn:hover
    {
        opacity: .5;
    }
</style>

    ',
    'js'                => Replica::assets_load('js',['js/script.js']),

    'script'            =>'

    <script>
        alert("Welcome to Replica - this is inline javascript");
    </script>

    '

]);

if(Replica::input_exists())
{

}


?>

<div class="contact-us">

    <h2>Contact Us</h2>
    <form method="post" action="">

        <p>
            <label>First Name:</label>
            <input type="text" value="<?=Replica::input_get(Replica::get_system('send_email_contact_firstname'));?>" name="<?=Replica::get_system('send_email_contact_firstname');?>">
        </p>

        <p>
            <label>Last Name:</label>
            <input type="text" value="<?=Replica::input_get(Replica::get_system('send_email_contact_lastname'));?>" name="<?=Replica::get_system('send_email_contact_lastname');?>">
        </p>

        <p>
            <label>Email Address:</label>
            <input type="text" value="<?=Replica::input_get(Replica::get_system('send_email_contact_email'));?>" name="<?=Replica::get_system('send_email_contact_email');?>">
        </p>


        <p>
            <label>Message:</label>
            <textarea name="<?=Replica::get_system('send_email_contact_message');?>"><?=Replica::input_get(Replica::get_system('send_email_contact_message'));?></textarea>
        </p>

        <input type="hidden" name="token" value="<?=Replica::token('generate');?>">

        <p>
            <button value="submit" class="submit-btn">Send Message</button>
        </p>
    </form>
    <div class="clearfix"></div>
</div>
<script>

</script>

<?php
Replica::inc_part('footer','footer',['footer-widgets'=>false]);


