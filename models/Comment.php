<?php

class Comment
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Collect comments and notes from the subscriber
    public function findBySubscriberAndAnnouncement($subId, $annId)
    {
        $sql = "SELECT *
                FROM comments
                WHERE subscriber_id = :subId
                AND announcement_id = :annId
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'subId' => $subId,
            'annId' => $annId
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new comment + note
    public function create($subId, $annId, $rating, $commentText)
    {
        $sql = "INSERT INTO comments
                    (subscriber_id, anouncement_id, rating, comment)
                VALUES
                    (:subId, :annId, :rating, :comment)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'subId'   => $subId,
            'annId'   => $annId,
            'rating'  => $rating,
            'comment' => $commentText
        ]);
    }

    // Update comment + note
    public function update($id, $rating, $commentText)
    {
        $sql = "UPDATE comments
                    SET rating = :rating,
                        comment = :comment,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'rating'  => $rating,
            'comment' => $commentText,
            'id'      => $id
        ]);
    }

    // Delete comment + note
    public function delete($id)
    {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Collect list of comments for the announcement
    public function getAllByAnnouncement($annId)
    {
        $sql = "SELECT c.id,
                       c.subscriber_id,
                       c.rating,
                       c.comment,
                       c.created_at,
                       s.username
                FROM comments c
                LEFT JOIN subscriber s ON c.subscriber_id = s.id
                WHERE c.announcement_id = :annId
                ORDER BY c.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['annId' => $annId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //AVERAGE CALCUL
    public function getAverageRating($annId)
    {
        $sql = "SELECT AVG(rating) AS avg_rating
                FROM comments
                WHERE announcement_id = :annId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['annId' => $annId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && $row['avg_rating'] !== null ? round((float)$row['avg_rating'], 1) : null;
    }
}