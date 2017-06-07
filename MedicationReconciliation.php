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

        .material-icons.md-36 { font-size: 36px; }
    </style>
    <!-- CSS for AutoComplete -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Font Awesome -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
</head>
<body>
    <div class="testArea" style="border: 1px solid #000;">
        <form style="padding: 10px;" action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>" method="get">
            <label>Medication</label>
            <input type="text" spellcheck="true" id="drugName" name="drugName" required="required">
<!--            Removed to test for just 2 Fields-->
<!--            <label>Dosage</label>-->
<!--            <input type="number" id="drugDosage" name="drugDosage" required="required">-->
<!--            <label>Unit(s)</label>-->
<!--            <select id="drugUnit" name="drugUnit" required="required">-->
<!--                <option value="N/A">N/A</option>-->
<!--                <option value="cc">cc</option>-->
<!--                <option value="milligrams">mg</option>-->
<!--                <option value="micrograms">mcg</option>-->
<!--                <option value="unit">unit</option>-->
<!--                <option value="capsule">capsule</option>-->
<!--                <option value="tablet">tablet</option>-->
<!--            </select>-->
            <label>Description</label>
            <input type="text" spellcheck="true" id="drugNote" name="drugNote" required="required">
            <button class="submit" type="submit" name="submit">Submit</button>
        </form>
    </div>
    <div class="medList">
        <table id="medTable">
            <tr>
                <th>Medication</th>
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
                    echo "<td><a href='$item[1]'><i class=\"material-icons md-36\" style='display: inline-block'>link</i></a></td>";
                    echo "<td>$item[2]</td>";


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
                    } else {

                        echo "<h1>This medication cannot be added.</h1>";

                        if(isset($_SESSION['medList'])) {

                            foreach ($_SESSION['medList'] as $item) {

                                printRows($item);

                                echo "<td><form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='discontinue'><button type='submit' name='discontinue' value=" . $number . ">Discontinue</button></form></td></tr>";

                                $number++;
                            }

                        }

                    }

                } else if(isset($_GET['discontinue'])) {

                    echo "<h1>This medication is now discontinued. Please press Continue to refresh the medication list.</h1>";

                    $_SESSION['discontinued'][] = $_SESSION['medList'][$_GET['discontinue']];

                    unset($_SESSION['medList'][$_GET['discontinue']]);
                    $_SESSION['medList'] = array_values($_SESSION['medList']);

                    echo "<form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='confirm'><button type='submit' name='continue' id=" . $number . "continue" ." value='-1'>Continue</button></form>";

                    foreach ($_SESSION['medList'] as $item) {

                        printRows($item);

                        echo "<td><form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='discontinue'><button type='submit' name='discontinue' value=" . $number . " disabled='disabled'>Discontinue</button></form></td></tr>";

                        $number++;

                    }

                } else if (isset($_SESSION['medList']) || isset($_SESSION['discontinued'])) {

                    foreach ($_SESSION['medList'] as $item) {

                        printRows($item);

                        echo "<td><form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='discontinue'><button type='submit' name='discontinue' value=" . $number . ">Discontinue</button></form></td></tr>";

                        $number++;

                    }

                    echo "<br><br>";
                    echo "<tr></tr>";
                    echo "<tr></tr>";
                    echo "<tr><th>Discontinued Medication</th>";
                    echo "<th>Daily Med Link</th>";
                    echo "<th>Notes</th>";
                    echo "<th>Edit</th></tr>";

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
</body>
</html>