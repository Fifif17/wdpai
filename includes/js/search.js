function searchSets() {
    var input, filter;
    input = document.getElementById('searchbar');
    filter = input.value.toLowerCase();

    fetch('searchSets',  {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'filter=' + encodeURIComponent(filter),
    })
    .then(response => response.text())
    .then(data => {
        document.querySelector('.setContainer').innerHTML = data;
    })
    .catch(error => {
        console.error('Error fetching search results:', error);
    });
}
