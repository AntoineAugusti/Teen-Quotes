<?php 
include "header.php";
$action = mysql_real_escape_string($_GET['action']);
include "../lang/$language/addquote.php";
if (empty($action)) { ?>
<div class="post">
	<h1><img src="http://www.teen-quotes.com/images/icones/add.png" class="icone" /><?php echo $add_quote; ?></h1>
	<?php echo $add_consignes; ?>
	<form action="?action=add_quote" method="post">
		<?php echo $enter_quote; ?><br>
		<textarea name="texte_quote" style="width:100%;height:50px"></textarea>
		<br /><br />
		<div class="clear"></div>
		<center><p><input type="submit" value="Okey" class="submit" /></p></center>
	</form>
<?php }
elseif ($action == "add_quote") 
	{ ?>
	<div class="post">
	<h1><img src="http://www.teen-quotes.com/images/icones/add.png" class="icone" /><?php echo $add_quote; ?></h1>
	<?php
	$texte_quote= ucfirst(htmlspecialchars(mysql_escape_string($_POST['texte_quote'])));
	$texte_quote=str_replace(array("\r", "\n"), array('',''), $texte_quote);
	$date = date("d/m/Y");
	$texte_quote=stripslashes($texte_quote);


	if (strlen($texte_quote) >= '30') 
		{
		$submitted_today = mysql_num_rows(mysql_query("SELECT id FROM teen_quotes_quotes WHERE auteur_id='$id' AND date='$date'"));
		if ($submitted_today < '5')
			{
			if (is_quote_exist($texte_quote) == FALSE) 
				{
				$query = mysql_query("INSERT INTO teen_quotes_quotes (texte_english,auteur,date,auteur_id,approved) VALUES ('$texte_quote', '$username', '$date', '$id','0')");
				
				if ($query) 
					{
					echo ''.$succes.' '.$add_ok.'';
					}
					else 
					{
					echo '<h2>'.$error.'</h2> '.$lien_retour.'';
					}
				}
				else
				{
				echo '<span class="erreur">'.$quote_already_exist.'</span> '.$lien_retour.'';
				}
			}
		else
			{
			echo '<span class="erreur">'.$submitted_too_much.'</span> '.$lien_retour.'';
			}
		}
		else 
		{
		echo '<span class="erreur">'.$too_short.'</span> '.$lien_retour.'';
		}
										
	} 
echo '</div>';
include "footer.php"; ?>