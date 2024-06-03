// Attend que le DOM soit entièrement chargé
document.addEventListener("DOMContentLoaded", () => {
  // Récupère le bouton "backToTopBtn" par son ID
  const backToTopBtn = document.getElementById("backToTopBtn");

  // Définit le comportement du clic sur le bouton
  backToTopBtn.onclick = () => {
    // Fait défiler la fenêtre vers le haut en douceur
    window.scrollTo({ top: 0, behavior: "smooth" });
  };
});
