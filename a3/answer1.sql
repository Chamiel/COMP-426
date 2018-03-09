SELECT COUNT(*) FROM `ScoreEvent` WHERE `game` IN (SELECT `id` FROM `Game` WHERE ((`team1`) IN (SELECT `id` FROM `Team` WHERE `name`='Atlanta')) OR ((`team2`) IN (SELECT `id` FROM `Team` WHERE `name`='Atlanta'))) AND (`player` IN (SELECT `id` FROM `Player` WHERE `First` = 'CAM' AND `Last` = 'Newton') OR `passer` IN (SELECT `id` FROM `Player` WHERE `First` = 'Cam' AND `Last` = 'Newton'))