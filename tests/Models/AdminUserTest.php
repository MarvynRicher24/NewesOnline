<?php
// tests/Models/AdminUserTest.php
namespace Tests\Models;

use Tests\BaseTestCase;
use App\AdminUser;

class AdminUserTest extends BaseTestCase
{
    public function testFindByUsernameReturnsAdmin(): void
    {
        $model = new \AdminUser($this->pdo);
        $admin = $model->findByUsername('marvyn');

        $this->assertIsArray($admin);
        $this->assertEquals('marvyn', $admin['username']);
    }

    public function testFindByUsernameReturnsFalseForNonExisting(): void
    {
        $model = new \AdminUser($this->pdo);
        $result = $model->findByUsername('unknown');

        $this->assertFalse($result);
    }
}