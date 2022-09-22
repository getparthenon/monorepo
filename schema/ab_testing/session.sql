CREATE TABLE IF NOT EXISTS "ab_sessions" (
	"id" uuid,
	"user_id" uuid DEFAULT NULL,
	"user_agent" varchar(255),
	"ip_address" varchar(255),
	"created_at" timestamp
);
select create_hypertable('ab_sessions', 'created_at');