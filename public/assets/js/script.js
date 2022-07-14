
// PAGE REGISTER

function effacer() {
const entreprise = document.getElementById("register_raison_sociale");

entreprise.disabled = true;
entreprise.removeAttribute("required");

}

function afficher() {
    const entreprise = document.getElementById("register_raison_sociale");

    entreprise.disabled = false;
    entreprise.setAttribute("required", "");

    }



// COOKIES
window.addEventListener('load', function() {

const btnSuccess = document.querySelector('.btn-ok');
const cookies = document.querySelector('.cookies');
let valeurCle = localStorage.getItem('banniere');

btnSuccess.addEventListener('click', function() {
    cookies.style.opacity = "0";
    localStorage.setItem('banniere', 'oui');
});

function check(){
    if(valeurCle){
        cookies.remove();
    }else {
        console.log("stockage n'existe pas");
    };
}

check();

})


// MENU BURGER
window.addEventListener('load', function() {
const icone = document.querySelector('.navbar-mobile i');
const modal = document.querySelector('.modale');
//console.log(modal);

icone.addEventListener('click', function(){
    modal.classList.toggle('change-modale');
    icone.classList.toggle('fa-times');
})

})

// ************* ANIMATION AU SCROLL *************

window.addEventListener('load', function() {
    const images = document.querySelectorAll(".images-container img")

    let options = {
        root : null,
        rootMargin: "-10% 0px",
        threshold: 0.4
    }
    
    function handleIntersect(entries) {
        console.log(entries);
    
        entries.forEach(entry => {
            if(entry.isIntersecting){
            entry.target.style.opacity = 1;
            }
        })
    }
    
    const observer = new IntersectionObserver
    (handleIntersect, options)
    
    images.forEach(image => {
        observer.observe(image)
    })
    
})


// ETOILES SUR LES AVIS
window.onload = () => {
    const stars = document.querySelectorAll(".la-star");
    //console.log(stars);
    const note = document.getElementById("note");

    //on boucle sur toutes les étoiles
    for(star of stars){
        // on écoute le survol
        star.addEventListener('mouseover', function() {
            resetStars();
            this.style.color= "#006d77";
            this.classList.add('las');
            this.classList.remove('lar');
            //élément précédent de même niveau, balise soeur, dans le DOM
            let previousStar = this.previousElementSibling;

            while(previousStar){
                  //on passe l'étoile qui précède en orange
                previousStar.style.color = "#006d77";
                previousStar.classList.add('las');
                previousStar.classList.remove('lar');
                  //onrécupère l'étoile qui la précède
                previousStar = previousStar.previousElementSibling;
            }
        });

            //on écoute le clic
            star.addEventListener("click", function() {
                note.value = this.dataset.value;
            });

            star.addEventListener("mouseout", function() {
                resetStars(note.value);
            })
    }

function resetStars(note = 0) {
    for(star of stars){
        if(star.dataset.value > note){
            star.style.color="#006d77";
            star.classList.add('lar');
            star.classList.remove('las');
        }else {
            star.style.color="#006d77";
            star.classList.add('las');
            star.classList.remove('lar');
        }
        
    }
}

}








