<?php 

declare(strict_types = 1); 

class ProfileModel extends Model {
    public function __construct() {
        $this->db = new Database;
        $this->userModel = new UserModel;
    }

    public function isFollowing(int $userId, int $profileId): bool {
        $this->db->query("SELECT id FROM followers WHERE follower_id = :follower_id AND profile_id = :profile_id");
        $this->db->bind(":follower_id", $userId);
        $this->db->bind(":profile_id", $profileId);
        $this->db->execute();

        return $this->db->rowCount() === 1;
    }

    public function followingCount(int $userId): int {
        $this->db->query("SELECT id FROM followers WHERE follower_id = :follower_id");
        $this->db->bind(":follower_id", $userId);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function followersCount(int $profileId): int {
        $this->db->query("SELECT id FROM followers WHERE profile_id = :profile_id");
        $this->db->bind(":profile_id", $profileId);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function toggleFollow(int $userId, int $profileId): bool {
        if(!$this->isFollowing($userId, $profileId)) {
            return $this->db->dbInsert("followers", [
                "follower_id" => $userId,
                "profile_id" => $profileId
            ]);
        } else {
            $this->db->query("DELETE from followers WHERE follower_id = :follower_id AND profile_id = :profile_id");
            $this->db->bind(":profile_id", $profileId);
            $this->db->bind(":follower_id", $userId);

            return $this->db->execute();
        }
    }

    public function getProfileFollowing(int $profileId, int $userId, int $limit, int $lastId = NULL) {
        $idConstraint = !is_null($lastId) ? " AND followers.id < :id " : " ";
        
        $this->db->query("SELECT followers.id, users.username, users.uniq_id,
                            users.display_name, users.profile_img
                          FROM followers
                          INNER JOIN users
                          WHERE followers.follower_id = :profile_id
                          AND users.id = followers.profile_id
                          $idConstraint
                          ORDER BY id DESC LIMIT $limit
                         ");

        $this->db->bind(":profile_id", $profileId);

        if(!is_null($lastId)) $this->db->bind(":id", $lastId);

        $this->db->execute();
        
        $rows = $this->db->fetchRows();

        foreach($rows as $row) {
            $followedProfileId = $this->userModel->getInfoByUniqId($row->uniq_id)->id;
            $row->show_btn = $userId !== $followedProfileId;
            $row->is_following = $this->isFollowing($userId, $followedProfileId);
        }

        return $rows;
    }

    public function getProfileFollowers(int $profileId, int $userId, int $limit, int $lastId = NULL) {
        $idConstraint = !is_null($lastId) ? " AND followers.id < :id " : " ";
        
        $this->db->query("SELECT followers.id, users.username, users.uniq_id,
                            users.display_name, users.profile_img
                          FROM followers
                          INNER JOIN users
                          WHERE followers.profile_id = :profile_id
                          AND users.id = followers.follower_id
                          $idConstraint
                          ORDER BY id DESC LIMIT $limit
                         ");

        $this->db->bind(":profile_id", $profileId);
        if(!is_null($lastId)) $this->db->bind(":id", $lastId);

        $this->db->execute();
        $rows = $this->db->fetchRows();
        
        foreach($rows as $row) {
            $followedProfileId = $this->userModel->getInfoByUniqId($row->uniq_id)->id;
            $row->show_btn = $userId !== $followedProfileId;
            $row->is_following = $this->isFollowing($userId, $followedProfileId);
        }

        return $rows;
    }
}