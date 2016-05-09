<?php

include './config.php';

$action = isset($_GET['act']) ? trim($_GET['act']) : '';


switch($action){

	
	
	case 'addActorDirector':
		//Add actor and director
		if(isset($post['submit'])){
			
			$identity = trim($post['identity']);
			if(false == $identity) errorTips('identity can\t be empty!');
			$first = $post['first'];
			if(false == $first) errorTips('first can\t be empty!');
			$last = $post['last'];
			if(false == $last) errorTips('last can\t be empty!');
			$sex = $post['sex'];
			if(false == $sex) errorTips('sex can\t be empty!');
			$birth = $post['dob'];
			if(false == $birth) errorTips('birth can\t be empty!');
			$dod = $post['dod'];
			//if(false == $dod) errorTips('dod can\t be empty!');
			
			$tableName = $identity=='Actor' ? 'Actor' : 'Director';
			$maxId = getMaxId($tableName, 'id')+1;
			$insertActorDirectorSql = "INSERT INTO `{$tableName}` (`id`,`last`,`first`,`sex`,`dob`,`dod`) VALUES ('{$maxId}','{$last}','{$first}','$sex','$dob','$dod')";
			mysql_query($insertActorDirectorSql, $mysql_resource) or errorTips('Actor/Director add failed:'.mysql_error());
			errorTips('Actor/Director add success');
			
		}else{
			include './addActorDirector.html';
			

		}
		break;
	case 'addMovie':
		//Add movie information
		if($post['submit']){
			
			$title = trim($post['title']);
			if(false == $title) errorTips('title can\'t be empty!');
			$company = trim($post['company']);
			if(false == $company) errorTips('company can\'t be empty!');
			$year = trim($post['year']);
			if(false == $year) errorTips('year can\'t be emtpy!');
			$mpaarating = trim($post['mpaarating']);
			if(false == $mpaarating) errorTips('mpaarating can\'t be empty!');
			$genre = $post['genre'];
			if(false==$genre) errorTips('genre can\'t be empty!');
			$maxId = getMaxId('Movie', 'id')+1;
			$insertMovieSql = "INSERT INTO `Movie` (`id`,`title`,`year`,`rating`,`company`) VALUES ('{$maxId}','{$title}', '{$year}','{$mpaarating}', '{$company}')";
			$ret = mysql_query($insertMovieSql, $mysql_resource) or exit(mysql_error());
			$mid = mysql_insert_id($mysql_resource);
			foreach($genre as $key => $genelName){
			
				$insertGenreSql = "INSERT INTO `MovieGenre` (`mid`, `genre`) VALUES ('{$mid}', '{$genelName}')";
				mysql_query($insertGenreSql, $mysql_resource) or exit(mysql_error());
			}
			errorTips('add movie information success!');
		}else{
			//Get movie genre
			$getMovieGenreSql = 'SELECT distinct `genre` FROM `MovieGenre`';
			$movieGenreRs = mysql_query($getMovieGenreSql, $mysql_resource) or exit(mysql_error());
			include './addMovie.html';
		}
		break;
	case 'addMovieActor':
		if($post['submit']){
			$Movie = trim($post['mid']);
			if(false == $Movie) errorTips('Movie can\'t be empty!');
			$Actor = trim($post['aid']);
			if(false == $Actor) errorTips('Actor can\'t be empty!');
			$Role = trim($post['role']);
			if(false == $Role) errorTips('Role can\'t be empty!');	
			
			$insertMovieActorSql = "INSERT INTO `MovieActor`(`mid`,`aid`,`role`) VALUES('{$Movie}','{$Actor}','{$Role}')";
			mysql_query($insertMovieActorSql, $mysql_resource) or errorTips('Movie/Actor add failed:'.mysql_error());
			errorTips('Movie/Actor add success!');
		}else{
			//Get movie information
			$getMovieInfoSql = 'SELECT * FROM `Movie`';
			$movieInfoRs = mysql_query($getMovieInfoSql, $mysql_resource) or exit(mysql_error());
			//Actor information
			$getActorInfoSql = 'SELECT * FROM `Actor`';
			$actorInfoRs = mysql_query($getActorInfoSql, $mysql_resource) or exit(mysql_error());
			
			include './addMovieActor.html';	
		}
		break;
	case 'addMovieDirector':
		if($post['submit']){
			$Movie = trim($post['mid']);
			if(false == $Movie) errorTips('Movie can\'t be empty!');
			$Director = trim($post['did']);
			if(false == $Director) errorTips('Director can\'t be empty!');
			
			$insertMovieDirectorSql = "INSERT INTO `MovieDirector`(`mid`,`did`) VALUES('{$Movie}','{$Director}')";
			mysql_query($insertMovieDirectorSql, $mysql_resource) or errorTips('Movie/Director add failed:'.mysql_error());
			errorTips('Movie/Director add success!');
		}else{
			//Get movie information
			$getMovieInfoSql = 'SELECT * FROM `Movie`';
			$movieInfoRs = mysql_query($getMovieInfoSql, $mysql_resource) or exit(mysql_error());
			//Actor information
			$getDirectorInfoSql = 'SELECT * FROM `Director`';
			$directorInfoRs = mysql_query($getDirectorInfoSql, $mysql_resource) or exit(mysql_error());
			
			include './addMovieDirector.html';	
		}
		break;
	case 'addComment':
		if($post['submit']){
			$mid = intval($post['mid']);
			$userName = trim($post['username']);
			$rating = trim($post['rating']);
			$comment = addslashes(trim($post['comment']));
			$time = date('Y-m-d H:i:s');
			if(false==$comment || false==$userName || false== $mid) errorTips('comment or yourname can\'t be empty!');
			$insertCommentSql = "INSERT INTO `Review` (`name`,`time`,`mid`,`rating`,`comment`) VALUES ('{$userName}', '{$time}', '{$mid}', '{$rating}', '{$comment}')";
			if(mysql_query($insertCommentSql, $mysql_resource)) errorTips('add comment success!');
			errorTips('add comment failed!');
		}else{
			$mid = $get['mid'];
			$getMovieSql = "SELECT * FROM `Movie` WHERE `id`='{$mid}'";
			$movieRs = mysql_query($getMovieSql, $mysql_resource) or exit(mysql_error());
			$movie = mysql_fetch_assoc($movieRs);
			include './addComment.html';
		}
		break;
	default:
		include './index.html';

		break;
}


function getMaxId($table, $primary='id'){
	global $mysql_resource;
	$getMaxIdSql = "SELECT max($primary) as max FROM $table LIMIT 0,1";
	$maxRs = mysql_query($getMaxIdSql, $mysql_resource) or exit(mysql_error());
	$maxRow = mysql_fetch_assoc($maxRs);
	return $maxRow['max'];
}