document.addEventListener('DOMContentLoaded', function () {
    var sets = document.querySelectorAll('.wordSet');

    sets.forEach(function (set) {
        set.addEventListener('click', function () {
            var set_id = this.querySelector('#set_id').innerText;

            fetch('insertUserHistory', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'uid=' + encodeURIComponent(uid) + '&set_id=' + encodeURIComponent(set_id),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('History inserted successfully!');
                } else {
                    console.log('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
        });
    });
});