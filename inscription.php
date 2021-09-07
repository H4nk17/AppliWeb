<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Inscription | CDC</title>
		
		<link rel="shortcut icon" href="assets/images/logo.png">
		
		<link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/style.css">
	</head>
	<body>
		
		
		<?php
			//si le formulaire est envoyé ("envoyé" signifie que le bouton submit est cliqué)
			if(isset($_POST['valider'])){
				//vérifie si tous les champs sont bien  pris en compte:
				//on peut combiner isset() pour valider plusieurs champs à la fois
				if(!isset($_POST['nom'],$_POST['pseudo'],$_POST['mdp'],$_POST['mail'])){
					echo "Un des champs n'est pas reconnu.";
				} else {
					//on vérifie le contenu de tous les champs, savoir si ils sont correctement remplis avec les types de valeurs qu'on souhaitent qu'ils aient
					if(!preg_match("#^[a-z0-9]{1,15}$#",$_POST['nom'])){
						//la preg_match définie: ^ et $ pour dire commence et termine par notre masque;
						//notre masque défini a-z pour toutes les lettres en minuscules et 0-9 pour tous les chiffres;
						//d'une longueur de 1 min et 15 max
						echo "Le nom est incorrect, doit contenir seulement des lettres minuscules et/ou des chiffres, d'une longueur minimum de 1 caractère et de 15 maximum.";
						//Il est préférable que le pseudo soit en lettres minuscules ceci afin d'être unique, par exemple si le choix peut être avec majuscule, deux membres pourrait avoir le même pseudo, par exemple Admin et admin et ce n'est pas ce que l'on veut.
					} else {
						//on vérifie le contenu de tous les champs, savoir si ils sont correctement remplis avec les types de valeurs qu'on souhaitent qu'ils aient
						if(!preg_match("#^[a-z0-9]{1,15}$#",$_POST['pseudo'])){
							//la preg_match définie: ^ et $ pour dire commence et termine par notre masque;
							//notre masque défini a-z pour toutes les lettres en minuscules et 0-9 pour tous les chiffres;
							//d'une longueur de 1 min et 15 max
							echo "Le pseudo est incorrect, doit contenir seulement des lettres minuscules et/ou des chiffres, d'une longueur minimum de 1 caractère et de 15 maximum.";
							//Il est préférable que le pseudo soit en lettres minuscules ceci afin d'être unique, par exemple si le choix peut être avec majuscule, deux membres pourrait avoir le même pseudo, par exemple Admin et admin et ce n'est pas ce que l'on veut.
						} else {
							//on vérifie le mot de passe:
							if(strlen($_POST['mdp'])<5 or strlen($_POST['mdp'])>15){
								echo "Le mot de passe doit être d'une longueur minimum de 5 caractères et de 15 maximum.";
							} else {
								//on vérifie que l'adresse est correcte:
								if(!preg_match("#^[a-z0-9_-]+((\.[a-z0-9_-]+){1,})?@[a-z0-9_-]+((\.[a-z0-9_-]+){1,})?\.[a-z]{2,30}$#i",$_POST['mail'])){
									//cette preg_match est un petit peu complexe, je vous invite à regarder l'explication détaillée sur mon site c2script.com
									echo "L'adresse mail est incorrecte.";
									//normalement l'input type="email" vérifie que l'adresse mail soit correcte avant d'envoyer le formulaire mais il faut toujours être prudent et vérifier côté serveur (ici) avant de valider définitivement
								} else {
									if(strlen($_POST['mail'])<7 or strlen($_POST['mail'])>50){
										echo "Le mail doit être d'une longueur minimum de 7 caractères et de 50 maximum.";
									} else {
										//tout est précisés correctement, on inscrit le membre dans la base de données si le pseudo n'est pas déjà utilisé par un autre utilisateur
										//d'abord il faut créer une connexion à la base de données dans laquelle on souhaite l'insérer:
										$mysqli=mysqli_connect('localhost','root','root','membres');//'serveur','nom d'utilisateur','pass','nom de la table'
										if(!$mysqli) {
											echo "Erreur connexion BDD";
											//Dans ce script, je pars du principe que les erreurs ne sont pas affichées sur le site, vous pouvez donc voir qu'elle erreur est survenue avec mysqli_error(), pour cela décommentez la ligne suivante:
											//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
										} else {
											$Nom=htmlentities($_POST['nom'],ENT_QUOTES,"UTF-8");
											$Pseudo=htmlentities($_POST['pseudo'],ENT_QUOTES,"UTF-8");//htmlentities avec ENT_QUOTES permet de sécuriser la requête pour éviter les injections SQL, UTF-8 pour dire de convertir en ce format
											$Mdp=md5($_POST['mdp']);// la fonction md5() convertie une chaine de caractères en chaine de 32 caractères d'après un algorithme PHP, cf doc
											$Mail=htmlentities($_POST['mail'],ENT_QUOTES,"UTF-8");
											if(mysqli_num_rows(mysqli_query($mysqli,"SELECT * FROM membres WHERE nom='$Nom'"))!=0){//si mysqli_num_rows retourne pas 0
												echo "Ce pseudo est déjà utilisé par un autre membre, veuillez en choisir un autre svp.";
											} else {
												//insertion du membre dans la base de données:
												if(mysqli_query($mysqli,"INSERT INTO membres SET nom='$Nom', pseudo='$Pseudo', mdp='$Mdp', mail='$Mail'")){
													echo header("Location: connexion.php");
													$TraitementFini=true;//pour cacher le formulaire
												} else {
													echo "Une erreur est survenue, merci de réessayer ou contactez-nous si le problème persiste.";
													//echo "<br>Erreur retournée: ".mysqli_error($mysqli);
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
			if(!isset($TraitementFini)){
		?>
		

		<div class="registration-form">
			<form method="post"	action="inscription.php">
				<div class="form-icon">
					<span><i class="icon icon-user"></i></span>
				</div>
				<div class="form-group">
					<input type="text" name="nom" class="form-control item" id="name" placeholder="Nom" required>
				</div>
				<div class="form-group">
					<input type="text" name="pseudo" class="form-control item" id="username" placeholder="Pseudo" required>
				</div>
				<div class="form-group">
					<input type="password" name="mdp" class="form-control item" id="password" placeholder="Mot de passe" required>
				</div>
				<div class="form-group">
					<input type="email" name="mail" class="form-control item" id="email" placeholder="Email" required>
				</div>
				<div class="form-group">
					<button type="submit" name="valider" class="btn btn-block create-account">Créer mon compte</button>
				</div>
				<div class="link text-center">Déjà membre ? <a href="connexion.php">Connexion</a></div>
			</form>
			</div>


		<?php
			
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