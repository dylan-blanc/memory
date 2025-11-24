document.addEventListener("DOMContentLoaded", function () {
  let firstCard = null;
  let secondCard = null;
  let lockBoard = false;

  const cards = document.querySelectorAll(".memory-card-toggle");

  cards.forEach((card) => {
    card.addEventListener("change", function (e) {
      // Empêche de remettre la carte côté dos (donc décocher)
      if (!this.checked) {
        // Si la carte est déjà retournée (face visible), on empêche de la remettre côté dos
        e.preventDefault();
        this.checked = true;
        return;
      }

      // Empêche de sélectionner plus de deux cartes
      if (lockBoard || (firstCard && secondCard)) {
        e.preventDefault();
        this.checked = false;
        return;
      }

      if (!firstCard) {
        firstCard = this;
      } else if (!secondCard && this !== firstCard) {
        secondCard = this;
        lockBoard = true;

        const id1 = firstCard
          .closest(".memory-card")
          .getAttribute("data-card-id");
        const id2 = secondCard
          .closest(".memory-card")
          .getAttribute("data-card-id");

        if (id1 !== id2) {
          setTimeout(() => {
            firstCard.checked = false;
            secondCard.checked = false;
            resetBoard();
          }, 500);
        } else {
          resetBoard();
        }
      }
    });
  });

  function resetBoard() {
    [firstCard, secondCard] = [null, null];
    lockBoard = false;
  }
});
