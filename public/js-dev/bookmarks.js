import { URL, clipboardCopy, newTokenData,  isJson, LiveLoader, ht } from "./script.js";

const toast = document.getElementById('articles-toast');
const toastBody = toast.querySelector('.toast-body');

document.addEventListener("click", (e) => {
    let tg = e.target;

    if(tg.classList.contains("copy-link")) {
    	clipboardCopy(tg.getAttribute("data-link"));

		toastBody.innerHTML = `Copied Link`;
		let bsAlert = new bootstrap.Toast(toast, { delay: 2000 });
		bsAlert.show();

    } else if(tg.classList.contains('toggle-bookmark')) {

		const form = newTokenData({"article_id": tg.getAttribute("data-article-id")})
        fetch(`${URL}/ajax/article/toggle-bookmark`, {method: "POST", body: form})
        .then(r => r.text())
        .then(res => {
            if(isJson(res)) {
                let obj = JSON.parse(res);

                if(obj.status === 200) {
                    const i = tg.querySelector("i");
                    if(i.classList.contains("fas")) {
                        i.classList.remove("fas");
                        i.classList.add("far");
                    } else {
                        i.classList.remove("far");
                        i.classList.add("fas");
                    }
                }
            }
        })
    }
})

function isBlank(str) {
    return (!str || /^\s*$/.test(str));
}

const bodyLoader = new LiveLoader();
bodyLoader.addIdSelector("[name='last_article_id']");
bodyLoader.addBtn("#articles-more-btn");
bodyLoader.addEndPoint("ajax/bookmarks/load-bookmarks");

bodyLoader.addListener((response) => {
  const articles = response.articles;
    
  Object.entries(articles).forEach(
      ([key, value]) => {
        const article = value;
        const view_link = `${URL}/article?a=${article.article_id}`;
        const tagline = isBlank(article.tagline) ? "<i class='fs-6'>Title</i> " : ht(article.tagline, 100)
        const img = !isBlank(article.preview_img) ? `<img src="${article.preview_img}" class="card-img-top" alt="...">` : "";
        
        document.querySelector("#articles-container").innerHTML += `
            <div class="col mb-3">
                <div class="card shadow-sm">
                    ${img}
                    <div class="card-body">
                        <h4 class='draft-name'><a href="${view_link}" class='text-dark text-decoration-none'>${ht(article.title, 40)}</a></h4>
                        <div class="p-2"></div>
                        <p class="card-text tagline">${tagline}</p>
                        <p class="card-text">${ht(article.content, 200)}</p>
                        
                        <br>

                        <div class="btn-group mx-auto">
                            <button type="button" class="btn btn-sm btn-outline-primary copy-link" data-link="${view_link}">Copy Link</button>
                        </div>

                        <button type="button" class="toggle-bookmark btn btn-sm btn-primary delete-article float-end" data-article-id="${article.article_id}">
                            <i class="fas fa-bookmark toggle-bookmark"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
      }
  );
});