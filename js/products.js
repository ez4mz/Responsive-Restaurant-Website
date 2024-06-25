document.addEventListener('DOMContentLoaded', function () {
    // set shipping amount
    const shippingAmount = 7.00;

    // FUNCTION - reset background color of fields
    function resetBackgroundColor(element) {
        element.style.backgroundColor = '';
    }

    // Event listeners for change events to update totals
    document.querySelectorAll('input[type=number]').forEach(item => {
        item.addEventListener('change', total);
    });

    // Event listeners for input events - needed for validation
    document.querySelectorAll('#customerInfo input, .product input[type=number], #zip, #email, #ccnumber').forEach(field => {
        field.addEventListener('input', () => resetBackgroundColor(field));
    });
    document.querySelectorAll('input[name="shipping"]').forEach(radio => {
        radio.addEventListener('change', () => resetBackgroundColor(document.getElementById('shippingOptions')));
    });

    // FUNCTION - calculate and display totals
    function total() {
        let grandTotal = 0;
        document.querySelectorAll('.product').forEach(product => {
            const price = parseFloat(product.querySelectorAll('p')[1].textContent.replace('Price: $', ''));
            const quantity = parseInt(product.querySelector('input[type=number]').value) || 0;
            const subtotal = price * quantity;
            product.querySelector('input[type=text]').value = subtotal.toFixed(2);
            grandTotal += subtotal;
        });

        // Add shipping if selected
        const shippingOption = document.querySelector('input[name="shipping"]:checked');
        if (shippingOption && shippingOption.value === 'Delivery') {
            grandTotal += shippingAmount;
            document.querySelector('input[name="shippingAmount"]').value = shippingAmount.toFixed(2);
        } else {
            document.querySelector('input[name="shippingAmount"]').value = '0.00';
        }

        document.querySelector('input[name="grandTotal"]').value = grandTotal.toFixed(2);
    }

    // Event listener for shipping options - to add to total
    document.querySelectorAll('input[name="shipping"]').forEach(radio => {
        radio.addEventListener('change', total);
    });

    // Event listener for submit event - for validation
    document.getElementById('shoppingForm').addEventListener('submit', validateOrder);

    // FUNCTION - validate order details
    function validateOrder(e) {
        // Check product quantities
        for (const input of document.querySelectorAll('.product input[type=number]')) {
            if (!input.value || parseInt(input.value) < 0) {
                showAlertAndFocus(input, 'Please enter a valid quantity.');
                e.preventDefault();
                return; // Exit the function
            }
        }

        // Check if shipping option is selected
        const shippingSelected = document.querySelector('input[name="shipping"]:checked');
        if (!shippingSelected) {
            showAlert('Please select a shipping option.', document.getElementById('shippingOptions'));
            e.preventDefault();
            return; // Exit the function
        }

        // Validate customer info fields
        for (const field of document.querySelectorAll('#customerInfo input')) {
            if (!field.value.trim()) {
                showAlertAndFocus(field, 'Please fill out the ' + field.name);
                e.preventDefault();
                return; // Exit the function
            }
        }

        // Validate phone number
        const phone = document.querySelector('#phone');
        if (!phone.value.match(/^\d{10}$/)) {
            showAlertAndFocus(phone, 'Phone number must be 10 digits.');
            e.preventDefault();
            return;
        }

        // Validate email format
        const email = document.querySelector('#email');
        if (!email.value.includes('@') || !email.value.includes('.')) {
            showAlertAndFocus(email, 'Please enter a valid email address.');
            e.preventDefault();
            return;
        }

        // Validate zip code
        const zip = document.querySelector('#zip');
        if (zip.value.length !== 5) {
            showAlertAndFocus(zip, 'Zip code must be 5 digits.');
            e.preventDefault();
            return;
        }

        // Validate credit card number
        const ccnumber = document.querySelector('#ccnumber');
        if (!ccnumber.value.match(/^\d{16}$/)) {
            showAlertAndFocus(ccnumber, 'Please enter a valid credit card number with 16 digits.');
            e.preventDefault();
            return;
        }
    }

    // Helper function to show alert and focus on the field
    function showAlertAndFocus(field, message) {
        alert(message);
        field.focus();
        field.style.backgroundColor = 'rgb(218, 100, 100)';
    }

    // Helper function to show alert for non-input elements
    function showAlert(message, element) {
        alert(message);
        element.style.backgroundColor = 'rgb(218, 100, 100)';
    }
});

// document.addEventListener('DOMContentLoaded', function () {
//     // set shipping amount
//     const shippingAmount = 7.00;

