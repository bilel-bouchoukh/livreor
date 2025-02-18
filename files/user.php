<?php

class User
{
    protected $db;
    protected $id;

    public function __construct(Database $db)
    {
        $this->db = $db->getPDO();

            // Si un ID d'utilisateur est stocké en session, on le récupère
            if (isset($_SESSION['id'])) {
                $this->id = $_SESSION['id'];
            }
     
    }

    public function createUser($login, $password)
    {   
        // Vérification que l'utilisateur n'existe pas en base de données
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM user WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        $usernameExists = $stmt->fetchColumn();

        if ($usernameExists > 0) {
            echo "Ce nom d'utilisateur est déjà pris.";
        } else {
            // Hashage du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO user (login, password) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(1, $login);
            $stmt->bindParam(2, $hashedPassword);

            return $stmt->execute();
        }
    }


   public function connectUser($login, $password)
   {
       // Récupérer le mot de passe en base de données pour vérifier la connexion
       $sql = "SELECT * FROM user WHERE login = :login";
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(':login', $login);
       $stmt->execute();

       if ($stmt->rowCount() > 0) {
           $user = $stmt->fetch(PDO::FETCH_ASSOC);
           // Vérification du mot de passe
           if (password_verify($password, $user['password'])) {
               // Connexion réussie : enregistrer l'ID de l'utilisateur dans la session
               $_SESSION['id'] = $user['id'];
               $_SESSION['login'] = $user['login'];
               $_SESSION['logged_in'] = true; // Flag de connexion

               // Retourner l'utilisateur connecté
               return $user;
           }
       }
       return false; // Échec de la connexion
   }

   
   public function selectUserById($id)
   {
       $query = "SELECT * FROM user WHERE id = :id";
       $stmt = $this->db->prepare($query);
       $stmt->bindParam(':id', $id, PDO::PARAM_INT);
       $stmt->execute();
  
       // Vérifie si l'utilisateur existe
       if ($stmt->rowCount() > 0) {
           return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne l'utilisateur sous forme de tableau associatif
       } else {
           return null; // Aucun utilisateur trouvé
       }
    }

  
    public function getAllUsers()
    {
        $query = "SELECT * FROM user";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Vérifie si des utilisateurs ont été trouvés
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne tous les utilisateurs sous forme de tableau associatif
        } else {
            return []; // Aucun utilisateur trouvé
        }
    }
  

     // Requête DELETE pour supprimer un utilisateur par son ID
    public function deleteUserById($id)
    {
        $query = "DELETE FROM user WHERE id = :id";
        $params = ['id' => $id];
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params); // Exécute la requête
    }


    // Mettre à jour un utilisateur
    public function updateUser($id, $new_login, $new_password)
    {   
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

        $query = "UPDATE user SET login = :login, password = :password WHERE id = :id";
        $params = [
            ':login' => $new_login,
            ':password' => $hashedPassword,
            ':id' => $id
        ];

        $stmt = $this->db->prepare($query);
        return $stmt->execute($params); // Exécute la requête
    }
}
?>
