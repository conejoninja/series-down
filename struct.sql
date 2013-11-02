CREATE TABLE IF NOT EXISTS /*TABLE_PREFIX*/t_media (
  `pk_i_id` int(11) NOT NULL,
  `s_name` varchar(250) DEFAULT NULL,
  `s_code` varchar(250) DEFAULT NULL,
  `e_media_type` int(11) DEFAULT NULL,
  `s_thumbnail` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`pk_i_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS /*TABLE_PREFIX*/t_episode (
  `pk_i_id` int(11) NOT NULL,
  `fk_i_media_id` int(11) DEFAULT NULL,
  `s_name` varchar(250) DEFAULT NULL,
  `i_season` int(11) DEFAULT NULL,
  `i_episode` int(11) DEFAULT NULL,
  `b_has_links` tinyint(1) DEFAULT NULL,
  `s_status` varchar(100) DEFAULT NULL,
  `s_download_url` varchar(250) DEFAULT NULL,
  `s_downloaded_path` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`pk_i_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