//     // FUNCTION - reset background color of fields
//     function resetBackgroundColor(element) {
//         element.style.backgroundColor = '';
//     }    

//     // event listener for change and submit events
//     document.getElementById('shoppingForm').addEventListener('submit', receipt);
//     document.querySelectorAll('input[type=number]').forEach(item => {
//         item.addEventListener('change', total);
//     });

//     // event listeners for input events - need for validation
//     document.querySelectorAll('#customerInfo input, .product input[type=number], #zip, #email, #ccnumber').forEach(field => {
//         field.addEventListener('input', () => resetBackgroundColor(field));
//     });
//     document.querySelectorAll('input[name="shipping"]').forEach(radio => {
//         radio.addEventListener('change', () => resetBackgroundColor(document.getElementById('shippingOptions')));
//     });

//     // FUNCTION - calculate and display totals
//     function total() {
//         let grandTotal = 0;
//         document.querySelectorAll('.product').forEach(product => {
//             const price = parseFloat(product.querySelectorAll('p')[1].textContent.replace('Price: $', ''));
//             const quantity = parseInt(product.querySelector('input[type=number]').value) || 0;
//             const subtotal = price * quantity;
//             product.querySelector('input[type=text]').value = subtotal.toFixed(2);
//             grandTotal += subtotal;
//         });

//         // add shipping if selected
//         const shippingOption = document.querySelector('input[name="shipping"]:checked');
//         if (shippingOption && shippingOption.value === 'Delivery') {
//             grandTotal += shippingAmount;
//             document.querySelector('input[name="shippingAmount"]').value = shippingAmount.toFixed(2);
//         } else {
//             document.querySelector('input[name="shippingAmount"]').value = '0.00';
//         }

//         document.querySelector('input[name="grandTotal"]').value = grandTotal.toFixed(2);
//     }

//     // event listener for shipping options - to add to total
//     document.querySelectorAll('input[name="shipping"]').forEach(radio => {
//         radio.addEventListener('change', total);
//     });

//     // FUNCTION - validate and generate receipt
//     function receipt(e) {
//         e.preventDefault();
//         let isValid = true;

//         // check each product quantity
//         document.querySelectorAll('.product input[type=number]').forEach(input => {
//             if (isValid && (!input.value || parseInt(input.value) == null)) {
//                 alert('Please enter a quantity.');
//                 input.focus();
//                 input.style.backgroundColor = 'rgb(218, 100, 100)';
//                 isValid = false;
//             }
//         });
    
//         if (!isValid) return; // exit if validation failed

//         // check if a shipping option is selected
//         const shippingSelected = document.querySelector('input[name="shipping"]:checked');
//         if (!shippingSelected) {
//             alert('Please select a shipping option.');
//             document.getElementById('shippingOptions').style.backgroundColor = 'rgb(218, 100, 100)';
//             isValid = false;
//         }

//         if (!isValid) return; // exit if validation failed
    
//         // (helper) FUNCTION - validate a field
//         function validateField(field) {
//             if (!field.value.trim()) {
//                 alert('Please fill out the ' + field.name);
//                 field.focus();
//                 field.select();
//                 field.style.backgroundColor = 'rgb(218, 100, 100)';
//                 isValid = false;
//             } else {
//                 field.style.backgroundColor = '';
//             }
//         }
    
//         // validate customer info fields
//         document.querySelectorAll('#customerInfo input').forEach(field => {
//             if (isValid) {
//                 validateField(field);
//             }
//         });
    
//         if (!isValid) return; // exit if validation failed
    
//         // check phone number is 10 digits
//         const phone = document.querySelector('#phone');
//         if (!phone.value.match(/^\d{10}$/)) { 
//             alert('Phone number must be 10 digits.');
//             phone.focus();
//             phone.select();
//             phone.style.backgroundColor = 'rgb(218, 100, 100)';
//             isValid = false;
//         }

//         if (!isValid) return; // exit if validation failed

//         // check email format
//         const email = document.querySelector('#email');
//         if (!email.value.includes('@') || !email.value.includes('.')) {
//             alert('Please enter a valid email address.');
//             email.focus();
//             email.select();
//             email.style.backgroundColor = 'rgb(218, 100, 100)';
//             isValid = false;
//         }
    
//         if (!isValid) return; // exit if validation failed
    
//         // check zip code is 5 digits
//         const zip = document.querySelector('#zip');
//         if (zip.value.length !== 5) {
//             alert('Zip code must be 5 digits.');
//             zip.focus();
//             zip.select();
//             zip.style.backgroundColor = 'rgb(218, 100, 100)';
//             isValid = false;
//         }
    
