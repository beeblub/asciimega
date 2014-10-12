<?php
include("mysql_connect.php");

//animations
$command = "CREATE TABLE IF NOT EXISTS `animations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) DEFAULT NULL,
  `title` varchar(30) NOT NULL,
  `description` varchar(300) NOT NULL,
  `url` varchar(50) NOT NULL,
  `filesize` int(11) NOT NULL,
  `frames` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `last_view` timestamp NULL DEFAULT NULL,
  `thumbnail` varchar(350) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comments` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

mysqli_query($link,$command);

//animations
$command = "CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(20) NOT NULL,
  `content` varchar(500) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

mysqli_query($link,$command);

//animations
$command = "CREATE TABLE IF NOT EXISTS `anonymousrights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_animation` int(11) NOT NULL,
  `authkey` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

mysqli_query($link,$command);

//animations
$command = "CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_animation` int(11) DEFAULT NULL,
  `content` varchar(200) NOT NULL,
  `content_url_animation` varchar(20) DEFAULT NULL,
  `id_author` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

mysqli_query($link,$command);

?>
