CREATE TABLE IF NOT EXISTS `#__jpetition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,  
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `answer` text NOT NULL,
  `state` tinyint(4) NOT NULL,
  `start_signing` datetime NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__jpetition_votes` (
  `user_id` int(11) NOT NULL,
  `petition_id` int(11) NOT NULL,
  `signed` datetime NOT NULL,
  UNIQUE KEY `idx_user_id_petition_id` (`user_id`,`petition_id`),
  KEY `fk_user_id` (`user_id`),
  KEY `fk_petition_id` (`petition_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

  
ALTER TABLE `#__jpetition`
  ADD CONSTRAINT `#__jpetition_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `#__users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
  
ALTER TABLE `#__jpetition_votes`
  ADD CONSTRAINT `#__jpetition_votes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `#__users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `#__jpetition_votes_ibfk_2` FOREIGN KEY (`petition_id`) REFERENCES `#__jpetition` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
  
ALTER TABLE `#__jpetition` ADD COLUMN `notify_end_collect_signs` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `#__jpetition` ADD COLUMN `notify_admin_collected_signs` tinyint(1) NOT NULL DEFAULT '0';