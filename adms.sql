
CREATE TABLE IF NOT EXISTS adms_devices (
  sn VARCHAR(50) PRIMARY KEY,
  last_stamp INT DEFAULT 0,
  last_opstamp INT DEFAULT 0,
  last_activity DATETIME NULL,
  ip_address VARCHAR(50),
  fw_version VARCHAR(50),
  online TINYINT DEFAULT 0
);
CREATE TABLE IF NOT EXISTS adms_users (
  pin VARCHAR(50) PRIMARY KEY,
  name VARCHAR(100),
  privilege INT DEFAULT 0,
  card VARCHAR(50),
  group_id INT DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS adms_attlog (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  sn VARCHAR(50),
  pin VARCHAR(50),
  scan_date DATETIME,
  status INT DEFAULT 0,
  verify INT DEFAULT 0,
  raw TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(sn), INDEX(pin), INDEX(scan_date)
);
CREATE TABLE IF NOT EXISTS adms_templates (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  pin VARCHAR(50),
  fid INT DEFAULT 0,
  type ENUM('fp','face') DEFAULT 'fp',
  tmp LONGTEXT,
  valid INT DEFAULT 1,
  INDEX(pin)
);
CREATE TABLE IF NOT EXISTS adms_commands (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  sn VARCHAR(50) NULL,
  command TEXT,
  status ENUM('pending','sent','done','error') DEFAULT 'pending',
  response TEXT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX(sn), INDEX(status)
);
