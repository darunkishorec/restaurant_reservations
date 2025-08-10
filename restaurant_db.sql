-- Simple Restaurant Reservation Database
CREATE DATABASE IF NOT EXISTS restaurant_reservations;
USE restaurant_reservations;

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id VARCHAR(20) UNIQUE NOT NULL,
    guest_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    guest_count INT NOT NULL,
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    special_requests TEXT,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample data
INSERT INTO reservations (reservation_id, guest_name, email, phone, guest_count, reservation_date, reservation_time, special_requests, status) VALUES
('RES202412011001', 'John Smith', 'john@email.com', '555-123-4567', 2, '2024-12-15', '19:00:00', 'Window seat preferred', 'confirmed'),
('RES202412011002', 'Sarah Johnson', 'sarah@email.com', '555-987-6543', 4, '2024-12-16', '18:30:00', 'Birthday celebration', 'confirmed'); 