ALTER TABLE `shua_tools`
ADD COLUMN `goods_sid` tinyint(1) NOT NULL DEFAULT '0',
ADD COLUMN `audit_status` tinyint(1) NOT NULL DEFAULT '0',
ADD COLUMN `sup_price` decimal(10,2) NOT NULL DEFAULT '0.00';

ALTER TABLE `shua_faka`
ADD COLUMN `sid` int(11) unsigned DEFAULT 0;