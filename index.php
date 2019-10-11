<?php
session_start();

include_once '../config/database.php';

if(!($_SESSION['username'])) {  
  
    header("Location: signin.php");//redirect to login page to secure the welcome page without login access.  
}
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
                            <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="troubleshooting_list.html" class="text-warning">Chantiers</a></li>
                            <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="list_profil.php" class="text-warning">Salariés</a></li>
                            <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="#" class="text-warning">Paramètres</a></li>
                            <li class="bg-dark border-top border-bottom border-warning rounded-0 p-0 menu-link"><a href="api/User/logout.php" class="text-warning">Déconnexion</a></li>
                        </ul>
                    </div>
                </nav>
                <div class="icons-navbar">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning m-auto"><h2 class="m-0">S.K.elec</h2></a>
                    <a href="add_troubleshooting.html" class="text-white pl-3"><i class="menu-btn-plus fas fa-plus-circle text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div id="container">
            <div class="content">
                <div class="w-50 m-auto p-3">
                    <h4 class="text-center"><?php echo date('d/m/Y'); ?></h4>
                    <div class="text-center"><?php echo $_SESSION['username']; ?></div>
                    <div class="text-center"><?php if($_SESSION['username'] == "admin") { echo "Administrateur de S.K.elec_app ;)";}?></div>
                </div>
                <form class="text-center">
                    <select name="Listing ID" size="1">
                        <option class="listing_title">Listing IDs</option>
                        <option>Choix 1</option>
                        <option>Choix 2</option>
                        <option>Choix 3</option>
                        <option>Choix 4</option>
                    </select>
                </form>
                <form class="pt-1 w-25">
                    <div class="m-auto text-center">
                        <div class="ml-0 mr-0">
                            <label for="input_time m-auto">Heures réalisées</label>
                            <div class='col'>
                                <input type='text' class="form-control text-center p-1" placeholder="hh:mm" />
                            </div>
                        </div>
                    </div>
                </form>
                <form class="d-flex flex-column border-top pt-3 pb-3 w-75">
                    <div class="border-bottom pt-1 pb-3">
                        <div class="form mb-2">
                            <input type="checkbox" class="form-check-input ml-3 mt-1">
                            <label class="mt-auto ml-5 mb-auto" for="">Panier repas</label>
                        </div>
                        <div class="d-inline-flex h-25">
                            <div class="mt-auto mb-auto pt-1">
                                <input type="checkbox" class="form-check-input ml-3 mb-0 mt-2">
                                <label class="mb-0 ml-5 text-center" for="">Dont :</label>
                            </div>
                            <div class="col d-inline-flex pr-0 pl-2 mt-auto mb-auto">
                                <input type="text" class="col-7 form-control p-1 mt-auto mb-auto text-center h-75" placeholder="minutes/heures">
                                <label class="mt-auto ml-2 mb-auto">heures de nuit</label>
                            </div>
                        </div>
                        <div class="form mt-2 mb-3 pt-2 pb-3">
                            <textarea class="form-control" placeholder="Informations ?"></textarea>
                        </div>
                    </div>
                    <div class="mt-4 mr-auto ml-auto">
                        <a href="#" type="submit" value="Soumettre" class="btn send border-0 bg-white z-depth-1a mt-4 mb-3 text-dark">Soumettre</a>
                        <a href="#" type="submit" value="Clotûrer le chantier" class="btn finish border-0 bg-white z-depth-1a mt-3 mb-1 text-dark">Clôturer le chantier</a>
                    </div>
                </form>
                
                <footer>
                </footer>
            </div>
        </div>

        <footer>
        </footer>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/script.js"></script>
        <script src="js/bootstrap.js"></script>
    </body>
</html>