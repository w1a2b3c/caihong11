ALTER TABLE `shua_tools`
ADD COLUMN `ts` tinyint(1) DEFAULT '0';

ALTER TABLE `shua_workorder`
ADD COLUMN `ts` tinyint(1) DEFAULT 0;