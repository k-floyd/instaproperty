window.addEventListener("load", () => {
    document.getElementById('hoverIcon').addEventListener("click", () => {document.getElementById('dropdownContent').classList.toggle("hide");});

    const searchInput = document.getElementById('searchInput');

    // get all properties listed
    let properties = document.getElementsByClassName('card');

    // when search term is typed
    searchInput.addEventListener("input", e => {
        const value = e.target.value.toLowerCase()
        // for each property
        for (let j = 0; j < properties.length; j++) {
            const property = properties[j];
            let isVisible = false;
            // get keywords
            let keywords = property.getAttribute("data-card-keywords").split(",");
            keywords.forEach(key => {
                // if keyword includes search term, set visible to true
                if(key.toLowerCase().includes(value)) {
                    isVisible = true;
                }
            })

            // determine whether to show the card based on visible
            property.classList.toggle("hide", !isVisible);
        }
    })
})

function validateSignUpForm() {
    //get the values from the form
	var fname = document.getElementById("firstName").value;
    var lname = document.getElementById("lastName").value;
    var mail = document.getElementById("email").value;

    var uname = document.getElementById("userName").value;
	var password1 = document.getElementById("userPassword").value;
    var password2 = document.getElementById("confirmPassword").value;

    //verify all fields have been filled out
	if (mail == "" || fname == "" || lname == "" || uname == "" || password1 == "" || password2 == "") {
		document.getElementById("errorMsg").innerHTML = "Please fill the required fields"
		return false;
	}

    //verify the password is at least 8 characters long
	else if (password1.length < 8) {
		document.getElementById("errorMsg").innerHTML = "Your password must be at least 8 characters long"
		return false;
	}

    //verify the passwords match
    else if (password1 != password2) { 
        document.getElementById("errorMsg").innerHTML = "Your passwords do not match. Please try again."
        return false; 
    } 

    // Verify the email format using a regular expression
    else if (!isValidEmail(mail)) {
        document.getElementById("errorMsg").innerHTML = "Please enter a valid email address";
        return false;
    }

    // the user has successfully filled out form
	else {
		return true;
	}
}

function validateLoginForm() {
    var uname = document.getElementById("userName").value;
	var password = document.getElementById("userPassword").value;

    // verify all fields have been filled out
    if(uname == "" || password == "") {
        document.getElementById("errorMsg").innerHTML = "Please fill the required fields"
		return false;
    }

    // the user has successfully filled out form
    return true;
}

function validatePropertyForm() {
    //get the values from the form
	var pname = document.getElementById("p_name").value;
    var paddress = document.getElementById("p_address").value;
    var paddress_city = document.getElementById("p_address_city").value;
    var paddress_state = document.getElementById("p_address_state").value;
    var paddress_zc = document.getElementById("p_address_zc").value;

    var pprice = document.getElementById("p_price").value;

    var ptype = document.getElementById("p_type").value;
    var pstatus = document.getElementById("p_status").value;

    var pbeds = document.getElementById("p_bds").value;
    var pbaths = document.getElementById("p_ba").value;
    var psqft = document.getElementById("p_sqft").value;
        
    var pimage = document.getElementById("p_image").value;


    //verify all fields have been filled out
	if (pname == "" || paddress == "" || paddress_city == "" || paddress_state == "" || paddress_zc == "" || pbeds == "" || pbaths == "" || pprice == "" || ptype == "" || pstatus == "" || psqft == "" || pimage == null) {
		document.getElementById("errorMsg").innerHTML = "Please fill the required fields"
		return false;
	}

    return true;
}

//function to validate the email format using a regular expression
function isValidEmail(email) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function openModal(info) {
    const modal = document.getElementById('myModal');
    const modalHeader = document.getElementById('modalTitle');
    const modalImage = document.getElementById('modalImage');
    const modalPrice = document.getElementById('modalPrice');
    const modalType = document.getElementById('modalType');
    const modalBds = document.getElementById('modalBds');
    const modalBa = document.getElementById('modalBa');
    const modalSqft = document.getElementById('modalSqft');
    const modalAddr = document.getElementById('modalAddr');
  
    modalHeader.textContent = info.children[0].children[1].innerText;
    modalImage.src = info.children[0].children[0].src;
    modalImage.alt = info.children[0].children[0].alt;

    modalPrice.textContent = info.children[0].children[2].children[0].innerText;
    modalType.textContent = info.children[0].children[2].children[1].innerText;
    modalBds.textContent = info.children[1].children[0].children[0].innerText;
    modalBa.textContent = info.children[1].children[0].children[1].innerText;
    modalSqft.textContent = info.children[1].children[0].children[2].innerText;
    modalAddr.innerHTML = info.children[1].children[1].innerHTML;
  
    modal.classList.toggle("hide");
  }
  
  function closeModal() {
    const modal = document.getElementById('myModal');
    modal.classList.toggle("hide");
  }
  
  function goToWishlist() {
    window.location.href = 'wishlist.html';
  }