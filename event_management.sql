
-- EVENT MANAGEMENT SYSTEM DATABASE

CREATE DATABASE IF NOT EXISTS event_management;
USE event_management;

-- TABLE: events

CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    location VARCHAR(255) NOT NULL
);

-- TABLE: participants

CREATE TABLE participants (
    participant_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
);

-- TABLE: registrations

CREATE TABLE registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    participant_id INT NOT NULL,
    registration_date DATE NOT NULL,

    CONSTRAINT fk_event
        FOREIGN KEY (event_id)
        REFERENCES events(event_id)
        ON DELETE CASCADE,

    CONSTRAINT fk_participant
        FOREIGN KEY (participant_id)
        REFERENCES participants(participant_id)
        ON DELETE CASCADE,

    UNIQUE (event_id, participant_id)
);

-- SAMPLE DATA

INSERT INTO events (event_name, event_date, location) VALUES
('Orientation Day', '2026-02-01', 'School Hall'),
('Tech Seminar', '2026-02-15', 'IT Lab'),
('Sports Fest', '2026-03-10', 'Main Campus');
