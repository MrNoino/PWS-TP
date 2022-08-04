SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT;
SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS;
SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION;
SET NAMES utf8;
SET @OLD_TIME_ZONE=@@TIME_ZONE;
SET TIME_ZONE='+00:00';
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0;

DROP DATABASE IF EXISTS watchasave;

CREATE DATABASE watchasave;

USE watchasave;

DROP TABLE IF EXISTS `actors`;

CREATE TABLE `actors` (
  `actor_biography` varchar(2048) DEFAULT NULL,
  `actor_birthplace` varchar(512) DEFAULT NULL,
  `actor_person_id` bigint NOT NULL,
  PRIMARY KEY (`actor_person_id`),
  CONSTRAINT `fk_actors_persons` FOREIGN KEY (`actor_person_id`) REFERENCES `persons` (`person_id`)
);

LOCK TABLES `actors` WRITE;

INSERT INTO `actors` VALUES ('Ator 5 estrelas','London, UK',2);

UNLOCK TABLES;

DROP TABLE IF EXISTS `directors`;

CREATE TABLE `directors` (
  `director_id` bigint NOT NULL AUTO_INCREMENT,
  `director_name` varchar(128) NOT NULL,
  PRIMARY KEY (`director_id`)
);

LOCK TABLES `directors` WRITE;

UNLOCK TABLES;

DROP TABLE IF EXISTS `episodedirectors`;

CREATE TABLE `episodedirectors` (
  `episode_director_episode_id` bigint NOT NULL,
  `episode_director_id` bigint NOT NULL,
  KEY `fk_episodedirectors_episodes` (`episode_director_episode_id`),
  KEY `fk_episodedirectors_directors` (`episode_director_id`),
  CONSTRAINT `fk_episodedirectors_directors` FOREIGN KEY (`episode_director_id`) REFERENCES `directors` (`director_id`),
  CONSTRAINT `fk_episodedirectors_episodes` FOREIGN KEY (`episode_director_episode_id`) REFERENCES `episodes` (`episode_id`)
);

LOCK TABLES `episodedirectors` WRITE;

UNLOCK TABLES;

DROP TABLE IF EXISTS `episodes`;

CREATE TABLE `episodes` (
  `episode_id` bigint NOT NULL AUTO_INCREMENT,
  `episode_title` varchar(256) DEFAULT NULL,
  `episode_number` smallint DEFAULT NULL,
  `epsiode_duration` timestamp NULL DEFAULT NULL,
  `episode_resume` varchar(2048) DEFAULT NULL,
  `episode_season_id` bigint DEFAULT NULL,
  `episode_api_id` bigint DEFAULT NULL,
  PRIMARY KEY (`episode_id`),
  KEY `fk_episodes_seasons` (`episode_season_id`),
  CONSTRAINT `fk_episodes_seasons` FOREIGN KEY (`episode_season_id`) REFERENCES `seasons` (`season_id`)
);

LOCK TABLES `episodes` WRITE;

INSERT INTO `episodes` VALUES (1,NULL,NULL,NULL,NULL,NULL,2438301);

UNLOCK TABLES;

DROP TABLE IF EXISTS `episodewriters`;

CREATE TABLE `episodewriters` (
  `episode_writer_episode_id` bigint NOT NULL,
  `episode_writer_id` bigint NOT NULL,
  KEY `fk_episodewriters_episodes` (`episode_writer_episode_id`),
  KEY `fk_episodewriters_writers` (`episode_writer_id`),
  CONSTRAINT `fk_episodewriters_episodes` FOREIGN KEY (`episode_writer_episode_id`) REFERENCES `episodes` (`episode_id`),
  CONSTRAINT `fk_episodewriters_writers` FOREIGN KEY (`episode_writer_id`) REFERENCES `writers` (`writer_id`)
);

LOCK TABLES `episodewriters` WRITE;

UNLOCK TABLES;

DROP TABLE IF EXISTS `genders`;

CREATE TABLE `genders` (
  `gender_id` int NOT NULL AUTO_INCREMENT,
  `gender_description` varchar(128) NOT NULL,
  `gender_code` varchar(10) NOT NULL,
  PRIMARY KEY (`gender_id`),
  UNIQUE KEY `gender_code_UNIQUE` (`gender_code`)
);

LOCK TABLES `genders` WRITE;

