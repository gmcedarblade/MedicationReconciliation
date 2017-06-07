<?php

session_start();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Drug Adding with Table</title>
    <meta charset="utf-8">
    <style>
        th {
            padding: 25px;
            border: 1px solid #243;

        }
        td {
            padding: 25px;
            border: 1px solid #243;
        }

        .material-icons.md-36 {
            font-size: 36px;
        }

        #medicationEnterArea {
            border:2px solid black;
        }

        #discontinuedMedList {

            background-color: #e5e5e5;
            color: #8f8f8f;

        }

        #discontinuedMedList button {
            color: #8f8f8f;
        }

        #activeMedList i {
            text-align: justify;
        }
    </style>
    <link href="https://www.wisc-online.com/ARISE_Files/CSS/AriseMainCSS.css?random=wer" rel="stylesheet">
    <!-- CSS for AutoComplete -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Font Awesome -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
</head>
<body>
    <table id="patientInfoTable">
        <tr>
            <th>Patient Name</th>
            <th>DOB</th>
            <th>MR#</th>
        </tr>
        <tr>
            <td><span id="ptntNameOutput"></span></td>
            <td><span id="ptntDOBOutput"></span></td>
            <td><span id="ptntMROutput"></span></td>
        </tr>
        <tr>
            <th>Allergies</th>
            <th>Height(cm)</th>
            <th>Admission Weight(kg)</th>
        </tr>
        <tr>
            <td><span id="ptntAllergyOutput"></span></td>
            <td><span id="ptntHeightOutput"></span></td>
            <td><span id="ptntWeightOutput"></span></td>
        </tr>
    </table>
    <br>
    <br>
    <div class="medicationEnterArea" id="medicationEnterArea">
        <form style="padding: 10px;" action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>" method="get">
            <label>Medication</label>
            <input type="text" spellcheck="true" id="drugName" name="drugName" required="required">
            <label>Notes</label>
            <input type="text" spellcheck="true" id="drugNote" name="drugNote" required="required">
            <button class="submit" type="submit" name="submit" style="float: inherit;">Submit</button>
        </form>
    </div>
    <br>
    <br>
    <div class="medList">
        <table id="activeMedList">
            <tr>
                <th>Current Medication</th>
                <th>Daily Med Link</th>
                <th>Notes</th>
                <th>Edit</th>
                <?php
                echo "</tr>\n";

                global $number;
                $number = 0;

                $ourDrugs = array(
                        "Aspirin PO 5MG"=>"https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=7d1950b4-3237-4512-bab3-4c7364bdd618",
                    "Metropolol PO 2MG"=>"https://www.google.com",
                    "Day Quill"=>"https://vicks.com/en-us");

                function printRows($item) {

                    echo "<tr><td>$item[0]</td>";
                    echo "<td><a href='$item[1]'><i class=\"material-icons md-36\" style='float: none;'>link</i></a></td>";
                    echo "<td>$item[2]</td>";


                }

                function discontinuedTable() {

                    echo "</table><br><br><table id='discontinuedMedList'><tr><th>Discontinued Medication</th>";
                    echo "<th>Daily Med Link</th>";
                    echo "<th>Notes</th>";
                    echo "<th>Edit</th></tr>";

                }

                if (isset($_GET['submit'])) {

                    $drug = $_GET['drugName'];

                    $note = $_GET['drugNote'];

                    if (array_key_exists($drug, $ourDrugs)) {

                        $_SESSION['medList'][] = array($drug, $ourDrugs[$drug], $note);

                        foreach ($_SESSION['medList'] as $item) {

                            printRows($item);

                            echo "<td><form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='discontinue'><button type='submit' name='discontinue' value=" . $number . ">Discontinue</button></form></td></tr>";

                            $number++;

                        }

                        if (isset($_SESSION['discontinued'])) {

                            discontinuedTable();

                            foreach ($_SESSION['discontinued'] as $value) {

                                printRows($value);

                                echo "<td><form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='discontinue'><button type='submit' name='discontinue' value=" . $number . " disabled='disabled'>Discontinue</button></form></td></tr>";

                                $number++;

                            }

                        }
                    } else {

                        echo "<h1>This medication cannot be added.</h1>";

                        if(isset($_SESSION['medList'])) {

                            foreach ($_SESSION['medList'] as $item) {

                                printRows($item);

                                echo "<td><form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='discontinue'><button type='submit' name='discontinue' value=" . $number . ">Discontinue</button></form></td></tr>";

                                $number++;
                            }

                            if (isset($_SESSION['discontinued'])) {

                                discontinuedTable();

                                foreach ($_SESSION['discontinued'] as $value) {

                                    printRows($value);

                                    echo "<td><form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='discontinue'><button type='submit' name='discontinue' value=" . $number . " disabled='disabled'>Discontinue</button></form></td></tr>";

                                    $number++;

                                }

                            }

                        }

                    }

                } else if(isset($_GET['discontinue'])) {

                    $_SESSION['discontinued'][] = $_SESSION['medList'][$_GET['discontinue']];

                    unset($_SESSION['medList'][$_GET['discontinue']]);
                    $_SESSION['medList'] = array_values($_SESSION['medList']);

                    foreach ($_SESSION['medList'] as $item) {

                        printRows($item);

                        echo "<td><form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='discontinue'><button type='submit' name='discontinue' value=" . $number . " >Discontinue</button></form></td></tr>";

                        $number++;

                    }

                    discontinuedTable();

                    foreach ($_SESSION['discontinued'] as $value) {

                        printRows($value);

                        echo "<td><form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='discontinue'><button type='submit' name='discontinue' value=" . $number . " disabled='disabled'>Discontinue</button></form></td></tr>";

                        $number++;

                    }

                } else if (isset($_SESSION['medList']) || isset($_SESSION['discontinued'])) {

                    foreach ($_SESSION['medList'] as $item) {

                        printRows($item);

                        echo "<td><form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='discontinue'><button type='submit' name='discontinue' value=" . $number . ">Discontinue</button></form></td></tr>";

                        $number++;

                    }

                    discontinuedTable();

                    foreach ($_SESSION['discontinued'] as $value) {

                        printRows($value);

                        echo "<td><form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='discontinue'><button type='submit' name='discontinue' value=" . $number . " disabled='disabled'>Discontinue</button></form></td></tr>";

                        $number++;

                    }

                }

                ?>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        /*
         Input names for the drugs in the source to have them be
         included on the AutoComplete results when user types in
         the drug name on the form.
         */
        $('#drugName').autocomplete({
            source: [ 'Aspirin PO 5MG', 'Methylprednisolone', 'Advair', 'Fentanyl', 'Flonase', 'Zyrtec', 'Day Quill', 'Metropolol PO 2MG']
        });

    </script>
    <script type="text/javascript" src="https://www.wisc-online.com/ARISE_Files/JS/PatientInfo/HectorFernandezInfo.js"></script>
    <script type="text/javascript" src="https://www.wisc-online.com/ARISE_Files/JS/ptntInfoInclude.js"></script>
</body>
</html>