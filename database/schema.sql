CREATE DATABASE IF NOT EXISTS jadwal_tanam_panen CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jadwal_tanam_panen;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS crops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age_days INT NOT NULL,
    water_requirement DECIMAL(10,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS planting_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    crop_id INT NOT NULL,
    land_area DECIMAL(10,2) NOT NULL,
    planting_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_planting_crop FOREIGN KEY (crop_id) REFERENCES crops(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS harvest_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    planting_schedule_id INT NOT NULL,
    estimated_harvest_date DATE NOT NULL,
    actual_harvest_date DATE NULL,
    notes TEXT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'Belum waktunya panen',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_harvest_planting FOREIGN KEY (planting_schedule_id) REFERENCES planting_schedules(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS rainfall_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    measurement_date DATE NOT NULL,
    amount_mm DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);