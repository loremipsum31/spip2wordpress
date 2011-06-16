<?php
/**********************************************************************
 ******************************* VERSION 1 ****************************
 **********************************************************************
 *************************** SELECT FROM SPIP *************************
 * The SPIP installation and the WordPress installation must be on the 
 * same root folder.
 * Edit username, password and database below 
 **********************************************************************/
$link_to_spip = mysql_connect('localhost', 'spip_username', 'spip_password');
if (!$link_to_spip) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db('spip_database') or die(mysql_error());

/* START Select Articles */
$query_spip_articles = "
SELECT *
FROM spip_articles, spip_auteurs_articles
WHERE spip_articles.id_article = spip_auteurs_articles.id_article
AND 
	`id_secteur` =3
AND 
	`statut` LIKE 'publie'
AND 
	`titre` NOT LIKE '%Infolettre%'
OR 
	spip_articles.id_article = spip_auteurs_articles.id_article
AND 
	`statut` LIKE 'publie'		
AND
	`id_rubrique` = 103	
					  "; //AND spip_articles.id_article = 2140
$result_spip_articles = mysql_query($query_spip_articles) or die(mysql_error()); 

$article_rows = array();
while($row = mysql_fetch_array($result_spip_articles))
{
	$article = $row['id_article'];
	$auteur = $row['id_auteur'];
	$date_written = $row['date'];
	$content_original = $row['texte'];
	
	$contenu_img_src_correct = remplacer_img_src_html($content_original);
	$contenu_sans_images_spip = remplacer_img_spip($contenu_img_src_correct);
	$contenu_sans_spip =  remplacer_spip($contenu_sans_images_spip);
	$contenu_correct = preg_replace('#([a-zA-Z])(\?)([a-zA-Z])#','$1\'$3',$contenu_sans_spip);
	
	if($row['ps'])
		$texte = mysql_real_escape_string($contenu_correct).'<div>'.mysql_real_escape_string($row['ps']).'</div>';
	else
		$texte = mysql_real_escape_string($contenu_correct);
	
	$titre = mysql_real_escape_string($row['titre']);
	$chapo = mysql_real_escape_string($row['chapo']);
	
	if($row['id_rubrique'] == 103)
		$publier = 'draft';
	else
		$publier = 'publish';
	
	$postname = string_into_slug($row['titre']);
	$modif = $row['date_modif'];
	$guid = 'http://localhost/spip2wordpress/?p='.$row['id_article'];
	$comment_count = get_comment_count_article($article);
	
	$article_rows[] = '('.	$article.','.
							$auteur.',"'.
							$date_written.'","'.
							$date_written.'","'.
							$texte.'","'.
							$titre.'","'.
							$chapo.'","'.
							$publier.'","'.
							$postname.'","'.
							$modif.'","'.
							$modif.'","'.
							$guid.'",'.
							$comment_count.')';
}
//var_dump($article_rows);
/* END Select Articles */

/* START Select Comments */
$query_spip_comments = "		
SELECT	
	articles.id_article,
	comments.*
FROM 
	spip_articles articles
LEFT JOIN 
	spip_forum comments 
ON 
	comments.id_article=articles.id_article
WHERE 
	articles.id_secteur=3 
AND 
	articles.statut LIKE 'publie' 
AND 
	articles.titre NOT LIKE '%Infolettre%'	
AND 
	comments.statut LIKE 'publie' 
AND 
	comments.id_article IS NOT NULL
					  ";
$result_spip_comments = mysql_query($query_spip_comments) or die(mysql_error());

$comment_rows = array();
while($c_row = mysql_fetch_array($result_spip_comments))
{
	$comment 		= 	$c_row['id_article'];
	$comment_auteur = 	mysql_real_escape_string($c_row['auteur']);
	$comment_email	= 	$c_row['email_auteur'];
	$comment_ip		= 	$c_row['ip'];
	$comment_date	= 	$c_row['date_heure'];
	$comment_contenu= 	mysql_real_escape_string($c_row['titre']).' : '.
						mysql_real_escape_string($c_row['texte']); /* TITRE EST-IL NECESSAIRE? */
	$comment_approved= 	1;
	
	$comment_rows[] = '('.	$comment.',"'.
							$comment_auteur.'","'.
							$comment_email.'","'.
							$comment_ip.'","'.
							$comment_date.'","'.
							$comment_date.'","'.
							$comment_contenu.'",'.
							$comment_approved.')';
}
//var_dump($comment_rows);
/* END Select Comments */

