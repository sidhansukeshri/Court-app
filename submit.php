<?php
// Your database connection setup here

$host = "localhost";
$port = "5432";
$dbname = "courtapp";
$db_username = "postgres";
$db_password = "Spysid@#69";

$conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$db_username;password=$db_password");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Extract form data
    $courtName = $_POST["court_name"];
    $numberType = $_POST["numberType"];
    $caseType = ($_POST["numberType"] === "caseNumber") ? $_POST["caseType"] : $_POST["fillingCaseType"];
    $caseNo = ($_POST["numberType"] === "caseNumber") ? $_POST["caseNo"] : $_POST["fillingNumber"];
    $caseYear = ($_POST["numberType"] === "caseNumber") ? $_POST["caseYear"] : $_POST["fillingYear"];
    $caseDisposalDate = $_POST["caseDisposalDate"];
    $respondentNames = $_POST["respondentName"];
    $awardAmounts = $_POST["awardAmount"];
    $petitionerNames = $_POST["petitionerName"];
    $petitionerAwards = $_POST["petitionerAward"];
    $petitionerAges = $_POST["petitionerAge"];
    $petitionerDOBs = $_POST["petitionerDOB"];
    $bankNames = $_POST["bankName"];
    $bankIFSs = $_POST["bankIFS"];
    $bankAccounts = $_POST["bankAccount"];
    $awardNatures = $_POST["awardNature"];
    $petitionerWiseNames = $_POST["petitionerWiseName"];
    $petitionerWiseAmounts = $_POST["petitionerWiseAmount"];
    $petitionerWiseMaturityDates = $_POST["petitionerWiseMaturityDate"];
    $petitionerWiseROIs = $_POST["petitionerWiseROI"];
    $petitionerWiseCalculationDates = $_POST["petitionerWiseCalculationDate"];
    $createdBy = "your_created_by"; // Replace with actual created by user
    $updatedBy = "your_updated_by"; // Replace with actual updated by user

    try {
        // Start a database transaction
        $conn->beginTransaction();

        // Insert data into case_award_respondent table
        $insertRespondentSql = "INSERT INTO case_award_respondent (cino, case_type, case_no, case_year, respondent_name, award_amount, created_by, created_date, updated_by, updated_date) 
                                VALUES (:cino, :case_type, :case_no, :case_year, :respondent_name, :award_amount, :created_by, NOW(), :updated_by, NOW())";
        $stmtInsertRespondent = $conn->prepare($insertRespondentSql);

        for ($i = 0; $i < count($respondentNames); $i++) {
            $stmtInsertRespondent->bindParam(":cino", "your_cino", PDO::PARAM_STR); // Replace with actual cino
            $stmtInsertRespondent->bindParam(":case_type", $caseType, PDO::PARAM_STR);
            $stmtInsertRespondent->bindParam(":case_no", $caseNo, PDO::PARAM_STR);
            $stmtInsertRespondent->bindParam(":case_year", $caseYear, PDO::PARAM_STR);
            $stmtInsertRespondent->bindParam(":respondent_name", $respondentNames[$i], PDO::PARAM_STR);
            $stmtInsertRespondent->bindParam(":award_amount", $awardAmounts[$i], PDO::PARAM_INT);
            $stmtInsertRespondent->bindParam(":created_by", $createdBy, PDO::PARAM_STR);
            $stmtInsertRespondent->bindParam(":updated_by", $updatedBy, PDO::PARAM_STR);
            $stmtInsertRespondent->execute();
        }

        // Insert data into case_award_petitioner table
        $insertPetitionerSql = "INSERT INTO case_award_petitioner (cino, case_type, case_no, case_year, petitioner_name, award_amount, age, dob, bank_acct_no, bank_IFS_code, bank_name, award_nature, created_by, created_date, updated_by, updated_date) 
                                VALUES (:cino, :case_type, :case_no, :case_year, :petitioner_name, :award_amount, :age, :dob, :bank_acct_no, :bank_IFS_code, :bank_name, :award_nature, :created_by, NOW(), :updated_by, NOW())";
        $stmtInsertPetitioner = $conn->prepare($insertPetitionerSql);

        for ($i = 0; $i < count($petitionerNames); $i++) {
            $stmtInsertPetitioner->bindParam(":cino", "your_cino", PDO::PARAM_STR); // Replace with actual cino
            $stmtInsertPetitioner->bindParam(":case_type", $caseType, PDO::PARAM_STR);
            $stmtInsertPetitioner->bindParam(":case_no", $caseNo, PDO::PARAM_STR);
            $stmtInsertPetitioner->bindParam(":case_year", $caseYear, PDO::PARAM_STR);
            $stmtInsertPetitioner->bindParam(":petitioner_name", $petitionerNames[$i], PDO::PARAM_STR);
            $stmtInsertPetitioner->bindParam(":award_amount", $petitionerAwards[$i], PDO::PARAM_INT);
            $stmtInsertPetitioner->bindParam(":age", $petitionerAges[$i], PDO::PARAM_INT);
            $stmtInsertPetitioner->bindParam(":dob", $petitionerDOBs[$i], PDO::PARAM_STR);
            $stmtInsertPetitioner->bindParam(":bank_acct_no", $bankAccounts[$i], PDO::PARAM_STR);
            $stmtInsertPetitioner->bindParam(":bank_IFS_code", $bankIFSs[$i], PDO::PARAM_STR);
            $stmtInsertPetitioner->bindParam(":bank_name", $bankNames[$i], PDO::PARAM_STR);
            $stmtInsertPetitioner->bindParam(":award_nature", $awardNatures[$i], PDO::PARAM_STR);
            $stmtInsertPetitioner->bindParam(":created_by", $createdBy, PDO::PARAM_STR);
            $stmtInsertPetitioner->bindParam(":updated_by", $updatedBy, PDO::PARAM_STR);
            $stmtInsertPetitioner->execute();
        }

        // Insert data into case_award_petitioner_ln table
        $insertPetitionerLnSql = "INSERT INTO case_award_petitioner_ln (cino, case_type, case_no, case_year, petitioner_id, amount, maturity_date, roi, interest_from_dt, created_by, created_date, updated_by, updated_date) 
                                  VALUES (:cino, :case_type, :case_no, :case_year, :petitioner_id, :amount, :maturity_date, :roi, :interest_from_dt, :created_by, NOW(), :updated_by, NOW())";
        $stmtInsertPetitionerLn = $conn->prepare($insertPetitionerLnSql);

        for ($i = 0; $i < count($petitionerWiseNames); $i++) {
            $stmtInsertPetitionerLn->bindParam(":cino", "your_cino", PDO::PARAM_STR); // Replace with actual cino
            $stmtInsertPetitionerLn->bindParam(":case_type", $caseType, PDO::PARAM_STR);
            $stmtInsertPetitionerLn->bindParam(":case_no", $caseNo, PDO::PARAM_STR);
            $stmtInsertPetitionerLn->bindParam(":case_year", $caseYear, PDO::PARAM_STR);
            $stmtInsertPetitionerLn->bindParam(":petitioner_id", "replace_with_actual_petitioner_id", PDO::PARAM_INT); // Replace with actual petitioner_id
            $stmtInsertPetitionerLn->bindParam(":amount", $petitionerWiseAmounts[$i], PDO::PARAM_INT);
            $stmtInsertPetitionerLn->bindParam(":maturity_date", $petitionerWiseMaturityDates[$i], PDO::PARAM_STR);
            $stmtInsertPetitionerLn->bindParam(":roi", $petitionerWiseROIs[$i], PDO::PARAM_INT);
            $stmtInsertPetitionerLn->bindParam(":interest_from_dt", $petitionerWiseCalculationDates[$i], PDO::PARAM_STR);
            $stmtInsertPetitionerLn->bindParam(":created_by", $createdBy, PDO::PARAM_STR);
            $stmtInsertPetitionerLn->bindParam(":updated_by", $updatedBy, PDO::PARAM_STR);
            $stmtInsertPetitionerLn->execute();
        }

        // Commit the transaction
        $conn->commit();
        echo json_encode(["status" => "success", "message" => "Form data inserted successfully"]);
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $conn->rollBack();
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }
} else {
    // Invalid request
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
