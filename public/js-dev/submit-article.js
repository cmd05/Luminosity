import { URL, isJson, newTokenData } from "./script.js";

const postBtn = document.querySelector("#submit-post");
postBtn.addEventListener("click", () => {
    const data = newTokenData({
        'title': document.querySelector('#title').value,
        'tagline': document.querySelector('#tagline').value,
        'content': document.querySelector('.ql-editor').innerHTML,
        'tags': document.querySelector('#tags').value,

    });

    fetch(`${URL}/ajax/write/submit-article`, {
        method: "POST",
        body: data
    })
    .then(response => response.text())
    .then(result => {
        if(isJson(result)) {
            let toast = document.getElementById('draft-err-toast');
            let bsAlert = new bootstrap.Toast(toast, { delay: 6000 });
            let obj = JSON.parse(result);

            if(obj.status === 200) {
                location.replace(`${URL}/article?a=${obj.article_id}`);
            } else {
                delete obj.status;
                toast.querySelector('.toast-body').innerHTML = `${obj[Object.keys(obj)[0]]}`;
                bsAlert.show();
            }
        }
    })
});