//         if (!isValid) return; // exit if validation failed
    
//         // check credit card number
//         const ccnumber = document.querySelector('#ccnumber');
//         if (!ccnumber.value.match(/^\d{16}$/)) { 
//             alert('Please enter a valid credit card number with 16 digits.');
//             ccnumber.focus();
//             ccnumber.select();
//             ccnumber.style.backgroundColor = 'rgb(218, 100, 100)';
//             isValid = false;
//         }
    
//         if (!isValid) return; // exit if validation failed
    
//         // generate receipt
//         generateReceipt();
//     }
    

//     // FUNCTION - create html page for receipt
//     function generateReceipt() {
//         const customerInfo = {
//             firstName: document.querySelector('#firstName').value,
//             lastName: document.querySelector('#lastName').value,
//             phone: document.querySelector('#phone').value,
//             email: document.querySelector('#email').value,
//             address: document.querySelector('#address').value,
//             zip: document.querySelector('#zip').value,
//             ccname: document.querySelector('#ccname').value,
//             cctype: document.querySelector('#cctype').value,
//             ccexpiration: document.querySelector('#ccexpiration').value,
//             cvv: document.querySelector('#cvv').value
//         };

//         let receiptWindow = window.open('', '_blank');
//         receiptWindow.document.write('<html><head><title>AB | Receipt</title>');
//         receiptWindow.document.write('<link rel="stylesheet" type="text/css" href="products_styles.css">');
//         receiptWindow.document.write('</head><body id="receipt"><center>');
        
//         receiptWindow.document.write('<h1 style="font-family: Vogue; font-size: 50px;">THE ARCH BAR</h1>');
//         receiptWindow.document.write('<h2>RECEIPT</h2>');
//         receiptWindow.document.write('<p>Thank you for your order!</p>');
//         receiptWindow.document.write('<p>Date: ' + new Date().toLocaleDateString() + '</p>');
//         receiptWindow.document.write('<h2>Customer Information</h2>');
//         receiptWindow.document.write('<p>Name: ' + customerInfo.firstName + ' ' + customerInfo.lastName + '</p>');
//         receiptWindow.document.write('<p>Phone Number: ' + customerInfo.phone + '</p>');
//         receiptWindow.document.write('<p>Email: ' + customerInfo.email + '</p>');
//         receiptWindow.document.write('<p>Address: ' + customerInfo.address + ' ' + customerInfo.zip + '</p>');

//         receiptWindow.document.write('<h2>Order Details</h2>');
//         receiptWindow.document.write('<table><tr><th>Product</th><th>Quantity</th><th>Subtotal</th></tr>');
//         document.querySelectorAll('.product').forEach(product => {
//             const productName = product.querySelector('h3').textContent;
//             const quantity = product.querySelector('input[type=number]').value;
//             const subtotal = product.querySelector('input[type=text]').value;
//             receiptWindow.document.write('<tr><td>' + productName + '</td><td>' + quantity + '</td><td>$' + subtotal + '</td></tr>');
//         });
//         receiptWindow.document.write('</table>');

//         const shippingOption = document.querySelector('input[name="shipping"]:checked');
//         const shippingAmount = document.querySelector('input[name="shippingAmount"]').value;
//         receiptWindow.document.write('<h2>Pickup / Delivery Details</h2>');
//         if (shippingOption && shippingOption.value === 'Delivery') {
//             receiptWindow.document.write('<p>Delivery Method: ' + shippingOption.value + '</p>');
//             receiptWindow.document.write('<p>Delivery Amount: $' + shippingAmount + '</p>');
//         } else {
//             receiptWindow.document.write('<p>Pickup (No delivery)</p>');
//         }

//         receiptWindow.document.write('<h2>Payment Details</h2>');
//         receiptWindow.document.write('<p>Card Type: ' + customerInfo.cctype + '</p>');
//         const maskedCC = 'xxxx-xxxx-xxxx-' + ccnumber.value.slice(-4);
//         receiptWindow.document.write('<p>Card Number: ' + maskedCC + '</p>');
//         receiptWindow.document.write('<p>Expiration Date: ' + customerInfo.ccexpiration + '</p>');

//         const grandTotal = document.querySelector('input[name="grandTotal"]').value;
//         receiptWindow.document.write('<h2>Grand Total: $' + grandTotal + '</h2>');

//         receiptWindow.document.write('</center></body></html>');
//         receiptWindow.document.close();
//     }
// });