<?php
// Redirect if it's not logged
if (!isset($_SESSION['usuario'])) {
    header('Location: /projects/tasku5dawes/index.php?page=login');
}

require_once(__DIR__ . '../../class/Usuari.php');

$usuari = new Usuari();
?>
<div class="row">
    <div class="col-sm-8">
        <?php
        // Mensajes de la web
        if (isset($_SESSION['message']) && $_SESSION['message'] != "") {
            if (isset($_SESSION['message_type']) && $_SESSION['message_type'] == "success") {
        ?>
                <div class="alert alert-success" role="alert">
                    <?= $_SESSION['message'] ?>
                </div>
            <?php
            } else if (isset($_SESSION['message_type']) && $_SESSION['message_type'] == "error") {
            ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_SESSION['message'] ?>
                </div>
        <?php
                $_SESSION['message'] = "";
            }
        }
        ?>
        <div class="alert alert-primary" role="alert">
            <div class="row">
                <div class="col-sm-9">
                    <h2> Usuaris</h2>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <?php
            $usuaris_array = $usuari->getUsuaris()->data;
            if (count($usuaris_array) > 0) {
            ?>
                <table id="userList" class="table table-bordered table-hover table-striped">
                    <tr>
                        <th>ID client</th>
                        <th>nom</th>
                        <th>llinatges</th>
                        <th>telefon</th>
                        <th>nom de usuari</th>
                        <th>contrasenya (SHA2)</th>
                    </tr>
                    <?php
                    // output data of each row
                    foreach ($usuaris_array as $usuari) {
                        echo "<tr><td>" . $usuari->id . "</td><td>" . $usuari->nom . "</td><td>" . $usuari->llinatges . "</td><td>" . $usuari->telefon . "</td><td>" . $usuari->username . "</td><td>" . $usuari->password . "</td>";
                    ?>
                        </tr>";
                    <?php
                    }
                    ?>
                </table>
            <?php
            } else {
                echo "0 results";
            }
            ?>
        </div>
    </div>

</div>