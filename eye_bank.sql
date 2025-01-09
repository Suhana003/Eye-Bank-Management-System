CREATE DATABASE eye_bank;

USE eye_bank;

CREATE TABLE donors (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(10) NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    contact VARCHAR(15) NOT NULL,
    address TEXT NOT NULL
);

CREATE TABLE requests (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(10) NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    contact VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    reason TEXT NOT NULL
);
-- Create donors table if it doesn't exist
CREATE TABLE IF NOT EXISTS donors (
    donor_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(10) NOT NULL,
    blood_group VARCHAR(10) NOT NULL,
    contact VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    availability VARCHAR(10) NOT NULL DEFAULT 'Yes'
);

-- Create requests table if it doesn't exist
CREATE TABLE IF NOT EXISTS requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(10) NOT NULL,
    blood_group VARCHAR(10) NOT NULL,
    contact VARCHAR(20) NOT NULL,
    eye_required VARCHAR(10) NOT NULL,
    donor_id INT,
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES donors(donor_id)
);
CREATE TABLE avail (
    stock_id INT AUTO_INCREMENT PRIMARY KEY,
    
    stock_date DATE,
    expiration_date DATE,
    availability_status VARCHAR(50) DEFAULT 'available'
    
);
CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_type VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    event_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



