<?php

class PendaftaranModel
{
    public static function getAll(mysqli $conn, string $status_filter = 'semua'): array
    {
        if ($status_filter !== 'semua') {

            $stmt = $conn->prepare("
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
                    ON p.open_trip_id = ot.id
                WHERE p.status = ?
                ORDER BY p.created_at DESC
            ");

            $stmt->bind_param('s', $status_filter);
            $stmt->execute();

            $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            $stmt->close();

            return $data;
        }

        $res = $conn->query("
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
                ON p.open_trip_id = ot.id
            ORDER BY p.created_at DESC
        ");

        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    public static function getTerbaru(mysqli $conn, int $limit = 5): array
    {
        $stmt = $conn->prepare("
            SELECT *
            FROM view_pendaftaran_terbaru
            ORDER BY created_at DESC
            LIMIT ?
        ");

        $stmt->bind_param('i', $limit);

        $stmt->execute();

        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $data;
    }

    public static function getByIdAndUser(mysqli $conn, int $id, int $user_id): ?array
    {
        $stmt = $conn->prepare("
            SELECT
                p.*,
                o.nama AS nama_trip,
                o.harga,
                o.tanggal,
                py.status AS status_pembayaran
            FROM pendaftaran p
            JOIN open_trip o
                ON p.open_trip_id = o.id
            LEFT JOIN pembayaran py
                ON py.pendaftaran_id = p.id
            WHERE p.id = ?
            AND p.user_id = ?
            LIMIT 1
        ");

        $stmt->bind_param('ii', $id, $user_id);

        $stmt->execute();

        $data = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return $data ?: null;
    }

    public static function insert(mysqli $conn, array $data): int
    {
        $stmt = $conn->prepare("
            INSERT INTO pendaftaran
            (user_id, open_trip_id, nama_lengkap, no_whatsapp, jumlah_orang, catatan)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            'iissis',
            $data['user_id'],
            $data['open_trip_id'],
            $data['nama_lengkap'],
            $data['no_whatsapp'],
            $data['jumlah_orang'],
            $data['catatan']
        );

        $stmt->execute();

        $new_id = (int)$conn->insert_id;

        $stmt->close();

        return $new_id;
    }

    public static function updateStatus(
        mysqli $conn,
        int $id,
        string $status,
        string $alasan = ''
    ): bool {

        if ($status === 'ditolak') {

            $stmt = $conn->prepare("
                UPDATE pendaftaran
                SET status = 'ditolak',
                    alasan_tolak = ?
                WHERE id = ?
            ");

            $stmt->bind_param('si', $alasan, $id);

        } else {

            $stmt = $conn->prepare("
                UPDATE pendaftaran
                SET status = ?
                WHERE id = ?
            ");

            $stmt->bind_param('si', $status, $id);
        }

        $ok = $stmt->execute();

        $stmt->close();

        return $ok;
    }

    public static function countByStatus(mysqli $conn, string $status): int
	{
        $stmt = $conn->prepare("
            SELECT COUNT(*) AS total
            FROM pendaftaran
            WHERE status = ?
        ");

        $stmt->bind_param('s', $status);

        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return (int)($result['total'] ?? 0);
	}

    public static function countBulanIni(mysqli $conn): int
    {
        $res = $conn->query("
            SELECT COUNT(*) AS total
            FROM pendaftaran
            WHERE MONTH(created_at) = MONTH(NOW())
            AND YEAR(created_at) = YEAR(NOW())
        ");

        if (!$res) {
            return 0;
        }

        $data = $res->fetch_assoc();

        return (int)($data['total'] ?? 0);
    }

    public static function getByUser(mysqli $conn, int $user_id): array
    {
    $query = "
    SELECT 
    p.*,
    o.nama AS nama_trip,
    py.status AS status_pembayaran,
    py.bukti_transfer
    FROM pendaftaran p
    JOIN open_trip o
        ON p.open_trip_id = o.id
    LEFT JOIN pembayaran py
        ON py.pendaftaran_id = p.id
    WHERE p.user_id = ?
    ORDER BY p.id DESC
    ";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $user_id
    );

    mysqli_stmt_execute($stmt);

    $data = mysqli_fetch_all(
        mysqli_stmt_get_result($stmt),
        MYSQLI_ASSOC
    );

    mysqli_stmt_close($stmt);

    return $data;
    }
}
?>