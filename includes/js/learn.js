function unsetStuff(event) {
    if (event.type === 'beforeunload' && document.activeElement.tagName.toLowerCase() === 'button' && (document.activeElement.name === 'iKnow' || document.activeElement.name === 'iDontKnow')) {
        return;
    }

    fetch('unloadLearnPanel', {
        method: 'GET',
        mode: 'same-origin',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
        },
    });
}

window.onbeforeunload = unsetStuff;


function toggleWords() {
    var wordEn = document.getElementById("wordEn");
    var wordPl = document.getElementById("wordPl");

    if (wordEn.classList.contains("visible")) {
        wordEn.classList.remove("visible");
        wordEn.classList.add("hidden");

        wordPl.classList.remove("hidden");
        wordPl.classList.add("visible");
    } else {
        wordPl.classList.remove("visible");
        wordPl.classList.add("hidden");

        wordEn.classList.remove("hidden");
        wordEn.classList.add("visible");
    }
}