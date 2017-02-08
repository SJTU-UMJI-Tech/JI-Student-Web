CREATE TABLE IF NOT EXISTS `config` (
  `obj`    VARCHAR(50) NOT NULL,
  `value`  TEXT        NOT NULL,
  `module` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`obj`),
  UNIQUE KEY `obj` (`obj`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;