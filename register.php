<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style/style.css">
	</head>
	<body>
		<?php 
			session_start();
		?>
		<div id ='register' class='register-form'>
			Uzpildykite visus laukus:<br><br>
			<form action='registerAuth.php' method = 'post'>
				Vartotojo vardas: <input type = 'text' name = 'user_name'><br>
				Slaptazodis: <input type = 'password' name = 'user_pass'><br><br>
				Vardas: <input type = 'text' name = 'first_name'><br>
				Pavarde: <input type = 'text' name = 'last_name'><br><br>
				<button type = 'submit'>Registruotis</button>
			</form>
		</div>
	</body>
</html>