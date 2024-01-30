document.addEventListener('DOMContentLoaded', function () {
    var wordsContainer = document.querySelector('.wordsContainer');
    
    wordsContainer.addEventListener('click', function (event) {
        var word = event.target.closest('.word');

        if (event.target.classList.contains('Delete')) {
            var word_en = word.querySelector('#wordEn').innerHTML;
            var word_pl = word.querySelector('#wordPl').innerHTML;

            fetch('removeWord', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'wordEn=' + encodeURIComponent(word_en) + '&wordPl=' + encodeURIComponent(word_pl),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                console.log('Word deleted');
                window.location.reload();
            })
            .catch(error => {
                console.error('Error deleting word:', error);
            });

            return;
        }
    });
});