/* START Select Users */
$query_spip_users = "
SELECT 
	id_auteur, nom, login, email 
FROM 
	`spip_auteurs` 
WHERE 
	statut ='0minirezo'
AND	
	id_auteur != 1
					";
$result_spip_users = mysql_query($query_spip_users) or die(mysql_error());

$user_rows = array();
while($u_row = mysql_fetch_array($result_spip_users))
{
	$user_id 	= $u_row['id_auteur'];
	$user_nom 	= $u_row['nom'];
	$user_login	= $u_row['login'];
	$user_pass	= md5('csawFTW');
	$user_email	= $u_row['email'];
	$user_reg	= date("Y-m-d H:i:s");
	
	$user_rows[] = '('.	$user_id.',"'.
						$user_nom.'","'.
						$user_nom.'","'.
						$user_login.'","'.
						$user_pass.'","'.
						$user_email.'","'.
						$user_reg.'")';
}
//var_dump($user_rows);
/* END Select Users */

/* START Select Categories */
$query_spip_categories = "
SELECT 
	id_rubrique, titre
FROM 
	`spip_rubriques`
WHERE	
	`id_secteur` = 3
AND
	`id_rubrique` != 3
OR 
	`id_secteur` = 1
AND 
	`id_rubrique` = 103
					";
$result_spip_categories = mysql_query($query_spip_categories) or die(mysql_error());

$term_rows = array('(3,"AccessiVeille","accessiveille")');
$term_taxonomy_rows = array('(3,3,"category",62)');
while($cat_row = mysql_fetch_array($result_spip_categories))
{
	$term_id 				= $cat_row['id_rubrique'];
	$term_titre 			= $cat_row['titre'];
	$term_slug 				= string_into_slug($cat_row['titre']);
	$term_taxonomy_id 		= $term_id;
	$term_taxonomy_cat		= 'category';
	$term_relationships_id 	= $cat_row['id_article'];
	$term_taxonomy_count 	= get_taxonomy_count($term_id);
		
	$term_rows[] = '('.	$term_id.',"'.
						$term_titre.'","'.
						$term_slug.'")';
	$term_taxonomy_rows[] = 
				'('.	$term_taxonomy_id.','.
						$term_id.',"'.
						$term_taxonomy_cat.'",'.
						$term_taxonomy_count.')';											
}
//var_dump($term_rows);
//var_dump($term_taxonomy_rows);
/* END Select Categories */

/* START Select Categories Relationship with Articles */
$query_spip_categories_articles = "
SELECT 
	categories.id_rubrique, 
	articles.id_rubrique, 
	articles.id_article, 
	articles.titre
FROM 
	spip_rubriques categories,
	spip_articles articles
WHERE 
	categories.id_rubrique = articles.id_rubrique
AND	
	categories.id_secteur = 3
AND 	
	categories.id_rubrique != 3
OR 
	categories.id_rubrique = articles.id_rubrique
AND 
	categories.id_parent = 7
AND 	
	categories.id_rubrique = 103	
					";
$result_spip_categories_articles = mysql_query($query_spip_categories_articles) or die(mysql_error());

$term_relationships_rows = array();
while($cat_art_row = mysql_fetch_array($result_spip_categories_articles))
{
	if(stristr($cat_art_row['titre'], 'AccessiVeille'))
		$term_taxonomy_id		= 3;
	else
		$term_taxonomy_id		= $cat_art_row['id_rubrique'];
	$term_relationships_id 	= $cat_art_row['id_article'];
		
	$term_relationships_rows[] = 
				'('.	$term_relationships_id.','.
						$term_taxonomy_id.')';														
}
//var_dump($term_relationships_rows);
/* END Select Categories */


/**********************************************************************
 ***************************** FUNCTIONS ******************************
 **********************************************************************/
 
/* Remplacer la source des balises img html pour pointer vers le répertoire dans ../wp-content/uploads/spip */ 
function remplacer_img_src_html($contenu) {
	$patternImg 		= '#(src="../IMG)#';
	$replacementImg 	= 'src="http://localhost/spip2wordpress/wp-content/uploads/spip';
	return preg_replace($patternImg, $replacementImg, $contenu);
}

