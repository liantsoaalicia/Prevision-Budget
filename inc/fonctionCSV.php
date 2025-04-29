<?php 

    include('connection.php');

    function importCsv(string $filename, string $tableName, string $separator = ';'):bool {
        $con = dbConnect();
        if (!file_exists($filename) || !is_readable($filename)) {
            echo "Le fichier n'existe pas ou n'est pas lisible.";
            return false;
        }

        if (($handle = fopen($filename, 'r')) === false) {
            echo "Impossible d'ouvrir le fichier CSV.";
            return false;
        }
        
        $header = fgetcsv($handle, 1000, $separator);
        if ($header === false) {
            echo "Le fichier CSV est vide ou l'en-tête est invalide.";
            fclose($handle);
            return false;
        }

        $header = array_map('trim', $header);

        // INSERT INTO client (col1, col2, col3) VALUES (:col1, :col2, :col3)
        $columns = implode(', ', $header);
        $placeholders = ':' . implode(', :', $header);
        $sql = "INSERT INTO $tableName ($columns) VALUES ($placeholders)";

        try {
            $stmt = $con->prepare($sql);
        } catch (PDOException $e) {
            echo "Erreur lors de la préparation de la requête : " . $e->getMessage();
            fclose($handle);
            return false;
        }

        while (($data = fgetcsv($handle, 1000, $separator)) !== false) {
            if (count($data) !== count($header)) {
                echo "Le nombre de valeurs ne correspond pas au nombre de colonnes.<br>";
                continue;
            }

            $data = array_map('trim', $data);
            
            $row = array_combine($header, $data);
            if ($row === false) {
                echo "Erreur lors de l'association des données avec l'en-tête.<br>";
                continue;
            }
            try {
                $stmt->execute($row);
            } catch (PDOException $e) {
                echo "Erreur lors de l'insertion : " . $e->getMessage() . "<br>";
            }
        }

        fclose($handle);
        return true;
    }

?>