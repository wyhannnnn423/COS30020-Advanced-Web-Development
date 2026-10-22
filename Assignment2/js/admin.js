// js/admin.js: AJAX approve / reject for the admin tables.
// Clicking Approve or Reject sends the form in the background and updates the row in place,
// so the page does not reload. With JavaScript off the same forms still work as normal posts;
// if a request fails, a red message appears and the buttons go back to how they were.

document.addEventListener('submit', function (event) {
    var form = event.target;
    if (!form.classList || !form.classList.contains('status-form')) {
        return;
    }
    event.preventDefault();

    var buttons = form.querySelectorAll('button');
    var data = new FormData(form);
    if (event.submitter && event.submitter.name) {
        data.set(event.submitter.name, event.submitter.value);   // approve or reject
    }
    buttons.forEach(function (b) { b.dataset.wasDisabled = b.disabled ? '1' : ''; b.disabled = true; });

    fetch(window.location.href, {
        method: 'POST',
        body: data,
        headers: { 'X-Requested-With': 'fetch' }
    })
        .then(function (response) {
            return response.json().then(function (json) { return { ok: response.ok, json: json }; });
        })
        .then(function (result) {
            if (!result.ok || !result.json.ok) {
                throw new Error(result.json.message || 'Something went wrong.');
            }
            updateRow(form, result.json.status);
            toast('Entry #' + result.json.id + ' ' + result.json.status + '.', false);
        })
        .catch(function (error) {
            buttons.forEach(function (b) { b.disabled = b.dataset.wasDisabled === '1'; });
            toast(error.message || 'Could not update. Please try again.', true);
        });
});

// Changes the status badge and which of the two buttons is enabled.
function updateRow(form, status) {
    var row = form.closest('tr');
    var badge = row ? row.querySelector('.status-badge') : null;
    if (badge) {
        badge.className = 'status-badge status-' + status;
        badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
    }
    form.querySelectorAll('button').forEach(function (b) {
        b.disabled = (b.value === 'approve' && status === 'approved') ||
                     (b.value === 'reject' && status === 'rejected');
    });
}

function toast(message, isError) {
    var el = document.createElement('div');
    el.className = 'admin-toast' + (isError ? ' admin-toast-error' : '');
    el.textContent = message;
    document.body.appendChild(el);
    setTimeout(function () { el.classList.add('hide'); }, 2200);
    setTimeout(function () { el.remove(); }, 2800);
}
