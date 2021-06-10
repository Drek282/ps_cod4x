CREATE TABLE `ps_map_data_cod4x` (
  `dataid` int unsigned NOT NULL default '0',
  `allieskills` smallint unsigned NOT NULL default '0',
  `axiskills` smallint unsigned NOT NULL default '0',
  `joinedallies` smallint unsigned NOT NULL default '0',
  `joinedaxis` smallint unsigned NOT NULL default '0',
  `joinedspectator` smallint unsigned NOT NULL default '0',
  `allieswon` smallint unsigned NOT NULL default '0',
  `allieslost` smallint unsigned NOT NULL default '0',
  `axiswon` smallint unsigned NOT NULL default '0',
  `axislost` smallint unsigned NOT NULL default '0',
  PRIMARY KEY  (`dataid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;
CREATE TABLE `ps_plr_data_cod4x` (
  `dataid` int unsigned NOT NULL default '0',
  `allieskills` smallint unsigned NOT NULL default '0',
  `axiskills` smallint unsigned NOT NULL default '0',
  `alliesdeaths` smallint unsigned NOT NULL default '0',
  `axisdeaths` smallint unsigned NOT NULL default '0',
  `joinedallies` smallint unsigned NOT NULL default '0',
  `joinedaxis` smallint unsigned NOT NULL default '0',
  `joinedspectator` smallint unsigned NOT NULL default '0',
  `allieswon` smallint unsigned NOT NULL default '0',
  `allieslost` smallint unsigned NOT NULL default '0',
  `axiswon` smallint unsigned NOT NULL default '0',
  `axislost` smallint unsigned NOT NULL default '0',
  PRIMARY KEY  (`dataid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;
CREATE TABLE `ps_plr_maps_cod4x` (
  `dataid` int unsigned NOT NULL default '0',
  `allieskills` smallint unsigned NOT NULL default '0',
  `alliesdeaths` smallint unsigned NOT NULL default '0',
  `axiskills` smallint unsigned NOT NULL default '0',
  `axisdeaths` smallint unsigned NOT NULL default '0',
  `joinedallies` smallint unsigned NOT NULL default '0',
  `joinedaxis` smallint unsigned NOT NULL default '0',
  `joinedspectator` smallint unsigned NOT NULL default '0',
  `allieswon` smallint unsigned NOT NULL default '0',
  `allieslost` smallint unsigned NOT NULL default '0',
  `axiswon` smallint unsigned NOT NULL default '0',
  `axislost` smallint unsigned NOT NULL default '0',
  PRIMARY KEY  (`dataid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

-- Moved from defaults.sql.
INSERT INTO `ps_config_events` (`id`, `gametype`, `modtype`, `eventname`, `alias`, `regex`, `idx`, `ignore`, `codefile`) 
    VALUES 
        (52,'cod4x','','cod4x_init','','/^InitGame:\\s*(.+)/',520,0,''),
        (53,'cod4x','','cod4x_shutdown','','/^ShutdownGame:/',530,0,''),
        (59,'cod4x','','cod4x_plrtrigger','','/^(\\w+);(.+)/',590,0,'');

INSERT INTO `ps_config_plrbonuses` (`id`, `eventname`, `enactor`, `enactor_team`, `victim`, `victim_team`, `description`, `gametype`, `modtype`) 
    VALUES 
        (34,'ffkill',-10,0,0,0,'when a player kills a team mate','','');

INSERT INTO `ps_config_awards` (`id`, `enabled`, `idx`, `type`, `negative`, `class`, `name`, `groupname`, `phrase`, `expr`, `order`, `where`, `limit`, `format`, `gametype`, `modtype`, `rankedonly`, `description`) 
    VALUES 
        (4,1,10,'player',0,'','Highest Skill','','{$player.link} has the highest skill with {$award.value}','{$skill}','desc','',0,'%.02f',NULL,NULL,1,'Player with the highest skill'),
        (6,1,20,'player',0,'','Most Kills','','{$player.link} has killed the most players ({$award.value})','{$kills}','desc','',0,'commify',NULL,NULL,1,'Player with the most kills (any team)'),
        (8,1,70,'player',0,'','Most Time Online','','{$player.link} has been online the longest ({$award.value})','{$onlinetime}','desc','',25,'compacttime',NULL,NULL,1,'Player with the most online time'),
        (9,1,190,'weaponclass',0,'','Most Kills with {$weapon.class} weapons','','{$player.link} has the most {$weapon.class} kills ({$award.value})','{$kills}','desc','',0,'commify',NULL,NULL,1,'Most kills with {$weapon.class} type weapons (including: {$weapon.list}).'),
        (10,1,180,'weapon',0,'','Most Kills with {$weapon.name}','','{$player.link} has the most {$weapon.link} kills ({$award.value})','{$kills}','desc','',0,'commify',NULL,NULL,1,'Most kills with a {$weapon.name}'),
        (13,1,30,'player',0,'','Most Bonus Points','','{$player.link} achieved the most bonus points ({$award.value})','{$totalbonus}','desc','',25,'commify',NULL,NULL,1,'Player that has achieved the most bonus points.'),
        (23,1,50,'player',1,'','Worst Teammate','','{$player.link} has killed the most teammates ({$award.value})','{$ffkills}','desc','',0,'commify',NULL,NULL,1,'Player with the most friendly fire kills (ie: Worst Teammate)'),
        (25,1,60,'player',1,'','Worst Team Kill Percentage','','{$player.link} has the highest team kill percentage ({$award.value})','{$ffkills} / {$kills} * 100','desc','',0,'%0.02f%%',NULL,NULL,1,'Player with the highest team kill percentage.');
