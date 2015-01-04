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
        box-sizing: border-box;
        position: relative;
    }
    .contact-us h2{
        padding: 0 10px 10px 10px;
        border-bottom: 1px solid #efefef;
    }
    .contact-us input[type="text"], textarea
    {
        position: relative;
        padding: 10px;
        display: block;
        margin: 3px 0 5px 0;
        width: 650px;
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
    .contact-us .error
    {
        color: darkred;
        font-size: .80em;
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
        <p class="error" id="fname-error"></p>
            <label>First Name:</label>
            <input type="text" value="<?=Replica::input_get(Replica::get_system('send_email_contact_firstname'));?>" id="<?=Replica::get_system('send_email_contact_firstname');?>" name="<?=Replica::get_system('send_email_contact_firstname');?>">
        </p>

        <p>
        <p class="error" id="lname-error"></p>
            <label>Last Name:</label>
            <input type="text" value="<?=Replica::input_get(Replica::get_system('send_email_contact_lastname'));?>" id="<?=Replica::get_system('send_email_contact_lastname');?>" name="<?=Replica::get_system('send_email_contact_lastname');?>">
        </p>

        <p>

        <p class="error" id="email-error"></p>
            <label>Email Address:</label>
            <input type="text" value="<?=Replica::input_get(Replica::get_system('send_email_contact_email'));?>" id="<?=Replica::get_system('send_email_contact_email');?>" name="<?=Replica::get_system('send_email_contact_email');?>">
        </p>


        <p>
            <p class="error" id="message-error"></p>
            <label>Message:</label>
            <textarea id="<?=Replica::get_system('send_email_contact_message');?>" name="<?=Replica::get_system('send_email_contact_message');?>"><?=Replica::input_get(Replica::get_system('send_email_contact_message'));?></textarea>
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
Replica::inc_part('footer','footer',['footer-widgets'=>false, 'js'=>Replica::assets_load('js',['js/contact.validator.js']),
'script'=>'
<script>
$("form").submit(function(e){
e.preventDefault();

var fname = $("#contact_firstname").val();
var lname = $("#contact_lastname").val();
var email = $("#contact_email").val();
var msg  = $("#contact_message").val();

if(fname=="")
{
    $("#fname-error").html("First name is required!").fadeIn();
}else
{
    $("#fname-error").fadeOut();
}


if(lname=="")
{
    $("#lname-error").html("Last name is required!").fadeIn();
}else
{
    $("#lname-error").fadeOut();
}


if(email=="")
{
    $("#email-error").html("Email is required!").fadeIn();
}else
{
    $("#email-error").fadeOut();
}


if(msg=="")
{
    $("#message-error").html("Your must provide a message!").fadeIn();
}else
{
    $("#message-error").fadeOut();
}

});
</script>
<script>
    $(document).ready(function(){
        doSomething("Ok! But JQuery cannot get any useless than alert, but it is good enough to demonstrate Replica inline javascript is processed correctly");
    });
</script>
<script>
 function doSomething(useless)
 {
    console.log(useless);
 }
</script>

'
]);


