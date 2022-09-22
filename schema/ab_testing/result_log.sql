CREATE TABLE IF NOT EXISTS "ab_result_log" (
	"id" uuid,
	"session_id" uuid,
	"user_id" uuid,
	"result_string_id" varchar(255),
	"created_at" timestamp
);
select create_hypertable('ab_result_log', 'created_at');