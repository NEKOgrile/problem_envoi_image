<?php
//include('connectionbdd.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $heureDepart = $_POST['heure-depart-reelle'];
    $heureArrivee = $_POST['heure-arrivee-reelle'];
    $signature = $_POST['signature'];
    $infoSupplementaire = $_POST['info-supplementaire'];
    $photo_enfant = $_POST['photo_enfant'];
    $heure_depart_theorique = $_POST['heure-depart-theorique'];
    $heure_arrivee_theorique = $_POST['heure-arrivee-theorique'];

    $insertion_bdd = $bdd->prepare("INSERT INTO formulaire_chauffeurs (signature, photo, heure_depart_theorique_formulaire , heure_arrivee_theorique_formulaire ,heure_depart_reel_formulaire , heure_arrivee_reel_formulaire , info_supp_formulaire ) VALUES (?, ?, ?, ? , ? , ? , ?)"); //id_enfant_formulaire
    $insertion_bdd->execute(array($signature, $photo_enfant, $heure_depart_theorique, $heure_arrivee_theorique, $heureDepart, $heureArrivee, $infoSupplementaire));

}
//print_r($_POST);


?>
<!DOCTYPE html>
<html>

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <meta charset="UTF-8" />
    <title>chauffeur</title>
    <link rel="stylesheet" media="screen" type="text/css" href="problem.css" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
</head>

<body class="body_formulaire">
    <form class="formulaire_arrive" method="POST">
        <table>
            <tr>
                <td>
                    <label class="nom_element_formulaire_arrive">Nom de l'enfant :</label>
                    <input type="text" name="nom-enfant" value="<?php echo $_GET['nom']; ?>" readonly>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="nom_element_formulaire_arrive">Prénom enfant :</label>
                    <input type="text" name="prenom-enfant" value="<?php echo $_GET['prenom']; ?>" readonly>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="heure_formulaire_arrive">
                        <div>
                            <label class="nom_element_formulaire_arrive">Heure de départ :</label>
                            <input type="time" name="heure-depart-theorique"
                                value="<?php echo $_GET['heure_depart'] . ':' . $_GET['minute_depart']; ?>" readonly>
                        </div>
                        <div>
                            <label class="nom_element_formulaire_arrive">Heure d'arrivée :</label>
                            <input type="time" name="heure-arrivee-theorique"
                                value="<?php echo $_GET['heure_arrivee'] . ':' . $_GET['minute_arrivee']; ?>" readonly>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="heure_formulaire_arrive">
                        <div>
                            <label class="nom_element_formulaire_arrive">Heure de départ :</label>
                            <input type="time" name="heure-depart-reelle" />
                        </div>
                        <div>
                            <label class="nom_element_formulaire_arrive">Heure d'arrivée :</label>
                            <input type="time" name="heure-arrivee-reelle" />
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

            <input type="hidden" id="signature_id" name="signature" />


            <tr>
                <td colspan="2" style="text-align: center">
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
                            <input type="file" name="photo" id="photo" accept="image/*" />
                        </div>
                    </div>
                </td>
            </tr>

            <input type="hidden" id="photo_enfant_id" name="photo_enfant" />

            <tr>
                <td colspan="2">
                    <div class="depôt_formulaire_arrive">
                        <label class="nom_element_formulaire_arrive">Info supp sur la route :</label>
                        <div>
                            <textarea name="info-supplementaire" rows="3" style="width: 100%"></textarea>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center">
                    <button id="ok-button" type="submit">OK</button>
                </td>
            </tr>
        </table>
    </form>

</body>
<!--<script type="text/javascript" src="formulaire_arrive/formulaire_arrive.js"></script>-->
<script type="text/javascript">
    window.addEventListener("load", function () {
        var canvas = document.getElementById("signatureCanvas");
        var context = canvas.getContext("2d");
        var clearButton = document.getElementById("clearButton");
        var okButton = document.getElementById("ok-button");

        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;

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
            saveSignature();
        }

        function clearCanvas() {
            context.clearRect(0, 0, canvas.width, canvas.height);
        }

        function saveSignature() {
            const dataURL = canvas.toDataURL("image/png"); // Récupérer l'image sous forme de base64

            const inputText = document.getElementById("signature_id"); // Set your value to your input (hidden I guess)
            inputText.value = dataURL;
        }

        // Listen changes
        document
            .querySelector("#photo")
            .addEventListener("change", function (event) {
                const file = event.target.files[0];

                const reader = new FileReader();
                reader.onloadend = function () {
                    const base64Data = reader.result;
                    const inputText = document.getElementById("photo_enfant_id"); // Set your value to your input (hidden I guess)
                    inputText.value = base64Data;

                };
                reader.readAsDataURL(file);
            });

        // Event handlers
        canvas.addEventListener("mousedown", startDrawing);
        canvas.addEventListener("mousemove", draw);
        canvas.addEventListener("mouseup", stopDrawing);
        canvas.addEventListener("mouseout", stopDrawing);
        canvas.addEventListener("touchstart", startDrawing);
        canvas.addEventListener("touchmove", draw);
        canvas.addEventListener("touchend", stopDrawing);
        clearButton.addEventListener("click", clearCanvas);

    });
</script>

</html>