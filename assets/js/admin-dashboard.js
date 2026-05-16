document.querySelectorAll("[data-confirm-delete]").forEach((link) => {
    link.addEventListener("click", (event) => {
        const message = link.dataset.confirmDelete || "Delete this record?";

        if (!window.confirm(message)) {
            event.preventDefault();
        }
    });
});
