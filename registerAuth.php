<html>
	<head>
	</head>
	<body>
		<?php
			session_start();
			require 'maincore.php';
			
			if(empty($_POST['user_name']) || empty($_POST['user_pass']) || empty($_POST['first_name']) || empty($_POST['last_name']))
			{
				echo "<center>Pildant registracijos forma nebuvo uzpildyti visi galimi laukai.<br>";
				echo "Spauskite mygtuka noredami pameginti dar karta.<br>";
				echo "<form action='register.php'><button type='submit'>Atgal</button></form></center>";
			}
			else if(!add_user($_POST['user_name'],$_POST['user_pass'],$_POST['first_name'],$_POST['last_name'],1))
			{
				echo "<center>Registruojantis ivyko klaida<br>";
				echo "Spauskite mygtuka noredami pameginti dar karta.<br>";
				echo "<form action='register.php'><button type='submit'>Atgal</button></form></center>";
			}
			else
			{
				echo "<center>Sekmingai uzsiregistravote, galite gryzti atgal ir prisijungti.<br>";
				echo "<form action='index.html'><button type='submit'>Atgal</button></form></center>";
			}
		?>
	</body>
</html>