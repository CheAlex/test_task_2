CREATE DATABASE `phalcon`;

CREATE TABLE `pages` (
    `id`      int(10)      unsigned NOT NULL AUTO_INCREMENT,
    `content` text         NOT NULL,
    `host`    varchar(255) NOT NULL,
    `uri`     varchar(255) NOT NULL,

    PRIMARY KEY (`id`)
);

USE `phalcon`;
INSERT INTO `pages` (`content`, `host`, `uri`)
VALUES ('content_blog1_page_1', 'blog1.dev', 'page_1'),
       ('content_blog1_page_2', 'blog1.dev', 'page_2'),
       ('content_blog2_page_1', 'blog2.dev', 'page_1'),
       ('content_blog2_page_2', 'blog2.dev', 'page_2')

# Индексы повесить
