import { URL, loginMdl, newTokenData,  isJson, LiveLoader } from "./script.js";

document.addEventListener("click", (e) => {
    const target = e.target;
    if(target.classList.contains("toggle-bookmark")) {
        const articleId = target.getAttribute("data-article-id");
        const form = newTokenData({"article_id": articleId})
        fetch(`${URL}/ajax/article/toggle-bookmark`, {method: "POST", body: form})
        .then(r => r.text())
        .then(res => {
            if(isJson(res)) {
                target.querySelector("i").classList.toggle("far");
                target.querySelector("i").classList.toggle("fas");
            } else {
                loginMdl();
            }
        })
    }
})

const bodyLoader = new LiveLoader();
bodyLoader.addIdSelector("[name='last_id']");
bodyLoader.addBtn("#load-more-articles");
bodyLoader.addEndPoint("ajax/home/load-articles");

function isBlank(str) {
    return (!str || /^\s*$/.test(str));
  }
  
bodyLoader.addListener((response) => {
    document.querySelector(".articles-container").innerHTML += response.article_renders;
});