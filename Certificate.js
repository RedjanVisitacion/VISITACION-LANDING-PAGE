document.addEventListener("DOMContentLoaded", function() {
  const recipientName = prompt("Enter your full name:");
  /*const courseName = prompt("Enter course name:");*/  
  const date = new Date().toLocaleDateString();

  document.getElementById("recipientName").innerText = recipientName;
/*document.getElementById("courseName").innerText = courseName;*/  
  document.getElementById("date").innerText = date;


  // Download certificate as PDF
  const downloadBtn = document.getElementById("downloadBtn");
  downloadBtn.addEventListener("click", function() {
    const certificate = document.getElementById("certificate");
    const opt = {
      margin: 0.5,
      filename: 'Certificate of Remembrance.pdf',
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: { scale: 2 },
      jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
    };
    html2pdf().from(certificate).set(opt).save();
  });
});
