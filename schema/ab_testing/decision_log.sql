CREATE TABLE IF NOT EXISTS "ab_experiment_log" (
	"id" uuid,
	"session_id" uuid,
	"decision_string_id" varchar(255),
	"decision_output" text,
	"created_at" timestamp
);
select create_hypertable('ab_experiment_log', 'created_at');