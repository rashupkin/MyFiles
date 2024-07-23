<?php

namespace App\Repository;

use App\Core\User\CurrentUser;
use PHPMailer\PHPMailer\PHPMailer;
use App\Core\DB\BaseRepository;
use App\Core\DB\IRepository;

class UserRepository extends BaseRepository implements IRepository
{
    public function delete(int $id): bool
    {
        $db = $this->connection::getConnection();

        $userTableName = $this->getTableName();
        $stmt = $db->pdo->prepare("DELETE FROM $userTableName WHERE id = :id");
        $stmt->execute(["id" => $id]);

        return true;
    }

    public function userUpdate(int $id, array $data): bool
    {
        $db = $this->connection::getConnection();

        // get all fields from client
        $email = $data["email"];
        $role = $data["role"];
        $password = password_hash($data["password"], PASSWORD_DEFAULT);
        $nickname = $data["nickname"];
        $age = $data["age"];

        $userTableName = $this->getTableName();
        $updateUserStmt = $db->pdo->prepare("UPDATE $userTableName SET email = :email, role = :role, password = :password, nickname = :nickname, age = :age WHERE id = :id");
        $updateUserStmt->execute([
            "id" => $id,
            "email" => $email,
            "role" => $role,
            "password" => $password,
            "nickname" => $nickname,
            "age" => $age
        ]);

        return true;
    }

    public function fullList(): array
    {
        $users = $this->findBy();

        return $users;
    }

    public function partialList(): array
    {
        $db = $this->connection::getConnection();

        $userTableName = $this->getTableName();
        $users = $db->pdo->query("SELECT nickname, age FROM $userTableName")->fetchAll(\PDO::FETCH_ASSOC);

        return $users;
    }

    public function fullGet(int $id): array
    {
        $user = $this->find($id);

        return $user;
    }

    public function partialGet(int $id): array
    {
        $db = $this->connection::getConnection();

        $userTableName = $this->getTableName();
        $user = $db->pdo->query("SELECT nickname, age FROM $userTableName WHERE id = $id;")->fetch(\PDO::FETCH_ASSOC);

        return $user;
    }

    public function update(array $data): bool
    {
        $db = $this->connection::getConnection();

        // get all fields from client
        $email = $data["email"];
        $role = $data["role"];
        $password = password_hash($data["password"], PASSWORD_DEFAULT);
        $nickname = $data["nickname"];
        $age = $data["age"];

        $userTableName = $this->getTableName();

        $currentUser = CurrentUser::getInstance();
        $user = $currentUser->getUser();

        if (!$user) {
            $insertedUserStmt = $db->pdo->prepare("INSERT INTO $userTableName (email, role, password, nickname, age) VALUES (:email, :role, :password, :nickname, :age)");
            $insertedUserStmt->execute([
                "email" => $email,
                "role" => $role,
                "password" => $password,
                "nickname" => $nickname,
                "age" => $age
            ]);

            return true;
        }

        $updateUserStmt = $db->pdo->prepare("UPDATE $userTableName SET email = :email, role = :role, password = :password, nickname = :nickname, age = :age WHERE email = :userEmail");
        $updateUserStmt->execute([
            "userEmail" => $user->getEmail(),
            "email" => $email,
            "role" => $role,
            "password" => $password,
            "nickname" => $nickname,
            "age" => $age
        ]);

        return true;
    }

    public function login(string $email, string $password): int
    {
        $db = $this->connection::getConnection();

        $userTableName = $this->getTableName();
        $currentUser = CurrentUser::getInstance();
        $user = $currentUser->getUser();

        $userStmt = $db->pdo->prepare("SELECT * FROM $userTableName WHERE email = :email;");
        $userStmt->execute(["email" => $user->getEmail()]);

        $user = $userStmt->fetch(\PDO::FETCH_ASSOC);

        password_verify($password, $user["password"]);
        return $user["id"];
    }

    public function resetPassword(): bool
    {
        $currentUser = CurrentUser::getInstance();
        $user = $currentUser->getUser();

        if (!$user)
            return false;

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = $_ENV["MAIL_SMTP"];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV["MAIL_EMAIL"];
        $mail->Password = $_ENV["MAIL_PASS"];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV["MAIL_PORT"];

        $mail->CharSet = "UTF-8";
        $mail->setFrom($_ENV["MAIL_EMAIL"], "MyFiles");
        $mail->addAddress($user->getEmail(), $user->getName());
        $mail->Subject = 'Сброс пароля';

        $placeholders = [
            'username' => $user->getName(),
            'reset_link' => 'http://127.0.0.7/users/update'
        ];
        $template = $this->getTemplate($_ENV["MAIN_DIRECTORY"] . "/" . $_ENV["TEMPLATE_FOLDER"] . "/" . "reset_password.html", $placeholders);

        $mail->isHTML(true);
        $mail->Body = $template;

        $mail->send();

        return true;
    }

    public function getTemplate(string $filePath, array $placeholders): string
    {
        $template = file_get_contents($filePath);

        foreach ($placeholders as $key => $value) {
            $template = str_replace("{{" . $key . "}}", $value, $template);
        }

        return $template;
    }
}
