function loadCoursesFromStorage() {
  const stored = localStorage.getItem("registeredCourses");
  const rowsContainer = document.getElementById("courseRows");
  rowsContainer.innerHTML = "";

  let total = 0;

  if (stored) {
    const courses = JSON.parse(stored);
    courses.forEach(course => {
      const row = document.createElement("tr");
      row.innerHTML = `<td>${course.name}</td><td>$${course.price}</td>`;
      rowsContainer.appendChild(row);
      total += parseFloat(course.price);
    });
  }

  document.getElementById("totalCost").textContent = total.toFixed(2);
  document.getElementById("dueAmount").textContent = total.toFixed(2);
}

function updateDue() {
  const total = parseFloat(document.getElementById("totalCost").textContent) || 0;
  const paid = parseFloat(document.getElementById("paidAmount").value) || 0;
  const due = Math.max(total - paid, 0);
  document.getElementById("dueAmount").textContent = due.toFixed(2);
}

function validatePayment() {
  const paid = parseFloat(document.getElementById("paidAmount").value);
  const bank = document.getElementById("bank").value;
  const total = parseFloat(document.getElementById("totalCost").textContent);

  if (!bank) {
    alert("Please select a bank.");
    return;
  }

  if (isNaN(paid) || paid <= 0 || paid > total) {
    alert("Please enter a valid payment amount.");
    return;
  }

  alert("Payment successful!");
  document.getElementById("paidAmount").value = "";
  document.getElementById("bank").value = "";
  document.getElementById("dueAmount").textContent = (total - paid).toFixed(2);
}

function logout() {
  window.location.href = "landingpage.html";
}

document.addEventListener("DOMContentLoaded", () => {
  loadCoursesFromStorage();
  document.getElementById("paidAmount").addEventListener("input", updateDue);
});