<?php
    require '../recaptcha.php';
    $recaptcha = new LPRecaptcha('6Lfu-yUkAAAAAFaYM2sTog_Y3z0ZYz_FPRallYxu');
    $recaptcha->setSitekey('6Lfu-yUkAAAAAMfcafy7d3TLPGoHS3_KfE8tSKkH');
    
?>

<html>
    <head>
        <title>Demo LPTech Simple Recaptcha - Google</title>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    </head>
    <body>
        <div>
            <h1>Demo LPTech Simple Recaptcha - Google</h1>
        </div>
    
        <form id="newsletterForm" action="v3.php" method="post">
            <div>
                <input type="email" id="email" name="email">
                <input type="submit" value="submit">
            </div>
        </form>
    </body>
    <?=$recaptcha->render('newsletterForm','submit');?>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $resp = $recaptcha->setExpectedAction('submit')->verify($_POST['token']);

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