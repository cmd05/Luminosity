import { newTokenData, URL, isJson, clipboardCopy } from './script.js';
    
let title, tagline, content, tags;
const saveBtn = document.querySelector("#save-changes");
const articleId = document.querySelector("[name='article_id']").value;
const toast = document.getElementById('article-err-toast');
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
    tags = document.querySelector("#tags").value;
}

function changeSaveBtnState() {
    let tmp1 = title;
    let tmp2 = tagline;
    let tmp3 = content;
    let tmp4 = tags;
    updateVars();

    if(tmp1 != title || tmp2 != tagline || tmp3 != content || tmp4 != tags) {
        saveBtn.classList.add('btn-info');
        saveBtn.classList.remove('btn-success');
        saveBtn.innerText = "Publish Changes";
    }
}

function updateArticle() {
    updateVars();
    const data = newTokenData({
        'title': title,
        'tagline': tagline,
        'tags': tags,
        'content': content,
        'article_id': articleId
    });

    toastBody.innerHTML = `Saving Changes   <i class="fas fa-circle-notch fa-spin"></i>`;
    let bsAlert = new bootstrap.Toast(toast, { delay: 15000 });
    bsAlert.show();

    fetch(`${URL}/ajax/write/update-article`, {
            method: "POST",
            body: data
        })
        .then(response => response.text())
        .then(result => {
            if(isJson(result)) {
                let obj = JSON.parse(result);
                if(obj.status === 200) {
                    location.replace(`${URL}/article?a=${articleId}`)
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

document.querySelector('#delete').addEventListener("click", function() {
    let name = prompt("Enter username to delete article: ")??" ";
    const data = newTokenData({
        "username": name, 
        'article_id': articleId
    })

    toastBody.innerHTML = `Delete`;
    let bsAlert = new bootstrap.Toast(toast, { delay: 5000 });
    bsAlert.show();

    fetch(`${URL}/ajax/write/delete-article`, {
            method: "POST",
            body: data
        })
        .then(response => response.text())
        .then(result => {
            if(isJson(result)) {
                let obj = JSON.parse(result);
                if(obj.status === 200) {
                    location.replace(`${URL}/write/articles`);
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

saveBtn.addEventListener("click", updateArticle)

document.querySelector("#copy-link").addEventListener("click", function() {
    clipboardCopy(`${URL}/write/edit-article/${articleId}`);

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