INSERT INTO `genders` VALUES (1,'Feminino','F'),(2,'Masculino','M'),(3,'Não Binário','NB');

UNLOCK TABLES;

DROP TABLE IF EXISTS `languages`;

CREATE TABLE `languages` (
  `language_id` int NOT NULL AUTO_INCREMENT,
  `language_name` varchar(128) NOT NULL,
  `language_code` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`language_id`)
);

LOCK TABLES `languages` WRITE;

INSERT INTO `languages` VALUES (1,'English - United States','en-US'),(2,'Português - Portugal','pt-PT');

UNLOCK TABLES;

DROP TABLE IF EXISTS `mediagenres`;

CREATE TABLE `mediagenres` (
  `media_genre_id` int NOT NULL AUTO_INCREMENT,
  `media_genre_description` varchar(256) NOT NULL,
  PRIMARY KEY (`media_genre_id`)
);

LOCK TABLES `mediagenres` WRITE;

INSERT INTO `mediagenres` VALUES (1,'Drama'),(2,'Action'),(3,'Comedy');

UNLOCK TABLES;

DROP TABLE IF EXISTS `moviecast`;

CREATE TABLE `moviecast` (
  `movie_actor_role` varchar(256) DEFAULT NULL,
  `movie_cast_movie_id` bigint NOT NULL,
  `movie_cast_actor_id` bigint NOT NULL,
  KEY `fk_moviecast_movies` (`movie_cast_movie_id`),
  KEY `fk_moviecast_actors` (`movie_cast_actor_id`),
  CONSTRAINT `fk_moviecast_actors` FOREIGN KEY (`movie_cast_actor_id`) REFERENCES `actors` (`actor_person_id`),
  CONSTRAINT `fk_moviecast_movies` FOREIGN KEY (`movie_cast_movie_id`) REFERENCES `movies` (`movie_id`)
);

LOCK TABLES `moviecast` WRITE;

UNLOCK TABLES;


DROP TABLE IF EXISTS `moviedirectors`;

CREATE TABLE `moviedirectors` (
  `movie_director_id` bigint NOT NULL,
  `movie_director_movie_id` bigint NOT NULL,
  KEY `fk_moviedirectors_directors` (`movie_director_id`),
  KEY `fk_moviedirectors_movies` (`movie_director_movie_id`),
  CONSTRAINT `fk_moviedirectors_directors` FOREIGN KEY (`movie_director_id`) REFERENCES `directors` (`director_id`),
  CONSTRAINT `fk_moviedirectors_movies` FOREIGN KEY (`movie_director_movie_id`) REFERENCES `movies` (`movie_id`)
) ;

LOCK TABLES `moviedirectors` WRITE;

UNLOCK TABLES;

DROP TABLE IF EXISTS `moviegenres`;

CREATE TABLE `moviegenres` (
  `movie_media_genre_id` int NOT NULL,
  `movie_genre_movie_id` bigint NOT NULL,
  KEY `fk_moviegenders_mediagenders` (`movie_media_genre_id`),
  KEY `fk_moviegenders_movies` (`movie_genre_movie_id`)
);

LOCK TABLES `moviegenres` WRITE;

INSERT INTO `moviegenres` VALUES (2,4);

UNLOCK TABLES;

DROP TABLE IF EXISTS `movies`;

CREATE TABLE `movies` (
  `movie_id` bigint NOT NULL AUTO_INCREMENT,
  `movie_title` varchar(256) DEFAULT NULL,
  `movie_poster` varchar(512) DEFAULT NULL,
  `movie_realesedate` date DEFAULT NULL,
  `movie_duration` int DEFAULT NULL,
  `movie_resume` varchar(2048) DEFAULT NULL,
  `movie_language_id` int DEFAULT NULL,
  `movie_api_id` bigint DEFAULT NULL,
  PRIMARY KEY (`movie_id`),
  UNIQUE KEY `movie_api_id_UNIQUE` (`movie_api_id`),
  KEY `fk_movies_languages` (`movie_language_id`),
  CONSTRAINT `fk_movies_languages` FOREIGN KEY (`movie_language_id`) REFERENCES `languages` (`language_id`)
);

LOCK TABLES `movies` WRITE;

