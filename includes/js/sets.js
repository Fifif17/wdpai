document.addEventListener('DOMContentLoaded', function () {
    var setsContainer = document.querySelector('.setContainer');

    setsContainer.addEventListener('click', function (event) {
        var set = event.target.closest('.wordSet');

        if (event.target.classList.contains('Delete')) {
            var setId = event.target.getAttribute('dataSetId');

            fetch('removeSet', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'remSetId=' + encodeURIComponent(setId),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                console.log('Set deleted');
                window.location.reload();
            })
            .catch(error => {
                console.error('Error deleting set:', error);
            });

            return;
        }

        if (set) {
            var set_id = set.querySelector('#set_id').innerText;

            if (uid == -1) {
                window.location.href = 'setPage?id=' + set_id;
            }

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
                    window.location.href = 'setPage?id=' + set_id;
                } else {
                    console.log('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
        }
    });
});
