<?php
session_start();
include 'api/config/db_connexion.php';

if(!($_SESSION['username'])) {  
  
    header("Location: signin.php");//redirect to login page to secure the welcome page without login access.  
}

$stmt = $bdd->prepare("SELECT id FROM users WHERE username = '". $_SESSION['username'] ."'");
$stmt->execute();
$user = $stmt->fetch();

$stmt_admin = $bdd->prepare("SELECT * FROM `admin` WHERE admin_name = '". $_SESSION['admin_name'] ."'");
$stmt_admin->execute();
$admin = $stmt_admin->fetch();

if($user) {
    $_SESSION['id'] = $user['id'];
} elseif ($admin and empty($user)) {
    $_SESSION['id'] = $admin['id'];
} else {
    echo "ERROR: Could not get 'id' of current user [first_method]";
}
?>

<!DOCTYPE html>

<html class='overflow-y mb-0'>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="S.K.elec">
        <link  rel="stylesheet" href="assets/css/bootstrap.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,700,700italic" rel="stylesheet">
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

        <?php

            $sql = "SELECT 
                concat(year(g.updated),
                month(g.updated),
                week(g.updated)) AS `concat`,
                c.name AS name_chantier,
                g.updated as inter_chantier,
                c.created as date_chantier,
                c.id AS chantier_id,
                username,
                u.id AS `user_id`,
                night_hours,
                SUM(night_hours) AS h_night_tot,
                SUM(intervention_hours) AS totalheure,
                SUM(intervention_hours - night_hours) AS tothsnight,
                if (SUM(intervention_hours - night_hours) - 350000 > 0, if (SUM(intervention_hours - night_hours) - 350000 > 80000, 80000, SUM(intervention_hours - night_hours) - 350000), NULL) AS maj25,
                if (SUM(intervention_hours - night_hours) > 430000, if (SUM(night_hours) > 0, SUM(intervention_hours) - 430000, SUM(intervention_hours - night_hours) - 430000), if (SUM(intervention_hours - night_hours) < 430000, if (SUM(night_hours) > 0, SUM(night_hours), NULL), NULL)) AS maj50
            FROM
                chantiers AS c
                JOIN
                global_reference AS g ON c.id = chantier_id
                JOIN
                users AS u ON g.user_id = u.id
            WHERE
                u.id = '" . $_GET['id'] . "'
            GROUP BY concat(year(g.updated) , month(g.updated), week(g.updated)) DESC , g.updated DESC, u.id , username , c.id , c.created , c.name , night_hours with ROLLUP #//u.id , //username , c//.id , //c.created , //g.updated , //c.name , //night_hours , //concat(year(g.updated) , month(g.updated), week(g.updated)) with ROLLUP";


            $month_sql = "SELECT 
                concat(month(g.updated)) AS `concat`,
                g.updated as inter_chantier,
                u.id AS `user_id`,
                SUM(night_hours) AS h_night_tot,
                #SUM(intervention_hours) AS totalheure,
                SUM(intervention_hours - night_hours) AS tothsnight,
                floor((SUM(floor(intervention_hours / 10000)) + (SUM(((floor(intervention_hours) - floor(floor(intervention_hours) / 10000) * 10000) / 100) / 60))) * 100) AS tot_glob,
                if (SUM(intervention_hours - night_hours) - 1514000 > 0, if (SUM(intervention_hours - night_hours) - 1514000 > 344000, 344000, SUM(intervention_hours - night_hours) - 1514000), NULL) AS maj25,
                if (SUM(intervention_hours - night_hours) > 1862000, if (SUM(night_hours) > 0, SUM(intervention_hours) - 1862000, SUM(intervention_hours - night_hours) - 1862000), if (SUM(intervention_hours - night_hours) < 1862000, if (SUM(night_hours) > 0, SUM(night_hours), NULL), NULL)) AS maj50
            FROM
                chantiers AS c
                JOIN
                global_reference AS g ON c.id = chantier_id
                JOIN
                users AS u ON g.user_id = u.id
            WHERE
                u.id = '" . $_GET['id'] . "'
                AND
                concat(month(g.updated)) = (
                                        SELECT 
                                            MAX(concat(month(updated)))
                                        FROM
                                            global_reference
                                        )
            GROUP BY concat(month(g.updated)) , g.updated , u.id with ROLLUP";



            
            if(($_GET['id'] != $admin['id'] and $_SESSION['id'] == $_GET['id'] and $_SESSION['id'] == $user['id']) or $_SESSION['id'] == $admin['id']) {
        ?>
        <!-- Content -->
                <div id="container">
                    <div class="content">
                        <h3 class="text-center mt-0 mb-3 pt-5">Édition du compte</h3>
                        <form class="w-100 pt-2 pl-4 pb-0 pr-4" action="api/user/edit_profil.php" method="POST">
                            <?php
                                $stmt = $bdd->prepare("SELECT * FROM users WHERE id = '". $_GET['id'] ."'");
                                $stmt->execute();
                                $user = $stmt->fetch();
                                if($user) {
                                    $modif_user['id'] = $user['id'];
                                    $modif_user['username'] = $user['username'];
                                    $modif_user['first_name'] = $user['first_name'];
                                    $modif_user['last_name'] = $user['last_name'];
                                    $modif_user['e_mail'] = $user['e_mail'];
                                    $modif_user['phone'] = $user['phone'];

                                    echo '<input type="text" value="' . $modif_user['id'] . '" id="id" name="id" style="display: none;"">';
                                    echo '<div class="md-form mt-1">';
                                        echo '<label for="fusername">Username</label>';
                                        echo '<input type="text" value="' . $modif_user['username'] . '" id="username" name="username" class="form-control" placeholder="' . $modif_user['username'] . '"">';
                                    echo '</div>';
                                    echo '<div class="md-form mt-4">';
                                        echo '<label for="first-name">First name</label>';
                                        echo '<input type="text" value="' . $modif_user['first_name'] . '" id="first_name" name="first_name" class="form-control" placeholder="' . $modif_user['first_name'] . '">';
                                    echo '</div>';
                                    echo '<div class="md-form mt-4">';
                                        echo '<label for="last_name">Last name</label>';
                                        echo '<input type="text" value="' . $modif_user['last_name'] . '" id="last_name" name="last_name" class="form-control" placeholder="' . $modif_user['last_name'] . '">';
                                    echo '</div>';
                                    echo '<div class="md-form mt-4">';
                                        echo '<label for="e_mail">E_mail</label>';
                                        echo '<input type="email" value="' . $modif_user['e_mail'] . '" id="e-mail" name="e_mail" class="form-control" placeholder="' . $modif_user['e_mail'] . '">';
                                    echo '</div>';
                                    echo '<div class="md-form mt-4 pb-3">';
                                        echo '<label for="phone">Téléphone</label>';
                                        echo '<input type="text" value="' . $modif_user['phone'] . '" id="phone" name="phone" class="form-control" placeholder="' . $modif_user['phone'] . '">';
                                    echo '</div>';

                                    echo '<h4 class="w-75 mt-5 ml-auto mb-4 mr-auto pb-2 text-center border-bottom">Heures globales du mois</h4>';
                                    echo "<table class='table table-striped border mt-2 ml-auto mb-3 mr-auto w-100 text-center'>";
                                        echo "<thead>
                                            <tr>
                                                <th scope='col' class='text-center border-right align-middle w-25 font-weight-normal'>H/totales<br />[avec h/nuit]</th>
                                                <th scope='col' class='text-center border-right align-middle w-25 font-weight-normal'>H + 25%</th>
                                                <th scope='col' class='text-center align-middle w-25 font-weight-normal'>H + 50%</th>
                                            </tr>
                                        </thead>
                                    </table>";
                                    echo '<div class="m-auto">
                                        <table class="table table-striped border ml-auto mb-1 mr-auto pb-3 w-100 text-center">';
                                            if($result = mysqli_query($db, $month_sql)) {

                                                if (mysqli_num_rows($result) > 0) {
                                                    
                                                    echo "<tbody>";

                                                        while ($row = $result->fetch_array()) {

                                                            if( $db === false){
                                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                                            }

                                                            if (empty($row['concat']) && empty($row['inter_chantier']) && empty($row['user_id'])) {
                                                                
                                                                echo '<tr>';
                                                                    
                                                                    echo '<td class="align-middle p-1 w-25" style="word-wrap: break-word;">';
                                                                        $total = $row['tot_glob'];
                                                                        $hours = (int)($total / 100);
                                                                        $minutes = ((int)($total - ($hours * 100)) / 100) * 60;
                                                                        if ($minutes > 59) {
                                                                            $hours += 1;
                                                                            $minutes -= 60;
                                                                        }
                                                                        if ($minutes > 10) {
                                                                            $minutes = $minutes;
                                                                        } elseif ($minutes < 10 and $minutes > 0) {
                                                                            $minutes = "0" . $minutes;
                                                                        } else {
                                                                            $minutes = "00";
                                                                        }
                                                                        echo $hours . '.' . $minutes . 
                                                                    '</td>';
                                                                    echo '<td class="align-middle border-left border-right p-1 w-25" style="word-wrap: break-word;">';
                                                                        $m25 = $row['maj25'];
                                                                        $hours = (int)($m25 / 10000);
                                                                        $minutes = ((int)($m25 - ($hours * 10000)) / 100) / 60;
                                                                        $m25 = $hours + $minutes;
                                                                        if ($m25 > 0) {
                                                                            echo $m25 . '<br />' . $m25 * 25 / 100 . ' maj.';
                                                                        } else {
                                                                            echo '<div class="bg-secondary w-100 h-100"></div>';
                                                                        }
                                                                    echo '</td>';
                                                                    echo '<td class="align-middle p-1 w-25" style="word-wrap: break-word;">';
                                                                        $m50 = $row['maj50'];
                                                                        $hours = (int)($m50 / 10000);
                                                                        $minutes = ((int)($m50 - ($hours * 10000)) / 100) / 60;
                                                                        $m50 = $hours + $minutes;
                                                                        if ($m50 > 0) {
                                                                            echo $m50;
                                                                            if ($row['h_night_tot'] != 0) {
                                                                                $hnight = $row['h_night_tot'];
                                                                                $hours = (int)($hnight / 10000);
                                                                                $minutes = ((int)($hnight - ($hours * 10000)) / 100) / 60;
                                                                                $hnight = $hours + $minutes;
                                                                                echo '<div class="d-inline small text-success">&nbsp;&nbsp;&nbsp;[nuit =&nbsp;' . $hnight . ']</div>';
                                                                            } 
                                                                            echo '<br />' . $m50 * 50 / 100 . ' maj.';
                                                                        } else {
                                                                            echo '<div class="bg-secondary w-100 h-100"></div>';
                                                                        }
                                                                    echo '</td>';

                                                                echo '</tr>';
                                                            }
                                                        }
                                                    echo '</tbody>';
                                                    mysqli_free_result($result);
                                                } else {
                                                    echo "Cette personne n'a pas encore effectué d'heure pour le moment.";
                                                }
                                            }
                                    echo '</table>
                                    </div>';

                                    echo '<h4 class="w-75 mt-5 ml-auto mb-4 mr-auto pt-3 pb-2 text-center border-bottom">Heures sur 4 semaines</h4>';
                                    echo "<table class='table table-striped border mt-4 ml-auto mb-3 mr-auto w-100 text-center'>";
                                        echo "<thead>
                                            <tr>
                                                <th scope='col' class='text-center border-right align-middle w-25 font-weight-normal'>H/totales</th>
                                                <th scope='col' class='text-center border-right align-middle w-25 font-weight-normal'>H + 25%</th>
                                                <th scope='col' class='text-center align-middle w-25 font-weight-normal'>H + 50%</th>
                                            </tr>
                                        </thead>
                                    </table>";
                                    echo '<div class="container-list-details m-auto">
                                        <table class="table table-striped border ml-auto mb-3 mr-auto w-100 text-center">';
                                            if($result = mysqli_query($db, $sql)) {

                                                if (mysqli_num_rows($result) > 0) {

                                                    $flag = 1;

                                                    echo "<tbody>";

                                                        while ($row = $result->fetch_array()) {

                                                            if( $db === false){
                                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                                            }

                                                            if (empty($row['date_chantier']) && empty($row['inter_chantier']) && empty($row['name_chantier']) && !empty($row['concat'])/*&& !empty($row['chantier_id'])*/ && $flag <= 4) {
                                                                
                                                                $flag += 1;
                                                                
                                                                echo '<tr>';
                                                                    
                                                                    echo '<td class="align-middle p-1 w-25" style="word-wrap: break-word;">';
                                                                        $total = $row['tothsnight'];
                                                                        $hours = (int)($total / 10000);
                                                                        $minutes = ((int)($total - ($hours * 10000)) / 100) / 60;
                                                                        $total = $hours + $minutes;
                                                                    echo $total . '</td>';
                                                                    echo '<td class="align-middle border-left border-right p-1 w-25" style="word-wrap: break-word;">';
                                                                        $m25 = $row['maj25'];
                                                                        $hours = (int)($m25 / 10000);
                                                                        $minutes = ((int)($m25 - ($hours * 10000)) / 100) / 60;
                                                                        $m25 = $hours + $minutes;
                                                                        if ($m25 > 0) {
                                                                            echo $m25 . '<br />' . $m25 * 25 / 100 . ' maj.';
                                                                        } else {
                                                                            echo '<div class="bg-secondary w-100 h-100"></div>';
                                                                        }
                                                                    echo '</td>';
                                                                    echo '<td class="align-middle p-1 w-25" style="word-wrap: break-word;">';
                                                                        $m50 = $row['maj50'];
                                                                        $hours = (int)($m50 / 10000);
                                                                        $minutes = ((int)($m50 - ($hours * 10000)) / 100) / 60;
                                                                        $m50 = $hours + $minutes;
                                                                        if ($m50 > 0) {
                                                                            echo $m50;
                                                                            if ($row['h_night_tot'] != 0) {
                                                                                $hnight = $row['h_night_tot'];
                                                                                $hours = (int)($hnight / 10000);
                                                                                $minutes = ((int)($hnight - ($hours * 10000)) / 100) / 60;
                                                                                $hnight = $hours + $minutes;
                                                                                echo '<div class="d-inline small text-success">&nbsp;&nbsp;&nbsp;[nuit =&nbsp;' . $hnight . ']</div>';
                                                                            } 
                                                                            echo '<br />' . $m50 * 50 / 100 . ' maj.';
                                                                        } else {
                                                                            echo '<div class="bg-secondary w-100 h-100"></div>';
                                                                        }
                                                                    echo '</td>';

                                                                echo '</tr>';
                                                            }
                                                        }
                                                    echo '</tbody>';
                                                    mysqli_free_result($result);
                                                } else {
                                                    echo "Cette personne n'a pas encore effectué d'heure pour le moment.";
                                                }
                                            }
                                    echo '</table>
                                    </div>';

                                    if (($_SESSION['id'] == $admin['id']) or ($_SESSION['id'] == $user['id'] and $_GET['id'] == $user['id'])) {
                                        echo '<div class="md-form mt-4">';
                                            echo '<label for="pass1">Password</label>';
                                            echo '<input type="password" id="pass1" name="pass1" class="form-control" data-type="password" required>';
                                        echo '</div>';
                                        echo '<div class="md-form mt-4">';
                                            echo '<label for="pass2">Confirm Password</label>';
                                            echo '<input type="password" id="pass2" name="pass2" class="form-control">';
                                        echo '</div>';
                                    }
                                } elseif ($admin) {

                                    $admin_sql = "SELECT 
                                        concat(year(g.updated),
                                        month(g.updated),
                                        week(g.updated)) AS `concat`,
                                        c.name AS name_chantier,
                                        g.updated as inter_chantier,
                                        c.created as date_chantier,
                                        c.id AS chantier_id,
                                        admin_name,
                                        a.id AS admin_id,
                                        night_hours,
                                        SUM(night_hours) AS h_night_tot,
                                        SUM(intervention_hours) AS totalheure,
                                        SUM(intervention_hours - night_hours) AS tothsnight,
                                        if (SUM(intervention_hours - night_hours) - 350000 > 0, if (SUM(intervention_hours - night_hours) - 350000 > 80000, 80000, SUM(intervention_hours - night_hours) - 350000), NULL) AS maj25,
                                        if (SUM(intervention_hours - night_hours) > 430000, if (SUM(night_hours) > 0, SUM(intervention_hours) - 430000, SUM(intervention_hours - night_hours) - 430000), if (SUM(intervention_hours - night_hours) < 430000, if (SUM(night_hours) > 0, SUM(night_hours), NULL), NULL)) AS maj50
                                    FROM
                                        chantiers AS c
                                        JOIN
                                        global_reference AS g ON c.id = chantier_id
                                        JOIN
                                        `admin` AS a ON g.user_id = a.id
                                    WHERE
                                        a.id = '" . $admin['id'] . "'
                                    GROUP BY concat(year(g.updated) , month(g.updated), week(g.updated)), g.updated , a.id , admin_name , c.id , c.created , c.name , night_hours with ROLLUP";


                                    $month_admin = "SELECT 
                                        concat(month(g.updated)) AS `concat`,
                                        g.updated as inter_chantier,
                                        a.id AS admin_id,
                                        SUM(night_hours) AS h_night_tot,
                                        #SUM(intervention_hours) AS totalheure,
                                        SUM(intervention_hours - night_hours) AS tothsnight,
                                        floor((SUM(floor(intervention_hours / 10000)) + (SUM(((floor(intervention_hours) - floor(floor(intervention_hours) / 10000) * 10000) / 100) / 60))) * 100) AS tot_glob,
                                        if (SUM(intervention_hours - night_hours) - 1514000 > 0, if (SUM(intervention_hours - night_hours) - 1514000 > 344000, 344000, SUM(intervention_hours - night_hours) - 1514000), NULL) AS maj25,
                                        if (SUM(intervention_hours - night_hours) > 1862000, if (SUM(night_hours) > 0, SUM(intervention_hours) - 1862000, SUM(intervention_hours - night_hours) - 1862000), if (SUM(intervention_hours - night_hours) < 1862000, if (SUM(night_hours) > 0, SUM(night_hours), NULL), NULL)) AS maj50
                                    FROM
                                        chantiers AS c
                                        JOIN
                                        global_reference AS g ON c.id = chantier_id
                                        JOIN
                                        `admin` AS a ON g.user_id = a.id
                                    WHERE
                                        a.id = '" . $admin['id'] . "'
                                        AND
                                        concat(month(g.updated)) = (
                                                                SELECT 
                                                                    MAX(concat(month(updated)))
                                                                FROM
                                                                    global_reference
                                                                )
                                    GROUP BY concat(month(g.updated)) , g.updated , a.id with ROLLUP";

                                    
                                    if ($_SESSION['id'] == $admin['id'] and $admin['id'] == $_GET['id']) {

                                        echo '<input type="text" value="' . $admin['id'] . '" id="id" name="id" style="display: none;"">';
                                        echo '<div class="md-form mt-1">';
                                            echo '<label for="fusername">Username</label>';
                                            echo '<input type="text" value="' . $admin['admin_name'] . '" id="username" name="username" class="form-control" placeholder="' . $admin['admin_name'] . '"">';
                                        echo '</div>';
                                        echo '<div class="md-form mt-4">';
                                            echo '<label for="first-name">First name</label>';
                                            echo '<input type="text" value="' . $admin['first_name'] . '" id="first_name" name="first_name" class="form-control" placeholder="' . $admin['first_name'] . '">';
                                        echo '</div>';
                                        echo '<div class="md-form mt-4">';
                                            echo '<label for="last_name">Last name</label>';
                                            echo '<input type="text" value="' . $admin['last_name'] . '" id="last_name" name="last_name" class="form-control" placeholder="' . $admin['last_name'] . '">';
                                        echo '</div>';
                                        echo '<div class="md-form mt-4">';
                                            echo '<label for="e_mail">E_mail</label>';
                                            echo '<input type="email" value="' . $admin['e_mail'] . '" id="e-mail" name="e_mail" class="form-control" placeholder="' . $admin['e_mail'] . '">';
                                        echo '</div>';
                                        echo '<div class="md-form mt-4 pb-3">';
                                            echo '<label for="phone">Téléphone</label>';
                                            echo '<input type="text" value="' . $admin['phone'] . '" id="phone" name="phone" class="form-control" placeholder="' . $admin['phone'] . '">';
                                        echo '</div>';

                                        echo '<h4 class="w-75 mt-5 ml-auto mb-4 mr-auto pb-2 text-center border-bottom">Heures globales du mois</h4>';
                                        echo "<table class='table table-striped border mt-2 ml-auto mb-3 mr-auto w-100 text-center'>";
                                            echo "<thead>
                                                <tr>
                                                    <th scope='col' class='text-center border-right align-middle w-25 font-weight-normal'>H/totales<br />[avec h/nuit]</th>
                                                    <th scope='col' class='text-center border-right align-middle w-25 font-weight-normal'>H + 25%</th>
                                                    <th scope='col' class='text-center align-middle w-25 font-weight-normal'>H + 50%</th>
                                                </tr>
                                            </thead>
                                        </table>";
                                        echo '<div class="m-auto">
                                            <table class="table table-striped border ml-auto mb-1 mr-auto pb-3 w-100 text-center">';
                                                if($result = mysqli_query($db, $month_admin)) {

                                                    if (mysqli_num_rows($result) > 0) {
                                                        
                                                        echo "<tbody>";

                                                            while ($row = $result->fetch_array()) {

                                                                if( $db === false){
                                                                    die("ERROR: Could not connect. " . mysqli_connect_error());
                                                                }

                                                                if (empty($row['concat']) && empty($row['inter_chantier']) && empty($row['admin_id'])) {
                                                                    
                                                                    echo '<tr>';
                                                                        
                                                                        echo '<td class="align-middle p-1 w-25" style="word-wrap: break-word;">';
                                                                            $total = $row['tot_glob'];
                                                                            $hours = (int)($total / 100);
                                                                            $minutes = ((int)($total - ($hours * 100)) / 100) * 60;
                                                                            if ($minutes > 59) {
                                                                                $hours += 1;
                                                                                $minutes -= 60;
                                                                            }
                                                                            if ($minutes > 10) {
                                                                                $minutes = $minutes;
                                                                            } elseif ($minutes < 10 and $minutes > 0) {
                                                                                $minutes = "0" . $minutes;
                                                                            } else {
                                                                                $minutes = "00";
                                                                            }
                                                                            echo $hours . '.' . $minutes . 
                                                                        '</td>';
                                                                        echo '<td class="align-middle border-left border-right p-1 w-25" style="word-wrap: break-word;">';
                                                                            $m25 = $row['maj25'];
                                                                            $hours = (int)($m25 / 10000);
                                                                            $minutes = ((int)($m25 - ($hours * 10000)) / 100) / 60;
                                                                            $m25 = $hours + $minutes;
                                                                            if ($m25 > 0) {
                                                                                echo $m25 . '<br />' . $m25 * 25 / 100 . ' maj.';
                                                                            } else {
                                                                                echo '<div class="bg-secondary w-100 h-100"></div>';
                                                                            }
                                                                        echo '</td>';
                                                                        echo '<td class="align-middle p-1 w-25" style="word-wrap: break-word;">';
                                                                            $m50 = $row['maj50'];
                                                                            $hours = (int)($m50 / 10000);
                                                                            $minutes = ((int)($m50 - ($hours * 10000)) / 100) / 60;
                                                                            $m50 = $hours + $minutes;
                                                                            if ($m50 > 0) {
                                                                                echo $m50;
                                                                                if ($row['h_night_tot'] != 0) {
                                                                                    $hnight = $row['h_night_tot'];
                                                                                    $hours = (int)($hnight / 10000);
                                                                                    $minutes = ((int)($hnight - ($hours * 10000)) / 100) / 60;
                                                                                    $hnight = $hours + $minutes;
                                                                                    echo '<div class="d-inline small text-success">&nbsp;&nbsp;&nbsp;[nuit =&nbsp;' . $hnight . ']</div>';
                                                                                } 
                                                                                echo '<br />' . $m50 * 50 / 100 . ' maj.';
                                                                            } else {
                                                                                echo '<div class="bg-secondary w-100 h-100"></div>';
                                                                            }
                                                                        echo '</td>';

                                                                    echo '</tr>';
                                                                }
                                                            }
                                                        echo '</tbody>';
                                                        mysqli_free_result($result);
                                                    } else {
                                                        echo "Cette personne n'a pas encore effectué d'heure pour le moment.";
                                                    }
                                                }
                                        echo '</table>
                                        </div>';

                                        echo '<h4 class="w-75 mt-5 ml-auto mb-4 mr-auto pt-3 pb-2 text-center border-bottom">Heures sur 4 semaines</h4>';

                                        echo "<table class='table table-striped border mt-4 ml-auto mb-3 mr-auto w-100 text-center'>";
                                            echo "<thead>
                                                <tr>
                                                    <th scope='col' class='text-center border-right align-middle w-25'>H/totales</th>
                                                    <th scope='col' class='text-center border-right align-middle w-25'>H + 25%</th>
                                                    <th scope='col' class='text-center align-middle w-25'>H + 50%</th>
                                                </tr>
                                            </thead>
                                        </table>";
                                        echo '<div class="container-list-details m-auto">
                                            <table class="table table-striped border ml-auto mb-3 mr-auto w-100 text-center">';
                                                if($result = mysqli_query($db, $admin_sql)) {

                                                    if (mysqli_num_rows($result) > 0) {
                                                        
                                                        $flag = 1;

                                                        echo "<tbody>";
                                                        
                                                            while ($row = $result->fetch_array()) {

                                                                if( $db === false){
                                                                    die("ERROR: Could not connect. " . mysqli_connect_error());
                                                                }

                                                                if (empty($row['date_chantier']) && empty($row['inter_chantier']) && empty($row['name_chantier']) /*&& !empty($row['chantier_id'])*/ && $flag <= 4) {
                                                                    
                                                                    $flag += 1;
                                                                    
                                                                    echo '<tr>';
                                                                        
                                                                        echo '<td class="align-middle p-1 w-25" style="word-wrap: break-word;">';
                                                                            $total = $row['tothsnight'];
                                                                            $hours = (int)($total / 10000);
                                                                            $minutes = ((int)($total - ($hours * 10000)) / 100) / 60;
                                                                            $total = $hours + $minutes;
                                                                        echo $total . '</td>';
                                                                        echo '<td class="align-middle border-left border-right p-1 w-25" style="word-wrap: break-word;">';
                                                                            $m25 = $row['maj25'];
                                                                            $hours = (int)($m25 / 10000);
                                                                            $minutes = ((int)($m25 - ($hours * 10000)) / 100) / 60;
                                                                            $m25 = $hours + $minutes;
                                                                            if ($m25 > 0) {
                                                                                echo $m25 . '<br />' . $m25 * 25 / 100 . ' maj.';
                                                                            } else {
                                                                                echo '<div class="bg-secondary w-100 h-100"></div>';
                                                                            }
                                                                        echo '</td>';
                                                                        echo '<td class="align-middle p-1 w-25" style="word-wrap: break-word;">';
                                                                            $m50 = $row['maj50'];
                                                                            $hours = (int)($m50 / 10000);
                                                                            $minutes = ((int)($m50 - ($hours * 10000)) / 100) / 60;
                                                                            $m50 = $hours + $minutes;
                                                                            if ($m50 > 0) {
                                                                                echo $m50;
                                                                                if ($row['h_night_tot'] != 0) {
                                                                                    $hnight = $row['h_night_tot'];
                                                                                    $hours = (int)($hnight / 10000);
                                                                                    $minutes = ((int)($hnight - ($hours * 10000)) / 100) / 60;
                                                                                    $hnight = $hours + $minutes;
                                                                                    echo '<div class="d-inline small text-success">&nbsp;&nbsp;&nbsp;[nuit =&nbsp;' . $hnight . ']</div>';
                                                                                } 
                                                                                echo '<br />' . $m50 * 50 / 100 . ' maj.';
                                                                            } else {
                                                                                echo '<div class="bg-secondary w-100 h-100"></div>';
                                                                            }
                                                                        echo '</td>';
                                                                        
                                                                    echo '</tr>';
                                                                }
                                                            }
                                                        echo '</tbody>';
                                                        mysqli_free_result($result);
                                                    } else {
                                                        echo "Cette personne n'a pas encore effectué d'heure pour le moment.";
                                                    }
                                                mysqli_close($db);
                                                }
                                        echo '</table>
                                        </div>';

                                    echo '<div class="md-form mt-4">';
                                        echo '<label for="pass1">Password</label>';
                                        echo '<input type="password" id="pass1" name="pass1" class="form-control" data-type="password" required>';
                                    echo '</div>';
                                    echo '<div class="md-form mt-4">';
                                        echo '<label for="pass2">Confirm Password</label>';
                                        echo '<input type="password" id="pass2" name="pass2" class="form-control" data-type="password" required>';
                                    echo '</div>';
                                    
                                    }
                                } else {
                                    echo "ERROR : Could not get 'id' of current user [second_method]";
                                }
                            ?>
                            <div class="pt-5 w-75 m-auto">
                                <input type="submit" value="Valider" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark">
                                <a href="javascript:history.go(-1)" value="return" class="btn finish border-0 bg-white z-depth-1a mt-1 mb-4 text-dark">Précédent</a>
                            </div>
                        </form>
                        
                    </div>
                </div>
        <?php 
            } else { 
        ?>
            <div id="container">
                <div class="content">
                    <h3 class="text-center mt-0 mb-3 pt-5">Détails d'un compte</h3>
                    <form class="w-100 pt-2 pl-4 pb-0 pr-4">
                        <?php
                            $stmt = $bdd->prepare("SELECT * FROM users WHERE id = '". $_GET['id'] ."'");
                            $stmt->execute();
                            $user = $stmt->fetch();

                            if($user) {
                                $modif_user['username'] = $user['username'];
                                $modif_user['first_name'] = $user['first_name'];
                                $modif_user['last_name'] = $user['last_name'];
                                $modif_user['e_mail'] = $user['e_mail'];
                                $modif_user['phone'] = $user['phone'];

                                echo '<div class="md-form mt-1">';
                                    echo '<label for="fusername" class="text-secondary">Username</label>';
                                    echo '<input type="text" value="' . $modif_user['username'] . '" id="username" name="username" class="form-control" placeholder="' . $modif_user['username'] . '"" disabled>';
                                echo '</div>';
                                echo '<div class="md-form mt-4">';
                                    echo '<label for="first-name" class="text-secondary">First name</label>';
                                    echo '<input type="text" value="' . $modif_user['first_name'] . '" id="first_name" name="first_name" class="form-control" placeholder="' . $modif_user['first_name'] . '" disabled>';
                                echo '</div>';
                                echo '<div class="md-form mt-4">';
                                    echo '<label for="last_name" class="text-secondary">Last name</label>';
                                    echo '<input type="text" value="' . $modif_user['last_name'] . '" id="last_name" name="last_name" class="form-control" placeholder="' . $modif_user['last_name'] . '" disabled>';
                                echo '</div>';
                                echo '<div class="md-form mt-4">';
                                    echo '<label for="e_mail" class="text-secondary">E_mail</label>';
                                    echo '<input type="email" value="' . $modif_user['e_mail'] . '" id="e-mail" name="e_mail" class="form-control" placeholder="' . $modif_user['e_mail'] . '" disabled>';
                                echo '</div>';
                                echo '<div class="md-form mt-4 pb-3">';
                                    echo '<label for="phone" class="text-secondary">Téléphone</label>';
                                    echo '<input type="text" value="' . $modif_user['phone'] . '" id="phone" name="phone" class="form-control" placeholder="' . $modif_user['phone'] . '" disabled>';
                                echo '</div>';

                                echo '<h4 class="w-75 mt-5 ml-auto mb-4 mr-auto pb-2 text-center border-bottom">Heures globales du mois</h4>';
                                    echo "<table class='table table-striped border mt-2 ml-auto mb-3 mr-auto w-100 text-center'>";
                                        echo "<thead>
                                            <tr>
                                                <th scope='col' class='text-center border-right align-middle w-25 font-weight-normal'>H/totales<br />[avec h/nuit]</th>
                                                <th scope='col' class='text-center border-right align-middle w-25 font-weight-normal'>H + 25%</th>
                                                <th scope='col' class='text-center align-middle w-25 font-weight-normal'>H + 50%</th>
                                            </tr>
                                        </thead>
                                    </table>";
                                    echo '<div class="m-auto">
                                        <table class="table table-striped border ml-auto mb-1 mr-auto pb-3 w-100 text-center">';
                                            if($result = mysqli_query($db, $month_sql)) {

                                                if (mysqli_num_rows($result) > 0) {
                                                    
                                                    echo "<tbody>";

                                                        while ($row = $result->fetch_array()) {

                                                            if( $db === false){
                                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                                            }

                                                            if (empty($row['concat']) && empty($row['inter_chantier']) && empty($row['user_id'])) {
                                                                
                                                                echo '<tr>';
                                                                    
                                                                    echo '<td class="align-middle p-1 w-25" style="word-wrap: break-word;">';
                                                                        $total = $row['tot_glob'];
                                                                        $hours = (int)($total / 100);
                                                                        $minutes = ((int)($total - ($hours * 100)) / 100) * 60;
                                                                        if ($minutes > 59) {
                                                                            $hours += 1;
                                                                            $minutes -= 60;
                                                                        }
                                                                        if ($minutes > 10) {
                                                                            $minutes = $minutes;
                                                                        } elseif ($minutes < 10 and $minutes > 0) {
                                                                            $minutes = "0" . $minutes;
                                                                        } else {
                                                                            $minutes = "00";
                                                                        }
                                                                        echo $hours . '.' . $minutes . 
                                                                    '</td>';
                                                                    echo '<td class="align-middle border-left border-right p-1 w-25" style="word-wrap: break-word;">';
                                                                        $m25 = $row['maj25'];
                                                                        $hours = (int)($m25 / 10000);
                                                                        $minutes = ((int)($m25 - ($hours * 10000)) / 100) / 60;
                                                                        $m25 = $hours + $minutes;
                                                                        if ($m25 > 0) {
                                                                            echo $m25 . '<br />' . $m25 * 25 / 100 . ' maj.';
                                                                        } else {
                                                                            echo '<div class="bg-secondary w-100 h-100"></div>';
                                                                        }
                                                                    echo '</td>';
                                                                    echo '<td class="align-middle p-1 w-25" style="word-wrap: break-word;">';
                                                                        $m50 = $row['maj50'];
                                                                        $hours = (int)($m50 / 10000);
                                                                        $minutes = ((int)($m50 - ($hours * 10000)) / 100) / 60;
                                                                        $m50 = $hours + $minutes;
                                                                        if ($m50 > 0) {
                                                                            echo $m50;
                                                                            if ($row['h_night_tot'] != 0) {
                                                                                $hnight = $row['h_night_tot'];
                                                                                $hours = (int)($hnight / 10000);
                                                                                $minutes = ((int)($hnight - ($hours * 10000)) / 100) / 60;
                                                                                $hnight = $hours + $minutes;
                                                                                echo '<div class="d-inline small text-success">&nbsp;&nbsp;&nbsp;[nuit =&nbsp;' . $hnight . ']</div>';
                                                                            } 
                                                                            echo '<br />' . $m50 * 50 / 100 . ' maj.';
                                                                        } else {
                                                                            echo '<div class="bg-secondary w-100 h-100"></div>';
                                                                        }
                                                                    echo '</td>';

                                                                echo '</tr>';
                                                            }
                                                        }
                                                    echo '</tbody>';
                                                    mysqli_free_result($result);
                                                } else {
                                                    echo "Cette personne n'a pas encore effectué d'heure pour le moment.";
                                                }
                                            }
                                    echo '</table>
                                    </div>';

                                echo '<h4 class="w-75 mt-5 ml-auto mb-4 mr-auto pt-3 pb-2 text-center border-bottom">Heures sur 4 semaines</h4>';
                                echo "<table class='table table-striped border mt-4 ml-auto mb-3 mr-auto w-100 text-center'>";
                                        echo "<thead>
                                            <tr>
                                                <th scope='col' class='text-center border-right align-middle w-25'>H/totales</th>
                                                <th scope='col' class='text-center border-right align-middle w-25'>H + 25%</th>
                                                <th scope='col' class='text-center align-middle w-25'>H + 50%</th>
                                            </tr>
                                        </thead>
                                    </table>";
                                    echo '<div class="container-list-details m-auto">
                                        <table class="table table-striped border ml-auto mb-3 mr-auto w-100 text-center">';
                                            if($result = mysqli_query($db, $sql)) {

                                                if (mysqli_num_rows($result) > 0) {

                                                    echo "<tbody>";

                                                        $flag = 1;
                                                        
                                                        while ($row = $result->fetch_array()) {
                                                            
                                                            if( $db === false){
                                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                                            }

                                                            if (empty($row['date_chantier']) && empty($row['inter_chantier']) && empty($row['name_chantier']) && !empty($row['concat'])/*&& !empty($row['chantier_id'])*/ && $flag <= 4) {
                                                                
                                                                $flag += 1;

                                                                echo '<tr>';

                                                                    echo '<td class="align-middle p-1 w-25" style="word-wrap: break-word;">';
                                                                        $total = $row['tothsnight'];
                                                                        $hours = (int)($total / 10000);
                                                                        $minutes = ((int)($total - ($hours * 10000)) / 100) / 60;
                                                                        $total = $hours + $minutes;
                                                                    echo $total . '</td>';
                                                                    echo '<td class="align-middle border-left border-right p-1 w-25" style="word-wrap: break-word;">';
                                                                        $m25 = $row['maj25'];
                                                                        $hours = (int)($m25 / 10000);
                                                                        $minutes = ((int)($m25 - ($hours * 10000)) / 100) / 60;
                                                                        $m25 = $hours + $minutes;
                                                                        if ($m25 > 0) {
                                                                            echo $m25 . '<br />' . $m25 * 25 / 100 . ' maj.';
                                                                        } else {
                                                                            echo '<div class="bg-secondary w-100 h-100"></div>';
                                                                        }
                                                                    echo '</td>';
                                                                    echo '<td class="align-middle p-1 w-25" style="word-wrap: break-word;">';
                                                                        $m50 = $row['maj50'];
                                                                        $hours = (int)($m50 / 10000);
                                                                        $minutes = ((int)($m50 - ($hours * 10000)) / 100) / 60;
                                                                        $m50 = $hours + $minutes;
                                                                        if ($m50 > 0) {
                                                                            echo $m50;
                                                                            if ($row['h_night_tot'] != 0) {
                                                                                $hnight = $row['h_night_tot'];
                                                                                $hours = (int)($hnight / 10000);
                                                                                $minutes = ((int)($hnight - ($hours * 10000)) / 100) / 60;
                                                                                $hnight = $hours + $minutes;
                                                                                echo '<div class="d-inline small text-success">&nbsp;&nbsp;&nbsp;[nuit =&nbsp;' . $hnight . ']</div>';
                                                                            } 
                                                                            echo '<br />' . $m50 * 50 / 100 . ' maj.';
                                                                        } else {
                                                                            echo '<div class="bg-secondary w-100 h-100"></div>';
                                                                        }
                                                                    echo '</td>';
                                                                    
                                                                echo '</tr>';
                                                            }
                                                        }
                                                    echo '</tbody>';
                                                    mysqli_free_result($result);
                                                } else {
                                                    echo "Cette personne n'a pas encore effectué d'heure pour le moment.";
                                                }
                                            } else {
                                                echo "ERROR: Could not get 'id' of current user [third_method]";
                                            }
                                    echo '</table>
                                </div>';
                
                            }
                        mysqli_close($db);
                        ?>
                        <div class="pt-5 w-75 m-auto">
                            <a href="javascript:history.go(-1)" value="return" class="btn finish border-0 bg-white z-depth-1a mt-1 mb-4 text-dark">Précédent</a>
                        </div>
                    </form>
            <?php } ?>

            </div>
        </div>
    </body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="/js/jQuery.stayInWebApp-master/jquery.stayInWebApp.js"></script>
    <script src="/js/jQuery.stayInWebApp-master/jquery.stayInWebApp.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
        $(function() {
            $.stayInWebApp();
        });
    </script>
</html>