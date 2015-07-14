<?php
namespace SparkTests\Auth\Fake;

use Spark\Auth\AbstractPermissions;

class FakePermissions extends AbstractPermissions
{

    public $perms = [];

    public function can($perm)
    {
        return in_array($perm, $this->perms);
    }

}