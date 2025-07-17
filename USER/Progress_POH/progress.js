async function fetchP_Insp_IN(workshopIn, category, corrosionHrs) {
    let url = `fetch_p_insp_in.php?category=${encodeURIComponent(category)}&corrosionHrs=${encodeURIComponent(corrosionHrs)}`;

    console.log("Request URL: " + url);

    try {
        let response = await fetch(url);

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        let data = await response.json();
        let logMessages = [];

        if (data.success) {
            updateProgressInputs(data);
            let pInspPDC = await calculateDatePDC(workshopIn, data.P_Insp_IN, 'P_Insp_PDC');
            logMessages.push(`P_Insp_PDC: ${pInspPDC}`);

            let lLPDC = await calculateDatePDC(workshopIn, data.L_L_IN, 'L_L_PDC');
            logMessages.push(`L_L_PDC: ${lLPDC}`);

            let corrPDC = await calculateDatePDC(workshopIn, data.Corr_IN, 'Corr_PDC');
            logMessages.push(`Corr_PDC: ${corrPDC}`);

            let paintPDC = await calculateDatePDC(workshopIn, data.Paint_IN, 'Paint_PDC');
            logMessages.push(`Paint_PDC: ${paintPDC}`);

            let berthPDC = await calculateDatePDC(workshopIn, data.Berth_IN, 'Berth_PDC');
            logMessages.push(`Berth_PDC: ${berthPDC}`);

            let wTankPDC = await calculateDatePDC(workshopIn, data.W_Tank_IN, 'W_Tank_PDC');
            logMessages.push(`W_Tank_PDC: ${wTankPDC}`);

            let carriagePDC = await calculateDatePDC(workshopIn, data.Carriage_IN, 'Carriage_PDC');
            logMessages.push(`Carriage_PDC: ${carriagePDC}`);

            let etlACPDC = await calculateDatePDC(workshopIn, data.ETL_AC_IN, 'ETL_AC_PDC');
            logMessages.push(`ETL_AC_PDC: ${etlACPDC}`);

            let airbrPDC = await calculateDatePDC(workshopIn, data.Air_Br_IN, 'Air_Br_PDC');
            logMessages.push(`Air_Br_PDC: ${airbrPDC}`);
            

            updateDifference('P_Insp_IN', 'P_Insp_OUT', 'inspection-input-2', 'inspection-input-1');
            updateDifference('L_L_IN', 'L_L_OUT', 'lift-lower-input-2', 'lift-lower-input-1');
            updateDifference('Corr_IN', 'Corr_OUT', 'corrosion-input-2', 'corrosion-input-1');
            updateDifference('Paint_IN', 'Paint_OUT', 'paint-input-2', 'paint-input-1');
            updateDifference('Berth_IN', 'Berth_OUT', 'trimming-input-2', 'trimming-input-1');
            updateDifference('W_Tank_IN', 'W_Tank_OUT', 'water-tank-input-2', 'water-tank-input-1');
            updateDifference('Carriage_IN', 'Carriage_OUT', 'carriage-input-2', 'carriage-input-1');
            updateDifference('ETL_AC_IN', 'ETL_AC_OUT', 'etl-ac-input-2', 'etl-ac-input-1');
            updateDifference('Air_Br_IN', 'Air_Br_OUT', 'air-brake-input-2', 'air-brake-input-1');
            // updateDifference('NTXR_IN', 'NTXR_OUT', 'ntxr-input-2', '');

            checkAndHighlightDates();//while fetching its working

            // console.log(logMessages.join('\n'));

        } else {
            handleError(data);
        }
    } catch (error) {
        console.error("Fetch error: " + error);
    }
}

async function calculateDatePDC(baseDate, days, outputElementId) {
    if (!days) {
        console.log(`Skipping ${outputElementId} calculation due to undefined days`);
        return baseDate;
    }

    let base = new Date(baseDate);
    let targetDate = new Date(base);
    let remainingDays = parseInt(days, 10);

    while (remainingDays > 0) {
        targetDate.setDate(targetDate.getDate() + 1);
        let isWDCountZero = await checkWDCountZero(targetDate);

        if (!isWDCountZero) {
            remainingDays--;
        } else {
            console.log("Skipping date with WD_count 0.00:", targetDate.toISOString().split('T')[0]);
        }
    }

    let calculatedDate = targetDate.toISOString().split('T')[0];
    document.getElementById(outputElementId).value = calculatedDate;
    //console.log(`Set ${outputElementId} to ${calculatedDate}`);
    return calculatedDate;
}

async function checkWDCountZero(date) {
    let formattedDate = date.toISOString().split('T')[0];
    let url = `check_wd_count.php?date=${encodeURIComponent(formattedDate)}`;

    try {
        let response = await fetch(url);

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        let data = await response.json();
        return data.success && data.WD_count === 0.00;
    } catch (error) {
        console.error("Fetch error: " + error);
        return false;
    }
}
function handleError(response) {
    if (response && response.message) {
        console.error(response.message);
    } else {
        console.error("An unknown error occurred.");
    }
}


