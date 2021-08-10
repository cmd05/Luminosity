import {URL, isJson, newTokenData, loginMdl} from './script.js'

const articleId = document.querySelector("[name='article_id']").value;
const toast = document.getElementById('message-toast');
const toastBody = toast.querySelector('.toast-body');

document.querySelectorAll('.content-area pre').forEach(block => hljs.highlightBlock(block));

function isInViewport(el) {
    const rect = el.getBoundingClientRect();
    return rect.top >= 0 ||  rect.bottom >= 500;
}

function toggleActions() {
    const bar = document.querySelector(".fixed-action-bar");
    const showBar = document.querySelector(".show-fixed-bar");

    if(isInViewport(showBar) || isInViewport(showBar)) {
        bar.style.visibility = "visible";
    } else {
        bar.style.visibility = "hidden";
    }
}

document.addEventListener('scroll', toggleActions, {passive: true});
toggleActions();

document.addEventListener("click", function(e) {
    const t = e.target;
    const c = t.classList;
    const form = newTokenData({"article_id": articleId})

    toastBody.innerHTML = ``;

    if(c.contains("react-written") || c.contains("react-interesting") || c.contains("react-confused")) {
        let type;
        if(c.contains("react-written"))
            type = "well-written"
        else if(c.contains("react-interesting")) 
            type = "interesting"
        else 
            type = "confused";
          
        form.append("type", type);
        fetch(`${URL}/ajax/article/toggle-reaction`, {
            method: "POST",
            body: form
        })    
        .then(r => r.text())
        .then(res => {
            if(isJson(res)) {
                let obj = JSON.parse(res);
                if(obj.status === 200) {
                    if(type === "well-written") type = "written";

                    const toggle = document.querySelector(`.react-${type}-count`).getAttribute("data-toggle") === "true";
                    const count = parseInt(document.querySelector(`.react-${type}-count`).getAttribute("data-count"));

                    document.querySelectorAll(`button.react-${type}`).forEach(x => {x.classList.toggle('reacted')});

                    document.querySelectorAll(`.count-${type}`).forEach(x => {
                        const num = toggle ? count - 1 : count + 1;
                        x.innerHTML = num;
                        document.querySelector(`.react-${type}-count`).setAttribute("data-count", num);
                        document.querySelector(`.react-${type}-count`).setAttribute("data-toggle", !toggle);
                    })
                }
            } else {
                loginMdl();
            }
        })
    }
})

document.querySelectorAll(".toggle-bookmark").forEach(el => {
    el.addEventListener("click", () => {
        const form = newTokenData({"article_id": articleId})
        fetch(`${URL}/ajax/article/toggle-bookmark`, {method: "POST", body: form})
        .then(r => r.text())
        .then(res => {
            if(isJson(res)) {
                document.querySelectorAll(".toggle-bookmark i").forEach(i => {
                    i.classList.toggle("fas");
                    i.classList.toggle("far");
                })
            } else {
                loginMdl();
            }
        })
    })
})