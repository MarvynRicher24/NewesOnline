<?php
// tests/Models/SubscriberTest.php
namespace Tests\Models;

use Tests\BaseTestCase;
use App\Subscriber;

class SubscriberTest extends BaseTestCase
{
    public function testCreateAndFind(): void
    {
        $model = new \Subscriber($this->pdo);
        $model->create('john', 'john@example.com', password_hash('secret', PASSWORD_DEFAULT));

        $found = $model->findByUsername('john');
        $this->assertEquals('john@example.com', $found['email']);

        $byId = $model->findById($found['id']);
        $this->assertEquals('john', $byId['username']);
    }

    public function testUpdateProfile(): void
    {
        $model = new \Subscriber($this->pdo);
        $model->create('jane', 'jane@example.com', password_hash('pwd', PASSWORD_DEFAULT));
        $found = $model->findByUsername('jane');

        $model->updateProfile(
            $found['id'],
            'jane_doe',
            'jane.doe@example.com',
            password_hash('new', PASSWORD_DEFAULT)
        );

        $updated = $model->findById($found['id']);
        $this->assertEquals('jane_doe', $updated['username']);
    }
}