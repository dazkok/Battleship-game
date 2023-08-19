function sendFetch(fetchUrl, data) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
    };

    return fetch(fetchUrl, options)
        .then(response => response.json())
        .catch(error => {
            console.error('Error:', error);
        });
}