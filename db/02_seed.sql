CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- Users
INSERT INTO users (username, first_name, last_name, email, password_hash, role, employee_code)
VALUES
('admin', 'Super', 'Admin', 'admin@example.com', crypt('adminpass', gen_salt('bf')), 'admin', '1234567'),
('manager', 'Manny', 'Manager', 'manager@example.com', crypt('managerpass', gen_salt('bf')), 'manager', '1234568'),
('employee1', 'Alice', 'Anderson', 'alice@example.com', crypt('employee1pass', gen_salt('bf')), 'employee', '1234569'),
('employee2', 'Bob', 'Brown', 'bob@example.com', crypt('employee2pass', gen_salt('bf')), 'employee', '1234577');

-- Vacation Requests
WITH emp1 AS (SELECT id FROM users WHERE username='employee1'),
     emp2 AS (SELECT id FROM users WHERE username='employee2')
INSERT INTO vacation_requests (user_id, start_date, end_date, reason, status)
VALUES
-- Employee 1 vacation requests
((SELECT id FROM emp1), '2025-08-01', '2025-08-05', 'Family trip', 'approved'),
((SELECT id FROM emp1), '2025-12-20', '2025-12-25', 'Christmas holiday', 'pending'),
-- Employee 2 vacation requests
((SELECT id FROM emp2), '2025-09-10', '2025-09-15', 'Beach vacation', 'pending'),
((SELECT id FROM emp2), '2025-11-01', '2025-11-05', 'Conference', 'approved');