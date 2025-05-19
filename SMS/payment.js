// Run when the page loads
window.onload = function() {
  calculateTotal();
  setupPaidInputListener();
};

// Calculate total price by summing the Price column in the table
function calculateTotal() {
  const table = document.getElementById("paymentTable");
  const totalCostElement = document.getElementById("totalCost");
  const dueAmountElement = document.getElementById("dueAmount");
  
  let total = 0;

  // Skip the header row, iterate over tbody rows
  const rows = table.tBodies[0].rows;

  for (let i = 0; i < rows.length; i++) {
    // Price is in second cell (index 1)
    const priceText = rows[i].cells[1].innerText.trim();

    // Convert to float number
    const price = parseFloat(priceText);

    if (!isNaN(price)) {
      total += price;
    }
  }

  // Display total (2 decimals)
  totalCostElement.innerText = total.toFixed(2);

  // Initialize due amount as total (since paid is empty on load)
  dueAmountElement.innerText = total.toFixed(2);
}

// Listen for changes in the Paid input field and update Due accordingly
function setupPaidInputListener() {
  const paidInput = document.getElementById("paidAmount");
  const totalCostElement = document.getElementById("totalCost");
  const dueAmountElement = document.getElementById("dueAmount");

  paidInput.addEventListener("input", function() {
    const paidValue = parseFloat(paidInput.value);
    const totalValue = parseFloat(totalCostElement.innerText);

    if (!isNaN(paidValue) && paidValue >= 0) {
      let due = totalValue - paidValue;

      
      if (due < 0) due = 0;

      dueAmountElement.innerText = due.toFixed(2);
    } else {
      
      dueAmountElement.innerText = totalValue.toFixed(2);
    }
  })
}


function validatePayment() {
  const bankSelect = document.getElementById("bank");
  const paidInput = document.getElementById("paidAmount");
  const totalCostElement = document.getElementById("totalCost");

  const selectedBank = bankSelect.value;
  const paidValue = parseFloat(paidInput.value);
  const totalValue = parseFloat(totalCostElement.innerText);

  if (selectedBank === "") {
    alert("Please select a bank before proceeding.");
    return false;
  }

  if (isNaN(paidValue) || paidValue < 0) {
    alert("Please enter a valid paid amount.");
    return false;
  }

  if (paidValue > totalValue) {
    alert("Paid amount cannot be more than the total cost.");
    return false;
  }

  alert("Payment successful!");
  
  return true;
}
