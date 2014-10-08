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
				if(empty($_POST['uni_name']))
				{
					echo"Prideti aukstajai mokyklai irasykite jos pavadinima: <br><br>";
					echo"<form action='manageUni.php?action=add' method='post'><input type='text' name='uni_name'><br><br>";
					echo"<button action='submit'>Prideti</button></form>";
				}
				else
					if(add_university($_POST['uni_name']))
						echo "Aukstoji mokykla prideta! Galite gryzti i pradini puslapi paspaude <a href='manageUni.php'>cia</a>";
					else
						echo "Pridedant aukstaja mokykla ivyko klaida. Pabandykite dar karta paspaude <a href='manageUni.php?action=add'>cia</a>";
			}
			else if($_GET['action'] == "edit")
			{
				if(empty($_POST['new_name']))
				{
					$uni = get_uni_data($_GET['uni_id'],false);
				
					echo "Dabar redaguojama <b>".$uni->uni_name."</b>.";
					echo "<form action='manageUni.php?action=edit&uni_id=".$_GET['uni_id']."' method='post'>";
					echo "<input type='text' name='new_name' value='".$uni->uni_name."'><br>";
					echo "<button type='submit'>Patvirtinti Redagavima</button></form>";
				}
				else
					if(change_uni_name($_GET['uni_id'],$_POST['new_name']))
						echo "Aukstoji mokykla redaguota sekmingai! Galite gryzti i pradini puslapi paspaude <a href='manageUni.php'>cia</a>";
					else
						echo "Redaguojant aukstaja mokykla ivyko klaida. Pabandykite dar karta paspaude <a href='manageUni.php?action=edit&".$_GET['uni_id']."'>cia</a>";
			}
			else if($_GET['action'] == "delete")
			{
				if(empty($_POST['confirm']))
				{
					$uni = get_uni_data($_GET['uni_id'],false);
					
					echo"Noredami patvirtini <b>".$uni->uni_name."</b> panaikinima is sistemos spauskite 'Patvirtinti'.";
					echo "<form action='manageUni.php?action=delete&uni_id=".$_GET['uni_id']."' method='post'>";
					echo "<input type='hidden' name='confirm' value='true'>";
					echo  "<button type='submit'>Patvirtinti</button></form>";
				}
				else
					if(remove_uni($_GET['uni_id']))
						echo "Aukstoji mokykla panaikinta sekmingai! Galite gryzti i pradini puslapi paspaude <a href='manageUni.php'>cia</a>";
					else
						echo "Panaikinant aukstaja mokykla ivyko klaida. Pabandykite dar karta paspaude <a href='manageUni.php?action=delete&".$_GET['uni_id']."'>cia</a>";
			}
			else
			{
				$universitys = get_uni_data(0,true);

				echo "<center> Siuo metu yra uzregistruotos sios aukstosios mokyklos: <br><br>";
				echo "<table>";
				echo "<tr><th>Pavadinimas</th><th>Redagavimas</th><th>Trinimas</th></tr>";
			
				foreach($universitys as $uni)
					echo "<tr><th>".$uni -> uni_name."</th><th><a href='manageUni.php?action=edit&uni_id=".$uni->uni_id."'>Redaguoti</a></th><th><a href='manageUni.php?action=delete&uni_id=".$uni->uni_id."'>Trinti</a></th></tr>"; 
			
				echo "</table></center>";
			
				echo "<br><a href='/testam/manageUni.php?action=add'>Prideti nauja</a><br>";
			}
			echo "<div>";
		?>
	</body>
</html>