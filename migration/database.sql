create database if not exists `youredb` ;
use `youredb`;
create table if not exists `users`
(
    `id` bigint unsigned unique primary key not null auto_increment,
    `name` varchar(24) not null collate `utf8_general_ci` ,
    `family` varchar(24) not null collate `utf8_general_ci` ,
    `user_id` varchar(64) not null unique ,
    `email` varchar(128) not null unique  ,
    `phone` varchar(11) not null unique  ,
    `password` varchar(60) not null  ,
    `thumbnail` varchar(164) default null,
    `created_at` datetime default CURRENT_TIMESTAMP ,
    `updated_at` datetime default null,
    `email_verified_at` datetime default null,
    `phone_verified_at` datetime default null,
    index (`id`,`user_id`,`email`,`phone`)
);
drop table if exists `user_access_tokens`;
create table if not exists `user_access_tokens`
(
    `id` bigint unsigned not null unique primary key auto_increment,
    `access_token` varchar(255) not null unique ,
    `user_id` bigint unsigned not null ,
    `created_at` datetime not null default CURRENT_TIMESTAMP,
    `expired_at` datetime not null ,
    foreign key (`user_id`) references `users`(`id`) on delete cascade on update cascade ,
    index (`id`,`expired_at`,`access_token`,`user_id`)
);

