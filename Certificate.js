document.addEventListener("DOMContentLoaded", function () {
  const recipientName = prompt("Enter your full name:") || "[Recipient Name]";
  const date = new Date().toLocaleDateString();

  document.getElementById("recipientName").innerText = recipientName;
  document.getElementById("date").innerText = date;

  const downloadBtn = document.getElementById("downloadBtn");
  if (downloadBtn) {
    downloadBtn.addEventListener("click", function () {
      const certificate = document.getElementById("certificate");

      if (certificate) {
        // Add inline background image to ensure it's captured in PDF
        certificate.style.background = "url('img/bgg.jpg') no-repeat center center";
        certificate.style.backgroundSize = "cover";
        certificate.style.border = "10px double black";
        certificate.style.padding = "40px";
        certificate.style.width = "1023px";  // A4 Landscape
        certificate.style.height = "693px";

        const opt = {
          margin: 0,
          filename: 'Certificate_of_Remembrance.pdf',
          image: { type: 'jpeg', quality: 1 },
          html2canvas: { scale: 3, useCORS: true, backgroundColor: null },
          jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
        };

        html2pdf().from(certificate).set(opt).save();
      } else {
        alert("Certificate element not found!");
      }
    });
  } else {
    console.error("Download button not found!");
  }
});
