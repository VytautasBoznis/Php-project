<html>
	<head>
	</head>
	<body>
		<?php
			ini_set("display_errors", 1);
			session_start();
			require "maincore.php";
			
			if(isset($_SESSION['user_id']))
				header("Location: http://".$_SERVER['HTTP_HOST']."/testam/home.php");
			
			$user_id = get_user_id($_POST["user_name"],$_POST["user_pass"]);
			
			if($user_id != 0)
			{
				$_SESSION['user_id'] = $user_id;
				header("Location: http://".$_SERVER['HTTP_HOST']."/testam/home.php");
			}
			else
			{
				echo "<center>Neteisingas vartotojo vardas arba slaptazodis.<br>";
				echo "Noredami pabandyti prisijunkti dar karta spauskite mygtuka:<br>";
				echo "<form action='index.html'><button type='submit'>Atgal</button></form></center>";
			}
		?>
	</body>
<html>