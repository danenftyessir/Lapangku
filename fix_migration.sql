-- SQL Script to Fix Database Transaction State
-- Run this in PostgreSQL directly (pgAdmin, psql, or Supabase SQL Editor)

-- 1. Terminate all backend processes that are in failed transaction state
SELECT pg_terminate_backend(pid)
FROM pg_stat_activity
WHERE state = 'idle in transaction (aborted)'
  AND datname = 'postgres';

-- 2. Verify no more stuck transactions
SELECT pid, state, query
FROM pg_stat_activity
WHERE datname = 'postgres';

-- 3. Now you can run: php artisan migrate:fresh
