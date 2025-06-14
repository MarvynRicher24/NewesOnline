<?php
// tests/Models/AnnouncementCommentTest.php
namespace Tests\Models;

use Tests\BaseTestCase;
use App\Announcement;
use App\Comment;
use App\Subscriber as SubscriberModel;

class AnnouncementCommentTest extends BaseTestCase
{
    public function testAnnouncementCRUD(): void
    {
        $ann = new \Announcement($this->pdo);
        $ann->create('Title','Sub','Content',1);

        $all = $ann->getAll();
        $this->assertCount(1, $all);
    }

    public function testCommentLifecycle(): void
    {
        // Prepare subscriber
        $subModel = new \Subscriber($this->pdo);
        $subModel->create('alice','a@b.com',password_hash('x', PASSWORD_DEFAULT));
        $sub = $subModel->findByUsername('alice');

        // Prepare announcement
        $ann = new \Announcement($this->pdo);
        $ann->create('T','S','C',1);
        $announce = $ann->getAll()[0];

        // Create and verify comment
        $comm = new \Comment($this->pdo);
        $comm->create($sub['id'], $announce['id'], 4, 'Nice');
        $fetched = $comm->findBySubscriberAndAnnouncement($sub['id'], $announce['id']);
        $this->assertEquals(4, $fetched['rating']);

        // Update
        $comm->update($fetched['id'], 5, 'Great');
        $this->assertEquals('Great', $comm->findBySubscriberAndAnnouncement($sub['id'], $announce['id'])['comment']);

        // Average
        $this->assertEquals(5.0, $ann->getAverageRating($announce['id']));

        // Delete
        $comm->delete($fetched['id']);
        $this->assertNull($ann->getAverageRating($announce['id']));
    }
}