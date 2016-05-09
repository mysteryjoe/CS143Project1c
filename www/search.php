<?php
	include './config.php';
	$act = trim($get['act']);
	
	switch($act){
		case 'showActor':
			$keyword = trim($request['keyword']);
			if(false != $keyword){
					$actorData = array();
					
					//Search actor information by using keyword
					//Get information about actor
					$where = 'WHERE 1=1 ';

					if(false !== strpos($keyword, '@@@')){
						$keywords = explode('@@@', $keyword);
						$where .= "AND `first` LIKE '%{$keywords[0]}%' OR `last` LIKE '%{$keywords[0]}%' OR `last` LIKE '%{$keywords[1]}%' OR `first` LIKE '%{$keywords[1]}%'";
					}else if(false !== strpos($keyword, ' ')){
						$keywords = explode(' ', $keyword);
					$where .= "AND `first` = '{$keywords[0]}' AND `last` = '{$keywords[1]}'";}
					
					else{
						$where .= "AND `first` LIKE '%{$keyword}%' OR `last` LIKE '%{$keyword}%'";
					}
					$getActorSql = "SELECT * FROM `Actor` " . $where;
					$actorRs = mysql_query($getActorSql, $mysql_resource) or exit(mysql_error());
					while($row = mysql_fetch_assoc($actorRs)){
						$row['movieRole'] = array();
						
						$getMovieRoleSql = "SELECT `b`.`title`,`a`.`role`,`a`.`mid` FROM `MovieActor` AS `a` INNER JOIN `Movie` AS `b` ON `a`.`mid`=`b`.`id` WHERE `a`.`aid`=".$row['id'];
						$movieRoleRs = mysql_query($getMovieRoleSql, $mysql_resource) or exit(mysql_error());
						while($movieRoleRow = mysql_fetch_assoc($movieRoleRs)){
							$row['movieRole'][] = $movieRoleRow;
						}
						$actorData[] = $row;
					}
			}
			include './showActor.html';
			break;
		case 'showMovie':
			$keyword = trim($request['keyword']);
			if(false != $keyword){
					$movieData = array();
					//Get information of Movie and actor
					$getMovieSql = 'SELECT * FROM `Movie` WHERE `title` LIKE \'%'. $keyword .'%\'';
					$movieDirector = mysql_query($getMovieSql, $mysql_resource) or exit(mysql_error());
					while($row = mysql_fetch_assoc($movieDirector)){
						//Get information about director
						$getMovieDirectorSql = 'SELECT `b`.* FROM `MovieDirector` AS `a` INNER JOIN `Director` AS `b` ON `a`.`did`=`b`.`id` WHERE `mid`='. $row['id'];
						$movieDirectorRs = mysql_query($getMovieDirectorSql, $mysql_resource) or exit(mysql_error());
						while($mdRow = mysql_fetch_assoc($movieDirectorRs)){
							$row['director'][] = $mdRow;
						}
						//Get information about actor
						$getMovieActorSql = "SELECT * FROM `MovieActor` AS `a` INNER JOIN `Actor` AS `b` ON `a`.`aid`=`b`.`id` WHERE `a`.`mid`='{$row['id']}'";
						$movieActorRs = mysql_query($getMovieActorSql, $mysql_resource) or exit(mysql_error());
						while($maRow = mysql_fetch_assoc($movieActorRs)){
							$row['actor'][] = $maRow;
						}
						//Get information about Movie Genre
						$getMovieGenelSql = "SELECT * FROM `MovieGenre` WHERE `mid`={$row['id']}";
						$movieGenelRs = mysql_query($getMovieGenelSql, $mysql_resource) or exit(mysql_error());
						while($movieGeneRow = mysql_fetch_assoc($movieGenelRs)){
							$row['genre'][] = $movieGeneRow['genre'];
						}
						//Get movie comments
						$getMovieCommentSql = "SELECT * FROM `Review` WHERE `mid`={$row['id']}";
						$movieCommentRs = mysql_query($getMovieCommentSql, $mysql_resource) or exit(mysql_error());
						while($mcRow = mysql_fetch_assoc($movieCommentRs)){
							$row['comment'][] = $mcRow;
						}
						$movieData[] = $row;
					}
			}
			include './showMovie.html';
			break;
		case 'showMovieActor':
			$keyword = trim($get['keyword']);
			if($keyword){
                if(false !== strpos($keyword, ' ')){
                    $keywords = explode(' ', $keyword);
                    $actorSql = "SELECT * FROM `Actor` WHERE `last` = '$keywords[1]' AND `first` = '$keywords[0]'";
                }else{
                    $actorSql = "SELECT * FROM `Actor` WHERE `last` like '%$keyword%' or `first` like '%$keyword%'";
                }
				$movieSql = "SELECT * FROM `Movie` WHERE `title` like '%$keyword%'";
				$actorRs = mysql_query($actorSql, $mysql_resource) or exit(mysql_error());
				$movieRs = mysql_query($movieSql, $mysql_resource) or exit(mysql_error());
			}
			include './showMovieActor.html';
			break;
		default:
			break;
	}
?>