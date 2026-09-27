SQL files in this folder run automatically on deploy.

Name: YYYYMMDD_HHMM_short_description.sql
Example: 20260927_2015_add_cbc_test.sql

Push to staging first. After it works, push master for live.

Old files stay here. Each database only runs a file once (table _sql_changes).

Do not edit a file after it has run. Add a new file.

No DROP DATABASE. One change per file.

RNC extra DBs: first line -- @db tms   or   -- @db backend
