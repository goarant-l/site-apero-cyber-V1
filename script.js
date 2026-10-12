// Validation côté client pour le formulaire de connexion admin
function verifierSaisie() {
    const login    = document.getElementById("login");
    const mdp      = document.getElementById("mdp");
    const message  = document.getElementById("messageErreur");

    if (!login || !mdp || !message) return true; // pas sur la page connexion

    if (login.value.length < 3) {
        message.textContent = "Le login doit contenir au moins 3 caractères.";
        return false;
    } else if (mdp.value === "") {
        message.textContent = "Le mot de passe ne peut pas être vide.";
        return false;
    }

    message.textContent = "";
    return true;
}

// Attache la validation au formulaire de connexion admin s'il existe
document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form[action='connexion_exe.php']");
    if (form) {
        form.addEventListener("submit", (e) => {
            if (!verifierSaisie()) e.preventDefault();
        });
    }
});
