document.addEventListener("DOMContentLoaded", function () {
  let firstCard = null;
  let secondCard = null;
  let lockBoard = false;

  const cards = document.querySelectorAll(".memory-card-toggle");
  const boardElement = document.querySelector(".memory-board");
  if (!cards.length) {
    return;
  }

  const scoreElement = document.getElementById("current-score");
  const timerElement = document.getElementById("timer");
  const timerDuration = timerElement
    ? Number.parseInt(timerElement.dataset.duration, 10) || 60
    : 60;
  let timeRemaining = timerDuration;
  let timerId = null;
  const totalPairs = boardElement
    ? Number.parseInt(boardElement.dataset.totalPairs || "0", 10)
    : Math.max(1, Math.floor(cards.length / 2));
  let matchedPairs = 0;
  let gameCompleted = false;

  startTimer();

  cards.forEach((card) => {
    card.addEventListener("change", function (e) {
      // Empêche de remettre la carte côté dos (donc décocher)
      if (!this.checked) {
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
          incrementScore();
          matchedPairs = Math.min(totalPairs, matchedPairs + 1);
          resetBoard();
          checkForCompletion();
        }
      }
    });
  });

  function resetBoard() {
    [firstCard, secondCard] = [null, null];
    lockBoard = false;
  }

  function startTimer() {
    if (!timerElement) {
      return;
    }

    updateTimerDisplay(timeRemaining);
    timerId = window.setInterval(() => {
      timeRemaining = Math.max(0, timeRemaining - 1);
      updateTimerDisplay(timeRemaining);

      if (timeRemaining === 0 && timerId) {
        stopTimer();
        finalizeGame();
      }
    }, 1000);
  }

  function stopTimer() {
    if (!timerId) {
      return;
    }

    window.clearInterval(timerId);
    timerId = null;
  }

  function updateTimerDisplay(value) {
    if (!timerElement) {
      return;
    }

    const minutes = String(Math.floor(value / 60)).padStart(2, "0");
    const seconds = String(value % 60).padStart(2, "0");
    timerElement.textContent = `${minutes}:${seconds}`;
  }

  function incrementScore() {
    const formData = new URLSearchParams();
    formData.append("time_remaining", Math.max(0, timeRemaining).toString());

    fetch("/score", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
      },
      credentials: "same-origin",
      body: formData,
    })
      .then((response) => (response.ok ? response.json() : Promise.reject()))
      .then((data) => {
        if (scoreElement && typeof data.score === "number") {
          scoreElement.textContent = data.score;
        }
      })
      .catch(() => {
        console.error("Impossible de mettre à jour le score.");
      });
  }

  function checkForCompletion() {
    if (gameCompleted || matchedPairs < totalPairs) {
      return;
    }

    finalizeGame();
  }

  function finalizeGame() {
    if (gameCompleted) {
      return;
    }

    gameCompleted = true;
    stopTimer();
    lockBoard = true;
    cards.forEach((card) => {
      card.disabled = true;
    });

    const payload = new URLSearchParams();
    payload.append("time_remaining", Math.max(0, timeRemaining).toString());
    payload.append("timer_duration", timerDuration.toString());

    fetch("/game-complete", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
      },
      credentials: "same-origin",
      body: payload,
    })
      .then((response) => (response.ok ? response.json() : Promise.reject()))
      .then((data) => {
        if (data.success) {
          window.location.href = "/";
        }
      })
      .catch(() => {
        console.error("Impossible de finaliser la partie.");
      });
  }
});
