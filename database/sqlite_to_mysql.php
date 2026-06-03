<?php
/**
 * SQLite → MySQL exporter for Roberts Family ChildCare
 * Run: php database/sqlite_to_mysql.php
 * Output: database/mysql_export.sql
 */

$sqliteFile = __DIR__ . '/database.sqlite';
$outputFile = __DIR__ . '/mysql_export.sql';

if (!file_exists($sqliteFile)) {
    die("SQLite file not found: $sqliteFile\n");
}

$pdo = new PDO('sqlite:' . $sqliteFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ── Tables to export (app tables + Laravel infra tables) ──────────────────────
$APP_TABLES = [
    'Employee', 'ParentUser', 'Contact', 'ContactNote',
    'Child', 'ChildNote', 'ChildDocument', 'ChildContact', 'ChildCheckinLog',
    'Inquiry', 'InquiryNote', 'InquiryStatusHistory',
    'Message', 'StaffMember', 'StaffNote',
    'GalleryImage', 'TestimonialLink', 'Testimonial', 'SiteContent',
];

$LARAVEL_TABLES = [
    'migrations', 'sessions', 'cache', 'cache_locks',
    'jobs', 'job_batches', 'failed_jobs', 'password_reset_tokens', 'users',
];

// ── Type mapping: SQLite declared type → MySQL type ───────────────────────────
function mysqlType(string $colName, string $declaredType, bool $isPk): string
{
    $t = strtolower(trim($declaredType));
    $n = strtolower($colName);

    // Primary key UUIDs are always 32-char hex
    if ($isPk || $n === 'id') return 'varchar(32) NOT NULL';

    // Foreign key UUIDs (e.g. childId, employeeId)
    if (str_ends_with($n, 'id') && in_array($t, ['text', 'varchar', ''])) return 'varchar(32) DEFAULT NULL';

    // Explicit varchar(N) — keep size
    if (preg_match('/^varchar\s*\((\d+)\)$/i', $t, $m)) return "varchar({$m[1]})";

    // Known boolean column names stored as integer
    $boolNames = ['issnoozed','isactive','isprimary','mustchangepassword',
                  'uploadedbyparent','addedbyparent','isavailable'];
    if ($t === 'integer' && (in_array($n, $boolNames) || str_starts_with($n, 'is') || str_starts_with($n, 'has') || str_starts_with($n, 'must'))) {
        return 'tinyint(1)';
    }

    // Integers
    if (in_array($t, ['integer', 'int', 'bigint'])) return 'int';
    if ($t === 'tinyint' || $t === 'tinyint(1)') return 'tinyint(1)';

    // Floats
    if (in_array($t, ['real', 'float', 'double', 'numeric'])) return 'double';

    // Datetimes
    if (in_array($t, ['datetime', 'timestamp'])) return 'datetime DEFAULT NULL';
    if ($t === 'date') return 'date DEFAULT NULL';

    // Blob
    if ($t === 'blob') return 'longblob';

    // Text columns — use meaningful sizes based on name
    if (in_array($t, ['text', ''])) {
        if (str_ends_with($n, 'email') || $n === 'email')                return 'varchar(255) DEFAULT NULL';
        if (str_ends_with($n, 'phone'))                                   return 'varchar(30) DEFAULT NULL';
        if (str_ends_with($n, 'url') || str_ends_with($n, 'photo'))      return 'varchar(500) DEFAULT NULL';
        if ($n === 'token')                                                return 'varchar(64) NOT NULL';
        if (str_ends_with($n, 'name') && !in_array($n, ['firstname','lastname'])) return 'varchar(255) DEFAULT NULL';
        if (in_array($n, ['firstname','lastname','childname']))            return 'varchar(100) DEFAULT NULL';
        if (in_array($n, ['role','status','action','senderrole','oldsystem','newsystem','oldstatus','newstatus'])) return 'varchar(50) DEFAULT NULL';
        if ($n === 'relationship')                                         return 'varchar(100) DEFAULT NULL';
        if (in_array($n, ['key', 'value']))                               return 'text';
        // Long content: notes, messages, schedules, etc.
        return 'longtext DEFAULT NULL';
    }

    // Fallback
    return 'longtext DEFAULT NULL';
}

// ── Escape a value for MySQL INSERT ─────────────────────────────────────────
function escVal(mixed $v): string
{
    if ($v === null) return 'NULL';
    // Numeric integers — no quotes
    if (is_int($v)) return (string) $v;
    $s = (string) $v;
    // Escape backslash, single quote, NUL, newline, carriage return, ctrl-Z
    $s = str_replace(
        ['\\',  "'",  "\0", "\n",  "\r",  "\x1a"],
        ['\\\\', "\\'", '\\0', '\\n', '\\r', '\\Z'],
        $s
    );
    return "'{$s}'";
}

// ── Build CREATE TABLE (MySQL) from PRAGMA table_info ───────────────────────
function buildCreate(PDO $pdo, string $table): string
{
    $cols = $pdo->query("PRAGMA table_info(\"$table\")")->fetchAll(PDO::FETCH_ASSOC);
    if (empty($cols)) return '';

    // Find primary key column(s)
    $pkCols = array_filter($cols, fn($c) => $c['pk'] > 0);
    $singlePk = count($pkCols) === 1 ? array_values($pkCols)[0]['name'] : null;

    // MySQL bareword defaults that must not be quoted
    $unquotedDefaults = ['CURRENT_TIMESTAMP', 'NOW()', 'NULL', '0', '1'];

    $lines = [];
    foreach ($cols as $col) {
        $isPk = $col['pk'] > 0 && $singlePk !== null;
        $baseType = mysqlType($col['name'], $col['type'], $isPk);

        // Extract any embedded NOT NULL / DEFAULT NULL decoration from the type helper
        $notNull    = str_contains($baseType, 'NOT NULL');
        $defaultNull = str_contains($baseType, 'DEFAULT NULL');
        // Strip them — we'll reattach carefully below
        $cleanType = str_replace([' NOT NULL', ' DEFAULT NULL'], '', $baseType);

        // Determine notnull from schema
        $required = $notNull || (bool) $col['notnull'];

        // Determine default from schema (overrides embedded DEFAULT NULL)
        $dflt = $col['dflt_value'];
        $dfltClause = '';
        if ($dflt !== null && $dflt !== '') {
            $stripped = trim($dflt, "'\"");
            $upper = strtoupper($stripped);
            if (in_array($upper, $unquotedDefaults) || is_numeric($stripped)) {
                $dfltClause = " DEFAULT {$stripped}";
            } else {
                $dfltClause = " DEFAULT '" . addslashes($stripped) . "'";
            }
        } elseif ($defaultNull && !$required) {
            $dfltClause = ' DEFAULT NULL';
        }

        $nullClause = $required ? ' NOT NULL' : '';

        $lines[] = "  `{$col['name']}` {$cleanType}{$nullClause}{$dfltClause}";
    }

    // Primary key constraint
    if ($singlePk) {
        $lines[] = "  PRIMARY KEY (`{$singlePk}`)";
    } elseif (count($pkCols) > 1) {
        $pkNames = array_map(fn($c) => "`{$c['name']}`", array_values($pkCols));
        $lines[] = '  PRIMARY KEY (' . implode(', ', $pkNames) . ')';
    }

    $body = implode(",\n", $lines);
    return "CREATE TABLE `{$table}` (\n{$body}\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
}

// ── Main export ──────────────────────────────────────────────────────────────
$allTables = [...$APP_TABLES, ...$LARAVEL_TABLES];

// Filter to only tables that actually exist in the SQLite db
$existingTables = $pdo->query(
    "SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'"
)->fetchAll(PDO::FETCH_COLUMN);

$toExport = array_filter($allTables, fn($t) => in_array($t, $existingTables));

$out = fopen($outputFile, 'w');

fwrite($out, "-- Roberts Family ChildCare — MySQL Export\n");
fwrite($out, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
fwrite($out, "-- Source: SQLite → MySQL converter\n");
fwrite($out, "-- Import via phpMyAdmin: select your database, click Import, choose this file.\n\n");

fwrite($out, "SET FOREIGN_KEY_CHECKS=0;\n");
fwrite($out, "SET NAMES utf8mb4;\n");
fwrite($out, "SET time_zone='+00:00';\n\n");

foreach ($toExport as $table) {
    echo "Exporting: $table ... ";

    $ddl = buildCreate($pdo, $table);
    if (!$ddl) {
        echo "SKIPPED (no columns)\n";
        continue;
    }

    fwrite($out, "-- --------------------------------------------------------\n");
    fwrite($out, "-- Table: `{$table}`\n");
    fwrite($out, "-- --------------------------------------------------------\n");
    fwrite($out, "DROP TABLE IF EXISTS `{$table}`;\n");
    fwrite($out, $ddl . ";\n\n");

    // Rows
    $rows = $pdo->query("SELECT * FROM \"$table\"")->fetchAll(PDO::FETCH_ASSOC);
    $count = count($rows);

    if ($count > 0) {
        $cols = array_keys($rows[0]);
        $colList = implode(', ', array_map(fn($c) => "`$c`", $cols));
        fwrite($out, "INSERT INTO `{$table}` ({$colList}) VALUES\n");

        $chunks = array_chunk($rows, 500); // 500 rows per INSERT for phpMyAdmin safety
        foreach ($chunks as $ci => $chunk) {
            $valueRows = [];
            foreach ($chunk as $row) {
                $valueRows[] = '(' . implode(', ', array_map('escVal', array_values($row))) . ')';
            }
            // Last chunk of last INSERT gets semicolon; intermediate chunks get comma + new INSERT
            if ($ci < count($chunks) - 1) {
                fwrite($out, implode(",\n", $valueRows) . ";\n");
                fwrite($out, "INSERT INTO `{$table}` ({$colList}) VALUES\n");
            } else {
                fwrite($out, implode(",\n", $valueRows) . ";\n");
            }
        }
        fwrite($out, "\n");
    }

    echo "{$count} rows\n";
}

fwrite($out, "SET FOREIGN_KEY_CHECKS=1;\n");
fclose($out);

echo "\nDone! Output: $outputFile\n";
echo "File size: " . number_format(filesize($outputFile) / 1024, 1) . " KB\n";
