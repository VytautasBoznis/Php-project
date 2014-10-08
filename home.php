<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style/style.css">
	</head>
	<body>
		<?php
			session_start();
			require "maincore.php";
			
			$user = get_user_data($_SESSION['user_id'],false); 
			
			echo "<div id='side-menu' class='side-menu'>";
			echo "Sveiki <b>".$user->first_name." ".$user ->last_name."</b>! <br><br>";
			echo "Pasirinkite vaiksma: <br>";
			
			if($user->user_level >=3)
			{
				echo "<a href='/testam/manageUsers.php'>Redaguoti vartotojus</a><br>";
				echo "<a href='/testam/manageUni.php'>Redaguoti aukstasiai mokyklas</a><br>";
			}
			
			if($user->user_level >=2)
				echo "<a href='/testam/managePrograms.php'>Redaguoti studiju programas</a><br>";
			
			if($user->user_level ==1)
			{
				echo "<a href='/testam/regProgram.php'>Registruotis i stojimu sarasa</a><br>";
				echo "<a href='/testam/cancelReg.php'>Atsaukti registracija i stojimu sarasa</a><br>";
			}
			echo "</div>";
		
			echo "<div id='content' class='content'>";
			echo "";//stojimu sarasas virsui vardas pavarde paskui pasiriktas uni tada profesija tada vieta eilei ir  vidurkis pagal spec.
			echo "</div>";
		?>
	</body>
</html>