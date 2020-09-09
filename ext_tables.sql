

CREATE TABLE tx_riddle_log (
    riddle_id int(11) DEFAULT '0' NOT NULL,
    riddle_type varchar(32) DEFAULT '' NOT NULL,
    riddle_title varchar(255) DEFAULT '' NOT NULL,
    embed_location varchar(255) DEFAULT '' NOT NULL,
    time_taken int(11) DEFAULT '0' NOT NULL,

    result_score_number int(11) DEFAULT '0' NOT NULL,
    result_result_index int(11) DEFAULT '0' NOT NULL,
    result_score_percentage int(11) DEFAULT '0' NOT NULL,
    result_score_text varchar(255) DEFAULT '' NOT NULL,
    result_title varchar(255) DEFAULT '' NOT NULL,
    result_description varchar(255) DEFAULT '' NOT NULL,
    result_id varchar(255) DEFAULT '' NOT NULL,

    lead_name varchar(255) DEFAULT '' NOT NULL,
    lead_email varchar(255) DEFAULT '' NOT NULL,

    full_json blob
);
