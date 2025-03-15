CREATE DATABASE subscription_management_system;
 
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,         
    username VARCHAR(255) NOT NULL,            
    email VARCHAR(255) NOT NULL UNIQUE,        
    password_hash VARCHAR(255) NOT NULL,       
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);


CREATE TABLE subscription_plans (
    plan_id INT AUTO_INCREMENT PRIMARY KEY,         
    plan_name VARCHAR(255) NOT NULL,                
    price DECIMAL(10, 2) NOT NULL,                
    description TEXT NOT NULL  );

    CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('Paid', 'Pending', 'Failed') NOT NULL
);                    
-- payement
CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,         
    user_id INT NOT NULL,                              
    card_number VARCHAR(255) NOT NULL,                 
    expiry_date VARCHAR(5) NOT NULL,                  
    cvv VARCHAR(255) NOT NULL,                         
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP    
);

CREATE TABLE subscriptions (
    subscription_id INT AUTO_INCREMENT PRIMARY KEY,                           
    plan_name VARCHAR(255) NOT NULL,                
    price DECIMAL(10, 2) NOT NULL,                  
    start_date DATE NOT NULL,                       
    end_date DATE,                                  
    status ENUM('active', 'inactive') NOT NULL,     
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
); 

CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each notification
    message TEXT NOT NULL,                          -- Notification message
    is_read BOOLEAN DEFAULT FALSE,                 -- Whether the notification has been read
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp of notification creation
);
CREATE TABLE settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,      -- Unique ID for each setting
    theme VARCHAR(50),                              -- User's selected theme (e.g., "light", "dark")
    language VARCHAR(50),                           -- User's preferred language (e.g., "en", "fr")
    notifications_enabled BOOLEAN DEFAULT TRUE,    -- Whether notifications are enabled
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp of setting creation
);

CREATE TABLE contact_messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,      -- Unique ID for each message
    name VARCHAR(255) NOT NULL,                     -- Name of the sender
    email VARCHAR(255) NOT NULL,                    -- Email address of the sender
    message TEXT NOT NULL,                          -- Message content
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp of when the message was submitted
);