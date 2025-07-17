function openPopup(coachNumber) {
    // Fetch coach details from the server using AJAX
    fetchCoachDetails(coachNumber);
    
    // Directly call fetchCoachPositionData for testing
    fetchCoachPositionData(coachNumber);

    // Show the popup
    document.getElementById('popupForm').style.display = 'block';
}

function closePopup() {
    // Clear the form fields
    clearFormFields();

    // Hide the popup
    document.getElementById('popupForm').style.display = 'none';
}

function clearFormFields() {
    const formFields = document.querySelectorAll('#coachInfoForm input, #coachInfoForm textarea');
    formFields.forEach(field => {
        field.value = '';
    });
}

function fetchCoachDetails(coachNumber) {
    const formData = new FormData();
    formData.append('coachNumber', coachNumber);

    fetch('get_coach_details_pos.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateFormFields(data.coachDetails);
            // Call fetchCoachPositionData after updating form fields
            fetchCoachPositionData(coachNumber);
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error fetching coach details:', error);
        alert('An error occurred while fetching coach details');
    });
}

function fetchCoachPositionData(coachNumber) {
    if (!coachNumber) {
        console.error('Invalid coach number');
        return;
    }
    console.log('Fetching coach position data for:', coachNumber);
    const formData = new FormData();
    formData.append('coachNumber', coachNumber);

    fetch('fetch_coach_position.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Received response from fetch_coach_position.php');
        return response.json();
    })
    .then(data => {
        console.log('Parsed data:', data);
        if (data.success) {
            populateCoachPositionTable(data.coachPositionData);
        } else {
            console.error('Error fetching coach position data:', data.message);
            alert(data.message || 'Error fetching coach position data');
        }
    })
    .catch(error => {
        console.error('Error fetching coach position data:', error);
        alert('An error occurred while fetching coach position data');
    });
}

function populateCoachPositionTable(data) {
    const tbody = document.querySelector('.coach-table tbody');
    tbody.innerHTML = ''; // Clear existing rows

    data.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${row.CLocation || ''}</td>
            <td>${row.Shop_Activity || ''}</td>
            <td>${row.SDateIn || ''}</td>
            <td>${row.SdateOut || ''}</td>
            <td>${row.ShiftingTime || ''}</td>
            <td>${row.WorkType || ''}</td>
        `;
        tbody.appendChild(tr);
    });
}

const fieldMapping = {
    'CoachNo': 'CoachNo',
    'Drawing_No': '',
    'trp_code': 'Code',
    'mfg_type': 'Type',
    'built': 'Built',
    'owning_railway': 'Railway',
    'division': 'OwningDivision',
    'base_depot': 'BaseDepot',
    'coupling_type': 'CouplingType',
    'toilet_system': 'ToiletSystem',
    'transferred_from': '',
    'date_transferred_from': '',
    'brake_system': 'BrakeSystem',
    'body_type': '',
    'transferred_to': '', // Set an empty value or provide a default value
    'date_transferred_to': '', // Set an empty value or provide a default value
    'periodicity_poh': 'Periodicity',
    'status': '', // Set an empty value or provide a default value
    'db': '', // Set an empty value or provide a default value
    'return_date': '' // Set an empty value or provide a default value
};

function updateFormFields(coachDetails) {
    for (const fieldId in fieldMapping) {
        const field = document.getElementById(fieldId);
        const dbColumnName = fieldMapping[fieldId];

        if (field && dbColumnName) {
            const value = coachDetails[dbColumnName] || '';
            if (field.type === 'date') {
                field.value = value !== '9999-09-09' ? value : '';
            } else {
                field.value = value;
            }
        }
    }

    // Update CoachLocation and Cloaction fields based on fetched data
    // document.getElementById('CoachLocation').value = coachDetails.CoachLocation || '';
    // document.getElementById('Cloaction').value = coachDetails.Cloaction || '';
}

function updateLocation() {
    const coachNumber = document.getElementById('CoachNo').value;
    const dateOut = document.getElementById('dateOut').value;
    const shop = document.getElementById('shop').value;
    const lineNumber = document.getElementById('lineNumber').value;
    const newLocation = document.getElementById('newLocation').value;
    const activity = document.getElementById('activity').value;
    const dateIn = document.getElementById('dateIn').value;

    const formData = {
        coachNumber: coachNumber,
        dateOut: dateOut,
        shop: shop,
        lineNumber: lineNumber,
        newLocation: newLocation,
        activity: activity,
        dateIn: dateIn
    };

    fetch('change_location.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Record updated successfully!');
            // Refresh the page after successful update
            window.location.reload();
        } else {
            alert(data.message || 'Failed to update record.');
        }
    })
    .catch(error => {
        console.error('Error updating location:', error);
        alert('An error occurred while updating location: ' + error.message);
    });
}
