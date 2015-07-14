<?php
namespace Spark\Auth;

abstract class AbstractPermissions
{

    abstract public function can($perm);

}