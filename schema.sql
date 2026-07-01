CREATE TABLE users (
  id_users INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','staff','supplier','manager') NOT NULL DEFAULT 'staff'
);

CREATE TABLE users_profiles (
  profiles_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  name VARCHAR(255) DEFAULT NULL,
  address TEXT DEFAULT NULL,
  profile_picture VARCHAR(255) DEFAULT NULL,
  phone VARCHAR(14) DEFAULT NULL,
  member ENUM('true','false') DEFAULT 'false',
  FOREIGN KEY (user_id) REFERENCES users(id_users) ON DELETE CASCADE
);
