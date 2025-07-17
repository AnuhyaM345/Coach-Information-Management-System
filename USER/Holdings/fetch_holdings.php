<?php
include ('../../db.php'); // Make sure this file contains the database connection code

$sql = "
SELECT 
    SerialNumber,
    Railway,
    CoachNo,
    Code,
    Type,
    Category,
    AC_Flag,
    OwningDivision,
    
    BaseDepot,
    
    CouplingType,
    AgeGroup,
    last_POH_DATE,
    previous_RD,
    repair_type,
    workshop_IN,
    NC_offered,
    Corr_M_Hrs,
    Calendar_Days,
    Working_Days,
    
    
    CoachLocation 
FROM 
    vw_CMS_LGDSHolding
";

// Prepare and execute the query
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch and display the data
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . ($row['SerialNumber'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['Railway'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['CoachNo'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['Code'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['Type'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['Category'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['Corr_M_Hrs'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['OwningDivision'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['BaseDepot'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['AC_Flag'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['CouplingType'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['last_POH_DATE'] ? $row['last_POH_DATE']->format('Y-m-d H:i:s') : 'NULL') . "</td>";
    echo "<td>" . ($row['previous_RD'] ? $row['previous_RD']->format('Y-m-d H:i:s') : 'NULL') . "</td>";
    echo "<td>" . ($row['repair_type'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['AgeGroup'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['Calendar_Days'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['Working_Days'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['workshop_IN'] ? $row['workshop_IN']->format('Y-m-d H:i:s') : 'NULL') . "</td>";
    echo "<td>" . ($row['NC_offered'] ? $row['NC_offered']->format('Y-m-d H:i:s') : 'NULL') . "</td>";
    echo "<td>" . ($row['CoachLocation'] ?? 'NULL') . "</td>";
    echo "</tr>";
}

// Free statement and connection resources
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
