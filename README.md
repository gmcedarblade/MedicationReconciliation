# Custom Medication Reconciliation Documentation

By: Greg Cedarblade 

Created:05/29/17  
Updated:06/08/17

Languages: PHP, HTML, CSS, JavaScript, JQuery

Developed for the Act 4 Healthcare ARISE project, for use in ARISE simulation scenarios involving the entering of a patients current medications.

Utilizes JQuery Auto-complete found at: https://jqueryui.com/autocomplete/

## Features

Allows ARISE simulation users to record patient medication (add new medications/discontinue medications), saves them, and allows them to be reviewed at a later point in time if they retrun to the Medication Reconciliation Customization within the same simulation.

## Auto-complete source and medication list

Two parts of code that need to be updated per scenario:

1. Auto-complete source

   * This populates a drop-down list associated with the Medication text field that appears when the user begins typing. Users can then select the medication they want, and it will auto-complete the entry.



   * Medications can be typed free-form, but medication names are case sensitive and spelling must match what is in the approved medication $ourDrugs array, or the customization will not add the medication.
   
      ```jquery
      $('#drugName').autocomplete({
        source: [ 'medication1 someDosage', 'medication2 someDosage', etc..]
      });
      ```

      EX.

      ```jquery
      $('#drugName').autocomplete({
        source: [ 'Aspirin PO 5MG', 'Aspirin PO 10MG', 'Multivitamin 1 pill']
      });
      ```
  
2. Medication List

   * This array will hold our medication as the key and the URL link to the Daily Med Website as the value.
     ```php
     $ourDrugs = array(
        "medication1 someDosage"=>"https://urlhere",
        "medication2 someDosage"=>"https://otherURL",
        "medication3 someDosage"=>"https://thisURL"
     );
     ```

     EX.
     
     ```php
     $ourDrugs = array(
        "Aspirin PO 5MG"=>"https://urlhere",
        "Multivitamin 1 pill"=>"https://otherURL"
     );
     ```

   * If the medications in the $ourDrugs array and the autocomplete source do not match up when the user adds in a medication it will not be added to the session array to be saved. 
