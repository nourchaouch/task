ALTER TABLE users ADD COLUMN role VARCHAR(255) DEFAULT 'team_member' AFTER password;
