<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style/style.css">
	</head>
	<body>
		<?php 
			session_start();
			require 'maincore.php';
			
			$user = get_user_data($_SESSION['user_id'],false);
			
			if($user-> user_level < 3 || !isset($_SESSION['user_id']))
				header("Location: http://".$_SERVER['HTTP_HOST']."/testam/index.html");
				
			echo "<div id='side-menu' class='side-menu'>";
			echo "Sveiki <b>".$user->first_name." ".$user ->last_name."</b>! <br><br>";
			echo "Pasirinkite vaiksma: <br><br>";
			echo "<a href='/testam/home.php'>Grysti i pagrindini puslapi</a>";
			echo "</div>";
			
			echo "<div id='content' class='content'>";
			if($_GET['action'] == "add")
			{
				if(empty($_POST['confirm']))
				{
					echo "Noredami prideti vartotoja uzpildykite visus laukus:<br><br>";
					echo "<form action='manageUsers.php?action=add' method = 'post'>";
					echo "Vartotojo vardas: <input type = 'text' name = 'user_name'><br>";
					echo "Slaptazodis: <input type = 'password' name = 'user_pass'><br><br>";
					echo "Vardas: <input type = 'text' name = 'first_name'><br>";
					echo "Pavarde: <input type = 'text' name = 'last_name'><br><br>";
					echo "Vartotojo lygis: <input type ='text' name ='user_level'><br>";
					echo "<input type='hidden' name='confirm' value='true'>";
					echo "<button type = 'submit'>Prideti vartotoja</button></form>";
				}
				else
				{
					if(empty($_POST['user_name']) || empty($_POST['user_pass']) || empty($_POST['first_name']) || empty($_POST['last_name'])|| empty($_POST['user_level']))
						echo "Registruojant vartotoja nebuvo uzpildyti visi reikalaujami laukai, pabandyti dar karta galite paspaude <a href='/testam/manageUsers.php?action=add'>cia</a>";
					else if(add_user($_POST['user_name'],$_POST['user_pass'],$_POST['first_name'],$_POST['last_name'],$_POST['user_level']))
						echo "Vartotojas pridetas sekmingai gryzti i vartotoju valdima galite paspaude <a href='/testam/manageUsers.php'>cia</a>";
					else
						echo "Registruojant vartotoja ivyko klaida pabandyti dar karta galite paspaude <a href='/testam/manageUsers.php?action=add'>cia</a>";
				}
			}
			else if($_GET['action'] == "edit")
			{
				if(empty($_POST['confirm']))
				{
					$user = get_user_data($_GET['user_id'],false);
					
					echo "Jus redaguojate vartotoja <b>".$user->user_name."</b> noredami testi uzpildykite forma ir spauskite 'Redaguoti vartotoja'.<br><br>";
					echo "<form action='manageUsers.php?action=edit&user_id=".$user->user_id."' method = 'post'>";
					echo "Vartotojo vardas: <input type = 'text' name = 'user_name' value='".$user->user_name."'><br>";
					echo "Slaptazodis: <input type = 'password' name = 'user_pass' value='".$user->user_pass."'><br><br>";
					echo "Vardas: <input type = 'text' name = 'first_name' value='".$user->first_name."'><br>";
					echo "Pavarde: <input type = 'text' name = 'last_name' value='".$user->last_name."'><br><br>";
					echo "Vartotojo lygis: <input type ='text' name ='user_level' value='".$user->user_level."'><br>";
					echo "<input type='hidden' name='confirm' value='true'>";
					echo "<button type = 'submit'>Redaguoti vartotoja</button></form>";
				}
				else
				{
					if(empty($_POST['user_name']) || empty($_POST['user_pass']) || empty($_POST['first_name']) || empty($_POST['last_name'])|| empty($_POST['user_level']))
						echo "Redaguojant vartotoja nebuvo uzpildyti visi reikalaujami laukai, pabandyti dar karta galite paspaude <a href='/testam/manageUsers.php?action=edit&user_id=".$_GET['user_id']."'>cia</a>";
					else if(edit_user($_GET['user_id'],$_POST['user_name'],$_POST['user_pass'],$_POST['first_name'],$_POST['last_name'],$_POST['user_level']))
						echo "Vartotojas buvo sekmingai redaguotas grysti i pagrindini puslapi galite paspaude <a href='/testam/manageUsers.php'>cia</a>";
					else
						echo "Redaguojant vartotoja iskilo klaida pabandyti dar karta galite paspaude <a href='/testam/manageUsers.php?action=edit&user_id=".$_GET['user_id']."'>cia</a>";
				}
			}
			else if($_GET['action'] == "delete")
			{
				if(empty($_POST['confirm']))
				{
					$user = get_user_data($_GET['user_id'],false);
					
					echo "Noredami istrinti vartotoja <b>".$user->user_name." paspauskite 'Patvirtinti'."; 
					echo "<form action= 'manageUsers.php?action=delete&user_id=".$_GET['user_id']."' method='post'>";
					echo "<input type= 'hidden' name= 'confirm' value= 'true'>";
					echo "<button type='submit'>Patvirtinti</button>";
				}
				else
					if(delete_user($_GET['user_id']))
						echo "Vartotojas pasalintas sekmingai gryzti i pagrindini puslapi galite paspaude <a href='/testam/manageUsers.php'>cia</a>";
					else
						echo "Pasalinant vartotoja iskilo klaida pabandyti dar karta galite paspaude cia <a href='/testam/manageUsers.php?action=delete&user_id=".$_GET['user_id']."'>cia</a>";
			}
			else
			{
				$users = get_user_data(0,true);
				
				echo "Siuo metu yra uzregistrave sie vartotojai: <br><br>";
				echo "Vartotojo lygiu paaiskinimas: <br>";
				echo "1-ojo lygio vartotojai - vartotojai kurie gali uzsiregistruoti i siulomas mokymosi programas.<br>";
				echo "2-ojo lygio vartotojai - vartotojai kurie gali redaguoti mokymosi programas.<br>";
				echo "3-ojo lygio vartotojai - systemos administratoriai.<br><br>";
				echo "<center><table>";
				echo "<tr><th>Vartotojo vardas</th><th>Slaptazodis</th><th>Vardas</th><th>Pavarde</th><th>Vartotojo Lygis</th><th>Redagavimas</th><th>Trinimas</th></tr>";
			
				foreach($users as $user)
					echo "<tr><th>".$user -> user_name."</th><th>".$user -> user_pass."</th><th>".$user -> first_name."</th><th>".$user -> last_name."</th><th>".$user -> user_level."</th><th><a href='manageUsers.php?action=edit&user_id=".$user->user_id."'>Redaguoti</a></th><th><a href='manageUsers.php?action=delete&user_id=".$user->user_id."'>Trinti</a></th></tr>"; 
			
				echo "</table></center>";
			
				echo "<br><a href='/testam/manageUsers.php?action=add'>Prideti nauja</a><br>";
			}
			
			echo "<div>";
		?>
	</body>
</html>