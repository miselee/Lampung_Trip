CREATE DATABASE IF NOT EXISTS lampungtrip
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE lampungtrip;
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE destinasi (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    lokasi VARCHAR(150) NOT NULL,
    kategori VARCHAR(50) NOT NULL DEFAULT 'Wisata',
    foto VARCHAR(255) NOT NULL DEFAULT '',
    deskripsi TEXT,
    rating DECIMAL(2,1) NOT NULL DEFAULT 4.5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE open_trip (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    destinasi_id INT UNSIGNED,
    tanggal DATE NOT NULL,
    durasi VARCHAR(50) NOT NULL DEFAULT '1 hari',
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    kuota INT UNSIGNED NOT NULL DEFAULT 10,
    peserta_terdaftar INT UNSIGNED NOT NULL DEFAULT 0,
    foto VARCHAR(255) NOT NULL DEFAULT '',
    deskripsi TEXT,
    fasilitas TEXT,
    itinerary TEXT,
    status ENUM('aktif','penuh','selesai') NOT NULL DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (destinasi_id)
    REFERENCES destinasi(id)
    ON DELETE SET NULL
);

CREATE TABLE pendaftaran (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    open_trip_id INT UNSIGNED NOT NULL,
    nama_lengkap VARCHAR(150) NOT NULL,
    no_whatsapp VARCHAR(20) NOT NULL,
    jumlah_orang INT UNSIGNED NOT NULL DEFAULT 1,
    catatan TEXT,
    status ENUM('menunggu','disetujui','ditolak') NOT NULL DEFAULT 'menunggu',
    alasan_tolak TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE,

    FOREIGN KEY (open_trip_id)
    REFERENCES open_trip(id)
    ON DELETE CASCADE
);

CREATE TABLE pembayaran (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pendaftaran_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    open_trip_id INT UNSIGNED NOT NULL,
    jumlah DECIMAL(12,2) NOT NULL,
    bukti_transfer VARCHAR(255),
    status ENUM('menunggu','lunas','ditolak') NOT NULL DEFAULT 'menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (pendaftaran_id)
    REFERENCES pendaftaran(id)
    ON DELETE CASCADE,

    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE,

    FOREIGN KEY (open_trip_id)
    REFERENCES open_trip(id)
    ON DELETE CASCADE
);

INSERT INTO destinasi 
(nama, lokasi, kategori, foto, deskripsi, rating)
VALUES
('Pantai Pahawang', 'Pesawaran', 'Pantai', 'pahawang.png',
'Pulau indah dengan air laut jernih dan snorkeling.', 4.9),

('Gunung Krakatau', 'Pesisir Utara', 'Gunung', 'krakatau.jpg',
'Gunung berapi legendaris di Selat Sunda.', 4.7),

('Gunung Seminung', 'Lampung Barat', 'Gunung', 'seminung.png',
'Gunung indah dekat Danau Ranau.', 4.8);

INSERT INTO open_trip
(nama, destinasi_id, tanggal, durasi, harga, kuota,
peserta_terdaftar, foto, deskripsi, fasilitas, status)
VALUES

('Open Trip Pahawang', 1, '2026-05-10',
'2 Hari 1 Malam', 350000, 15, 8,
'pahawang.png',
'Trip wisata Pulau Pahawang.',
'Transport, Makan, Guide',
'aktif'),

('Open Trip Krakatau', 2, '2026-05-17',
'2 Hari 1 Malam', 500000, 10, 3,
'krakatau.jpg',
'Trip wisata Gunung Krakatau.',
'Transport, Tenda, Guide',
'aktif');

CREATE VIEW view_open_trip_admin AS
SELECT
    ot.*,
    d.nama AS nama_destinasi
FROM open_trip ot
LEFT JOIN destinasi d
ON ot.destinasi_id = d.id;


CREATE VIEW view_open_trip_aktif AS
SELECT
    ot.*,
    d.lokasi AS lokasi_destinasi
FROM open_trip ot
LEFT JOIN destinasi d
ON ot.destinasi_id = d.id
WHERE ot.status IN ('aktif', 'penuh');


CREATE VIEW view_trip_detail AS
SELECT
    ot.*,
    d.nama AS nama_destinasi,
    d.lokasi AS lokasi_destinasi
FROM open_trip ot
LEFT JOIN destinasi d
ON ot.destinasi_id = d.id;
DELIMITER $$

CREATE TRIGGER trg_trip_penuh
BEFORE UPDATE ON open_trip
FOR EACH ROW
BEGIN
    IF NEW.peserta_terdaftar >= NEW.kuota THEN
        SET NEW.status = 'penuh';
    END IF;
END$$

DELIMITER ;


CREATE VIEW view_pendaftaran_admin AS
SELECT
    p.*,
    u.nama AS nama_user,
    u.email,
    ot.nama AS nama_trip,
    ot.harga,
    ot.tanggal
FROM pendaftaran p
JOIN users u
    ON p.user_id = u.id
JOIN open_trip ot
    ON p.open_trip_id = ot.id;


CREATE VIEW view_pendaftaran_terbaru AS
SELECT
    p.*,
    u.nama AS nama_user,
    ot.nama AS nama_trip
FROM pendaftaran p
JOIN users u
    ON p.user_id = u.id
JOIN open_trip ot
    ON p.open_trip_id = ot.id;


CREATE VIEW view_pendaftaran_detail AS
SELECT
    p.*,
    ot.nama AS nama_trip,
    ot.harga,
    ot.foto AS foto_trip
FROM pendaftaran p
JOIN open_trip ot
    ON p.open_trip_id = ot.id;

CREATE VIEW view_count_pendaftaran_status AS
SELECT
    status,
    COUNT(*) AS total
FROM pendaftaran
GROUP BY status;


CREATE VIEW view_pendaftaran_bulan_ini AS
SELECT
    COUNT(*) AS total
FROM pendaftaran
WHERE MONTH(created_at) = MONTH(NOW())
AND YEAR(created_at) = YEAR(NOW());


CREATE VIEW view_pembayaran_admin AS
SELECT
    py.*,
    u.nama AS nama_user,
    ot.nama AS nama_trip,
    ot.harga,
    pd.jumlah_orang,
    pd.nama_lengkap
FROM pembayaran py
JOIN users u
    ON py.user_id = u.id
JOIN open_trip ot
    ON py.open_trip_id = ot.id
JOIN pendaftaran pd
    ON py.pendaftaran_id = pd.id;


CREATE VIEW view_pembayaran_menunggu AS
SELECT
    COUNT(*) AS total
FROM pembayaran
WHERE status = 'menunggu';


CREATE VIEW view_total_pendapatan AS
SELECT
    COALESCE(SUM(jumlah), 0) AS total
FROM pembayaran
WHERE status = 'lunas';

CREATE VIEW view_destinasi_all AS
SELECT *
FROM destinasi;

CREATE VIEW view_destinasi_rekomendasi AS
SELECT *
FROM destinasi
ORDER BY rating DESC;


CREATE VIEW view_total_destinasi AS
SELECT
    COUNT(*) AS total
FROM destinasi;