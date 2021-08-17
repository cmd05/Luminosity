import { newTokenData, URL, isJson, clipboardCopy } from './script.js';
    
let title, tagline, content;
const saveBtn = document.querySelector("#save-changes");
const draftId = document.querySelector("[name='draft_id']").value;
const toast = document.getElementById('draft-update-toast');
const toastBody = toast.querySelector(".toast-body");

// Wait for hljs to load
setTimeout(function() {
    updateVars();
    window.setInterval(changeSaveBtnState, 1000);
}, 2000);

function updateVars() {
    title = document.querySelector('#title').value;
    tagline = document.querySelector('#tagline').value;
    content = document.querySelector('.ql-editor').innerHTML;
}

function changeSaveBtnState() {
    let tmp1 = title;
    let tmp2 = tagline;
    let tmp3 = content;
    updateVars();

    if(tmp1 != title || tmp2 != tagline || tmp3 != content) {
        saveBtn.classList.add('btn-info');
        saveBtn.classList.remove('btn-success');
        saveBtn.innerText = "Save Changes";
    }
}

function updateDraft() {
    updateVars();
    const data = newTokenData({
        'title': title,
        'tagline': tagline,
        'content': content,
        'draft_id': draftId
    });

    toastBody.innerHTML = `Saving Changes   <i class="fas fa-circle-notch fa-spin"></i>`;
    let bsAlert = new bootstrap.Toast(toast, { delay: 15000 });
    bsAlert.show();

    fetch(`${URL}/ajax/write/update-draft`, {
            method: "POST",
            body: data
        })
        .then(response => response.text())
        .then(result => {
            if(isJson(result)) {
                let obj = JSON.parse(result);
                if(obj.status === 200) {
                    toastBody.innerHTML = `Saved Changes`;
                    saveBtn.classList.remove('btn-info');
                    saveBtn.classList.add('btn-success');
                    saveBtn.innerText = "Saved";

                    updateVars();
                } else {
                    delete obj.status;
                    toastBody.innerHTML = `${obj[Object.keys(obj)[0]]}`;
                }
            }
            setTimeout(function() {
                bsAlert.hide();
            }, 2000)
        })
}

document.querySelector('#rename').addEventListener("click", function() {
    let name = prompt("Enter new draft name: ")??" ";
    const data = newTokenData({
        "new_name": name, 
        'draft_id': draftId
    })

    toastBody.innerHTML = `Changing Name`;
    let bsAlert = new bootstrap.Toast(toast, { delay: 5000 });
    bsAlert.show();

    fetch(`${URL}/ajax/write/rename-draft`, {
            method: "POST",
            body: data
        })
        .then(response => response.text())
        .then(result => {
            if(isJson(result)) {
                let obj = JSON.parse(result);
                if(obj.status === 200) {
                    toastBody.innerHTML = `Renamed Draft`;
                    document.querySelector("#draft_name").innerText = name; 
                } else {
                    delete obj.status;
                    toastBody.innerHTML = `${obj[Object.keys(obj)[0]]}`;
                }
            }
            
            setTimeout(function() {
                bsAlert.hide();
            }, 3000)
        })
})

document.querySelector('#delete').addEventListener("click", function() {
    let name = prompt("Enter username to delete draft: ")??" ";
    const data = newTokenData({
        "username": name, 
        'draft_id': draftId
    })

    toastBody.innerHTML = `Delete`;
    let bsAlert = new bootstrap.Toast(toast, { delay: 5000 });
    bsAlert.show();

    fetch(`${URL}/ajax/write/delete-draft`, {
            method: "POST",
            body: data
        })
        .then(response => response.text())
        .then(result => {
            if(isJson(result)) {
                let obj = JSON.parse(result);
                if(obj.status === 200) {
                    location.replace(`${URL}/write/drafts`);
                } else {
                    delete obj.status;
                    toastBody.innerHTML = `${obj[Object.keys(obj)[0]]}`;
                }
            }

            setTimeout(function() {
                bsAlert.hide();
            }, 3000)
        })
})

saveBtn.addEventListener("click", updateDraft)
document.addEventListener('keydown', e => {
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        updateDraft();
    }
});

document.querySelector("#copy-link").addEventListener("click", function() {
    clipboardCopy(`${URL}/write/draft/${draftId}`);

    toastBody.innerHTML = `Copied Link`;
    let bsAlert = new bootstrap.Toast(toast, { delay: 2000 });
    bsAlert.show();
});

// window.onbeforeunload = function (e) {
//     e = e || window.event;
//     if (e) {
//         e.returnValue = 'Sure?';
//     }
//     return 'Sure?';
// };