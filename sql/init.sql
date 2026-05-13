CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,   
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
)

CREATE TABLE `todos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,     
  `due_date` date DEFAULT NULL,
  `priority` tinyint unsigned NOT NULL DEFAULT '2',  
  `status` tinyint unsigned NOT NULL DEFAULT '0',    
  `created_by` int NOT NULL,
  `group_id` int NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,   
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_todos_user` (`created_by`),
  KEY `fk_todos_group` (`group_id`),
  CONSTRAINT `fk_todos_group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `fk_todos_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
)

CREATE TABLE `groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,   
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
)

CREATE TABLE `group_users` (
  `user_id` int NOT NULL,
  `group_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,   
  PRIMARY KEY (`user_id`,`group_id`)
)

CREATE TABLE `invite_tokens` (     
  `id` int NOT NULL AUTO_INCREMENT,
  `group_id` int NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,   
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
)