<?php 

declare(strict_types = 1); 

class Model {
    protected $db;
    
    public function __construct() {
        $this->db = new Database;
    }

    public function isRateLimited(string $table, int $maxCount, float $minutes, string $col = "user_id", 
                                  $identifier = NULL, string $timeCol = "created_at"): bool {

        $identifier = $identifier ?? $_SESSION['user_id'];
        $this->db->query("SELECT COUNT(*) AS table_row_count
                          FROM $table
                          WHERE $timeCol >= DATE_SUB(NOW(), INTERVAL '$minutes' MINUTE)
                          AND $col = :$col
                        ");

        $this->db->bind(":$col", $identifier);
        $this->db->execute();

        $row = $this->db->fetchRow();
        $count = $row->table_row_count;

        if($maxCount < $count) return true;
        return false;
    }

    public function exceedsMaxRowCount(string $table, int $maxCount, string $col = "user_id", $identifier = NULL): bool {
        $identifier = $identifier ?? $_SESSION['user_id'];
        $this->db->query("SELECT COUNT(*) AS table_row_count
                          FROM $table
                          WHERE $col = :$col
                        ");

        $this->db->bind(":$col", $identifier);
        $this->db->execute();

        $row = $this->db->fetchRow();
        $count = $row->table_row_count;

        if($maxCount < $count) return true;
        return false;
    }

    public function checkRateLimitByLastRecord(string $table, float $seconds, string $timeCol = "created_at", 
                                               string $col = "user_id", $identifier = NULL): bool {

        $identifier = $identifier ?? $_SESSION['user_id'];
        // return true if last record is earlier than interval
        $this->db->query("SELECT $timeCol AS last_time
                          FROM $table
                          WHERE $col = :$col
        ");

        $this->db->bind(":$col", $identifier);
        $this->db->execute();

        $row = $this->db->fetchRow();
        $calculatedInterval = strtotime(date(DB_TIMESTAMP_FMT)) - strtotime($row->last_time);
        
        // Is being rate limited
        if($calculatedInterval < $seconds) return true;
        return false;
    }
}