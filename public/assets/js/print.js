window.onload = function() {
    const element = document.querySelector('.container');
    element.style.width = '100%';

    const opt = {
        margin: [0.3, 0.3, 0.3, 0.3],
        filename: 'invoice-' + invoiceNumber + '.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait', putOnlyUsedFonts: true },
        pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
    };

    if (typeof html2pdf !== 'undefined') {
        html2pdf().set(opt).from(element).save().then(() => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    } else {
        console.error("html2pdf.js not loaded");
    }
};