INSERT INTO `movies` VALUES (1,NULL,NULL,NULL,NULL,NULL,NULL,634649),(2,NULL,NULL,NULL,NULL,NULL,NULL,524434),(3,NULL,NULL,NULL,NULL,NULL,NULL,624860),(4,'007','4.jpg','2022-02-15',160,'Agente secreto maluco',1,NULL);

UNLOCK TABLES;

DROP TABLE IF EXISTS `moviewriters`;

CREATE TABLE `moviewriters` (
  `movie_writer_id` bigint NOT NULL,
  `movie_writer_movie_id` bigint NOT NULL,
  KEY `fk_moviewriters_writers` (`movie_writer_id`),
  KEY `fk_moviewriters_movies` (`movie_writer_movie_id`),
  CONSTRAINT `fk_moviewriters_movies` FOREIGN KEY (`movie_writer_movie_id`) REFERENCES `movies` (`movie_id`),
  CONSTRAINT `fk_moviewriters_writers` FOREIGN KEY (`movie_writer_id`) REFERENCES `writers` (`writer_id`)
);

LOCK TABLES `moviewriters` WRITE;

UNLOCK TABLES;

DROP TABLE IF EXISTS `persons`;

CREATE TABLE `persons` (
  `person_id` bigint NOT NULL AUTO_INCREMENT,
  `person_name` varchar(256) NOT NULL,
  `person_birthdate` date DEFAULT NULL,
  `person_photo` varchar(512) DEFAULT NULL,
  `person_gender_id` int NOT NULL,
  PRIMARY KEY (`person_id`),
  KEY `fk_persons_genders` (`person_gender_id`),
  CONSTRAINT `fk_persons_genders` FOREIGN KEY (`person_gender_id`) REFERENCES `genders` (`gender_id`)
);

LOCK TABLES `persons` WRITE;

INSERT INTO `persons` VALUES (1,'Nuno Lopes','2001-06-16','1.jpg',2),(2,'Daniel Craig','1965-05-12','2.jpg',2);

UNLOCK TABLES;

DROP TABLE IF EXISTS `seasons`;

CREATE TABLE `seasons` (
  `season_id` bigint NOT NULL AUTO_INCREMENT,
  `season_title` varchar(256) NOT NULL,
  `season_number` smallint NOT NULL,
  `season_resume` varchar(2048) DEFAULT NULL,
  `season_serie_id` bigint NOT NULL,
  PRIMARY KEY (`season_id`),
  KEY `fk_seasons_series` (`season_serie_id`),
  CONSTRAINT `fk_seasons_series` FOREIGN KEY (`season_serie_id`) REFERENCES `series` (`serie_id`)
);

LOCK TABLES `seasons` WRITE;

UNLOCK TABLES;

DROP TABLE IF EXISTS `seriecast`;

CREATE TABLE `seriecast` (
  `serie_actor_role` varchar(256) DEFAULT NULL,
  `serie_cast_actor_id` bigint NOT NULL,
  `serie_cast_serie_id` bigint NOT NULL,
  KEY `fk_seriecast_actors` (`serie_cast_actor_id`),
  KEY `fk_seriecast_series` (`serie_cast_serie_id`),
  CONSTRAINT `fk_seriecast_actors` FOREIGN KEY (`serie_cast_actor_id`) REFERENCES `actors` (`actor_person_id`),
  CONSTRAINT `fk_seriecast_series` FOREIGN KEY (`serie_cast_serie_id`) REFERENCES `series` (`serie_id`)
);

LOCK TABLES `seriecast` WRITE;

UNLOCK TABLES;

DROP TABLE IF EXISTS `seriegenres`;

CREATE TABLE `seriegenres` (
  `serie_genre_serie_id` bigint NOT NULL,
  `serie_media_genre_id` int NOT NULL,
  PRIMARY KEY (`serie_genre_serie_id`,`serie_media_genre_id`),
  KEY `fk_seriegenders_series` (`serie_genre_serie_id`),
  KEY `fk_seriegenders_mediagenders` (`serie_media_genre_id`)
);

LOCK TABLES `seriegenres` WRITE;

INSERT INTO `seriegenres` VALUES (2,3),(3,3);

UNLOCK TABLES;

DROP TABLE IF EXISTS `series`;