/* Remplacer les balises d'images spip en balise html */
function remplacer_img_spip($contenu){
	preg_match_all('#(<img)(?P<digit>\d+)#', $contenu, $matches);
	$img_ids = array();
	$img_array_ids = array_merge($img_ids, $matches[2]);
	
	if(!empty($img_array_ids)){
		foreach ($img_array_ids as $img_array_id)
		{
			$patternImg = array(
								'#(<img)('.$img_array_id.')\|([^\]]+)\|\|([^\]]+)(\{\d+,\d+\})>#',
								'#(<img)('.$img_array_id.')\|([^\]]+)>#'
								);
			$replacementImg = array(
								get_img_html_from_id($img_array_id), 
								get_img_html_from_id($img_array_id)
								);								
			$contenu_repeat = preg_replace($patternImg, $replacementImg, $contenu);
		}
		return $contenu_repeat;	
	} else {
		return $contenu;
	}
}

/* Obtenir une balise img complete à partir de son ID */
function get_img_html_from_id($id){
	$result_img_data = mysql_query("SELECT fichier, descriptif, titre FROM spip_documents where id_document =".$id);
	if (!$result_img_data) {
		echo 'Could not run query: ' . mysql_error();
		exit;
	}
	$img_row = mysql_fetch_row($result_img_data);
	$img_source 		= 'http://localhost/spip2wordpress/wp-content/uploads/spip/'.$img_row[0]; // fichier 
	$longdesc 			= $img_row[1]; // descriptif
	$alternative_text 	= $img_row[2]; // titre
	
	if(!empty($longdesc))
		return '<img src="'.$img_source.'" alt="'.$alternative_text.'" longdesc="'.$longdesc.'" />';
	else
		return '<img src="'.$img_source.'" alt="'.$alternative_text.'" />';	
}

/* Transformer des strings on format slug : mon-texte-sans-caractere-special */
function string_into_slug($str_formatted){
	$str_lowercase 			= mb_convert_encoding(mb_strtolower($str_formatted),'UTF-8');
	$withaccents = array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ');
	$withoutaccents = array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y');
	$witharticles = array(' l\'',' d\'',' des ',' de ',' le ',' la ',' a ',' pour ',' et ',' un ',' une ',' les ', ' avec ',' sur ',' y ',
							' du ',' en ',' au ',' ou ',' par ',' aux ',' cette ',' se ',' dans ',
							' ?',' :',': ','&#160;?',' -','.0','   ','! ');
	$withoutarticles = array(' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',
							' ',' ',' ',' ',' ',' ',' ',' ',' ',
							'?',':',' ','?','','','','!');
	$str_noaccents =	str_replace( $withaccents, $withoutaccents, $str_lowercase);
	$str_no_articles = str_replace( $witharticles, $withoutarticles, $str_noaccents);
	$str_alphanumeric_only = preg_replace("/[^a-zA-Z0-9\-\s]/", "", $str_no_articles);
	$str_slug			= str_replace(' ','-', $str_alphanumeric_only);
	return $str_slug;
}

/* Remplacement des hyperliens et les tags des articles de SPIP */
function remplacer_spip($str_SPIP){
	$pattern_SPIP = array("	#(\[)([^\]]+)(->art)(\d+)(\])#", 
							"#(\[)([^\]]+)(->rub)(\d+)(\])#", 
							"#(\[)([^\]]+)(->)([^\]]+)(\])#"); 
	$replacement_SPIP = array(	'<a href="http://localhost/spip2wordpress/?p=$4">$2</a>', 
								'<a href="http://localhost/spip2wordpress/?p=$4">$2</a>', 
								'<a href="$4">$2</a>');
	$contenu_sansLiensSPIP = preg_replace($pattern_SPIP, $replacement_SPIP, $str_SPIP);
	
	$tagsSPIP = array("{{", "}}", "<html>", "</html>");
	$correctHTML = array("<strong>", "</strong>", "", "");
	$contenu_sans_spip = str_replace($tagsSPIP, $correctHTML, $contenu_sansLiensSPIP);
		
	return $contenu_sans_spip;
}

/* Obtenir la somme des commentaires par article */
function get_comment_count_article($article_id){
	$comment_count_query = "SELECT COUNT(*) FROM spip_forum WHERE spip_forum.id_article = '.$article_id.' AND spip_forum.statut LIKE 'publie'";
	$result_comment_count = mysql_query($comment_count_query) or die(mysql_error());
	while($row_comment_count = mysql_fetch_array($result_comment_count))
		{return $row_comment_count[0];}	
}

/* Obtenir la somme des commentaires par article */
function get_taxonomy_count($taxonomy_id){
	$term_taxonomy_count_query = "SELECT COUNT(*) FROM spip_articles WHERE spip_articles.id_rubrique = ".$taxonomy_id;
	$result_term_taxonomy_count = mysql_query($term_taxonomy_count_query) or die(mysql_error());
	while($row_term_taxonomy_count = mysql_fetch_array($result_term_taxonomy_count))
		{return $row_term_taxonomy_count[0];}
}


