<?php

namespace App\Model;

use App\Core\DB;

class Image
{
    // all images
    public function getAllImages()
    {
        $sql = "SELECT user_image.id, user_image.filename, user.username, user.email FROM user_image
                INNER JOIN user ON user_image.user_id = user.id
                ORDER BY user.username ASC";

        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute();

        $listOfImages = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $listOfImages;
    }
    // number of images
    public function getNumberOfImages()
    {
        //$sql = "SELECT * FROM user_image";
        $sql = "SELECT COUNT(*) as count FROM user_image";
        $stmt = DB::getInstance()->prepare($sql);

        $stmt->execute();
        //$data = $stmt->fetch(\PDO::FETCH_ASSOC);

        $rowCount = $stmt->fetchAll();

        return (int) $rowCount[0]['count'];
        //return $stmt->rowCount();
    }


    // image upload
    public function imageUpload($user_id, $filename)
    {
        $sql = "INSERT INTO user_image (filename, user_id) VALUES (:filename, :user_id)";
        //$sql = "INSERT INTO user_image (filename, user_id) VALUES (SELECT id FROM user WHERE username = '$username')"

        $stmt = DB::getInstance()->prepare($sql);

        $stmt->bindParam(':filename', $filename);
        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();

    }
    // remove image
    public function removeImage($id)
    {
        //$sql = "DELETE id, filename FROM user_image WHERE id = '$id'";
        //$sql = "DELETE FROM user_image WHERE id = :id AND filename = :filename";
        $sql = "DELETE FROM user_image WHERE id = :id";

        $stmt = DB::getInstance()->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }

    public function getImage($id)
    {
        $sql = "SELECT id, filename FROM user_image WHERE id = :id";

        $stmt = DB::getInstance()->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        $image = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $image;
    }

}