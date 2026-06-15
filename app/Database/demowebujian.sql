-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Jun 2026 pada 03.59
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `demowebujian`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 60,
  `total_questions` int(11) NOT NULL DEFAULT 0,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `exams`
--

INSERT INTO `exams` (`id`, `title`, `description`, `duration_minutes`, `total_questions`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Matematika', 'Ujian matematika mencakup aljabar, geometri, dan aritmatika dasar', 60, 5, 'published', '2026-06-15 00:51:59', '2026-06-15 01:00:09'),
(2, 'Ipa', 'Ujian Ilmu Pengetahuan Alam mencakup fisika dan biologi', 60, 5, 'published', '2026-06-15 01:00:49', '2026-06-15 01:05:47'),
(3, 'PKN', 'Ujian Pendidikan Kewarganegaraan mencakup pancasila, UUD 1945, dan sejarah Indonesia', 60, 5, 'published', '2026-06-15 01:06:29', '2026-06-15 01:12:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `exam_registrations`
--

CREATE TABLE `exam_registrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `registered_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `exam_registrations`
--

INSERT INTO `exam_registrations` (`id`, `user_id`, `exam_id`, `registered_at`, `updated_at`) VALUES
(1, 2, 1, '2026-06-15 01:13:45', '2026-06-15 01:13:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `exam_sessions`
--

CREATE TABLE `exam_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `status` enum('ongoing','finished') NOT NULL DEFAULT 'ongoing',
  `total_time_taken` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `exam_sessions`
--

INSERT INTO `exam_sessions` (`id`, `user_id`, `exam_id`, `start_time`, `end_time`, `status`, `total_time_taken`, `updated_at`, `created_at`) VALUES
(1, 2, 1, '2026-06-15 01:13:49', '2026-06-15 02:33:49', 'finished', 7, '2026-06-15 01:21:03', '2026-06-15 08:13:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-06-10-130314', 'App\\Database\\Migrations\\CreateAllTables', 'default', 'App', 1781097891, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`options`)),
  `correct_answer` varchar(1) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `questions`
--

INSERT INTO `questions` (`id`, `exam_id`, `question_text`, `image_path`, `options`, `correct_answer`, `order`) VALUES
(1, 1, 'Untuk lulus ujian, nilai seorang siswa harus $\\geq$ 75 . Jika Budi mendapatkan nilai 70, berapakah minimal tambahan nilai yang harus diperoleh Budi agar lulus?', NULL, '{\"A\":\"3\",\"B\":\"5\",\"C\":\"7\",\"D\":\"10\"}', 'B', 1),
(2, 1, 'Ibu membeli 3 kg jeruk dan 2 kg apel dengan total harga Rp 85.000. Jika harga 1 kg jeruk adalah Rp 15.000, berapakah harga 1 kg apel?', NULL, '{\"A\":\"Rp 15.000\\t\\t\\t\",\"B\":\"Rp 18.000\",\"C\":\"Rp 20.000\",\"D\":\"Rp 25.000\"}', 'C', 2),
(3, 1, 'Sebuah bus berangkat dari Jakarta menuju Bandung dengan kecepatan rata-rata 60 km/jam. Jarak Jakarta-Bandung adalah 180 km. Berapa lama waktu yang diperlukan bus tersebut sampai di Bandung?', NULL, '{\"A\":\"2 jam\\t\\t\\t\",\"B\":\"3 jam\",\"C\":\"2,5 jam\",\"D\":\"3,5 jam\"}', 'B', 3),
(4, 1, 'Andi menabung di bank sebesar Rp 500.000 dengan bunga 6% per tahun. Berapakah jumlah tabungan Andi setelah 1 tahun?	', NULL, '{\"A\":\"Rp 510.000\\t\\t\\t\",\"B\":\"Rp 520.000\",\"C\":\"Rp 530.000\",\"D\":\"Rp 540.000\"}', 'C', 4),
(5, 1, 'Seorang pedagang memiliki 120 butir telur. Telur tersebut akan dimasukkan ke dalam kotak yang masing-masing berisi 8 butir. Berapa banyak kotak yang dibutuhkan?	', NULL, '{\"A\":\"12 kotak\\t\",\"B\":\"14 kotak\\t\",\"C\":\"15 kotak\\t\",\"D\":\"16 kotak\"}', 'C', 5),
(6, 2, 'Gaya yang menyebabkan benda jatuh ke bumi disebut?', NULL, '{\"A\":\"Gaya Magnet\\t\\t\",\"B\":\"Gaya Listrik\",\"C\":\"Gaya Gravitasi\\t\",\"D\":\"Gaya Gesek\"}', 'C', 1),
(7, 2, 'Air mendidih pada suhu berapa derajat Celcius?	', NULL, '{\"A\":\"50\\u00b0C\\t\",\"B\":\"75\\u00b0C\\t\",\"C\":\"100\\u00b0C\\t\",\"D\":\"150\\u00b0C\\t\"}', 'C', 2),
(8, 2, 'Perhatikan gambar sel tumbuhan berikut. Bagian yang ditunjuk anak panah berfungsi untuk?	', 'uploads/questions/1781485436_bbb3992bb367284caa58.png', '{\"A\":\"Pernapasan\\t\",\"B\":\"Fotosintesis\\t\",\"C\":\"Pencernaan\\t\",\"D\":\"Reproduksi\\t\"}', 'B', 3),
(9, 2, 'Perhatikan gambar rantai makanan berikut. Manakah yang berperan sebagai produsen?	', 'uploads/questions/1781485497_6601bbfed8056f38dd61.jpg', '{\"A\":\"Ular\\t\",\"B\":\"Katak\\t\",\"C\":\"Belalang\\t\",\"D\":\"Padi\\t\"}', 'D', 4),
(10, 2, 'Perhatikan gambar sistem tata surya berikut. Planet apakah yang terdekat dengan matahari?	', 'uploads/questions/1781485546_9d8ea5a78bdf561ca1a1.jpg', '{\"A\":\"Venus\\t\",\"B\":\"Merkurius\\t\",\"C\":\"Bumi\\t\",\"D\":\"Mars\\tB\"}', 'B', 5),
(11, 3, 'Siapakah yang merumuskan Pancasila sebagai dasar negara Indonesia?	', NULL, '{\"A\":\"Ir. Soekarno\\t\",\"B\":\"Moh. Hatta\\t\",\"C\":\"Sutan Syahrir\\t\",\"D\":\"Tan Malaka\\t\"}', 'A', 1),
(12, 3, 'UUD 1945 pertama kali disahkan pada tanggal?	', NULL, '{\"A\":\"17 Agustus 1945\\t\",\"B\":\"18 Agustus 1945\\t\",\"C\":\"19 Agustus 1945\\t\",\"D\":\"20 Agustus 1945\\t\"}', 'B', 2),
(13, 3, 'Perhatikan gambar lambang negara Indonesia berikut. Burung apakah yang menjadi lambang negara kita?	', 'uploads/questions/1781485768_52f8118daa1d2c8e2359.jpg', '{\"A\":\"Garuda\\t\",\"B\":\"Rajawali\\t\",\"C\":\"Merpati\\t\",\"D\":\"Elang\\t\"}', 'A', 3),
(14, 3, 'Perhatikan gambar pahlawan nasional berikut. Siapakah nama pahlawan pada gambar tersebut?	\r\n', 'uploads/questions/1781485856_3ee3f9ad2e4bfb445493.jpg', '{\"A\":\"Jenderal Sudirman\\t\",\"B\":\"Cut Nyak Dien\\t\",\"C\":\"Pangeran Diponegoro\\t\",\"D\":\"Bung Tomo\\t\"}', 'D', 4),
(15, 3, 'Apa arti dari Bhinneka Tunggal Ika?	', NULL, '{\"A\":\"Bersatu Kita Teguh\\t\",\"B\":\"Berbeda-beda Tetapi Tetap Satu\\t\",\"C\":\"Maju Tak Gentar\\t\",\"D\":\"Dari Sabang Sampai Merauke\\t\"}', 'B', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@example.com', '$2y$10$vnxV4psCxnZWFRinlTXVSu7dMOoZbnzL8cvvnV7aS490GXX4sxIKa', 'admin', '2026-06-15 00:41:43', NULL),
(2, 'Test User 1', 'user1@example.com', '$2y$10$9cer7taEY9COBWrgbTj/ceFVNnbGKWlNNh4M17goUUaJVfI7lD3D2', 'user', '2026-06-15 00:41:43', NULL),
(3, 'Test User 2', 'user2@example.com', '$2y$10$9cer7taEY9COBWrgbTj/ceFVNnbGKWlNNh4M17goUUaJVfI7lD3D2', 'user', '2026-06-15 00:41:43', NULL),
(4, 'Test User 3', 'user3@example.com', '$2y$10$9cer7taEY9COBWrgbTj/ceFVNnbGKWlNNh4M17goUUaJVfI7lD3D2', 'user', '2026-06-15 00:41:43', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_answers`
--

CREATE TABLE `user_answers` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_answer` varchar(1) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_doubtful` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_answers`
--

INSERT INTO `user_answers` (`id`, `session_id`, `question_id`, `selected_answer`, `updated_at`, `is_doubtful`) VALUES
(1, 1, 1, 'B', NULL, 0),
(2, 1, 2, 'C', NULL, 1),
(3, 1, 3, 'C', NULL, 1),
(4, 1, 4, 'C', NULL, 1),
(5, 1, 5, 'C', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `exam_registrations`
--
ALTER TABLE `exam_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_exam_id` (`user_id`,`exam_id`),
  ADD KEY `exam_registrations_exam_id_foreign` (`exam_id`);

--
-- Indeks untuk tabel `exam_sessions`
--
ALTER TABLE `exam_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_sessions_user_id_foreign` (`user_id`),
  ADD KEY `exam_sessions_exam_id_foreign` (`exam_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_exam_id_foreign` (`exam_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_id_question_id` (`session_id`,`question_id`),
  ADD KEY `user_answers_question_id_foreign` (`question_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `exam_registrations`
--
ALTER TABLE `exam_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `exam_sessions`
--
ALTER TABLE `exam_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `user_answers`
--
ALTER TABLE `user_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `exam_registrations`
--
ALTER TABLE `exam_registrations`
  ADD CONSTRAINT `exam_registrations_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_registrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `exam_sessions`
--
ALTER TABLE `exam_sessions`
  ADD CONSTRAINT `exam_sessions_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_answers`
--
ALTER TABLE `user_answers`
  ADD CONSTRAINT `user_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_answers_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `exam_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
