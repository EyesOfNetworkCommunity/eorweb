ALTER TABLE groups ADD COLUMN validator BOOLEAN DEFAULT FALSE;
UPDATE groups SET validator = TRUE WHERE group_id = 1;