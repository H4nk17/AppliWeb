<?php
    
$SQL['host']="localhost";
$SQL['user']="root";
$SQL['pass']="root";
$SQL['base']="recup_mdp";

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Récupération | CDC</title>
        
        <link rel="shortcut icon" href="assets/images/logo.png">
        
        <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        
        
        <?php
        if(isset($_GET['code'],$_GET['mail'])){
            //un lien est cliqué dans un mail, on recherche si le code et le mail correspondent à une ligne dans la table "recup_mdp"
            $Code=htmlentities($_GET['code'],ENT_QUOTES,"UTF-8");
            $Mail=htmlentities($_GET['mail'],ENT_QUOTES,"UTF-8");
            $mysqli=mysqli_connect($SQL['host'],$SQL['user'],$SQL['pass'],$SQL['base']);
            if(!$mysqli) {
                echo "Erreur connexion BDD";
                //Dans ce script, je pars du principe que les erreurs ne sont pas affichées sur le site, vous pouvez donc voir qu'elle erreur est survenue avec mysqli_connect_error(), pour cela décommentez la ligne suivante:
                
                echo "<br>Erreur retournée: ".mysqli_connect_error();
                
            } else {
                $req=mysqli_query($mysqli,"SELECT * FROM recup_mdp WHERE code='$Code' AND mail='$Mail'");
                if(mysqli_num_rows($req)==1){
                    //on génère un nouveau pass (de 5 caractères) et on lui envoi:
                    $NouveauPass=substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"),0,5);
                    //on modifie son mot de passe pour son compte
                    mysqli_query($mysqli,"UPDATE membres SET mdp='".md5($NouveauPass)."' WHERE mail='$Mail'");
                    //on ui envoi un mail avec son pass temporaire:
                    mail($Mail,"Votre nouveau mot de passe","Le nouveau mot de passe pour votre compte est: $NouveauPass (Il est vivement conseille de le modifier depuis votre espace membre)");
                    //on supprime la demande mot de passe qui est dans la table "recup_mdp":
                    mysqli_query($mysqli,"DELETE FROM recup_mdp WHERE code='$Code' AND mail='$Mail'");
                    echo "Votre nouveau pass temporaire vient d'être envoyé par mail.";
                } else {
                    echo "Lien incorrect.";
                }
            }
        } else {
            //si le formulaire est envoyé ("envoyé" signifie que le bouton submit est cliqué)
            if(isset($_POST['valider'])){
                //vérifie si le champ mail est bien rempli:
                if(empty($_POST['mail'])){
                    echo "Le champs mail n'est pas renseigné.";
                } else {
                    //tous les champs sont précisés, on regarde si le membre est inscrit dans la bdd:
                    //d'abord il faut créer une connexion à la base de données dans laquelle on souhaite regarder:
                    $mysqli=mysqli_connect($SQL['host'],$SQL['user'],$SQL['pass'],$SQL['base']);
                    if(!$mysqli) {
                        echo "Erreur connexion BDD";
                        //Dans ce script, je pars du principe que les erreurs ne sont pas affichées sur le site, vous pouvez donc voir qu'elle erreur est survenue avec mysqli_error(), pour cela décommentez la ligne suivante:
                        
                        //echo "<br>Erreur retournée: ".mysqli_error($mysqli);
                        
                    } else {
                        //on défini nos variables:
                        $Mail=htmlentities($_POST['mail'],ENT_QUOTES,"UTF-8");//htmlentities avec ENT_QUOTES permet de sécuriser la requête pour éviter les injections SQL, UTF-8 pour dire de convertir en ce format
                        $req=mysqli_query($mysqli,"SELECT * FROM membres WHERE mail='$Mail'");
                        //on regarde si le membre est inscrit dans la bdd:
                        //même si le membre n'existe pas, on affiche qu'un mail à été envoyé, ceci permet d'empécher les robots de voir si un mail existe ou pas dans votre base de données et de vous le dérober
                        if(mysqli_num_rows($req)!=1){
                            //mail inconnu
                        } else {
                            //mail connu, on lance la procédure d'envoi du mail pour recevoir un nouveau mdp
                            $Code=md5(rand(1,99999999));
                            mysqli_query($mysqli,"INSERT INTO recup_mdp SET code='$Code', mail='$Mail'");
                            $Lien=$_SERVER['HTTP_HOST']."/mot-de-passe-oublie.php?code=$Code&mail=$Mail";
                            mail($Mail,"Recuperation du mot de passe","Pour recevoir un nouveau mot de passe cliquez sur le lien suivant: $Lien");
                        }
                        echo "<p>Vous allez recevoir un email contenant un lien afin de recevoir un nouveau mot de passe.<a href='connexion.php'> Retourner sur la page connexion</a></p>";
                        $TraitementFini=true;//pour cacher le formulaire
                    }
                }
            }
            if(!isset($TraitementFini)){//quand le membre sera connecté, on définira cette variable afin de cacher le formulaire
                ?>
                
                
        <div class="registration-form">
            <form method="post"	action="mot-de-passe-oublie.php">
                <div class="form-icon">
                    <span><i class="icon icon-user"></i></span>
                </div>
                <div class="pass text-center" ><h2>Mot de passe oublié</h2>
                    <p>Veuillez entrer l'adresse email associée à votre compte.</p>
                </div>
                <div class="form-group">
                    <input type="text" name="mail" class="form-control item" id="username" placeholder="Adresse email" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="valider" class="btn btn-block create-account">Réinitialiser mon mot de passe</button>
                </div>
            </form>
        </div>
        
        
        <?php
            
            }
            }
            
        ?>


        <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
        <script src="assets/js/script.js"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js" integrity="sha384-lpyLfhYuitXl2zRZ5Bn2fqnhNAKOAaM/0Kr9laMspuaMiZfGmfwRNFh8HlMy49eQ" crossorigin="anonymous"></script>
    </body>
</html>