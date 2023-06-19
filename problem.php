<?php
//include('connectionbdd.php'); base de donné 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $heureDepart = $_POST['heure-depart-reelle'];
    $heureArrivee = $_POST['heure-arrivee-reelle'];
    $signature = $_POST['signature']; // Récupérer le contenu du canvas
    $infoSupplementaire = $_POST['info-supplementaire'];


    //$stmt = $bdd->prepare("INSERT INTO formulaire_chauffeurs (heure_depart_reel_formulaire, heure_arrivee_reel_formulaire, signature, info_supp_formulaire) VALUES (?, ?, ?, ?)");
    //$stmt->bindValue(1, $heureDepart);
    //$stmt->bindValue(2, $heureArrivee);
    //$stmt->bindValue(3, $signature);
    //$stmt->bindValue(4, $infoSupplementaire);
    //$stmt->execute();
    //$stmt->closeCursor();

}
print_r($_POST);


?>
<!DOCTYPE html>
<html>

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <meta charset="UTF-8" />
    <title>chauffeur</title>
    <link rel="stylesheet" media="screen" type="text/css" href="problem.css" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
</head>

<body class="body_formulaire">
    <form class="formulaire_arrive" method="POST">
        <table>
            <!--<tr>
                <td>
                    <label class="nom_element_formulaire_arrive">Nom de l'enfant :</label>
                    <input type="text" name="nom-enfant" value="<?php //echo $_GET['nom']; ?>" readonly>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="nom_element_formulaire_arrive">Prénom enfant :</label>
                    <input type="text" name="prenom-enfant" value="<?php //echo $_GET['prenom']; ?>" readonly>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="heure_formulaire_arrive">
                        <div>
                            <label class="nom_element_formulaire_arrive">Heure de départ :</label>
                            <input type="time" name="heure-depart-theorique"
                                value="<?php //echo $_GET['heure_depart'] . ':' . $_GET['minute_depart']; ?>" readonly>
                        </div>
                        <div>
                            <label class="nom_element_formulaire_arrive">Heure d'arrivée :</label>
                            <input type="time" name="heure-arrivee-theorique"
                                value="<?php //echo $_GET['heure_arrivee'] . ':' . $_GET['minute_arrivee']; ?>" readonly>
                        </div>
                    </div>
                </td>
            </tr>--->
            <tr>
                <td colspan="2">
                    <div class="heure_formulaire_arrive">
                        <div>
                            <label class="nom_element_formulaire_arrive">Heure de départ :</label>
                            <input type="time" name="heure-depart-reelle">
                        </div>
                        <div>
                            <label class="nom_element_formulaire_arrive">Heure d'arrivée :</label>
                            <input type="time" name="heure-arrivee-reelle">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="signature_formulaire_arrive">
                        <label class="nom_element_formulaire_arrive">Signature :</label>
                        <div>
                            <canvas name="signature" id="signatureCanvas"></canvas>
                        </div>
                    </div>
                    <button id="clearButton" type="button">Effacer</button>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <div class="text">
                        <label class="nom_element_formulaire_arrive">Et / ou</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="document_formulaire_arrive">
                        <label class="nom_element_formulaire_arrive">Photo :</label>
                        <div>
                            <input type="file" name="photo" id="photo" accept="image/*">
                        </div>
                    </div>
                </td>
            </tr>

            <input type="hidden" id="phototest" name="tests">

            <tr>
                <td colspan="2">
                    <div class="depôt_formulaire_arrive">
                        <label class="nom_element_formulaire_arrive">Info supp sur la route :</label>
                        <div>
                            <textarea name="info-supplementaire" rows="3" style="width: 100%;"></textarea>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <button id="ok-button" type="submit">OK</button>
                </td>
            </tr>
        </table>
    </form>
</body>
<!--<script type="text/javascript" src="formulaire_arrive/formulaire_arrive.js"></script>-->
<script type="text/javascript">
    window.addEventListener('load', function () {
        var canvas = document.getElementById('signatureCanvas');
        var context = canvas.getContext('2d');
        var clearButton = document.getElementById('clearButton');
        var okButton = document.getElementById('ok-button');

        // Initialisation de la taille du canvas
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;

        // Variables pour suivre les mouvements de la souris/touches
        var isDrawing = false;
        var lastX = 0;
        var lastY = 0;

        function startDrawing(e) {
            isDrawing = true;
            [lastX, lastY] = [e.offsetX, e.offsetY];
        }

        function draw(e) {
            if (!isDrawing) return;
            context.beginPath();
            context.moveTo(lastX, lastY);
            context.lineTo(e.offsetX, e.offsetY);
            context.stroke();
            [lastX, lastY] = [e.offsetX, e.offsetY];
        }

        function stopDrawing() {
            isDrawing = false;
        }

        function clearCanvas() {
            context.clearRect(0, 0, canvas.width, canvas.height);
        }

        function saveSignature() {
            var dataURL = canvas.toDataURL("image/png"); // Récupérer l'image sous forme de base64
            // Ajouter les données de la signature au formulaire
            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'signature';
            hiddenInput.value = dataURL;
            document.querySelector('.formulaire_arrive').appendChild(hiddenInput);
        }





        function savePicture() {
            var photoInput = document.getElementById("photo"); // Récupérer l'élément input de type file

            photoInput.addEventListener("change", function (event) {
                var file = event.target.files[0]; // Récupérer le fichier sélectionné

                if (file) {
                    var reader = new FileReader(); // Créer une instance de FileReader
                    reader.onload = function (e) {
                        var base64Image = e.target.result; // Récupérer la chaîne base64 de l'image
                        console.log(base64Image); // Afficher la base64 de l'image dans la console (vous pouvez faire autre chose avec cette valeur)

                        var inputetest = document.getElementById("phototest"); // Récupérer l'élément input de type file
                        inputetest.value = base64Image
                    };
                }
            });
        }




        function validateForm() {
            savePicture();
            saveSignature();
            // ...
        }


        // Gestionnaires d'événements
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);
        canvas.addEventListener('touchstart', startDrawing);
        canvas.addEventListener('touchmove', draw);
        canvas.addEventListener('touchend', stopDrawing);
        clearButton.addEventListener('click', clearCanvas);
        okButton.addEventListener('click', validateForm);
    });

</script>

</html>