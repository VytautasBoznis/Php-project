<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style/style.css">
	</head>
	<body>
	<?php
		ini_set("display_errors", 1);
		session_start();
		require 'maincore.php';
		
		$user = get_user_data($_SESSION['user_id'],false);
			
			if($user-> user_level < 2 || !isset($_SESSION['user_id']))
				header("Location: http://".$_SERVER['HTTP_HOST']."/testam/index.html");
			
			echo "<div id='side-menu' class='side-menu'>";
			echo "Sveiki <b>".$user->first_name." ".$user ->last_name."</b>! <br><br>";
			echo "Pasirinkite vaiksma: <br><br>";
			echo "<a href='/testam/home.php'>Grysti i pagrindini puslapi</a>";
			echo "</div>";
			echo "<div id='content' class='content'>";
			
			if($_GET['action'] == 'add')
			{
				if(empty($_POST['confirm']))
				{
					echo "Noredami prideti aukstosios mokyklos programa uzpildykite visus nurodytus laukus: <br><br>";
					echo "<form id='program_form' action='managePrograms.php?action=add' method='post'>";
					echo "Programos pavadinimas: <input type='text' name='program_name'><br>";
					echo "Aukstoji mokykla kuriai ji priklauso: <select name='uni_id' form='program_form'><br>";
					
					$universitys = get_uni_data(0,true);
					foreach($universitys as $university)
						echo "<option value='".$university->uni_id."'>".$university->uni_name."</option>";
					
					echo "</select><br>";
					echo "Programos aprasimas:<br> <textarea form='program_form' name='program_desc'></textarea><br>";
					echo "<input type='hidden' name='confirm' value='true'>";
					echo "<button type='submit'>Prideti programa</button>";
					echo "</form>";
				}
				else
					if(empty($_POST['program_name']) || empty($_POST['uni_id']) || empty($_POST['program_desc']))
					{
						echo "Pildant programos aprasima nebuvo uzpildyti visi laukai. Pabandyti dar karta galite <a href='/testam/managePrograms.php?action=add'>cia</a>";
						echo $_POST['program_desc'];
					}
					else if(add_program($_POST['program_name'],$_POST['uni_id'],$_POST['program_desc']))
						echo "Programa buvo sekmingai prideta grysti atgal i pagrindi puslapi galite paspaude <a href='/testam/managePrograms.php'>cia</a>";
					else
						echo "Pridedant programa ivyko klaida pabandyti dar karta galite paspaude <a href='testam/managePrograms.php?action=add'>cia</a>";
			}
			else if($_GET['action'] == 'edit')
			{
				if(empty($_POST['confirm']))
				{
					$program= get_uni_program($_GET['program_id'],false);
					
					echo "<br>Noredami redaguoti. <b>".$program->program_name."</b> programa uzpildykite forma ir spauskite 'Patvirtinti'.<br><br>";
					echo "<form id='program_form' action='managePrograms.php?action=edit&program_id=".$program->program_id."' method='post'>";
					echo "Programos pavadinimas:<input type='text' name='program_name' value='".$program->program_name."'><br>";
					echo "Aukstoji mokykla kuriai ji priklauso: <select  name='uni_id' form='program_form'><br>";
					
					$universitys = get_uni_data(0,true);
					foreach($universitys as $university)
						echo "<option value='".$university->uni_id."'>".$university->uni_name."</option>";
					
					echo "</select><br>";
					echo "Programos aprasimas:<br> <textarea form='program_form' name='program_desc'>".$program->program_desc."</textarea><br>";
					echo "<input type='hidden' name='confirm' value='true'>";
					echo "<button type='submit'>Redaguoti programa</button>";
					echo "</form><br><br>";
					
					echo "Programos kriterijai:<br>";
					echo "<center><table><tr><th>Pavadinimas</th><th>Procentine dalis</th><th>Redaguoti</th><th>Trinti</th></tr>";
					
					$criterias = $program->get_criteria();
					foreach($criterias as $criteria)
						echo "<tr><th>".$criteria->criteria_name."</th><th>".$criteria->procentile_part."</th><th><a href='managePrograms.php?action=edit_criteria&criteria_id=".$criteria->criteria_id."'>Redaguoti</th><th><a href='managePrograms.php?action=delete_criteria&criteria_id=".$criteria->criteria_id."'>Trinti</a></th></tr>";
					
					echo "</table></center><br>";
					echo "<a href='managePrograms.php?action=add_criteria&program_id=".$program->program_id."'>Prideti kriteriju</a>";
				}
				else
					if(empty($_POST['program_name']) || empty($_POST['uni_id']) || empty($_POST['program_desc']))
						echo "Pildant programos redagavimo forma nebuvo uzpildyti visi reikalingi laukai noredami pabandyti dar karta spauckite <a href='/testam/managePrograms.php?action=edit&program_id=".$GET['program_id']."'>cia</a>";
					else if(edit_program($_GET['program_id'],$_POST['program_name'],$_POST['uni_id'],$_POST['program_desc']))
						echo "Programa buvo redaguota sekmingai noredami grysti i pagrindini puslapi spauskite <a href='/testam/managePrograms.php'>cia</a>";
					else
						echo "Redaguojant programa ivyko klaida noredami pameginti dar karta spauskite <a href='/testam/managePrograms.php?action=edit&program_id=".$_GET['program_id']."'>cia</a>";
			}
			else if($_GET['action'] == 'delete')
			{
				if(empty($_POST['confirm']))
				{
					$program = get_uni_program($_GET['program_id'],false);
					echo "<br>Noredami patvirtinti programos <b>".$program->program_name."</b> panaikinima paspauskite 'Patvirtinti' <br>";
					echo "<form action='managePrograms.php?action=delete&program_id=".$program->program_id."' method='post'>";
					echo "<input type='hidden' name='confirm' value='true'>";
					echo "<button type='submit'>Patvirtinti</button></form>";
				}
				else
					if(remove_program($_GET['program_id']))
						echo "Programa buvo sekmingai panaikinta noredami grysti i pagrindi langa paspauskite <a href='/testam/managePrograms.php'>cia</a>";
					else
						echo "Naikinant programa iskilo klaida noredami pabandyti dar karta spauskite <a href='/testam/managePrograms.php'>cia</a>";
			}
			else if($_GET['action'] == 'add_criteria')
			{
				if(empty($_POST['confirm']))
				{
					$program = get_uni_program($_GET['program_id'],false);
					echo "<br>Noredami prideti kriteriju programai <b>".$program->program_name."</b> uzpildykite forma ir paspauskite 'Prideti'.<br>";
					echo "<form action='managePrograms.php?action=add_criteria&program_id=".$program->program_id."' method ='post'>Kriterijaus pavadinimas: <input type='text' name='criteria_name'><br>";
					echo "Kriterijaus procentine dalis nuo balo: <input type='text' name='procentile_part'><br>";
					echo "<input type='hidden' name='confirm' value='true'><br>";
					echo "<button type='submit'>Patvirtinti</button></form>";
				}
				else
					if(empty($_POST['criteria_name'])||empty($_POST['procentile_part']))
						echo "Pildant kriterijaus pridejimo forma nebuvo uzpildyti visi reikalingi laukai. Pabandyti dar karta galite paspaude <a href='managePrograms.php?action=add_criteria&program_id=".$_GET['program_id']."'>cia</a>";
					else if(add_program_criteria($_GET['program_id'],$_POST['criteria_name'],$_POST['procentile_part']))
						echo "Programos kriterijus buvo sekmingai pridetas noredami testi paspauskite <a href='managePrograms.php'>cia</a>";
					else
						echo "Pridedant programos kriteriju ivyko klaida. Noredami pabandyti dar kart paspauskite <a href='managePrograms.php?action=add_criteria&program_id=".$_GET['program_id']."'>cia</a>";
			}
			else if($_GET['action'] == 'edit_criteria')
			{
				if(empty($_POST['confirm']))
				{
					$criteria = get_criteria($_GET['criteria_id']);
					echo "<br> Noredami redaguoti kriteriju uzpildikete forma ir paspauskite 'Patvirtinti'<br><br>";
					echo "<form action='managePrograms.php?action=edit_criteria&criteria_id=".$criteria->criteria_id."' method='post'>";
					echo "Kriterijaus pavadinimas: <input type='text' name='criteria_name' value='".$criteria->criteria_name."'><br>";
					echo "Kriterijaus procentine dalis nuo balo: <input type='text' name='procentile_part' value='".$criteria->procentile_part."'><br><br>";
					echo "<input type='hidden' name='confirm' value='true'>";
					echo "<button type='submit'>Patvirtinti</button></form>";
				}
				else
					if(empty($_POST['criteria_name'])||empty($_POST['procentile_part']))
						echo "Pildant kriterijaus redagavimo forma nebuvo uzpildyti visi reikalingi laukai. Pabandyti dar karta galite paspaude <a href='managePrograms.php?action=edit_criteria&criteria_id=".$_GET['criteria_id']."'>cia</a>";
					else if(edit_criteria($_GET['criteria_id'],$_POST['criteria_name'],$_POST['procentile_part']))
						echo "Kriterijus buvo redaguotas sekmingai noredami testi paspauskite <a href='managePrograms.php'>cia</a>";
					else
						echo "Redaguojant kriteriju ivyko klaida noredami pabandyti dar karta paspauskite <a href='managePrograms.php?action=edit_criteria&criteria_id=".$_GET['criteria_id']."'>cia</a>";
			}
			else if($_GET['action'] == 'delete_criteria')
			{
				if(empty($_POST['confirm']))
				{
					$criteria = get_criteria($_GET['criteria_id']);
					echo "<br>Noredami istrinti kriteri pavadinimu <b>".$criteria->criteria_name."</b> paspauskite 'Patvirtinti'<br>";
					echo "<form action='managePrograms.php?action=delete_criteria&criteria_id=".$_GET['criteria_id']."' method='post'>";
					echo "<input type ='hidden' name='confirm' value='true'>";
					echo "<button type='submit'>Patvirtinti</button></form>";
				}
				else
					if(remove_criteria($_GET['criteria_id']))
						echo "Kriterijus buvo sekmingai panaikintas. Noredami gristi i pagrindini meniu spauskite <a href='managePrograms.php'>cia</a>";
					else
						echo "Naikinant kriteriju iskilo klaida. Noredami pabandyti dar karta paspauskite <a href='managePrograms.php?action=delete_criteria&criteria_id=".$_GET['criteria_id']."'>cia</a>";
			}
			else
			{
				$programs = get_uni_program(0,true);
			
				echo "Visos uzregistruotos programos: <br><br>";
				echo "<center><table>";
				echo "<tr><th>Programos pavadinimas</th><th>Ausktoji mokykla</th> <th>Redagavimas</th><th>Trinimas</th></tr>";
			
				foreach($programs as $program)
					echo "<tr><th>".$program->program_name."</th><th>".get_uni_data($program->uni_id,false)->uni_name."</th><th><a href='/testam/managePrograms.php?action=edit&program_id=".$program->program_id."'>Redaguoti</a></th><th><a href='/testam/managePrograms.php?action=delete&program_id=".$program->program_id."'>Trinti</a></th>";
			
				echo "</table></center>";
				echo "<a href='/testam/managePrograms.php?action=add'>Prideti nauja</a>";
			}
			echo "<div>";
	?>
	</body>
</html>