<?php
session_start();
include_once 'api/config/db_connexion.php';
require_once 'api/user/edit_profil.php';

if(!($_SESSION['username'])) {  
  
    header("Location: signin.php");//redirect to login page to secure the welcome page without login access.
}
//header("Cache-Control: must-revalidate");
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link  rel="stylesheet" href="assets/css/bootstrap.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/f14bbc71a6.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <title>Appli Skelec</title>
    </head>

    <body>
        <header class="header">
            <!-- Menu Button -->
            <div class="navbar-expand-md double-nav scrolling-navbar navbar-dark bg-dark">
                <!--Menu -->
                <nav class="menu left-menu">
                    <div class="menu-content">
                        <ul class="pl-0">
                                <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="troubleshooting_list.php" class="text-warning">Chantiers</a></li>
                                <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="list_profil.php" class="text-warning">Salariés</a></li>
                                <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="#" class="text-warning">Paramètres</a></li>
                                <li class="bg-dark border-top border-bottom border-warning rounded-0 p-0 menu-link"><a href="signin.php" class="text-warning">Déconnexion</a></li>
                        </ul>
                    </div>
                </nav>
                <div class="icons-navbar">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning m-auto"><h2 class="m-0">S.K.elec</h2></a>
                    <a href="list_profil.php" class="text-white pl-3"><i class="menu-btn-plus fas fa-search text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div id="container">
            <div class="content">
                <h3 class="text-center mt-0 mb-3 pt-5">Modification du compte</h3>
                <form class="w-100 pt-2 pl-4 pb-0 pr-4" action="" method="POST">
                    <?php
                    $stmt = $bdd->prepare("SELECT * FROM users WHERE username = '". $_SESSION['username'] ."'");
                    $stmt->execute();
                    $user = $stmt->fetch();
                    if($user) {
                        $_SESSION['id'] = $user['id'];
                        $_SESSION['first_name'] = $user['first_name'];
                        $_SESSION['last_name'] = $user['last_name'];
                        $_SESSION['e_mail'] = $user['e_mail'];
                        $_SESSION['phone'] = $user['phone'];
                        $_SESSION['total_hours'] = $user['totals_hours'];
                        //print_r($_SESSION);
                    }
                    else {
                        echo "ERROR: Could not get 'id' of current user [first_method]";
                    }

                    echo '<div class="md-form mt-1">';
                        echo '<label for="fusername">Username</label>';
                        echo '<input type="text" id="username" name="username" class="form-control" placeholder="' . $_SESSION['username'] . '" disabled="disabled">';
                    echo '</div>';
                    echo '<div class="md-form mt-4">';
                        echo '<label for="first-name">First name</label>';
                        echo '<input type="text" id="first_name" name="first_name" class="form-control" placeholder="' . $_SESSION['first_name'] . '">';
                    echo '</div>';
                    echo '<div class="md-form mt-4">';
                        echo '<label for="last_name">Last name</label>';
                        echo '<input type="text" id="last_name" name="last_name" class="form-control" placeholder="' . $_SESSION['Last_name'] . '">';
                    echo '</div>';
                    echo '<div class="md-form mt-4">';
                        echo '<label for="e_mail">E_mail</label>';
                        echo '<input type="e-mail" id="e-mail" name="e_mail" class="form-control" placeholder="' . $_SESSION['e_mail'] . '">';
                    echo '</div>';
                    echo '<div class="md-form mt-4">';
                        echo '<label for="phone">Téléphone</label>';
                        echo '<input type="text" id="phone" name="phone" class="form-control" placeholder="' . $_SESSION['phone'] . '">';
                    echo '</div>';
                    echo '<div class="md-form mt-4">';
                        echo '<label for="total_hours">H/totales</label>';
                        echo '<input type="text" id="hours" name="total_hours" class="form-control" placeholder="' . $_SESSION['total_hours'] . '">';
                    echo '</div>';
                    echo '<div class="md-form mt-4">';
                        echo '<label for="pass1">Password</label>';
                        echo '<input type="password" id="pass1" name="pass1" class="form-control" data-type="password" required>';
                    echo '</div>';
                    echo '<div class="md-form mt-4">';
                        echo '<label for="pass2">Confirm Password</label>';
                        echo '<input type="password" id="pass2" name="pass2" class="form-control">';
                    echo '</div>';
                    ?>
                    <div class="pt-5 w-75 m-auto">
                        <input type="submit" value="Valider" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark">
                        <a href="list_profil.php" value="return" class="btn finish border-0 bg-white z-depth-1a mt-1 mb-4 text-dark">Précédent</a>
                    </div>
                </form>
            </div>
        </div>
    </body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/bootstrap.js"></script>
</html>