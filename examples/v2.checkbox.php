<?php
    require '../recaptcha.php';
    $recaptcha = new LPRecaptcha('your-secrect-key');
    $recaptcha->setSitekey('your-site-key')->setVersion(2);
    
?>
<html>
    <head>
        <title>reCAPTCHA v2: Explicit render after an onload callback</title>
    </head>
    <body>
        <form action="" method="post">
            <input type="email" placeholder="Type your email" size="40"><br><br>
            <textarea name="comment" rows="8" cols="39"></textarea><br><br>
            <div id="recaptcha"></div>
            <input type="submit" name="submit" value="Post comment"><br><br>
        </form>
    </body>
    <?=$recaptcha->render('recaptcha');?>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    // name is g-recaptcha-response
    $resp = $recaptcha->verify($_POST['g-recaptcha-response']);
    if ($resp->isSuccess()) 
    {
        // Verified!
        var_dump($resp->isSuccess());
    } else {
        $errors = $resp->getMessage();
        var_dump($errors);

    }

    echo "<pre>";
    var_dump($resp);
    echo "</pre>";
    die;
}
?>