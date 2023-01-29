<?php
    require '../recaptcha.php';
    $recaptcha = new LPRecaptcha('6LdC9AsTAAAAAP2SnfwJoRVEX6xBvJgp-2rmAYVo');
    $recaptcha->setSitekey('6LdC9AsTAAAAADlIXc7SpSMtdGk__dptSVD23nrU')->setVersion(2);
    
?>
<html>
    <head>
        <title>reCAPTCHA demo: Explicit render after an onload callback</title>
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