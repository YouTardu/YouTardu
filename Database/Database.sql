ALTER TABLE users
ADD is_verified BOOLEAN DEFAULT FALSE,
ADD verification_token VARCHAR(255) NULL;
