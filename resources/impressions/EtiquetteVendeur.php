<?php
require_once("..\\config.php");
require_once("..\\functions.php");
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            size: auto;
            margin: 0;
            margin-top: 5mm;
        }

        body {
            height: 297mm;
            width: 210mm;
            margin: 10mm 15mm 10mm 15mm;
        }

        div.one, div.two {
            width: 45%;
            top: 10mm;
            page-break-inside: avoid;
            page-break-after: auto;
            page-break-before: auto;
        }

        .container {
            width: 100%;

        }

        .one {
            float: left;
            width: 75%;
            overflow: visible
            page-break-after: always;
        }

        .two {
            float: left;
            width: 25%;
            overflow: visible
        }

        h1, h2, h4 {
            text-align: center;
        }

        h1 {
            -webkit-margin-after: 0.4em;
        }
    </style>
</head>
<body>
<?php

function printUser($userArr){
    $cpt = 0;
    foreach ($userArr as $user) {

        if ($cpt % 2 == 0)
            $class = "one";
        else
            $class = "two";
        ?>
        <div class="<?php echo $class ?>">
            <h4><?php echo $_SESSION['foire']->nomFoire(); ?></h4>
            <h1><?php echo $user->id(); ?></h1>
            <h1><?php echo $user->name(); ?></h1>
            <h2><?php echo $user->fname(); ?></h2>
        </div>


        <?php

        $cpt++;
    }
}

if (isset($_SESSION['foire'])) {

    if(empty($_POST)){
        $userMan = new UserManager();
        $userMan->loadUsersFromFoire($_SESSION['foire']->idFoire());
        printUser($userMan->users());
    }
    else{
        $userArr = array();
        foreach($_POST as $user){
            array_push($userArr, User::loadUserWithId(test_input($user)));
        }
        printUser($userArr);
    }

}
?>
</body>
</html>