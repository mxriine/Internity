let index = 0;
const diapositives = document.querySelectorAll('.diapositive');
const points = document.querySelectorAll('.point');

// function afficherDiapositive(n) {
//   index = (n + diapositives.length) % diapositives.length;
//   diapositives.forEach((diapo, i) => {
//     diapo.style.display = (i === index) ? 'block' : 'none';
//     points[i].classList.toggle('actif', i === index);
//   });
// }

function afficherDiapositive(n) {
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

function changerDiapositive(n) {
  afficherDiapositive(index + n);
}

function allerADiapositive(n) {
  afficherDiapositive(n - 1);
}

// Initialisation
afficherDiapositive(index);
