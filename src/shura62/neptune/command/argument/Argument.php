<<<<<<< HEAD
<?php

declare(strict_types=1);

namespace shura62\neptune\command\argument;

use shura62\neptune\user\User;

interface Argument {

    public function execute(User $user, array $args);

=======
<?php

declare(strict_types=1);

namespace shura62\neptune\command\argument;

use shura62\neptune\user\User;

interface Argument {
    
    public function execute(User $user, array $args);
    
>>>>>>> 41753135f6b613be18f0874b70ff0ada4d1e948d
}