let index = 0;
const diapositives = document.querySelectorAll('.diapositive');
const points = document.querySelectorAll('.point');
let intervalId = null; // Stocke l'ID de l'intervalle

function afficher(n) {
    if (n >= diapositives.length) {
        index = 0;
    } else if (n < 0) {
        index = diapositives.length - 1;
    } else {
        index = n;
    }
    const offset = -index * 100;
    document.querySelector('.conteneur').style.transform = `translateX(${offset}%)`;

    diapositives.forEach((diapo, i) => {
        points[i].classList.toggle('actif', i === index);
    });
}

function changer(n) {
    afficher(index + n);
    arret(); // Arrête l'intervalle actuel
    demarrage(); // Redémarre l'intervalle
}

function diapo(n) {
    afficher(n - 1);
    arret(); // Arrête l'intervalle actuel
    demarrage(); // Redémarre l'intervalle
}

function demarrage() {
    intervalId = setInterval(() => {
        changer(1); // Passe à la diapositive suivante
    }, 5000); // Change toutes les 10 secondes
}

function arret() {
    if (intervalId) {
        clearInterval(intervalId);
        intervalId = null;
    }
}

// Initialisation
afficher(index);
demarrage(); // Démarre le défilement automatique
