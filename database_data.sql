-- Insert Sample Users (passwords are hashed version of "password123")
INSERT INTO users (username, email, password, created_at) VALUES
('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
('jane_smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
('mike_wilson', 'mike@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
('sarah_jones', 'sarah@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW());

-- Insert Services
INSERT INTO services (name, description, duration, price) VALUES
('Career Counseling', 'One-on-one career guidance session with expert counselors', 60, 99.99),
('Resume Review', 'Professional review and optimization of your resume', 45, 49.99),
('Mock Interview', 'Practice interview session with feedback', 60, 79.99),
('Job Search Strategy', 'Personalized job search planning and strategy session', 90, 129.99),
('LinkedIn Profile Optimization', 'Expert review and enhancement of LinkedIn profile', 30, 39.99),
('Career Change Consultation', 'Guidance for career transition and planning', 75, 149.99),
('Skills Assessment', 'Comprehensive evaluation of professional skills', 120, 199.99),
('Industry Networking Session', 'Guided networking with industry professionals', 90, 89.99);

-- Insert Sample Appointments for different users
INSERT INTO appointments (user_id, service_type, appointment_date, appointment_time, notes, status) VALUES
-- John's appointments
(1, 'Career Counseling', '2024-02-20', '10:00:00', 'Need guidance on career transition', 'confirmed'),
(1, 'Resume Review', '2024-02-22', '14:30:00', 'Updated resume needs review', 'pending'),

-- Jane's appointments
(2, 'Mock Interview', '2024-02-21', '11:00:00', 'Preparing for senior role interview', 'confirmed'),
(2, 'LinkedIn Profile Optimization', '2024-02-23', '13:00:00', 'Need to improve profile for job search', 'confirmed'),
(2, 'Career Change Consultation', '2024-02-25', '15:00:00', 'Exploring opportunities in tech', 'pending'),

-- Mike's appointments
(3, 'Skills Assessment', '2024-02-24', '09:00:00', 'Need to identify skill gaps', 'confirmed'),
(3, 'Job Search Strategy', '2024-02-26', '16:00:00', 'Looking for management positions', 'pending'),
(3, 'Industry Networking Session', '2024-03-01', '14:00:00', 'Interested in fintech connections', 'confirmed'),

-- Sarah's appointments
(4, 'Career Counseling', '2024-02-27', '11:30:00', 'Career advancement guidance needed', 'confirmed'),
(4, 'Mock Interview', '2024-03-02', '10:00:00', 'Technical interview preparation', 'pending'),
(4, 'Resume Review', '2024-03-05', '13:30:00', 'Need to highlight recent certifications', 'confirmed');