CREATE TABLE `series` (
  `serie_id` bigint NOT NULL AUTO_INCREMENT,
  `serie_title` varchar(256) DEFAULT NULL,
  `serie_poster` varchar(512) DEFAULT NULL,
  `serie_releasedate` date DEFAULT NULL,
  `serie_resume` varchar(2048) DEFAULT NULL,
  `serie_language_id` int DEFAULT NULL,
  `serie_api_id` bigint DEFAULT NULL,
  PRIMARY KEY (`serie_id`),
  UNIQUE KEY `serie_api_id_UNIQUE` (`serie_api_id`),
  KEY `fk_series_languages` (`serie_language_id`),
  CONSTRAINT `fk_series_languages` FOREIGN KEY (`serie_language_id`) REFERENCES `languages` (`language_id`)
);

LOCK TABLES `series` WRITE;

INSERT INTO `series` VALUES (1,NULL,NULL,NULL,NULL,NULL,110492),(2,NULL,NULL,NULL,NULL,NULL,85552),(3,'Cobra Kai','3.png','2021-12-01','Karaté para todo o lado',1,NULL),(4,NULL,NULL,NULL,NULL,NULL,77169);

UNLOCK TABLES;

DROP TABLE IF EXISTS `userepisodesevaluation`;

CREATE TABLE `userepisodesevaluation` (
  `user_episode_evaluation_stars` smallint DEFAULT NULL,
  `user_episode_evaluation_comment` varchar(256) DEFAULT NULL,
  `user_episode_evaluation_user_id` bigint NOT NULL,
  `user_episode_evaluation_episode_id` bigint NOT NULL,
  PRIMARY KEY (`user_episode_evaluation_user_id`,`user_episode_evaluation_episode_id`),
  KEY `fk_userepisodesevaluation_users` (`user_episode_evaluation_user_id`),
  KEY `fk_userepisodesevaluation_episodes` (`user_episode_evaluation_episode_id`),
  CONSTRAINT `fk_userepisodesevaluation_episodes` FOREIGN KEY (`user_episode_evaluation_episode_id`) REFERENCES `episodes` (`episode_id`),
  CONSTRAINT `fk_userepisodesevaluation_users` FOREIGN KEY (`user_episode_evaluation_user_id`) REFERENCES `users` (`user_person_id`)
);

LOCK TABLES `userepisodesevaluation` WRITE;

INSERT INTO `userepisodesevaluation` VALUES (9,'Primeiro epsiódio já começou bem',1,1);

UNLOCK TABLES;

DROP TABLE IF EXISTS `userfavoritemovies`;

CREATE TABLE `userfavoritemovies` (
  `user_favorite_movie_user_id` bigint NOT NULL,
  `user_favorite_movie_id` bigint NOT NULL,
  PRIMARY KEY (`user_favorite_movie_user_id`,`user_favorite_movie_id`),
  KEY `fk_userfavoritemovies_users` (`user_favorite_movie_user_id`),
  KEY `fk_userfavoritemovies_movies` (`user_favorite_movie_id`),
  CONSTRAINT `fk_userfavoritemovies_movies` FOREIGN KEY (`user_favorite_movie_id`) REFERENCES `movies` (`movie_id`),
  CONSTRAINT `fk_userfavoritemovies_users` FOREIGN KEY (`user_favorite_movie_user_id`) REFERENCES `users` (`user_person_id`)
);

LOCK TABLES `userfavoritemovies` WRITE;

INSERT INTO `userfavoritemovies` VALUES (1,2),(1,3);

UNLOCK TABLES;

DROP TABLE IF EXISTS `userfavoriteseries`;

CREATE TABLE `userfavoriteseries` (
  `user_favorite_serie_user_id` bigint NOT NULL,
  `user_favorite_serie_id` bigint NOT NULL,
  PRIMARY KEY (`user_favorite_serie_user_id`,`user_favorite_serie_id`),
  KEY `fk_userfavoriteseries_users` (`user_favorite_serie_user_id`),
  KEY `fk_userfavoriteseries_series` (`user_favorite_serie_id`),
  CONSTRAINT `fk_userfavoriteseries_series` FOREIGN KEY (`user_favorite_serie_id`) REFERENCES `series` (`serie_id`),
  CONSTRAINT `fk_userfavoriteseries_users` FOREIGN KEY (`user_favorite_serie_user_id`) REFERENCES `users` (`user_person_id`)
);

LOCK TABLES `userfavoriteseries` WRITE;

