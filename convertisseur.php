<?php

require "connection.php";

?>

<!DOCTYPE html>
<html>
<head>
    <title>Convertisseur de devises</title>
</head>
<body>
    <h1>Convertisseur de devises en dirhams MAD</h1>
    
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        Montant : <input type="text" name="montant" required><br><br>
        Devises :
        <select name="devises" required>
            <?php
            
            require "connection.php";
            // Check the connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Retrieve the currency options from the database
            $sql = "SELECT id, name FROM currency";
            $result = $conn->query($sql);

            // Generate the currency options in the select dropdown
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            }

            // Close the database connection
            $conn->close();
            var_dump($result)
            ?>
        </select><br><br>
        
        <input type="submit" name="convertir" value="Convertir">
    </form>
    
    <?php
    if (isset($_POST['convertir'])) {
        $montant = floatval($_POST['montant']);
        $devises_id = $_POST['devises'];
        
        require "connection.php";
        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Retrieve the conversion rate from the database
        $sql = "SELECT taux FROM currency WHERE id = '$devises_id'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $taux_de_change = $row['taux'];
            
            // Perform the conversion
            $montant_mad = $montant * $taux_de_change;
            
            echo '<h2>Résultat :</h2>';
            echo $montant . ' ' . $devises_id . ' = ' . $montant_mad . ' MAD';
        } else {
            echo 'Le taux de change pour la devise sélectionnée n\'est pas disponible.';
        }
        
        // Close the database connection
        $conn->close();
    }
    ?>
    
</body>
</html>
