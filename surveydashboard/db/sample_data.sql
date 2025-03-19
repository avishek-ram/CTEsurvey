-- Check if records already exist
SET @count = (SELECT COUNT(*) FROM mdl_survey_dashboard);
SET @admin_id = (SELECT id FROM mdl_user WHERE username = 'admin' LIMIT 1);
SET @manager_role = (SELECT id FROM mdl_role WHERE shortname = 'manager' LIMIT 1);

-- Only insert if table is empty
IF @count = 0 THEN
    INSERT INTO mdl_survey_dashboard 
    (surveytype, surveystatus, statuslastupdated, lastupdatedby, roleid, rolename) 
    VALUES 
    (1, 1, UNIX_TIMESTAMP(), @admin_id, @manager_role, 'manager'),  -- Semester End Survey (enabled)
    (2, 0, UNIX_TIMESTAMP(), @admin_id, @manager_role, 'manager'),  -- Mid Semester Survey (disabled)
    (3, 1, UNIX_TIMESTAMP(), @admin_id, @manager_role, 'manager'),  -- Quarter End Survey (enabled)
    (4, 0, UNIX_TIMESTAMP(), @admin_id, @manager_role, 'manager');  -- Trimester End Survey (disabled)
    
    SELECT 'Sample data inserted successfully.' as result;
ELSE
    SELECT 'Table already contains data. No sample data inserted.' as result;
END IF; 