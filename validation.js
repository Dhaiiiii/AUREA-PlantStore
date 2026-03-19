// -----------------------------
// ADD PRODUCT VALIDATION
// -----------------------------
function validateAddProduct() {
    const name = document.getElementById("name").value.trim();
    const price = document.getElementById("price").value.trim();
    const quantity = document.getElementById("quantity").value.trim();
    const description = document.getElementById("description").value.trim();
    const image = document.getElementById("image").value.trim();

    if (!name || !price || !quantity || !description || !image) {
        alert("All fields are required.");
        return false;
    }

    if (isNaN(price)) {
        alert("Price must be a number.");
        return false;
    }

    if (quantity < 0) {
        alert("Quantity cannot be less than 0.");
        return false;
    }

    return true;
}

// -----------------------------
// EDIT PRODUCT VALIDATION
// -----------------------------
function validateEditProduct() {
    const name = document.querySelector("input[name='name']").value.trim();
    const price = document.querySelector("input[name='price']").value.trim();
    const quantity = document.querySelector("input[name='quantity']").value.trim();
    const description = document.querySelector("textarea[name='description']").value.trim();

    if (!name || !price || !quantity || !description) {
        alert("All fields are required.");
        return false;
    }

    if (isNaN(price)) {
        alert("Price must be a number.");
        return false;
    }

    if (quantity < 0) {
        alert("Quantity cannot be less than 0.");
        return false;
    }

    return true;
}

// -----------------------------
// CONTACT PAGE VALIDATION
// -----------------------------
function validateContact() {
    const name = document.getElementById("contact-name").value.trim();
    const email = document.getElementById("contact-email").value.trim();
    const message = document.getElementById("contact-message").value.trim();

    if (!name || !email || !message) {
        alert("All fields are required.");
        return false;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }

    return true;
}