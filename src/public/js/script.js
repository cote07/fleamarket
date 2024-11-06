function toggleMenu() {
    const menu = document.getElementById('menu');
    const menuIcon = document.getElementById('menu-icon');
    const closeIcon = document.getElementById('close-icon');

menu.classList.toggle("open");

    if (menu.classList.contains("open")) {
        menuIcon.style.display = "none";
        closeIcon.style.display = "inline";
        menu.style.display = "block";
    } else {
        menuIcon.style.display = "inline";
        closeIcon.style.display = "none";
        menu.style.display = "none";
    }
}

document.querySelector(".search-icon").addEventListener("click", function () {
    const searchContent = document.querySelector(".search-content");
    const keywordInput = document.getElementById("keyword-input");

    searchContent.classList.toggle("active");

    if (searchContent.classList.contains("active")) {
        keywordInput.focus();
    }
});

document.getElementById('keyword-input').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            document.getElementById('search-form').submit();
        }
});

 document.addEventListener('DOMContentLoaded', function() {

        const paymentSelect = document.getElementById('paymentSelect');
        const selectPayment = document.querySelector('.payment-method');
        paymentSelect.addEventListener('change', function() {
            selectPayment.textContent = paymentSelect.value ? paymentSelect.value : '';
        });
 });
