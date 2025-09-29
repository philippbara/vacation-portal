CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- Users
INSERT INTO users (username, first_name, last_name, email, password_hash, role, employee_code)
VALUES
-- Admin
('admin', 'Super', 'Admin', 'admin@example.com', crypt('adminpass', gen_salt('bf')), 'admin', '1000001'),

-- Managers
('manager1', 'Manny', 'Manager', 'manager1@example.com', crypt('manager1pass', gen_salt('bf')), 'manager', '1000002'),
('manager2', 'Monica', 'Smith', 'manager2@example.com', crypt('manager2pass', gen_salt('bf')), 'manager', '1000003'),

-- Employees
('employee1', 'Alice', 'Anderson', 'alice@example.com', crypt('employee1pass', gen_salt('bf')), 'employee', '1000004'),
('employee2', 'Bob', 'Brown', 'bob@example.com', crypt('employee2pass', gen_salt('bf')), 'employee', '1000005'),
('employee3', 'Charlie', 'Clark', 'charlie@example.com', crypt('employee3pass', gen_salt('bf')), 'employee', '1000006'),
('employee4', 'Diana', 'Davis', 'diana@example.com', crypt('employee4pass', gen_salt('bf')), 'employee', '1000007');

-- Vacation Requests
WITH e1 AS (SELECT id FROM users WHERE username='employee1'),
     e2 AS (SELECT id FROM users WHERE username='employee2'),
     e3 AS (SELECT id FROM users WHERE username='employee3'),
     e4 AS (SELECT id FROM users WHERE username='employee4')
INSERT INTO vacation_requests (user_id, start_date, end_date, reason, status)
VALUES
-- Employee 1
((SELECT id FROM e1), '2025-08-01', '2025-08-05', 'Family trip', 'approved'),
((SELECT id FROM e1), '2025-09-15', '2025-09-20', 'Medical leave', 'approved'),
((SELECT id FROM e1), '2025-12-24', '2025-12-26', 'Holiday travel', 'rejected'),

-- Employee 2
((SELECT id FROM e2), '2025-07-10', '2025-07-15', 'Beach vacation', 'pending'),
((SELECT id FROM e2), '2025-10-01', '2025-10-05', 'Conference', 'pending'),
((SELECT id FROM e2), '2025-11-20', '2025-11-25', 'Family event', 'rejected'),

-- Employee 3
((SELECT id FROM e3), '2025-06-05', '2025-06-10', 'Road trip', 'approved'),
((SELECT id FROM e3), '2025-09-12', '2025-09-16', 'Personal leave', 'pending'),
((SELECT id FROM e3), '2025-12-30', '2026-01-02', 'New Year travel', 'rejected'),

-- Employee 4
((SELECT id FROM e4), '2025-05-20', '2025-05-25', 'Wedding', 'approved'),
((SELECT id FROM e4), '2025-08-15', '2025-08-18', 'Vacation with friends', 'rejected'),
((SELECT id FROM e4), '2025-12-10', '2025-12-12', 'Short break', 'rejected');