INSERT INTO `userfavoriteseries` VALUES (1,1);

UNLOCK TABLES;


DROP TABLE IF EXISTS `usermoviesevaluation`;

CREATE TABLE `usermoviesevaluation` (
  `user_movie_evaluation_stars` smallint DEFAULT NULL,
  `user_movie_evaluation_comment` varchar(256) DEFAULT NULL,
  `user_movie_evaluation_user_id` bigint NOT NULL,
  `user_movie_evaluation_movie_id` bigint NOT NULL,
  PRIMARY KEY (`user_movie_evaluation_user_id`,`user_movie_evaluation_movie_id`),
  KEY `fk_usermoviesevaluation_users` (`user_movie_evaluation_user_id`),
  KEY `fk_usermoviesevaluation_movies` (`user_movie_evaluation_movie_id`),
  CONSTRAINT `fk_usermoviesevaluation_movies` FOREIGN KEY (`user_movie_evaluation_movie_id`) REFERENCES `movies` (`movie_id`),
  CONSTRAINT `fk_usermoviesevaluation_users` FOREIGN KEY (`user_movie_evaluation_user_id`) REFERENCES `users` (`user_person_id`)
);

LOCK TABLES `usermoviesevaluation` WRITE;

INSERT INTO `usermoviesevaluation` VALUES (9,'Belo Filme',1,1),(7,'É um bom filme, mas já vi melhores.',1,2),(3,'Mais do mesmo.',1,3);

