--------------------------------------------------------------------------

	RESEARCH ASSISTANT MODULE

--------------------------------------------------------------------------

=================================================
INSTALLING

1. Adding Reseach Assitant folder and researchassistant.php page
	- Navigate into the modules folder
	- Add the Research Assistant folder and the researchassistant.php page

2. Adding the editcomment.php page
	- Navigate to the Main folder (this is where you see all the php pages)
	- Add the editcomment.php

=================================================
DATABASE SCHEMA

CREATE TABLE `pgv_individualtask` (
  `it_t_id` int(11) NOT NULL,
  `it_i_id` varchar(255) NOT NULL,
  `it_i_file` int(11) NOT NULL,
  PRIMARY KEY  (`it_t_id`,`it_i_id`,`it_i_file`)
)

CREATE TABLE `pgv_probabilities` (
  `pr_id` int(11) NOT NULL,
  `pr_f_lvl` varchar(200) NOT NULL,
  `pr_s_lvl` varchar(200) default NULL,
  `pr_rel` varchar(200) NOT NULL,
  `pr_matches` int(11) NOT NULL,
  `pr_count` int(11) NOT NULL,
  `pr_file` int(11) default NULL,
  PRIMARY KEY  (`pr_id`)
)

CREATE TABLE `pgv_taskfacts` (
  `tf_id` int(11) NOT NULL default '0',
  `tf_t_id` int(11) default NULL,
  `tf_factrec` text,
  `tf_people` varchar(255) default NULL,
  PRIMARY KEY  (`tf_id`)
)

CREATE TABLE `pgv_tasks` (
  `t_id` int(11) NOT NULL,
  `t_fr_id` int(11) NOT NULL,
  `t_title` varchar(255) NOT NULL,
  `t_description` text NOT NULL,
  `t_startdate` int(11) NOT NULL,
  `t_enddate` int(11) default NULL,
  `t_results` text,
  `t_form` varchar(255) default NULL,
  `t_username` varchar(45) default NULL,
  PRIMARY KEY  (`t_id`)
)

CREATE TABLE `pgv_tasksource` (
  `ts_t_id` int(11) NOT NULL,
  `ts_s_id` varchar(255) NOT NULL,
  `ts_page` varchar(255) default NULL,
  `ts_date` varchar(50) default NULL,
  `ts_text` text,
  `ts_quay` varchar(50) default NULL,
  `ts_obje` varchar(20) default NULL,
  `ts_array` text,
  PRIMARY KEY  (`ts_s_id`,`ts_t_id`)
)

CREATE TABLE `pgv_user_comments` (
  `uc_id` int(11) NOT NULL,
  `uc_username` varchar(45) NOT NULL,
  `uc_datetime` int(11) NOT NULL,
  `uc_comment` varchar(500) NOT NULL,
  `uc_p_id` varchar(255) NOT NULL,
  `uc_f_id` int(11) NOT NULL,
  PRIMARY KEY  (`uc_id`)
)
