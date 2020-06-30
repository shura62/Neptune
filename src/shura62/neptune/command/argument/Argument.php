<?php

declare(strict_types=1);

namespace shura62\neptune\command\argument;

use shura62\neptune\user\User;

interface Argument {

    public function execute(User $user, array $args);

}