UNLOCK TABLES;

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_email` varchar(128) NOT NULL,
  `user_password` varbinary(256) NOT NULL,
  `user_person_id` bigint NOT NULL,
  `user_state_id` int NOT NULL,
  PRIMARY KEY (`user_person_id`),
  UNIQUE KEY `user_email` (`user_email`),
  KEY `fk_userstate_idx` (`user_state_id`),
  CONSTRAINT `fk_users_persons` FOREIGN KEY (`user_person_id`) REFERENCES `persons` (`person_id`)
);

LOCK TABLES `users` WRITE;

INSERT INTO `users` VALUES ('nunosantoslopes@hotmail.com',_binary '$2y$12$/FQWkHb3U95j/VJlT5IVv.Fsa5MSUPAr39AtIXEtDy4fGD.vu79b.',1,1);

UNLOCK TABLES;

DROP TABLE IF EXISTS `userseriesevaluation`;

CREATE TABLE `userseriesevaluation` (
  `user_serie_evaluation_stars` smallint DEFAULT NULL,
  `user_serie_evaluation_comment` varchar(256) DEFAULT NULL,
  `user_serie_evaluation_user_id` bigint NOT NULL,
  `user_serie_evaluation_serie_id` bigint NOT NULL,
  PRIMARY KEY (`user_serie_evaluation_serie_id`,`user_serie_evaluation_user_id`),
  KEY `fk_userseriesevaluation_users` (`user_serie_evaluation_user_id`),
  KEY `fk_userseriesevaluation_series` (`user_serie_evaluation_serie_id`),
  CONSTRAINT `fk_userseriesevaluation_series` FOREIGN KEY (`user_serie_evaluation_serie_id`) REFERENCES `series` (`serie_id`),
  CONSTRAINT `fk_userseriesevaluation_users` FOREIGN KEY (`user_serie_evaluation_user_id`) REFERENCES `users` (`user_person_id`)
);

LOCK TABLES `userseriesevaluation` WRITE;

INSERT INTO `userseriesevaluation` VALUES (10,'Série boa demais',1,1),(5,'Só é bom por causa da Zendaya',1,2);

UNLOCK TABLES;

DROP TABLE IF EXISTS `userstate`;

CREATE TABLE `userstate` (
  `userstate_id` int NOT NULL AUTO_INCREMENT,
  `userstate_description` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`userstate_id`),
  UNIQUE KEY `userstate_description_UNIQUE` (`userstate_description`)
);

LOCK TABLES `userstate` WRITE;

INSERT INTO `userstate` VALUES (1,'Ativo'),(3,'Inactivo'),(2,'Por Ativar');

UNLOCK TABLES;

DROP TABLE IF EXISTS `userwatchedepisodes`;

CREATE TABLE `userwatchedepisodes` (
  `user_watched_episode_user_id` bigint NOT NULL,
  `user_watched_episode_id` bigint NOT NULL,
  PRIMARY KEY (`user_watched_episode_user_id`,`user_watched_episode_id`),
  KEY `fk_userwatchedepisodes_users` (`user_watched_episode_user_id`),
  KEY `fk_userwatchedepisodes_episodes` (`user_watched_episode_id`),
  CONSTRAINT `fk_userwatchedepisodes_episodes` FOREIGN KEY (`user_watched_episode_id`) REFERENCES `episodes` (`episode_id`),
  CONSTRAINT `fk_userwatchedepisodes_users` FOREIGN KEY (`user_watched_episode_user_id`) REFERENCES `users` (`user_person_id`)
);

LOCK TABLES `userwatchedepisodes` WRITE;

INSERT INTO `userwatchedepisodes` VALUES (1,1);

UNLOCK TABLES;

DROP TABLE IF EXISTS `userwatchedmovies`;

CREATE TABLE `userwatchedmovies` (
  `user_watched_movie_user_id` bigint NOT NULL,
  `user_watched_movie_id` bigint NOT NULL,
  PRIMARY KEY (`user_watched_movie_user_id`,`user_watched_movie_id`),
  KEY `fk_userwatchedmovies_users` (`user_watched_movie_user_id`),
  KEY `fk_userwatchedmovies_movies` (`user_watched_movie_id`),
  CONSTRAINT `fk_userwatchedmovies_users` FOREIGN KEY (`user_watched_movie_user_id`) REFERENCES `users` (`user_person_id`)
);

LOCK TABLES `userwatchedmovies` WRITE;

INSERT INTO `userwatchedmovies` VALUES (1,2);

UNLOCK TABLES;

DROP TABLE IF EXISTS `userwatchlatermovies`;

CREATE TABLE `userwatchlatermovies` (
  `user_watch_later_movie_user_id` bigint NOT NULL,
  `user_watch_later_movie_id` bigint NOT NULL,
  PRIMARY KEY (`user_watch_later_movie_user_id`,`user_watch_later_movie_id`),
  KEY `fk_userwatchlatermovies_users` (`user_watch_later_movie_user_id`),
  KEY `fk_userwatchlatermovies_movies` (`user_watch_later_movie_id`),
  CONSTRAINT `fk_userwatchlatermovies_movies` FOREIGN KEY (`user_watch_later_movie_id`) REFERENCES `movies` (`movie_id`),
  CONSTRAINT `fk_userwatchlatermovies_users` FOREIGN KEY (`user_watch_later_movie_user_id`) REFERENCES `users` (`user_person_id`)
);

LOCK TABLES `userwatchlatermovies` WRITE;

INSERT INTO `userwatchlatermovies` VALUES (1,1),(1,2);

UNLOCK TABLES;

DROP TABLE IF EXISTS `userwatchlaterseries`;

CREATE TABLE `userwatchlaterseries` (
  `user_watch_later_serie_user_id` bigint NOT NULL,
  `user_watch_later_serie_id` bigint NOT NULL,
  PRIMARY KEY (`user_watch_later_serie_user_id`,`user_watch_later_serie_id`),
  KEY `fk_userwatchlaterseries_users` (`user_watch_later_serie_user_id`),
  KEY `fk_userwatchlaterseries_series` (`user_watch_later_serie_id`),
  CONSTRAINT `fk_userwatchlaterseries_series` FOREIGN KEY (`user_watch_later_serie_id`) REFERENCES `series` (`serie_id`),
  CONSTRAINT `fk_userwatchlaterseries_users` FOREIGN KEY (`user_watch_later_serie_user_id`) REFERENCES `users` (`user_person_id`)
);

LOCK TABLES `userwatchlaterseries` WRITE;

INSERT INTO `userwatchlaterseries` VALUES (1,4);

UNLOCK TABLES;

DROP TABLE IF EXISTS `writers`;

CREATE TABLE `writers` (
  `writer_id` bigint NOT NULL AUTO_INCREMENT,
  `writer_name` varchar(128) NOT NULL,
  PRIMARY KEY (`writer_id`)
);

LOCK TABLES `writers` WRITE;

UNLOCK TABLES;

SET TIME_ZONE=@OLD_TIME_ZONE;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT;
SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS;
SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION;
SET SQL_NOTES=@OLD_SQL_NOTES;