/**********************************************************************
 ************************* INSERT INTO WORDPRESS **********************
 * Edit username, password and database below 
 **********************************************************************/
$link_to_wordpress = mysql_connect('localhost', 'wp_username', 'wp_password');
if (!$link_to_wordpress) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db('wp_database') or die(mysql_error());

/* START Insert Posts */
$articles_migrated = 0;
foreach ($article_rows as $$article_row) {
	$query_articles = 'INSERT IGNORE INTO 
						wp_posts 
						(
							ID, 
							post_author, 
							post_date,
							post_date_gmt,
							post_content,
							post_title,
							post_excerpt,
							post_status,
							post_name,
							post_modified,
							post_modified_gmt,
							guid,
							comment_count
						) 
					VALUES '.$$article_row;
	$result_articles = mysql_query($query_articles) or die(mysql_error());
	if ($result_articles){
		$articles_migrated++;
		echo 'Migration of articles to WP was successful. Count '.$articles_migrated.'<br />'; 	
	}
}
/* END Insert Posts */

/* START Insert Comments */
$comments_migrated = 0;
foreach ($comment_rows as $comment_row) {
	$insert_comment = 'INSERT INTO 
						wp_comments 
						(
							comment_post_ID, 
							comment_author, 
							comment_author_email,
							comment_author_ip,
							comment_date,
							comment_date_gmt,
							comment_content,
							comment_approved
						) 
					VALUES '.$comment_row;
	$result_comment = mysql_query($insert_comment) or die(mysql_error());
	if ($result_comment) {
		$comments_migrated++;
		echo 'Migration of comments to WP was successful. Count '.$comments_migrated.'<br />'; 		
	}
}
/* END Insert Comments */

/* START Insert Users */
$users_migrated = 0;
foreach ($user_rows as $user_row) {
	$insert_user = 'INSERT INTO 
						wp_users 
						(
							ID, 
							user_nicename, 
							display_name,
							user_login,
							user_pass,
							user_email,
							user_registered
						) 
					VALUES '.$user_row;
	$result_user = mysql_query($insert_user) or die(mysql_error());
	if ($result_user) {
		$users_migrated++;
		echo 'Migration of user to WP was successful. Count '.$users_migrated.'<br />'; 		
	}		
} 
/* END Insert Users */

/* START Insert Terms */
$terms_migrated = 0;
foreach ($term_rows as $term_row) {
	$insert_term = 'INSERT INTO 
					wp_terms
						(
							term_id, 
							name, 
							slug
						) 
					VALUES '.$term_row;
	$result_term = mysql_query($insert_term) or die(mysql_error());
	if ($result_term) {
		$terms_migrated++;
		echo 'Migration of terms to WP was successful. Count '.$terms_migrated.'<br />'; 		
	}		
} 
/* END Insert Terms */

/* START Insert Terms Taxonomy */
$terms_taxonomy_migrated = 0;
foreach ($term_taxonomy_rows as $term_taxonomy_row) {
	$insert_term_taxonomy = 'INSERT INTO 
					wp_term_taxonomy
						(
							term_taxonomy_id, 
							term_id, 
							taxonomy,
							count
						) 
					VALUES '.$term_taxonomy_row;
	$result_term_taxonomy = mysql_query($insert_term_taxonomy) or die(mysql_error());
	if ($result_term_taxonomy) {
		$terms_taxonomy_migrated++;
		echo 'Migration of taxonomy terms to WP was successful. Count '.$terms_taxonomy_migrated.'<br />'; 		
	}		
}
/* END Insert Terms Taxonomy */

/* START Insert Terms Relationship */
$terms_relationship_migrated = 0;
foreach ($term_relationships_rows as $term_relationships_row) {
	$insert_term_relationship = 'INSERT INTO 
					wp_term_relationships
						(
							object_id,
							term_taxonomy_id
						) 
					VALUES '.$term_relationships_row;
	$result_term_relationship = mysql_query($insert_term_relationship) or die(mysql_error());
	if ($result_term_relationship) {
		$terms_relationship_migrated++;
		echo 'Migration of terms relationship to WP was successful. Count '.$terms_relationship_migrated.'<br />'; 		
	}		
} 
/* END Insert Terms Relationship */

mysql_close($link_to_spip);
mysql_close($link_to_wordpress);
?>
