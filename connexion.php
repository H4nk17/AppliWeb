<?php
	
	session_start();
	
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Connexion | CDC</title>
		
		<link rel="shortcut icon" href="assets/images/logo.png">
		
		<link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/style.css">
		
	</head>
	<body>
		
		
		<?php
			//si une session est déjà "isset" avec ce visiteur, on l'informe:
			if(isset($_SESSION['pseudo'])){
				echo header("Location: espace-membre.php");
			} else {
				//si le formulaire est envoyé
				if(isset($_POST['valider'])){
					//vérifie si tous les champs sont bien pris en compte:
					if(!isset($_POST['pseudo'],$_POST['mdp'])){
						echo "Un des champs n'est pas reconnu.";
					} else {
						//tous les champs sont précisés, on regarde si le membre est inscrit dans la bdd:
						//d'abord il faut créer une connexion à la base de données dans laquelle on souhaite regarder:
						$mysqli=mysqli_connect('localhost','root','root','membres');
						if(!$mysqli) {
							echo "Erreur connexion BDD";
							//Dans ce script, je pars du principe que les erreurs ne sont pas affichées sur le site, vous pouvez donc voir qu'elle erreur est survenue avec mysqli_error(), pour cela décommentez la ligne suivante:
							//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
						} else {
							//on défini nos variables:
							$Pseudo=htmlentities($_POST['pseudo'],ENT_QUOTES,"UTF-8");//htmlentities avec ENT_QUOTES permet de sécuriser la requête pour éviter les injections SQL, UTF-8 pour dire de convertir en ce format
							$Mdp=md5($_POST['mdp']);
							$req=mysqli_query($mysqli,"SELECT * FROM membres WHERE pseudo='$Pseudo' AND mdp='$Mdp'");
							//on regarde si le membre est inscrit dans la bdd:
							if(mysqli_num_rows($req)!=1){
								echo "Pseudo ou mot de passe incorrect.";
							} else {
								//pseudo et mot de passe sont trouvé sur une même colonne, on ouvre une session:
								$_SESSION['pseudo']=$Pseudo;
								echo header("Location: espace-membre.php");
								$TraitementFini=true;//pour cacher le formulaire
							}
						}
					}
				}
				if(!isset($TraitementFini)){//quand le membre sera connecté, on définira cette variable afin de cacher le formulaire
		?>
		
		
		<div class="registration-form">
			<form method="post"	action="connexion.php">
				<div class="form-icon">
					<span><i class="icon icon-user"></i></span>
				</div>
				<div class="form-group">
					<input type="text" name="pseudo" class="form-control item" id="username" placeholder="Pseudo" required>
				</div>
				<div class="form-group">
					<input type="password" name="mdp" class="form-control item" id="password" placeholder="Mot de passe" required>
				</div>
				<div class="form-group">
					<button type="submit" name="valider" class="btn btn-block create-account">Connexion</button>
					<button type="submit" class="btn btn-block create-account"><a href="mot-de-passe-oublie.php" class="text-muted text-white">Mot de passe oublié</a></button>
				</div>
				<div class="link text-center"><a href="inscription.php">S'inscrire</a></div>
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