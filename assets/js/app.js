document.addEventListener("DOMContentLoaded", () => {
    const navToggle = document.querySelector("[data-nav-toggle]");
    const navMenu = document.querySelector("[data-nav-menu]");

    if (navToggle && navMenu) {
        navToggle.addEventListener("click", () => {
            navMenu.classList.toggle("open");
        });
    }

    const sidebarToggle = document.querySelector("[data-sidebar-toggle]");
    const sidebar = document.querySelector("[data-sidebar]");
    const backdrop = document.querySelector("[data-sidebar-close]");

    const closeSidebar = () => {
        sidebar?.classList.remove("open");
        backdrop?.classList.remove("open");
    };

    if (sidebarToggle && sidebar && backdrop) {
        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.add("open");
            backdrop.classList.add("open");
        });
        backdrop.addEventListener("click", closeSidebar);
    }

    document.querySelectorAll("[data-toggle-password]").forEach((button) => {
        button.addEventListener("click", () => {
            const input = button.parentElement?.querySelector("input");
            if (!input) return;
            const isPassword = input.type === "password";
            input.type = isPassword ? "text" : "password";
            button.textContent = isPassword ? "Sembunyi" : "Lihat";
        });
    });

    document.querySelectorAll("[data-confirm]").forEach((link) => {
        link.addEventListener("click", (event) => {
            const message = link.getAttribute("data-confirm") || "Lanjutkan aksi ini?";

            if (window.Swal) {
                event.preventDefault();
                window.Swal.fire({
                    title: "Konfirmasi",
                    text: message,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya",
                    cancelButtonText: "Batal",
                    confirmButtonColor: "#2E7D32",
                    cancelButtonColor: "#EF4444",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = link.href;
                    }
                });
                return;
            }

            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll("form[data-validate]").forEach((form) => {
        form.addEventListener("submit", (event) => {
            let valid = true;
            form.querySelectorAll("input, textarea, select").forEach((field) => {
                const error = field.closest(".form-group")?.querySelector(".error-message") ||
                    field.parentElement?.nextElementSibling ||
                    field.nextElementSibling;

                if (!field.checkValidity()) {
                    valid = false;
                    if (error?.classList.contains("error-message")) {
                        error.textContent = field.validationMessage;
                    }
                } else if (error?.classList.contains("error-message")) {
                    error.textContent = "";
                }
            });

            if (!valid) {
                event.preventDefault();
            }
        });
    });
});
