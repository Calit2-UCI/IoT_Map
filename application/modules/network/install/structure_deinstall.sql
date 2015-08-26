DROP TABLE `[prefix]net_temp`;
DROP TABLE `[prefix]net_events_callbacks`;
ALTER TABLE `[prefix]users`
	DROP `net_id`,
	DROP `net_status`,
	DROP `net_info`,
    DROP `net_is_incomer`;
ALTER TABLE `[prefix]users_deleted`
	DROP `net_id`,
	DROP `net_status`,
	DROP `net_info`,
    DROP `net_is_incomer`;
