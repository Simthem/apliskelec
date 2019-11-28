<?php
session_start();

include '../config/db_connexion.php';

if(!($_SESSION['username'])) {  
  
    header('Location: ../../signin.php');//redirect to login page to secure the welcome page without login access.  
}

$stmt = $bdd->prepare("SELECT id FROM users WHERE username = '". $_SESSION['username'] ."'");
$stmt->execute();
$user = $stmt->fetch();
$stmt_admin = $bdd->prepare("SELECT * FROM `admin` WHERE admin_name = '". $_SESSION['admin_name'] ."'");
$stmt_admin->execute();
$admin = $stmt_admin->fetch();
if ($user) {
    $_SESSION['id'] = $user['id'];
} elseif ($admin) {
    $_SESSION['id'] = $admin['id'];
} else {
    echo "ERROR: Could not get 'id' of current user [first_method]";
}

if ($bdd === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}


if (isset($_POST['up_inter']) && !empty($_POST['up_inter'])) {

    $inter_glo = $bdd->prepare("SELECT * FROM global_reference WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "'");
    $inter_glo->execute();

    if ($bdd === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $x = 0;

    while ($reponse = $inter_glo->fetchColumn(PDO::FETCH_ASSOC)) {
        $x += 1;
    }

    $inter_glo->closeCursor();
    $inter_glo->execute();

    
    if ($test = $inter_glo->fetchAll(PDO::FETCH_ASSOC)) {
        //print_r($test);
        $i = 0;
        //echo '<br />' . $test[0]['id'] . '<br />';
        while ($i < $x) {

            $temp[$i] = $test[$i]['id'];

            if (isset($temp[$i]) && !empty($temp[$i])) {

                $sql = $bdd->prepare("SELECT * FROM global_reference WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' AND id = '" . $test[$j]['id'] . "'");
                $sql->execute();

                $final = $sql->fetch();

                //print_r($final);
                //echo '<br />';
                //print_r($_POST);
                if (isset($_POST["tot_h$i"]) AND !empty($_POST["tot_h$i"])) {

                    $norm_h = $_POST["tot_h$i"] . ':00';
                    $glo_h = DateTime::createFromFormat('H:i:s', $norm_h);
                    $glo_h = $glo_h->format('H:i:s');

                    if ($glo_h != $final['intervention_hours']) {
                        $new_normh = htmlspecialchars($glo_h);
                        $insertnormh = $bdd->prepare("UPDATE global_reference SET intervention_hours = '" . $new_normh . "' WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' AND id = '" . $test[$i]['id'] . "'" );
                        $insertnormh->execute(array($new_normh, $glo_h));
                        header("refresh:0; url= ../../valid_day.php?up_int=" . $_POST['up_inter']);
                    } 
                } 

                if (isset($_POST["tot_h_night$i"]) AND !empty($_POST["tot_h_night$i"])) {

                    //echo '<br />' . $_POST["tot_h_night$i"];
                    $night_h = $_POST["tot_h_night$i"] . ':00';
                    //echo '<br />' . $night_h;
                    $glo_night = DateTime::createFromFormat('H:i:s', $night_h);
                    $glo_night = $glo_night->format('H:i:s');
                    //echo $glo_night . '<br />';
                    //echo $final['night_hours'];

                    if ($glo_night != $final['night_hours']) {
                        $new_nighth = htmlspecialchars($glo_night);
                        $insertnighth = $bdd->prepare("UPDATE global_reference SET night_hours = '" . $new_nighth . "' WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' AND id = '" . $test[$i]['id'] . "'");
                        $insertnighth->execute(array($new_nighth, $glo_night));
                        header("refresh:0; url= ../../valid_day.php?up_int=" . $_POST['up_inter']);
                    }
                }

                if (isset($_POST['panier_repas']) AND !empty($_POST['panier_repas'])) {
                    $newpan = htmlspecialchars($_POST['panier_repas']);
                    $insertpan = $bdd->prepare("UPDATE global_reference SET panier_repas = '" . $newpan . "' WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' AND id = '" . $test[$i]['id'] . "'");
                    $insertpan->execute(array($newpan, $_POST['panier_repas']));
                    header("refresh:0; url= ../../valid_day.php?up_int=" . $_POST['up_inter']);
                } else if (!isset($_POST['panier_repas'])) {
                    $newpan = htmlspecialchars(0);
                    $insertpan = $bdd->prepare("UPDATE global_reference SET panier_repas = '" . $newpan . "' WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' AND id = '" . $test[$i]['id'] . "'");
                    $insertpan->execute(array($newpan, $_POST['panier_repas']));
                    header("refresh:0; url= ../../valid_day.php?up_int=" . $_POST['up_inter']);
                }
                $i += 1;
            }
        }

        if (isset($_POST['flag']) AND $_POST['flag'] != 0) {
            for ($j = 0; $j < $x; $j++) {
                $newstate = htmlspecialchars($_POST['flag']);
                $insertstate = $bdd->prepare("UPDATE global_reference SET `state`= '" . $newstate . "' WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' AND id = '" . $test[$j]['id'] . "'");
                $insertstate->execute(array($newstate, $_POST['flag']));
                print_r($insertstate);
                echo 'ca fonctionne';
            }
            echo '<script type="text/javascript">alert("Édition validée :)")</script>';
            header("refresh:0; url= ../../valid_day.php?up_int=" . $_POST['up_inter']);
            exit ();
        } else {
            echo '<script type="text/javascript">alert("Édition validée :)")</script>';
            header("refresh:0; url= ../../valid_day.php?up_int=" . $_POST['up_inter']);
            exit ();
        }
    }
}


?>