<?php
/**
 * Created by PhpStorm.
 * User: Tyler
 * Date: 1/13/2018
 * Time: 9:51 PM
 */

namespace Assassins;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Exception\PropelException;
use User;

/**
 * Class DatabaseHelp Helps with complex interactions dealing with the database.
 * @package Assassins
 */
class DatabaseHelp {

    /**
     * @param array $list The list to search.
     * @param array $keys The keys to check for.
     *
     * @return bool True if all keys are present, false otherwise.
     */
    public static function verifyAllKeysPresent(array $list, array $keys): bool {
        foreach ($keys as $key)
            if (!array_key_exists($key, $list))
                return false;
        return true;
    }

    /**
     * @param string $email The email to validate.
     *
     * @return bool True if email conforms to PHP's FILTER_VALIDATE_EMAIL and at least 5 but less than 276 characters.
     */
    public static function verifyDatabaseEmail(string $email): bool {
        // Validate email: conform to PHP's FILTER_VALIDATE_EMAIL and at least 5 but less than 276 characters.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) < 5 || strlen($email) > 275) {
            return false;
        }
        return true;
    }

    /**
     * @param string $username The username to validate.
     *
     * @return bool True if username is at least 2 but less than or equal to 25 characters
     * and only contain alpha numeric characters.
     */
    public static function verifyDatabaseUsername(string $username): bool {
        // Validate username: must be at least 2 but less than or equal to 25 characters and only contain
        // alpha numeric characters.
        if (strlen($username) > 25 || strlen($username) < 2 || !ctype_alnum($username)) {
            return false;
        }
        return true;
    }

    /**
     * @param string $realName The name to validate.
     *
     * @return bool True if realname has at least 1 character and less than 255.
     */
    public static function verifyDatabaseRealName(string $realName): bool {
        // Validate a users real name: must have at least 1 character and less than 255.
        if (strlen($realName) > 255 || strlen($realName) < 1) {
            return false;
        }
        return true;
    }

    /**
     * @param string $password The password to validate.
     *
     * @return bool True if password is at least and at most 60 characters and be in PASSWORD_BCRYPT form.
     */
    public static function verifyDatabasePassword(string $password): bool {
        // Validate password must be at least and at most 60 characters and be in PASSWORD_BCRYPT form.
        if (strlen($password) != 60) {
            return false;
        }
        return true;
    }

    /**
     * @param string $verificationToken The verification token to validate.
     *
     * @return bool True if the token is 32 characters long false otherwise.
     */
    public static function verifyDatabaseVerificationToken(string $verificationToken): bool {
        if (strlen($verificationToken) != 32) {
            return false;
        }
        return true;
    }

    /**
     * @param string $name The name to validate.
     *
     * @return bool True if the name is greater than 0 and less than 33 characters false otherwise.
     */
    public static function verifyDatabaseNameField(string $name): bool {
        if (strlen($name) < 1 || strlen($name) > 32) {
            return false;
        }
        return true;
    }

    /**
     * @param string $id The id of a id field.
     *
     * @return bool True if the id is greater than -1 false otherwise.
     */
    public static function verifyDatabaseIdString(string $id): bool {
        if (filter_var($id, FILTER_VALIDATE_INT)) {
            return DatabaseHelp::verifyDatabaseId((int)$id);
        }
        return true;
    }

    /**
     * @param int $id The id of a id field.
     *
     * @return bool True if the id is greater than -1 false otherwise.
     */
    public static function verifyDatabaseId(int $id): bool {
        if ($id < 0) {
            return false;
        }
        return true;
    }

    /**
     * @param int $money The amount of money.
     *
     * @return bool True if the money is greater than -1 false otherwise.
     */
    public static function verifyDatabaseMoney(int $money): bool {
        if ($money < 0) {
            return false;
        }
        return true;
    }

    /**
     * @param string $bool The string to check.
     *
     * @return bool True if the string is a boolean false otherwise.
     */
    public static function verifyDatabaseBoolean(string $bool): bool {
        if (!filter_var($bool, FILTER_VALIDATE_BOOLEAN)) {
            return false;
        }
        return true;
    }

    /**
     * @param User $user The user object to check.
     *
     * @return bool True if the user object is not null or true false otherwise.
     */
    public static function validateUserObject(User $user): bool {
        // Check if user exists
        if ($user == null || $user->isNew()) {
            return false;
        }
        return true;
    }

    /**
     * @param \Game $game The game object to check.
     *
     * @return bool True if the game object is not null or true false otherwise.
     */
    public static function validateGameObject(\Game $game): bool {
        // Check if game exists
        if ($game == null || $game->isNew()) {
            return false;
        }
        return true;
    }

    /**
     * @param \LtsGame $ltsGame The lts game object to check.
     *
     * @return bool True if the lts game object is not null or true false otherwise.
     */
    public static function validateLtsGameObject(\LtsGame $ltsGame): bool {
        // Check if game exists
        if ($ltsGame == null || $ltsGame->isNew()) {
            return false;
        }
        return true;
    }

    /**
     * @param \Preset $preset The preset object to check.
     *
     * @return bool True if the preset object is not null or true false otherwise.
     */
    public static function validatePresetObject(\Preset $preset): bool {
        // Check if game exists
        if ($preset == null || $preset->isNew()) {
            return false;
        }
        return true;
    }

    /**
     * @param \Group $group The group object to check.
     *
     * @return bool True if the group object is not null or true false otherwise.
     */
    public static function validateGroupObject(\Group $group): bool {
        // Check if game exists
        if ($group == null || $group->isNew()) {
            return false;
        }
        return true;
    }

    /**
     * @param \Game  $game  The game to check with.
     * @param \Group $group The to check if present.
     *
     * @return bool True if game contains group false otherwise.
     */
    public static function gameContainsGroup(\Game $game, \Group $group): bool {
        // Check if game contains group
        foreach ($game->getGroups() as $gameGroup) {
            // Check against group ids
            if ($gameGroup->getId() == $group->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param \Preset $preset The preset to check with.
     * @param \Group  $group  The to check if present.
     *
     * @return bool True if game contains group false otherwise.
     */
    public static function presetContainsGroup(\Preset $preset, \Group $group): bool {
        // Check if game contains group
        foreach ($preset->getGroups() as $presetGroup) {
            // Check against group ids
            if ($presetGroup->getId() == $group->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param \Game $game The game to be converted.
     *
     * @return \LtsGame The newly converted lts game or null if error.
     */
    public static function createLtsGameFromGame(\Game $game): \LtsGame {
        // Validate game object
        if (!DatabaseHelp::validateGameObject($game)) {
            GlobalStatic::$logger->warning('Invalid game for Lts game creation.');
            return null;
        }
        try {
            $return = new \LtsGame();
            $return->setOwner($game->getOwner())
                   ->setRules($game->getRules())
                   ->setInvite($game->getInvite())
                   ->setRequestInvite($game->getRequestInvite())
                   ->setAutoPlace($game->getAutoPlace());
            $return->save();
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Lts Game creation error: ' . $e->getMessage());
            return null;
        }
        return $return;
    }

    /**
     * @param \CircuitPlayer[] $circuitPlayers Players to convert to lts.
     *
     * @return Collection The collection of new lts players.
     */
    public static function createLtsPlayersFromGamePlayers(array $circuitPlayers): Collection {
        /** @var \LtsCircuitPlayer[] $ltsCircuitPlayers */
        $ltsCircuitPlayers = array();
        // Loop through players
        foreach ($circuitPlayers as $circuitPlayer) {
            try {
                $ltsCircuitPlayer = new \LtsCircuitPlayer();
                $ltsCircuitPlayer->setActive($circuitPlayer->getActive())
                                 ->setTarget($circuitPlayer->getTarget())
                                 ->setPlayer($circuitPlayer->getPlayer())
                                 ->setMoneySpent($circuitPlayer->getMoneySpent())
                                 ->setDateAssigned($circuitPlayer->getDateAssigned())
                                 ->setDateCompleted($circuitPlayer->getDateCompleted());
                array_push($ltsCircuitPlayers, $ltsCircuitPlayer);
            } catch (PropelException $e) {
                GlobalStatic::$logger->critical('Lts game circuit player creation error: ' . $e->getMessage());
                return null;
            }
        }
        // Save players if no errors
        foreach ($ltsCircuitPlayers as $ltsCircuitPlayer) {
            try {
                $ltsCircuitPlayer->save();
            } catch (PropelException $e) {
                GlobalStatic::$logger->critical('Partial Lts game circuit player saving error: ' . $e->getMessage());
                return null;
            }
        }
        // Return result
        return new Collection($ltsCircuitPlayers);
    }

    /**
     * @param \Game    $game    The game to copy action logs.
     * @param \LtsGame $ltsGame The game to create action logs for.
     *
     * @return bool True if action logs created false otherwise.
     */
    public static function createLtsGameLogFromGame(\Game $game, \LtsGame $ltsGame): bool {
        // Validate game object
        if (!DatabaseHelp::validateGameObject($game)) {
            GlobalStatic::$logger->warning('Invalid game.');
            return false;
        }
        // Validate lts game object
        if (!DatabaseHelp::validateLtsGameObject($ltsGame)) {
            GlobalStatic::$logger->warning('Invalid lts game.');
            return false;
        }
        try {
            foreach ($game->getActionLogs() as $actionLog) {
                $ltsActionLog = new \LtsActionLog();
                $ltsActionLog->setDate($actionLog->getDate())
                             ->addLtsGame($ltsGame)
                             ->setType($actionLog->getType())
                             ->setAction($actionLog->getAction());
                $ltsActionLog->save();
            }
            GlobalStatic::$logger->info('Action log created for lts game. Id: ' . $ltsGame->getId());
            return true;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Failed to create action logs for lts game. Id: ' .
                $ltsGame->getId() . 'Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * @param string $email     of the new user.
     * @param string $username  of the new user.
     * @param string $realName  of the new user.
     * @param string $password  of the new user.
     * @param bool   $sendEmail True to send an email to the user false to not.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -7: Database error.
     *             -6: Invalid password.
     *             -5: Invalid real name.
     *             -4: Username already in use.
     *             -3: Invalid username.
     *             -2: Email address already in use.
     *             -1: Invalid email.
     *              0: User created but email failed.
     *              1: User created.
     *              2: User created and email was sent.
     */
    public static function createUser(string $email, string $username, string $realName, string $password, bool $sendEmail): int {
        // Validate email
        if (!DatabaseHelp::verifyDatabaseEmail($email)) {
            GlobalStatic::$logger->warning('Invalid email address.');
            return -1;
        }
        // Check if new email is unique
        $emailCheck = \UserQuery::create()->findOneByEmail($email);
        if ($emailCheck != null) {
            GlobalStatic::$logger->warning('Email address already in use.');
            return -2;
        }
        // Validate username
        if (!DatabaseHelp::verifyDatabaseUsername($username)) {
            GlobalStatic::$logger->warning('Invalid username.');
            return -3;
        }
        // Check if new username is unique
        $usernameCheck = \UserQuery::create()->findOneByUsername($username);
        if ($usernameCheck != null) {
            GlobalStatic::$logger->warning('Username already in use.');
            return -4;
        }
        // Validate a users real name
        if (!DatabaseHelp::verifyDatabaseRealName($realName)) {
            GlobalStatic::$logger->warning('Invalid real name.');
            return -5;
        }
        // Validate password
        $newPassword = password_hash($password, PASSWORD_BCRYPT);
        if (!DatabaseHelp::verifyDatabasePassword($newPassword)) {
            GlobalStatic::$logger->warning('Invalid password.');
            return -6;
        }
        // Attempt to Create DB user
        try {
            $user = new User();
            $user->setEmail($email)
                 ->setUsername($username)
                 ->setRealName($realName)
                 ->setPassword($newPassword)
                 ->setVerificationToken(bin2hex(random_bytes(16)));
            // Add user to db
            $user->save();
            // Log that user was created
            GlobalStatic::$logger->info("User with id '" . $user->getId() . "' was successfully created.");
            if ($sendEmail) {
                // Prepare body
                $body = file_get_contents('body.html');
                str_replace('$username', $user->getUsername(), $body);
                str_replace('$verificationLink',
                    GlobalStatic::$configModule->getBaseUrl() . '/api/verification/'
                    . $user->getId() . '/' . $user->getVerificationToken(), $body);
                // Get ready to mail
                $body = str_replace('$verificationLink',
                    'https://reallifegames.net/assassins/verification?id=' . $user->getId() . '&token=' .
                    $user->getVerificationToken(), $body);
                $body = str_replace('$username', $user->getUsername(), $body);
                $mailResult = GlobalStatic::mail($user->getEmail(), $user->getRealName(),
                    GlobalStatic::$configModule->getSmtpFromAddress(),
                    GlobalStatic::$configModule->getSmtpFromName(),
                    'Game account registration', $body);
                if ($mailResult == 0) {
                    GlobalStatic::$logger->error("Email failed to be sent to user with id '" . $user->getId() . "'.");
                    return 0;
                } else {
                    GlobalStatic::$logger->info("Email sent to user with id '" . $user->getId() . "'.");
                    return 2;
                }
            }
            return 1;
        } catch (\Exception $e) {
            GlobalStatic::$logger->critical('User creation error: ' . $e->getMessage());
            return -7;
        }
    }

    /**
     * @param User   $user              The user to verify.
     * @param string $verificationToken The token to verify the user with.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -3: Database error.
     *             -2: Verification token mismatch.
     *             -1: User object is invalid.
     *              0: User is already active.
     *              1: User is verified and set active.
     */
    public static function verifyUser(User $user, string $verificationToken) {
        // Check if user exists
        if (!DatabaseHelp::validateUserObject($user)) {
            GlobalStatic::$logger->warning('User object is invalid.');
            return -1;
        }
        // check if user is active
        if ($user->isActive()) {
            GlobalStatic::$logger->warning('User is all ready active.');
            return 0;
        }
        // Check if tokens match
        if ($user->getVerificationToken() != $verificationToken) {
            GlobalStatic::$logger->warning('User verification token mismatch. User id: ' . $user->getId());
            return -2;
        }
        // Attempt to set user fields and save
        try {
            $user->setActive(true);
            if ($user->getVerificationTime() == '0000-00-00 00:00:00') {
                $user->setVerificationTime(new DateTimeImmutable('now', new DateTimeZone(GlobalStatic::$configModule->getTimeZoneOffset())));
            } else {
                GlobalStatic::$logger->warning('User is all ready verified.');
            }
            $user->save();
            GlobalStatic::$logger->info("User with id '" . $user->getId() . "' was successfully verified.");
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('User verification error: ' . $e->getMessage());
            return -3;
        }
        return 1;
    }

    /**
     * @param User   $user  The user to update.
     * @param string $email The email to set.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -4: Error saving user object.
     *             -3: Email address already in use.
     *             -2: Invalid email address.
     *             -1: User object is invalid.
     *              1: Success.
     */
    public static function updateUserEmail(User $user, string $email): int {
        // Check if user is valid
        if (!DatabaseHelp::validateUserObject($user)) {
            GlobalStatic::$logger->warning('User object is invalid.');
            return -1;
        }
        // Validate email
        if (!DatabaseHelp::verifyDatabaseEmail($email)) {
            GlobalStatic::$logger->warning('Invalid email address.');
            return -2;
        }
        // Check if new email is unique
        $emailCheck = \UserQuery::create()->findOneByEmail($email);
        if ($emailCheck != null) {
            GlobalStatic::$logger->warning('Email address already in use.');
            return -3;
        }
        // Set email and save
        try {
            $user->setEmail($email);
            $user->save();
            GlobalStatic::$logger->info('Updated user email. For user Id: ' . $user->getId());
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Error saving user object. Error: ' . $e->getMessage());
            return -4;
        }
    }

    /**
     * @param User   $user     The user to update.
     * @param string $username The username to set.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -4: Error saving user object.
     *             -3: Username already in use.
     *             -2: Invalid username.
     *             -1: User object is invalid.
     *              1: Success.
     */
    public static function updateUserUsername(User $user, string $username): int {
        // Check if user is valid
        if (!DatabaseHelp::validateUserObject($user)) {
            GlobalStatic::$logger->warning('User object is invalid.');
            return -1;
        }
        // Validate username
        if (!DatabaseHelp::verifyDatabaseUsername($username)) {
            GlobalStatic::$logger->warning('Invalid username.');
            return -2;
        }
        // Check if new username is unique
        $usernameCheck = \UserQuery::create()->findOneByUsername($username);
        if ($usernameCheck != null) {
            GlobalStatic::$logger->warning('Username already in use.');
            return -3;
        }
        // Set username and save user
        try {
            $user->setUsername($username);
            $user->save();
            GlobalStatic::$logger->info('Updated user username. For user Id: ' . $user->getId());
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Error saving user object. Error: ' . $e->getMessage());
            return -4;
        }
    }

    /**
     * @param User   $user     The user to update.
     * @param string $realName The real name to set.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -3: Error saving user object.
     *             -2: Invalid real name.
     *             -1: User object is invalid.
     *              1: Success.
     */
    public static function updateUserRealname(User $user, string $realName): int {
        // Check if user is valid
        if (!DatabaseHelp::validateUserObject($user)) {
            GlobalStatic::$logger->warning('User object is invalid.');
            return -1;
        }
        // Validate real name
        if (!DatabaseHelp::verifyDatabaseRealName($realName)) {
            GlobalStatic::$logger->warning('Invalid real name.');
            return -2;
        }
        // Set real name and save user
        try {
            $user->setRealName($realName);
            $user->save();
            GlobalStatic::$logger->info('Updated user name for user with Id: ' . $user->getId());
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Error saving user object. Error: ' . $e->getMessage());
            return -3;
        }
    }

    /**
     * @param User   $user     The user to update.
     * @param string $password The password to use.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -3: Error saving user object.
     *             -2: Invalid password.
     *             -1: User object is invalid.
     *              1: Success.
     */
    public static function updateUserPassword(User $user, string $password): int {
        // Check if user is valid
        if (!DatabaseHelp::validateUserObject($user)) {
            GlobalStatic::$logger->warning('User object is invalid.');
            return -1;
        }
        // Create users new password
        $newPassword = password_hash($password, PASSWORD_BCRYPT);
        // Validate password
        if (!DatabaseHelp::verifyDatabasePassword($newPassword)) {
            GlobalStatic::$logger->warning('Invalid password.');
            return -2;
        }
        // Set password and save user
        try {
            $user->setPassword($newPassword);
            $user->save();
            GlobalStatic::$logger->info('Updated password for user with Id: ' . $user->getId());
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Error saving user object. Error: ' . $e->getMessage());
            return -3;
        }
    }

    /**
     * @param User $user  The user to update.
     * @param int  $money The money to add.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -3: Error saving user object.
     *             -2: Invalid amount of money.
     *             -1: User object is invalid.
     *              1: Success.
     */
    public static function addMoneyToUser(User $user, int $money): int {
        // Check if user is valid
        if (!DatabaseHelp::validateUserObject($user)) {
            GlobalStatic::$logger->warning('User object is invalid.');
            return -1;
        }
        // Check money value
        if (!DatabaseHelp::verifyDatabaseMoney($money)) {
            GlobalStatic::$logger->warning('Invalid amount of money.');
            return -2;
        }
        // Add money to user and save
        try {
            $user->setTotalMoney($user->getTotalMoney() + $money);
            $user->setMoney($user->getMoney() + $money);
            $user->save();
            GlobalStatic::$logger->info('Added money to user with Id: ' . $user->getId());
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Error saving user object. Error: ' . $e->getMessage());
            return -3;
        }
    }

    /**
     * @param User $user           The user to update.
     * @param int  $money          The money to remove.
     * @param bool $allowUnderflow If true user can have negative money on false error code.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -4: Error saving user object.
     *             -3: User would have negative money.
     *             -2: Invalid amount of money.
     *             -1: User object is invalid.
     *              1: Success.
     */
    public static function removeMoneyFromUser(User $user, int $money, bool $allowUnderflow = false): int {
        // Check if user is valid
        if (!DatabaseHelp::validateUserObject($user)) {
            GlobalStatic::$logger->warning('User object is invalid.');
            return -1;
        }
        // Check money value
        if (!DatabaseHelp::verifyDatabaseMoney($money)) {
            GlobalStatic::$logger->warning('Invalid amount of money.');
            return -2;
        }
        // Check new balance
        $newBalance = $user->getMoney() - $money;
        if ($newBalance < 0 && !$allowUnderflow) {
            GlobalStatic::$logger->warning('User would have negative money.');
            return -3;
        }
        // Add money to user and save
        try {
            $user->setMoney($newBalance);
            $user->save();
            GlobalStatic::$logger->info('Added money to user with Id: ' . $user->getId());
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Error saving user object. Error: ' . $e->getMessage());
            return -4;
        }
    }

    /**
     * @param User $user  The user to update.
     * @param int  $money The money to add.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -3: Error saving user object.
     *             -2: Invalid amount of money.
     *             -1: User object is invalid.
     *              1: Success.
     */
    public static function setUserMoney(User $user, int $money): int {
        // Check if user is valid
        if (!DatabaseHelp::validateUserObject($user)) {
            GlobalStatic::$logger->warning('User object is invalid.');
            return -1;
        }
        // Check money value
        if (!DatabaseHelp::verifyDatabaseMoney($money)) {
            GlobalStatic::$logger->warning('Invalid amount of money.');
            return -2;
        }
        // Add money to user and save
        try {
            $user->setMoney($money);
            $user->save();
            GlobalStatic::$logger->info('Added money to user with Id: ' . $user->getId());
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Error saving user object. Error: ' . $e->getMessage());
            return -3;
        }
    }

    /**
     * @param User   $user              The user to update.
     * @param string $verificationToken The new token to use.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -3: Error saving user object.
     *             -2: Invalid verification token.
     *             -1: User object is invalid.
     *              1: Success.
     */
    public static function updateUserVerificationToken(User $user, string $verificationToken): int {
        // Check if user is valid
        if (!DatabaseHelp::validateUserObject($user)) {
            GlobalStatic::$logger->warning('User object is invalid.');
            return -1;
        }
        // Validate token
        if (!DatabaseHelp::verifyDatabaseVerificationToken($verificationToken)) {
            GlobalStatic::$logger->warning('Invalid verification token.');
            return -2;
        }
        // Set verification token and save user
        try {
            $user->setVerificationToken($verificationToken);
            $user->save();
            GlobalStatic::$logger->info('Verification token updated for user with Id: ' . $user->getId());
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Error saving user object. Error: ' . $e->getMessage());
            return -3;
        }
    }

    /**
     * @param User $user   The user to update.
     * @param bool $active The active status to set.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -5: Error saving user object.
     *             -4: User game removal error.
     *             -3: Failure to create lts game.
     *             -2: Unlinked circuit player.
     *             -1: User object is invalid.
     *              1: Success.
     */
    public static function setUserActive(User $user, bool $active): int {
        // Check if user is valid
        if (!DatabaseHelp::validateUserObject($user)) {
            GlobalStatic::$logger->warning('User object is invalid.');
            return -1;
        }
        // If user is being de-activated
        if ($user->getActive() && $active == false) {
            try {
                // Remove user from any games if present
                foreach ($user->getGames() as $game) {
                    // Remove users game groups if any
                    foreach ($game->getPlayerGroups() as $playerGroup) {
                        if ($playerGroup->getPlayerId() == $user->getId()) {
                            // Auto propagate on delete
                            $playerGroup->delete();
                        }
                    }
                    // fix game targets of game is started.
                    if ($game->isStarted()) {
                        // Get players assassin
                        $assassin = \CircuitPlayerQuery::create()
                                                       ->filterByGame($game)
                                                       ->filterByActive(true)
                                                       ->findOneByTargetId($user->getId());
                        // If assassin is null then player is dead
                        if ($assassin != null) {
                            // Get player and their target
                            $player = \CircuitPlayerQuery::create()
                                                         ->filterByGame($game)
                                                         ->filterByActive(true)
                                                         ->findOneByPlayerId($user->getId());
                            if ($player != null) {
                                // Disable both circuit players
                                $player->setActive(false)->save();
                                $assassin->setActive(false)->save();
                                // Add new circuit player
                                $newAssassin = new \CircuitPlayer();
                                $newAssassin->setPlayer($assassin->getPlayer())
                                            ->setTarget($player->getTarget())
                                            ->setGame($game);
                                $newAssassin->save();
                            } else {
                                GlobalStatic::$logger->error('Unlinked circuit player. This should not happen. CircuitPlayer Id: ' . $assassin->getId());
                                return -2;
                            }
                        }
                    }
                    // Check if user is owner and move game to lts
                    if ($user->getId() == $game->getOwner()->getId()) {
                        // Result from removed game
                        $result = DatabaseHelp::deleteGame($game);
                        if ($result != 1) {
                            GlobalStatic::$logger->critical('Failure to create lts game.');
                            return -3;
                        }
                    }
                    // Remove user from game
                    $game->removeUser($user);
                    $game->save();
                }
            } catch (PropelException $e) {
                GlobalStatic::$logger->critical('User game removal error: ' . $e->getMessage());
                return -4;
            }
        }
        // Set users active status and save
        try {
            $user->setActive($active);
            $user->save();
            GlobalStatic::$logger->info('Updated user active status for user with Id: ' . $user->getId());
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Error saving user object. Error: ' . $e->getMessage());
            return -5;
        }
    }

    /**
     * @param User    $user   The user to create a new game for.
     * @param string  $name   The name of the game.
     * @param \Preset $preset The preset to use or null to not use.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -4: Database error.
     *             -3: Invalid preset object.
     *             -2: Invalid name.
     *             -1: User object is invalid.
     *              1: Game added to said user.
     */
    public static function userCreateGame(User $user, string $name, \Preset $preset = null): int {
        // Check if user is valid
        if (!DatabaseHelp::validateUserObject($user)) {
            GlobalStatic::$logger->warning('User object is invalid.');
            return -1;
        }
        // Check if name is valid
        if (!DatabaseHelp::verifyDatabaseNameField($name)) {
            GlobalStatic::$logger->warning('Invalid name.');
            return -2;
        }
        // Create game
        try {
            $game = new \Game();
            $game->setOwner($user);
            $game->setName($name);
            // Check if preset is valid
            if ($preset != null) {
                // Check preset object
                if (!DatabaseHelp::validatePresetObject($preset)) {
                    GlobalStatic::$logger->warning('Invalid preset.');
                    return -3;
                }
                // Set preset fields
                $settings = $preset->getSetting();
                // Add preset groups
                $game->setGroups($preset->getGroups());
                $game->setRules($preset->getRules())
                    // Set settings fields
                     ->setInvite($settings->getInvite())
                     ->setRequestInvite($settings->getRequestInvite())
                     ->setAutoJoinGroup($settings->getAutoJoinGroup())
                     ->setAutoPlace($settings->getAutoPlace())
                     ->setDuplicateGameOnComplete($settings->getDuplicateGameOnComplete());
            } else {
                // Create new base group
                $baseGroup = new \Group();
                $baseGroup->setName("Auto Join Group")->setRank(0);
                $baseGroup->save();
                // Add group to game
                $game->addGroup($baseGroup);
                $game->setAutoJoinGroup($baseGroup);
            }
            $game->save();
            // add game log
            $gameLog = new \ActionLog();
            $gameLog->addGame($game)
                    ->setType(GlobalStatic::$createGame)
                    ->setAction('Game created.');
            $gameLog->save();
            // Add game to user
            $user->addGame($game);
            $user->save();
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Game creation error: ' . $e->getMessage());
            return -4;
        }
        GlobalStatic::$logger->info('New game added to user: ' . $user->getId());
        return 1;
    }

    /**
     * @param \Game $game The game to delete.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -5: Failure to delete game.
     *             -4: Could not copy game logs.
     *             -3: Failure to save Lts game.
     *             -2: Failure to create Lts game.
     *             -1: Invalid game object.
     *              1: Success.
     */
    public static function deleteGame(\Game $game): int {
        // Validate game object
        if (!DatabaseHelp::validateGameObject($game)) {
            GlobalStatic::$logger->warning('Invalid game object.');
            return -1;
        }
        // Convert game to lts game
        $ltsGame = DatabaseHelp::createLtsGameFromGame($game);
        // Check lts game
        if ($ltsGame == null) {
            GlobalStatic::$logger->warning('Failure to create Lts game.');
            return -2;
        }
        // Convert circuit to lts circuit
        try {
            $ltsCircuitPlayers = DatabaseHelp::createLtsPlayersFromGamePlayers($game->getCircuitPlayers());
            if ($ltsCircuitPlayers != null) {
                $ltsGame->setLtsCircuitPlayers($ltsCircuitPlayers);
            }
            // Save game
            $ltsGame->save();
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Failure to save Lts game. ' . $e->getMessage());
            return -2;
        }
        // Delete old game
        try {
            // Delete unlinked groups
            foreach ($game->getGroups() as $group) {
                $presets = \PresetQuery::create()->filterByGroup($group)->find();
                if (empty($presets)) {
                    $group->delete();
                }
            }
            // Move over game logs
            if (!DatabaseHelp::createLtsGameLogFromGame($game, $ltsGame)) {
                GlobalStatic::$logger->warning('Could not copy game logs.');
                return -4;
            }
            // Added lts game log about move to lts
            $gameMovedLog = new \LtsActionLog();
            $gameMovedLog->addLtsGame($ltsGame)
                         ->setType(GlobalStatic::$deleteGame)
                         ->setAction('Game deleted. ( Moved to LTS )');
            $gameMovedLog->save();
            // Delete game
            $game->delete();
            GlobalStatic::$logger->info('Game deleted.');
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Failure to delete game. Id: ' . $game->getId() .
                'Error: ' . $e->getMessage());
            return -5;
        }
    }

    /**
     * @param \Game $game  The game to updated.
     * @param User  $owner The new owner to set.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -3: Failure to set game owner.
     *             -2: Invalid user object.
     *             -1: Invalid game object.
     *              1: Success.
     */
    public static function updateGameOwner(\Game $game, User $owner): int {
        // Validate game object
        if (!DatabaseHelp::validateGameObject($game)) {
            GlobalStatic::$logger->warning('Invalid game object.');
            return -1;
        }
        // Check owner object
        if (!DatabaseHelp::validateUserObject($owner)) {
            GlobalStatic::$logger->warning('Invalid user object.');
            return -2;
        }
        // Set the owner
        try {
            $oldOwner = $game->getOwner();
            $game->setOwner($owner);
            // add game log
            $actionLog = new \ActionLog();
            $actionLog->addGame($game)
                      ->setType(GlobalStatic::$updateGame)
                      ->setAction('Game owner changed from (' . $oldOwner->getUsername() . ') to (' .
                          $owner->getUsername() . ')');
            $actionLog->save();
            GlobalStatic::$logger->info('Game \'' . $game->getId() . '\' owner updated.');
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Failure to set game owner. ' . $e->getMessage());
            return -3;
        }
    }

    /**
     * @param \Game  $game The game to update.
     * @param string $name The name to set.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -3: Failure to updated game name.
     *             -2: Invalid name.
     *             -1: Invalid game object.
     *              1: Success.
     */
    public static function updateGameName(\Game $game, string $name): int {
        // Validate game object
        if (!DatabaseHelp::validateGameObject($game)) {
            GlobalStatic::$logger->warning('Invalid game object.');
            return -1;
        }
        // Check if name is valid
        if (!DatabaseHelp::verifyDatabaseNameField($name)) {
            GlobalStatic::$logger->warning('Invalid name.');
            return -2;
        }
        // Set game name and save
        try {
            $oldGameName = $game->getName();
            $game->setName($name);
            $game->save();
            // add game log
            $actionLog = new \ActionLog();
            $actionLog->addGame($game)
                      ->setType(GlobalStatic::$updateGame)
                      ->setAction('Game name changed from (' . $oldGameName . ') to (' .
                          $name . ')');
            $actionLog->save();
            GlobalStatic::$logger->info('Game \'' . $game->getId() . '\' name updated.');
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Failure to updated game name. ' . $e->getMessage());
            return -3;
        }
    }

    /**
     * @param \Game $game    The game to update
     * @param bool  $started To start or stop a game.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -5: Failure to save game.
     *             -4: Failure to save circuit players.
     *             -3: Failure to create circuit players.
     *             -2: Failure to save disabled circuit players.
     *             -1: Invalid game object.
     *              1: Success.
     */
    public static function setGameStarted(\Game $game, bool $started): int {
        // Validate game object
        if (!DatabaseHelp::validateGameObject($game)) {
            GlobalStatic::$logger->warning('Invalid game object.');
            return -1;
        }
        // Stop game
        if (!$started) {
            // disable old circuit players
            try {
                foreach ($game->getCircuitPlayers() as $circuitPlayer) {
                    if ($circuitPlayer->isActive()) {
                        $circuitPlayer->setActive(false);
                        $circuitPlayer->save();
                    }
                }
            } catch (PropelException $e) {
                GlobalStatic::$logger->critical('Failure to save disabled circuit players. ' . $e->getMessage());
                return -2;
            }
        } else if ($started) {
            // Create circuit player loop
            $players = $game->getUsers();
            // shuffle players
            shuffle($players);
            /** @var \CircuitPlayer[] $circuitPlayers */
            $circuitPlayers = array();
            // Create circuit players
            try {
                for ($i = 0; $i < count($players) - 1; $i++) {
                    $circuitPlayer = new \CircuitPlayer();
                    $circuitPlayer->setPlayer($players[$i])
                                  ->setTarget($players[$i + 1]);
                    // Add new players to array
                    array_push($circuitPlayers, $circuitPlayer);
                }
            } catch (PropelException $e) {
                GlobalStatic::$logger->critical('Failure to create circuit players. ' . $e->getMessage());
                return -3;
            }
            // Save circuit players
            try {
                foreach ($circuitPlayers as $circuitPlayer) {
                    $circuitPlayer->save();
                }
            } catch (PropelException $e) {
                GlobalStatic::$logger->critical('Failure to save circuit players. ' . $e->getMessage());
                return -4;
            }
            // Add players to game
            foreach ($circuitPlayers as $circuitPlayer) {
                $game->addCircuitPlayer($circuitPlayer);
            }
        }
        // save game
        try {
            // Save
            $game->save();
            // add game log
            $actionLog = new \ActionLog();
            $actionLog->addGame($game)
                      ->setType(GlobalStatic::$updateGame)
                      ->setAction('Game started.');
            $actionLog->save();
            GlobalStatic::$logger->info('Game \'' . $game->getId() . '\' \'started\' state set to ' . $started);
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Failure to save game. ' . $e->getMessage());
            return -5;
        }
    }

    /**
     * @param \Game  $game           The game to update.
     * @param \Group $autoJoinGroup  The group to set as the new auto join.
     * @param bool   $addGroupToGame If game does not own group do you want to add it?
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -5: Failure to save game.
     *             -4: Group does not belong to game.
     *             -3: Failure to add game log.
     *             -2: Invalid group.
     *             -1: Invalid game object.
     *              1: Success.
     */
    public static function updateGameAutoJoinGroup(\Game $game, \Group $autoJoinGroup, bool $addGroupToGame = true): int {
        // Validate game object
        if (!DatabaseHelp::validateGameObject($game)) {
            GlobalStatic::$logger->warning('Invalid game object.');
            return -1;
        }
        // Check group
        if (!DatabaseHelp::validateGroupObject($autoJoinGroup)) {
            GlobalStatic::$logger->warning('Invalid group.');
            return -2;
        }
        //Check if game has group
        if (!DatabaseHelp::gameContainsGroup($game, $autoJoinGroup)) {
            // Check to see if we can add group to game
            if ($addGroupToGame) {
                $game->addGroup($autoJoinGroup);
                // add game log
                try {
                    $actionLog = new \ActionLog();
                    $actionLog->addGame($game)
                              ->setType(GlobalStatic::$addGroup)
                              ->setAction('Group (' . $autoJoinGroup->getName() . ') added to game.');
                    $actionLog->save();
                } catch (PropelException $e) {
                    GlobalStatic::$logger->critical('Failure to add game log. ' . $e->getMessage());
                    return -3;
                }
                GlobalStatic::$logger->info('Group \'' . $autoJoinGroup->getId()
                    . '\' added to game \'' . $game->getId() . '\'');
            } else {
                GlobalStatic::$logger->warning('Group \'' . $autoJoinGroup->getId()
                    . '\' does not belong to game \'' . $game->getId() . '\'');
                return -4;
            }
        }
        // Make sure game group belongs to game
        try {
            // Set group id
            $game->setAutoJoinGroup($autoJoinGroup);
            $game->save();
            // add game log
            $actionLog = new \ActionLog();
            $actionLog->addGame($game)
                      ->setType(GlobalStatic::$updateGame)
                      ->setAction('Auto join group set to (' . $autoJoinGroup->getName() . ').');
            $actionLog->save();
            GlobalStatic::$logger->info('Auto join group for game updated. Id: ' . $game->getId());
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Failure to save game. ' . $e->getMessage());
            return -5;
        }
    }

    /**
     * @param \Game   $game   The game to update.
     * @param \Preset $preset The preset to update the game with.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -4: Failure to save game.
     *             -3: Unable to get preset information.
     *             -2: Invalid preset object.
     *             -1: Invalid game object.
     *              1: Success.
     */
    public static function updateGameWithPreset(\Game $game, \Preset $preset): int {
        // Validate game object
        if (!DatabaseHelp::validateGameObject($game)) {
            GlobalStatic::$logger->warning('Invalid game object.');
            return -1;
        }
        // Validate preset object
        if (!DatabaseHelp::validatePresetObject($preset)) {
            GlobalStatic::$logger->warning('Invalid preset object.');
            return -2;
        }
        // Try to get settings from preset
        try {
            // Set preset fields
            $settings = $preset->getSetting();
            // Add preset groups
            $game->setGroups($preset->getGroups());
            $game->setRules($preset->getRules())
                // Set settings fields
                 ->setInvite($settings->getInvite())
                 ->setRequestInvite($settings->getRequestInvite())
                 ->setAutoJoinGroup($settings->getAutoJoinGroup())
                 ->setAutoPlace($settings->getAutoPlace())
                 ->setDuplicateGameOnComplete($settings->getDuplicateGameOnComplete());
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Unable to get preset \'' . $preset->getId() . '\' information. '
                . $e->getMessage());
            return -3;
        }
        // Save game.
        try {
            $game->save();
            // add game log
            $actionLog = new \ActionLog();
            $actionLog->addGame($game)
                      ->setType(GlobalStatic::$updateGame)
                      ->setAction('Game updated with preset (' . $preset->getName() . ').');
            $actionLog->save();
            GlobalStatic::$logger->info('Updated game \'' . $game->getId() . '\' with preset \'' . $preset->getId() . '\'');
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Failure to save game \'' . $game->getId() . '\'. ' . $e->getMessage());
            return -4;
        }
    }

    /**
     * @param User  $user The user to add.
     * @param \Game $game The game to add the user to.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -6: Failure to save game.
     *             -5: Auto add user to game error.
     *             -4: Null circuit player.
     *             -3: Game group save error.
     *             -2: Invalid game object.
     *             -1: User object is invalid
     *              1: Success.
     */
    public static function addUserToGame(User $user, \Game $game): int {
        // Check if user is valid
        if (!DatabaseHelp::validateUserObject($user)) {
            GlobalStatic::$logger->warning('User object is invalid.');
            return -1;
        }
        // Validate game object
        if (!DatabaseHelp::validateGameObject($game)) {
            GlobalStatic::$logger->warning('Invalid game object.');
            return -2;
        }
        // Add user to game
        $game->addUser($user);
        // Assign user group
        try {
            $playerGroup = new \PlayerGroup();
            $playerGroup->setUser($user)
                        ->setGroup($game->getAutoJoinGroup())
                        ->addGame($game);
            $playerGroup->save();
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Game group save error. ' . $e->getMessage());
            return -3;
        }
        // Add user to circuit if game is active
        if ($game->isStarted()) {
            // Only add if game has auto place on
            if ($game->isAutoPlace()) {
                // Get players assassin
                try {
                    $assassin = \CircuitPlayerQuery::create()
                                                   ->filterByGame($game)
                                                   ->filterByActive(true)
                                                   ->orderByDateAssigned(Criteria::DESC)
                                                   ->findOne();
                    if ($assassin != null) {
                        // Disable circuit player
                        $assassin->setActive(false)->save();
                        // fix current player
                        $newAssassin = new \CircuitPlayer();
                        $newAssassin->setPlayer($assassin->getPlayer())
                                    ->setTarget($user)
                                    ->setGame($game);
                        $newAssassin->save();
                        // Add player
                        $newPlayer = new \CircuitPlayer();
                        $newPlayer->setPlayer($user)
                                  ->setTarget($assassin->getTarget())
                                  ->setGame($game);
                        $newPlayer->save();
                    } else {
                        GlobalStatic::$logger->error('Null circuit player. Game Id: ' . $game->getId());
                        return -4;
                    }
                } catch (PropelException $e) {
                    GlobalStatic::$logger->critical('Auto add user to game error. ' . $e->getMessage());
                    return -5;
                }
            }
        }
        // Save game
        try {
            $game->save();
            // add game log
            $actionLog = new \ActionLog();
            $actionLog->addGame($game)
                      ->setType(GlobalStatic::$addUser)
                      ->setAction('User (' . $user->getUsername() . ') added to game.');
            $actionLog->save();
            GlobalStatic::$logger->info('Add user \'' . $user->getId() . '\' to game \'' . $game->getId() . '\'.');
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Failure to save game \'' . $game->getId() . '\'.' . $e->getMessage());
            return -6;
        }
    }

    /**
     * @param User  $user The user to add.
     * @param \Game $game The game to add the user to.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -4: Database error on removing user from game.
     *             -3: Failure to create lts game.
     *             -2: Invalid game object.
     *             -1: Invalid user object.
     *              1: Success.
     */
    public static function removeUserFromGame(User $user, \Game $game): int {
        // Check if user is valid
        if (!DatabaseHelp::validateUserObject($user)) {
            GlobalStatic::$logger->warning('User object is invalid.');
            return -1;
        }
        // Validate game object
        if (!DatabaseHelp::validateGameObject($game)) {
            GlobalStatic::$logger->warning('Invalid game object.');
            return -2;
        }
        // Check if user is owner and move game to lts
        // else fix game targets of game is started.
        try {
            if ($user->getId() == $game->getOwner()->getId()) {
                // Result from removed game
                $result = DatabaseHelp::deleteGame($game);
                if ($result != 1) {
                    GlobalStatic::$logger->error('Failure to create lts game.');
                    return -3;
                }
                GlobalStatic::$logger->info('Lts game created because owner was removed.');
            } else {
                // Get players assassin
                $assassin = \CircuitPlayerQuery::create()
                                               ->filterByGame($game)
                                               ->filterByActive(true)
                                               ->findOneByTargetId($user->getId());
                if ($assassin != null) {
                    // Get player and their target
                    $player = \CircuitPlayerQuery::create()
                                                 ->filterByGame($game)
                                                 ->filterByActive(true)
                                                 ->findOneByPlayerId($user->getId());
                    if ($player != null) {
                        // Disable both circuit players
                        $player->setActive(false)->save();
                        $assassin->setActive(false)->save();
                        // Add new circuit player
                        $newAssassin = new \CircuitPlayer();
                        $newAssassin->setPlayer($assassin->getPlayer())
                                    ->setTarget($player->getTarget())
                                    ->setGame($game);
                        $newAssassin->save();
                    } else {
                        GlobalStatic::$logger->error('Unlinked circuit player. This should not happen. 
                            CircuitPlayer Id: ' . $assassin->getId());
                        return -4;
                    }
                }
                // Remove users game groups
                foreach ($game->getPlayerGroups() as $playerGroup) {
                    if ($playerGroup->getPlayerId() == $user->getId()) {
                        $playerGroup->delete();
                    }
                }
                // Remove user from game
                $user->removeGame($game);
                $user->save();
                $game->save();
                GlobalStatic::$logger->info('User removed from game.');
                // add game log
                $actionLog = new \ActionLog();
                $actionLog->addGame($game)
                          ->setType(GlobalStatic::$removeUser)
                          ->setAction('User (' . $user->getUsername() . ') removed from game.');
                $actionLog->save();
            }
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Failed to remove user from game. ' . $e->getMessage());
            return -4;
        }
        // Success
        return 1;
    }

    /**
     * @param User   $user
     * @param string $name
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -7: Error added preset to user.
     *             -6: Error saving preset or setting.
     *             -5: Error adding group or auto join group.
     *             -4: Error creating and saving new group.
     *             -3: Error setting new setting to new group.
     *             -2: Invalid name.
     *             -1: User object is invalid.
     *              1: Success.
     */
    public static function createPreset(User $user, string $name): int {
        // Check if user is valid
        if (!DatabaseHelp::validateUserObject($user)) {
            GlobalStatic::$logger->warning('User object is invalid.');
            return -1;
        }
        // Check if name is valid
        if (!DatabaseHelp::verifyDatabaseNameField($name)) {
            GlobalStatic::$logger->error('Invalid name.');
            return -2;
        }
        // Create preset and settings
        $preset = new \Preset();
        $preset->setName($name);
        $settings = new \Setting();
        // Setting and base group to preset
        try {
            $preset->setSetting($settings);
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Error setting new setting to new group. ' . $e->getMessage());
            return -3;
        }
        // Create Groups
        try {
            $baseGroup = new \Group();
            $baseGroup->setName("Auto Join Group")->setRank(0);
            $baseGroup->save();
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Error creating and saving new group. ' . $e->getMessage());
            return -4;
        }
        // Add group and auto join group
        try {
            $preset->addGroup($baseGroup);
            $preset->getSetting()->setAutoJoinGroup($baseGroup);
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Error adding group or auto join group. ' . $e->getMessage());
            return -5;
        }
        // Save preset and setting
        try {
            $preset->save();
            $settings->save();
            GlobalStatic::$logger->info('Preset created \'' . $preset->getId() . '\'.');
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Error saving preset or setting. ' . $e->getMessage());
            return -6;
        }
        // Add preset to user
        try {
            $user->addPreset($preset);
            $user->save();
            GlobalStatic::$logger->info('Preset \'' . $preset->getId() . '\' added to user \''
                . $user->getId() . '\'.');
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Error added preset to user. ' . $e->getMessage());
            return -7;
        }
    }

    /**
     * @param \Preset $preset The preset to update.
     * @param string  $name   The new name to set.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -3: Failure to updated preset name.
     *             -2: Invalid name.
     *             -1: Invalid preset.
     *              1: Success.
     */
    public static function updatePresetName(\Preset $preset, string $name): int {
        // Check preset object
        if (!DatabaseHelp::validatePresetObject($preset)) {
            GlobalStatic::$logger->warning('Invalid preset.');
            return -1;
        }
        // Check if name is valid
        if (!DatabaseHelp::verifyDatabaseNameField($name)) {
            GlobalStatic::$logger->warning('Invalid name.');
            return -2;
        }
        // Set preset name and save
        try {
            $preset->setName($name);
            $preset->save();
            GlobalStatic::$logger->info('Preset \'' . $preset->getId() . '\' name updated.');
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Failure to updated preset name. ' . $e->getMessage());
            return -3;
        }
    }

    /**
     * @param \Preset $preset         The preset to update.
     * @param \Group  $autoJoinGroup  The group to set set.
     * @param bool    $addGroupToGame If game does not own group do you want to add it?
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -4: Failure to save preset.
     *             -3: Group does not belong to game.
     *             -2: Invalid group.
     *             -1: Invalid preset.
     *              1: Success.
     */
    public static function updatePresetAutoJoinGroup(\Preset $preset, \Group $autoJoinGroup, bool $addGroupToGame = true): int {
        // Check preset object
        if (!DatabaseHelp::validatePresetObject($preset)) {
            GlobalStatic::$logger->warning('Invalid preset.');
            return -1;
        }
        // Check group
        if (!DatabaseHelp::validateGroupObject($autoJoinGroup)) {
            GlobalStatic::$logger->warning('Invalid group.');
            return -2;
        }
        //Check if preset has group
        if (!DatabaseHelp::presetContainsGroup($preset, $autoJoinGroup)) {
            // Check to see if we can add group to game
            if ($addGroupToGame) {
                $preset->addGroup($autoJoinGroup);
                GlobalStatic::$logger->info('Group \'' . $autoJoinGroup->getId()
                    . '\' added to game \'' . $preset->getId() . '\'');
            } else {
                GlobalStatic::$logger->warning('Group \'' . $autoJoinGroup->getId()
                    . '\' does not belong to game \'' . $preset->getId() . '\'');
                return -3;
            }
        }
        // Make sure preset group belongs to game
        try {
            // Set group id
            $preset->getSetting()->setAutoJoinGroup($autoJoinGroup);
            $preset->save();
            $preset->getSetting()->save();
            GlobalStatic::$logger->info('Auto join group for preset updated. Id: ' . $preset->getId());
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Failure to save preset. ' . $e->getMessage());
            return -4;
        }
    }

    /**
     * @param \Preset $preset The preset to delete.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -2: Failure to delete group or preset.
     *             -1: Invalid preset.
     *              1: Success.
     */
    public static function deletePreset(\Preset $preset): int {
        // Check preset object
        if (!DatabaseHelp::validatePresetObject($preset)) {
            GlobalStatic::$logger->warning('Invalid preset.');
            return -1;
        }
        // Delete any unlinked groups and delete preset
        try {
            // Delete unlinked groups
            foreach ($preset->getGroups() as $group) {
                $games = \GameQuery::create()->filterByGroup($group)->find();
                if (empty($games)) {
                    $group->delete();
                }
            }
            // Delete preset
            $preset->delete();
            GlobalStatic::$logger->info('Preset deleted.');
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Failure to delete group or preset. ' . $e->getMessage());
            return -2;
        }
    }

    /**
     * @param string $name       The name for the new group.
     * @param int    $permission The permissions to use.
     * @param int    $rank       The rank of the group.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -2: Failure to create and save group.
     *             -1: Invalid name.
     *              1: Success.
     */
    public static function createGroup(string $name, int $permission = 0, int $rank = 0): int {
        // Check if name is valid
        if (!DatabaseHelp::verifyDatabaseNameField($name)) {
            GlobalStatic::$logger->warning('Invalid name.');
            return -1;
        }
        // Create ans save group
        try {
            $group = new \Group();
            $group->setName($name)
                  ->setPermissions($permission)
                  ->setRank($rank);
            $group->save();
            GlobalStatic::$logger->info('Group created.');
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Failure to create and save group. ' . $e->getMessage());
            return -2;
        }
    }

    /**
     * @param \Group $group The group to update.
     * @param string $name  The new name to set.
     *
     * @return int Negative numbers for failures, 0 for partial success, and positive number for success.
     *             -3: Failure to updated group name.
     *             -2: Invalid name.
     *             -1: Invalid group.
     *              1: Success.
     */
    public static function updateGroupName(\Group $group, string $name): int {
        // Check group object
        if (!DatabaseHelp::validateGroupObject($group)) {
            GlobalStatic::$logger->warning('Invalid group.');
            return -1;
        }
        // Check if name is valid
        if (!DatabaseHelp::verifyDatabaseNameField($name)) {
            GlobalStatic::$logger->warning('Invalid name.');
            return -2;
        }
        // Set preset name and save
        try {
            $group->setName($name);
            $group->save();
            GlobalStatic::$logger->info('Group \'' . $group->getId() . '\' name updated.');
            return 1;
        } catch (PropelException $e) {
            GlobalStatic::$logger->critical('Failure to updated group name. ' . $e->getMessage());
            return -3;
        }
    }
}