//insertion into poh
function onSubmitForm(event) {
    event.preventDefault(); // Prevent default form submission

    var confirmation = confirm("Are you sure you want to submit?");
    if (confirmation) {
        var formData = new FormData(document.getElementById("myForm"));

        // Create an object to hold the form data
        var formObject = {};
        formData.forEach((value, key) => {
            formObject[key] = value;
        });

        // Log the form data as a single object
        console.log("Form Data:", formObject);

        // Remove explicit appending of CoachNo and workshop_in
        formData.append('coachNo', document.getElementById('coachNo').value);
        formData.append('category', document.getElementById('category').value);
        formData.append('code', document.getElementById('code').value);
        // formData.append('workshop_in', document.getElementById('workshop_in').value);

        fetch('submit_progress.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // document.getElementById('workshop_in').disabled = true;
                // document.getElementById('corrosion_Hrs').disabled = true;
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

//highlighting with red if in and out dates are more than pdc
async function checkAndHighlightDates() {
    const stages = [
        'P_Insp', 'L_L', 'Corr', 'Paint', 'Berth', 'W_Tank', 'Carriage', 'ETL_AC', 'Air_Br'
    ];

    for (const stage of stages) {
        const inDateElem = document.getElementById(`${stage}_IN`);
        const pdcDateElem = document.getElementById(`${stage}_PDC`);
        const outDateElem = document.getElementById(`${stage}_OUT`);

        if (!inDateElem || !pdcDateElem || !outDateElem) continue;

        const inDate = new Date(inDateElem.value);
        const pdcDate = new Date(pdcDateElem.value);
        const outDate = new Date(outDateElem.value);

        // Set hours, minutes, seconds, and milliseconds to zero to strip the time part
        inDate.setHours(0, 0, 0, 0);
        pdcDate.setHours(0, 0, 0, 0);
        outDate.setHours(0, 0, 0, 0);

        if (!isNaN(inDate) && !isNaN(pdcDate) && inDate > pdcDate) {
            inDateElem.classList.add('highlight-red');
        } else {
            inDateElem.classList.remove('highlight-red');
            // inDateElem.removeAttribute('data-delay-reason');
        }

        if (!isNaN(outDate) && !isNaN(pdcDate) && outDate > pdcDate) {
            outDateElem.classList.add('highlight-red');
        } else {
            outDateElem.classList.remove('highlight-red');
            // outDateElem.removeAttribute('data-delay-reason');
        }
    }
}



//setting minimum for current stage's in date based on before stage's out date

// function setMinDateForStage(stageIndex) {
//     if (stageIndex <= 0) return; // No minimum date for the first stage

//     const stages = [
//         'P_Insp', 'L_L', 'Corr', 'Paint', 'Berth', 'W_Tank', 'Carriage', 'ETL_AC', 'Air_Br', 'NTXR'
//     ];
//     const currentStage = stages[stageIndex];
//     const previousStage = stages[stageIndex - 1];
    
//     const currentInElem = document.getElementById(`${currentStage}_IN`);
//     const previousOutElem = document.getElementById(`${previousStage}_OUT`);
    
//     if (currentInElem && previousOutElem && previousOutElem.value) {
//         currentInElem.min = previousOutElem.value;
//     }
// }

//circle 1 fetching
function updateProgressInputs(data) {
    const mappings = {
        'P_Insp_IN': 'inspection-input-1',
        'L_L_IN': 'lift-lower-input-1',
        'Corr_IN': 'corrosion-input-1',
        'Paint_IN': 'paint-input-1',
        'Berth_IN': 'trimming-input-1',
        'W_Tank_IN': 'water-tank-input-1',
        'Carriage_IN': 'carriage-input-1',
        'ETL_AC_IN': 'etl-ac-input-1',
        'Air_Br_IN': 'air-brake-input-1'
    };

    for (const [sourceId, targetId] of Object.entries(mappings)) {
        if (data[sourceId]) {
            document.getElementById(targetId).value = data[sourceId];
        }
    }
}

const stages = ['P_Insp', 'L_L', 'Corr', 'Paint', 'Berth', 'W_Tank', 'Carriage', 'ETL_AC', 'Air_Br'];
// updating
function populateForm(data) {
    // if (data.category) document.getElementById('category').value = data.category;
    // if (data.code) document.getElementById('code').value = data.code;
    // if (data.CoachNo) document.getElementById('coachNo').value = data.coachNo;
    // if (data.Corr_Hrs) document.getElementById('corrosion_Hrs').value = data.Corr_Hrs;
    // if (data.Workshop_In)setDateTimeValue ('workshop_in',data.Workshop_In);
    if (data.P_Insp_IN) setDateTimeValue('P_Insp_IN', data.P_Insp_IN);
    if (data.P_Insp_PDC) document.getElementById('P_Insp_PDC').value = data.P_Insp_PDC;
    if (data.P_Insp_OUT) setDateTimeValue('P_Insp_OUT', data.P_Insp_OUT);
    if (data.L_L_IN) setDateTimeValue('L_L_IN', data.L_L_IN);
    if (data.L_L_PDC) document.getElementById('L_L_PDC').value = data.L_L_PDC;
    if (data.L_L_OUT) setDateTimeValue('L_L_OUT', data.L_L_OUT);
    if (data.Corr_IN) setDateTimeValue('Corr_IN', data.Corr_IN);
    if (data.Corr_PDC) document.getElementById('Corr_PDC').value = data.Corr_PDC;
    if (data.Corr_OUT) setDateTimeValue('Corr_OUT', data.Corr_OUT);
    if (data.Paint_IN) setDateTimeValue('Paint_IN', data.Paint_IN);
    if (data.Paint_PDC) document.getElementById('Paint_PDC').value = data.Paint_PDC;
    if (data.Paint_OUT) setDateTimeValue('Paint_OUT', data.Paint_OUT);
    if (data.Berth_IN) setDateTimeValue('Berth_IN', data.Berth_IN);
    if (data.Berth_PDC) document.getElementById('Berth_PDC').value = data.Berth_PDC;
    if (data.Berth_OUT) setDateTimeValue('Berth_OUT', data.Berth_OUT);
    if (data.W_Tank_IN) setDateTimeValue('W_Tank_IN', data.W_Tank_IN);
    if (data.W_Tank_PDC) document.getElementById('W_Tank_PDC').value = data.W_Tank_PDC;
    if (data.W_Tank_OUT) setDateTimeValue('W_Tank_OUT', data.W_Tank_OUT);
    if (data.Carriage_IN) setDateTimeValue('Carriage_IN', data.Carriage_IN);
    if (data.Carriage_PDC) document.getElementById('Carriage_PDC').value = data.Carriage_PDC;
    if (data.Carriage_OUT) setDateTimeValue('Carriage_OUT', data.Carriage_OUT);
    if (data.ETL_AC_IN) setDateTimeValue('ETL_AC_IN', data.ETL_AC_IN);
    if (data.ETL_AC_PDC) document.getElementById('ETL_AC_PDC').value = data.ETL_AC_PDC;
    if (data.ETL_AC_OUT) setDateTimeValue('ETL_AC_OUT', data.ETL_AC_OUT);
    if (data.Air_Br_IN) setDateTimeValue('Air_Br_IN', data.Air_Br_IN);
    if (data.Air_Br_PDC) document.getElementById('Air_Br_PDC').value = data.Air_Br_PDC;
    if (data.Air_Br_OUT) setDateTimeValue('Air_Br_OUT', data.Air_Br_OUT);
    if (data.NTXR_IN) setDateTimeValue('NTXR_IN', data.NTXR_IN);
    if (data.NTXR_OUT) setDateTimeValue('NTXR_OUT', data.NTXR_OUT);
}

//circle 2 fetching
async function updateDifference(inDateId, outDateId, resultId, inputId) {
    const inDate = document.getElementById(inDateId).value;
    const outDate = document.getElementById(outDateId).value;
    const specificInput = document.getElementById(inputId).value;
    const resultInput = document.getElementById(resultId);

    if (inDate && outDate) {
        try {
            const response = await fetch('calculate_difference.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `inDate=${encodeURIComponent(inDate)}&outDate=${encodeURIComponent(outDate)}`
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.text();
            resultInput.value = result;

            const [days, hours] = result.split(' '); // assuming the result is in "X days Y hours" format
            const dayCount = parseInt(days);
            const hourCount = parseInt(hours);

            if (dayCount <= parseInt(specificInput) && hourCount === 0) {
                resultInput.style.backgroundColor = 'green';
            } else if (dayCount === parseInt(specificInput) && hourCount < 24) {
                resultInput.style.backgroundColor = 'red';
            } else if (dayCount === parseInt(specificInput) && hourCount >= 24) {
                resultInput.style.backgroundColor = 'red';
            } else if (dayCount < parseInt(specificInput) && hourCount > 0) {
                resultInput.style.backgroundColor = 'green';
            } else if (dayCount > parseInt(specificInput)) {
                resultInput.style.backgroundColor = 'red';
            } else {
                resultInput.style.backgroundColor = 'green';
            }
        } catch (error) {
            console.error('Error:', error);
            resultInput.value = 'Error calculating difference';
            resultInput.style.backgroundColor = 'red';
            resultInput.style.color = 'white';
        }
    } else {
        resultInput.style.backgroundColor = 'white';
        resultInput.value = '';
    }
}


