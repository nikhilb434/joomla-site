ALTER TABLE `#__miniorange_oauth_config`
ADD COLUMN `password` VARCHAR(255) NOT NULL AFTER `proxy_password`,
ADD COLUMN `proxy_host_name` VARCHAR(255) NOT NULL AFTER `password`,
ADD COLUMN `port_number` VARCHAR(255) NOT NULL AFTER `proxy_host_name`,
ADD COLUMN `username` VARCHAR(255) NOT NULL AFTER `port_number`;
