-- Database structure for Railway deployment

-- Table: users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: mahasiswa
CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `nim` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `angkatan` int(4) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  PRIMARY KEY (`nim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (password: admin123)
INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample mahasiswa data
INSERT INTO `mahasiswa` (`nim`, `nama`, `angkatan`, `jurusan`) VALUES
('2021001', 'Budi Santoso', 2021, 'Teknik Informatika'),
('2021002', 'Siti Nurhaliza', 2021, 'Sistem Informasi'),
('2022001', 'Ahmad Fauzi', 2022, 'Teknik Informatika');

-- Table: revoked_tokens
CREATE TABLE IF NOT EXISTS `revoked_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `jti` varchar(255) NOT NULL,
  `expired_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jti` (`jti`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
