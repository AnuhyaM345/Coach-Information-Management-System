<?php
include '../../db.php'; // Include the database connection file

// Function to get the category of a coach
function getCoachCategory($conn, $coachNo) {
    $query = "SELECT [Category] FROM [dbo].[tbl_CoachMaster] WHERE CoachNo = ?";
    $params = array($coachNo);
    
    // Execute the query
    $stmt = sqlsrv_query($conn, $query, $params);
    
    // Check for query execution error
    if ($stmt === false) {
        // Handle SQL error gracefully (you might log the error)
        die('SQL query execution error: ' . print_r(sqlsrv_errors(), true));
    }
    
    // Fetch the category from the result set
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    
    // Return the category (or null if not found)
    return $row['Category'] ?? null;
}
/**
 * Count the number of coaches in a given location.
 *
 */
function countCoaches($coachPositions, $location) {
    // Initialize the coach count to 0
    $coachCount = 0;

    // Check if the location exists in the coach positions array
    if (isset($coachPositions[$location])) {

        // Loop through each line in the location
        foreach ($coachPositions[$location] as $line => $coachNumbers) {

            // Check if the line has multiple coach numbers
            if (is_array($coachNumbers)) {

                // Increment the coach count by the number of coaches in the line
                $coachCount += count($coachNumbers);
            }
        }
    }

    // Return the total coach count
    return $coachCount;
}
?>