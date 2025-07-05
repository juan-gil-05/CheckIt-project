<?php

namespace App\Repository;

use App\Entity\User;

class UserRepository extends Repository
{
    public function createUser(User $user)
    {
        $sql = ("INSERT INTO User (nickname, email, password) VALUES (:nickname,:email, :password)");
        $query = $this->pdo->prepare($sql);
        $query->bindValue(":nickname", $user->getNickname(), $this->pdo::PARAM_STR);
        $query->bindValue(":email" , $user->getEmail( ), $this->pdo::PARAM_STR);
        $query->bindValue(":password", $user->getPassword(), $this->pdo::PARAM_STR);
        return $query->execute();
    }

    public function findOneByEmail(string $email)
    {
        $sql = ("SELECT * FROM User WHERE email = :email");
        $query = $this->pdo->prepare($sql);
        $query->bindValue(':email', $email, $this->pdo::PARAM_STR);
        $query->execute();
        $user = $query->fetch($this->pdo::FETCH_ASSOC);

        if ($user) {
            return User::createAndHydrate($user);
        } else {
            return false;
        }
    }
}
