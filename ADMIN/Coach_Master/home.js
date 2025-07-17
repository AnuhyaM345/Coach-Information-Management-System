document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('#searchform').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent form submission
        let searchInput = document.querySelector('input[name="search"]').value;
        if (searchInput.trim() !== '') {
            fetchCoachDetails(searchInput);
        }
    });
    // Add event listener to the "Delete" button
    document.getElementById('addbutton').addEventListener('click', function(e){
            e.preventDefault(); // Prevent default form submission
            addNewCoach();
        });
    
    const form = document.getElementById('form1');
    form.addEventListener('submit', submitForm);
});

function updateFormFields(data) {
    document.getElementById('coachID').value = data.coachDetails.CoachID;
    document.getElementById('coachNo').value = data.coachDetails.CoachNo;
    document.getElementById('oldCoachNo').value = data.coachDetails.OldCoachNo;
    document.getElementById('code').value = data.coachDetails.Code;
    document.getElementById('type').value = data.coachDetails.Type;
    document.getElementById('railway').value = data.coachDetails.Railway;
    document.getElementById('vehicleType').value = data.coachDetails.VehicleType;
    document.getElementById('category').value = data.coachDetails.Category;
    document.getElementById('AC_Flag').value = data.coachDetails.AC_Flag;
    document.getElementById('brakesystem').value = data.coachDetails.BrakeSystem;

    let builtDate = data.coachDetails.BuiltDate?.date ? new Date(data.coachDetails.BuiltDate.date) : null;
    let inductionDate = data.coachDetails.InductionDate?.date ? new Date(data.coachDetails.InductionDate.date) : null;
    
    // Function to format date to "YYYY-MM-DDTHH:MM"
    function formatDateToDateTimeLocal(date) {
        if (!date) return null;
        const pad = num => String(num).padStart(2, '0');
        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
    }
    
    document.getElementById('builtDate').value = formatDateToDateTimeLocal(builtDate);
    document.getElementById('inductionDate').value = formatDateToDateTimeLocal(inductionDate);

    document.getElementById('built').value = data.coachDetails.Built;
    document.getElementById('periodicity').value = data.coachDetails.Periodicity;
    document.getElementById('Tareweight').value = data.coachDetails.TareWeight;
    document.getElementById('owningDivision').value = data.coachDetails.OwningDivision;
    document.getElementById('baseDepot').value = data.coachDetails.BaseDepot;
    document.getElementById('workshop').value = data.coachDetails.Workshop;
    document.getElementById('codalLife').value = data.coachDetails.CodalLife;
    document.getElementById('powerGenerationType').value = data.coachDetails.PowerGenerationType;
    document.getElementById('couplingType').value = data.coachDetails.CouplingType;
    document.getElementById('Seating').value = data.coachDetails.SeatingCapacity;
    document.getElementById('Sleeping').value = data.coachDetails.SleepingCapacity;
}

function fetchCoachDetails(coachNumber) {
    let formData = new FormData();
    formData.append('coachNumber', coachNumber);

    fetch('get_coach_details.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateFormFields(data);
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error fetching coach details:', error));
}

function submitForm(event) {
    event.preventDefault(); // Prevent the default form submission

    const formData = new FormData(document.getElementById('form1'));

    fetch('update_coach_details.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); // Parse response JSON here
    })
    .then(data => {
        console.log(data);
        if (data.success) {
            alert(data.message); // Display success message
            
        } else {
            alert(data.message); // Display error message
        }
    })
    .catch(error => {
        console.error('Error updating coach details:', error);
        alert('An error occurred while updating the coach details.');
    });
}

function addNewCoach() {
    const formData = new FormData(document.getElementById('form1'));

    fetch('add_coach_details.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message); // Display success message
            // Optionally, reset form or update UI as needed
        } else {
            alert(data.message); // Display error message
            // Optionally, handle specific error scenarios
            if (data.message === 'Coach details already exist.') {
                // Handle case where coach details already exist
                // For example, show a different message or prevent duplicate submissions
            }
        }
    })
    .catch(error => {
        console.error('Error adding new coach details:', error);
        alert('An error occurred while adding the new coach details.');
    });
}