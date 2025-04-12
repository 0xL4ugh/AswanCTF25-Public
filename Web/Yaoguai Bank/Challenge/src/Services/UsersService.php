<?php
require_once __DIR__ . '/../Repositories/UsersRepository.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/SubUser.php';

class UsersService {

    public function __construct() {}

    /**
     * Calls the createSubUser function from UsersRepository to add a new sub-user.
     */
    public function createSubUser($email, $password, $name, $Owner) {
        return UsersRepository::createSubUser($email, $password, $name, $Owner);
    }

    /**
     * Calls the editSubUser function from UsersRepository to edit a sub-user's name.
     */
    public function editSubUser($id, $name,$OwnerId) {
        UsersRepository::editSubUser($id, $name);
        return UsersRepository::EnsureEditUserAuthority($id,$OwnerId);
    }

    /**
     * Calls the changeSubUserPassword function from UsersRepository to change the password.
     */
    public function changeSubUserPassword($email, $newPass) {
        $newPass = md5($newPass);
        return UsersRepository::changeSubUserPassword($email, $newPass);
    }

}
?>
