<?php

session_start();

?>
<!--Medication Reconciliation HFMA1-->
<!DOCTYPE html>
<html>
<head>
    <title>Medication Reconciliation</title>
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

        /*
        This is to increase the size of the link icon,
        IDE says that it is never used but it is, if
        removed the icon will shrink.
        */
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
            background-color: #cfd0d0;
            border-color: #8f8f8f;
        }

        #activeMedList i {
            text-align: justify;
        }

        #activeMedList th {
            width: 25%;
        }

        #activeMedList th {
            width: 25%;
        }

        #activeMedList td {
            width: 25%;
        }

        #discontinuedMedList td {
            width: 25%;
        }

        #linkTD {
            text-align: center;
        }

        #btnBegin {
            background: #fafafa;
            box-shadow: none;
            border-radius: 0;
            border-color: #dad6d3;
            border-width: 1px 0 0 0;
            color: #000;
            display:block;
            font-family: Helvetica Neue, Helvetica , Arial, sans-serif;
            font-size: 24px;
            font-weight: 200;
            margin: 0;
            position: absolute;
            bottom: -16px;
            right: -9px;
            text-align: right;
            width: 2000px;
            height: 60px;
        }

        .continueArrow {
            color: #106e9d;
            font-weight: bold;
        }

    </style>
    <link href="https://www.wisc-online.com/ARISE_Files/CSS/AriseMainCSS.css?random=wer" rel="stylesheet">
    <!-- CSS for AutoComplete -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- CSS for Google Material Icons -->
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

                /*
                 * Add new drugs that are allowed to be added to the table here
                 * as the drug name and it's dosage as the key and the URL to
                 * the Daily Med website for it as its value.
                 */

                $ourDrugs = array(
                    "Aspirin 81 mg PO"=>"https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=9829f56f-723c-473f-89ee-4cb2efb3b8bc",
                    "Furosemide 40 mg PO"=>"https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=a15d7845-5827-481d-ba3f-e4883489a5ef",
                    "Lisinopril 10 mg PO"=>"https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=27ccb2f4-abf8-4825-9b05-0bb367b4ac07",
                    "Metoprolol Succinate ER 25 mg PO"=>"https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=88f1abdc-f53e-4ff9-b4c1-3cfbc2609b79",
                    "Atorvastatin 40 mg PO"=>"https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=6ccdb6f3-22c7-5b48-46bc-ce4a4c65eb4d",
                    "Digoxin 125 mcg PO"=>"https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=52373737-f92c-43e7-b1c1-7b87f7b415cf",
                    "Acetaminophen 500 mg PO"=>"https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=168da31e-de62-4280-9c66-2b41d2d93c31",
                    "Insulin, Regular"=>"https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=9ec3e28a-cea9-4e45-9057-e5bf8e37014c",
                    "Insulin, Lantus"=>"https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=6328c99d-d75f-43ef-b19e-7e71f91e57f6");

                /*
                 * This is used for pre-populating medications if needed per
                 * scenario. Uncomment if need to use and update appropriately.
                 */

