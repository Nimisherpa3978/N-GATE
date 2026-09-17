USE ngate;

-- ==========================================
-- N-GATE DATABASE
-- Nepal Government Access & Trusted Exchange
-- ==========================================

-- USERS
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('citizen', 'agency', 'admin') NOT NULL DEFAULT 'citizen',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- GOVERNMENT AGENCIES
CREATE TABLE government_agencies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agency_name VARCHAR(150) NOT NULL,
    agency_code VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- GOVERNMENT SERVICES
CREATE TABLE government_services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(150) NOT NULL,
    description TEXT,
    agency_id INT NOT NULL,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (agency_id)
        REFERENCES government_agencies(id)
        ON DELETE CASCADE
);

-- SERVICE REQUESTS
CREATE TABLE service_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    citizen_id INT NOT NULL,
    service_id INT NOT NULL,
    request_number VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    status ENUM(
        'Pending',
        'Processing',
        'Approved',
        'Rejected',
        'Completed'
    ) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (citizen_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    FOREIGN KEY (service_id)
        REFERENCES government_services(id)
        ON DELETE CASCADE
);

-- CITIZEN CONSENT
CREATE TABLE consents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    citizen_id INT NOT NULL,
    request_id INT NOT NULL,
    consent_status ENUM('Granted', 'Revoked') DEFAULT 'Granted',
    granted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (citizen_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    FOREIGN KEY (request_id)
        REFERENCES service_requests(id)
        ON DELETE CASCADE
);

-- AUDIT LOGS
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(255) NOT NULL,
    table_name VARCHAR(100),
    record_id INT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE SET NULL
);