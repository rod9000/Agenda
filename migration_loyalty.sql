-- Run these SQL statements in phpMyAdmin on production

-- 1. Add points and total_visits columns to customers
ALTER TABLE customers ADD COLUMN points INT DEFAULT 0 AFTER notes;
ALTER TABLE customers ADD COLUMN total_visits INT DEFAULT 0 AFTER points;

-- 2. Create loyalty_rewards table
CREATE TABLE IF NOT EXISTS loyalty_rewards (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    points_required INT NOT NULL,
    discount_percent DECIMAL(5,2) DEFAULT 0,
    active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Create loyalty_redemptions table
CREATE TABLE IF NOT EXISTS loyalty_redemptions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id BIGINT UNSIGNED NOT NULL,
    loyalty_reward_id BIGINT UNSIGNED NOT NULL,
    points_spent INT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (loyalty_reward_id) REFERENCES loyalty_rewards(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Add performance indexes
ALTER TABLE appointments ADD INDEX idx_start (start);
ALTER TABLE appointments ADD INDEX idx_status (status);
ALTER TABLE appointments ADD INDEX idx_confirmation_token (confirmation_token);
ALTER TABLE products ADD INDEX idx_quantity (quantity);
ALTER TABLE products ADD INDEX idx_expiry_date (expiry_date);
ALTER TABLE notifications_log ADD INDEX idx_status (status);
ALTER TABLE notifications_log ADD INDEX idx_type (type);
ALTER TABLE notifications_log ADD INDEX idx_sent_at (sent_at);
ALTER TABLE customers ADD INDEX idx_phone (phone);

-- 5. Insert migration records so Laravel knows these ran
INSERT INTO migrations (migration, batch) VALUES
('2026_06_19_000002_add_points_to_customers_table', (SELECT COALESCE(MAX(batch), 0) + 1 FROM migrations)),
('2026_06_19_000003_create_loyalty_rewards_table', (SELECT COALESCE(MAX(batch), 0) + 1 FROM migrations)),
('2026_06_19_000004_create_loyalty_redemptions_table', (SELECT COALESCE(MAX(batch), 0) + 1 FROM migrations)),
('2026_06_19_000005_add_performance_indexes', (SELECT COALESCE(MAX(batch), 0) + 1 FROM migrations));