//                if(!isset($_SESSION['medList'])) {
//
//                    /*
//                     * Add each medication needed for pre-population as shown below,
//                     * make sure the medication is added to the $ourDrugs array
//                     * and source for the auto-complete if not there already.
//                     */
//
//                    $_SESSION['medList'][] = array("Aspirin 81 mg PO", $ourDrugs['Aspirin 81 mg PO'], "One tab daily");
//                    $_SESSION['medList'][] = array("Lisinopril 10 mg PO", $ourDrugs['Lisinopril 10 mg PO'], "One tab daily");
//
//                }

                /*
                 * Prints out the medication name/dosage, Daily Med link,
                 * and notes that were entered in when called
                 */
                function printRows($item) {

                    echo "<tr><td>$item[0]</td>";
                    echo "<td id='linkTD'><a href='$item[1]'><i class=\"material-icons md-36\" style='float: none;'>link</i></a></td>";
                    echo "<td>$item[2]</td>";


                }

                /*
                 * Start of the discontinue table to be called when
                 * there are discontinued medications to be displayed.
                 */
                function discontinuedTable() {

                    echo "</table><br><br><table id='discontinuedMedList'><tr><th>Discontinued Medication</th>";
                    echo "<th>Daily Med Link</th>";
                    echo "<th>Notes</th>";
                    echo "<th>Edit</th></tr>";

                }

                /*
                 * Check to see if the user has submitted the
                 * form to add a new medication with notes.
                 */
                if (isset($_GET['submit'])) {

                    // gets the drug name/dosage from what the user entered on form
                    $drug = $_GET['drugName'];

                    // get the notes the user has entered on form
                    $note = $_GET['drugNote'];

                    /*
                     * This checks to see if what the user entered on
                     * the form for the medication and if that medication
                     * is in our list of approved medications to be added.
                     */
                    if (array_key_exists($drug, $ourDrugs)) {

                        /*
                         * Sets the medication, daily med link, and medication note
                         * in the session "medList" so we are able to access it if
                         * the user returns to the scenario.
                         */
                        $_SESSION['medList'][] = array($drug, $ourDrugs[$drug], $note);

                        /*
                         * Iterate through the session "medList" to be able
                         * to get the values from it.
                         */
                        foreach ($_SESSION['medList'] as $item) {

                            printRows($item);

                            echo "<td><form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='discontinue'><button type='submit' name='discontinue' value=" . $number . ">Discontinue</button></form></td></tr>";

                            $number++;

                        }

                        /*
                         * Checking to see if the session "discontinued"
                         * is set and not null, if it is then we will
                         * print out the discontinue table.
                         */
                        if (isset($_SESSION['discontinued'])) {

                            discontinuedTable();

                            foreach ($_SESSION['discontinued'] as $value) {

                                printRows($value);

                                echo "<td><form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='discontinue'><button type='submit' name='discontinue' value=" . $number . " disabled='disabled'>Discontinue</button></form></td></tr>";

                                $number++;

                            }

                        }

                        /*
                         * This happens if what the user enters in a
                         * medication that is not in our approved list
                         * of medications
                         */
                    } else {


                        echo "<h1>This medication cannot be added.</h1>";

                        /*
                         * Check if the session "medList" is set and not null
                         * and then prints it out.
                         */
                        if(isset($_SESSION['medList'])) {

                            foreach ($_SESSION['medList'] as $item) {

                                printRows($item);

                                echo "<td><form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='discontinue'><button type='submit' name='discontinue' value=" . $number . ">Discontinue</button></form></td></tr>";

                                $number++;
                            }

                            /*
                             * Check if the session "discontinued" is set
                             * and not null and prints it out.
                             */
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

                    /*
                     * Checks if the user has clicked form button to
                     * discontinue a medication
                     */
                } else if(isset($_GET['discontinue'])) {

                    /*
                     * Starts the session "discontinued" and sets it to
                     * the medication, link, and note for the medication that
                     * the user has clicked to discontinue.
                     */
                    $_SESSION['discontinued'][] = $_SESSION['medList'][$_GET['discontinue']];

                    /*
                     * Takes what the user wants to discontinue and
                     * removes it from the session "medList"
                     */
                    unset($_SESSION['medList'][$_GET['discontinue']]);

                    /*
                     * Rearranges the array indexes for the session "medList"
                     */
                    $_SESSION['medList'] = array_values($_SESSION['medList']);

                    /*
                     * Print out the active medications in session "medList"
                     * and the discontinued medications in session "discontinued"
                     */
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

                    /*
                     * Check if either the session "medList" or the session "discontinued"
                     * are set and not null and then prints them out.
                     */
                } else if (isset($_SESSION['medList']) || isset($_SESSION['discontinued'])) {

                    foreach ($_SESSION['medList'] as $item) {

                        printRows($item);

                        echo "<td><form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='discontinue'><button type='submit' name='discontinue' value=" . $number . ">Discontinue</button></form></td></tr>";

                        $number++;

                    }

                    if(isset($_SESSION['discontinued'])) {

                        discontinuedTable();

                        foreach ($_SESSION['discontinued'] as $value) {

                            printRows($value);

                            echo "<td><form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get' name='discontinue'><button type='submit' name='discontinue' value=" . $number . " disabled='disabled'>Discontinue</button></form></td></tr>";

                            $number++;

                        }

                    }


                }

                ?>
        </table>
    </div>
    <button type="button" id="btnBegin">Continue<span class="continueArrow"> &rang; </span></button>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <!--Link for the jquery auto-complete code-->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        /*
         Input names for the drugs in the source to have them be
         included on the AutoComplete results when user types in
         the drug name on the form. BE CAREFUL OF SPELLING!
         */
        $('#drugName').autocomplete({
            source: [ 'Acetaminophen 325 mg PO',
                'Acetaminophen 500 mg PO',
                'Acetaminophen 650 mg PO',
                'Aspirin 81 mg PO',
                'Aspirin 325 mg PO',
                'Atorvastatin 10 mg PO',
                'Atorvastatin 40 mg PO',
                'Digoxin 125 mcg PO',
                'Digoxin 250 mcg PO',
                'Furosemide 20 mg PO',
                'Furosemide 40 mg PO',
                'Furosemide 80 mg PO',
                'Insulin, Regular',
                'Insulin, Lantus',
                'Lisinopril 5 mg PO',
                'Lisinopril 10 mg PO',
                'Metoprolol Succinate ER 25 mg PO',
                'Metoprolol Succinate ER 50 mg PO',
                'Metoprolol Tartrate 25 mg PO',
                'Metoprolol Tartrate 100 mg PO']
        });

        /*
        Code to allow us to check if certain medication is in the $_SESSION['medList'}
        and if it is then send to ARIS a set item count to 1. Do NOT uncomment unless
        we are using this in the scenario.
         */

//        var phpArray = <?php //echo json_encode($_SESSION['medList']); ?>//;
//
//        for (var i=0; i<phpArray.length; i++) {
//
//            if(phpArray[i][0] == "Aspirin PO 5MG") {
//                console.log("HERE!");
//                var ARIS = {};
//                ARIS.ready = function () {
//                    var addedMeds = ARIS.cache.idForItemName('addedMeds');
//                    ARIS.setItemCount(addedMeds, 1);
//                }
//            }
//
//        }

        /*
         JS for the continue button
         */
        var ARIS = {};

        ARIS.ready = function() {
            document.getElementById("btnBegin").onclick = function() {
                ARIS.exit();
            }
        }

    </script>
    <script type="text/javascript" src="https://www.wisc-online.com/ARISE_Files/JS/PatientInfo/HectorFernandezInfo.js"></script>
    <script type="text/javascript" src="https://www.wisc-online.com/ARISE_Files/JS/ptntInfoInclude.js"></script>
</body>
</html>