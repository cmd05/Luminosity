import { URL, loginMdl, newTokenData,  isJson, LiveLoader, ht } from "./script.js";

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
    Object.entries(response.articles).forEach(
        ([key, value]) => {
            const article = value;
            const img = !isBlank(article.preview_img) ? `<img src="${article.preview_img}" class="preview-img pb-3" alt="...">` : "";
            const id = article.article_id;
  
            document.querySelector(".articles-container").innerHTML += `
            <div class="p-1">
                <div class="card-body row">
                    <div class="col-12 ms-2">
                        ${img}
                        <br>
                        <a class='mb-3 d-block h3 text-decoration-none text-dark' href="${URL}/article?a=${id}">${ht(article.title, 100)}</a>

                        <div class="row mt-4 mb-3">
                            <div class="col-2 col-sm-1">
                                <span class="user-img d-inline-block me-3" style='background-image: url(${URL}/uploads/${article.profile_img});'></span>
                            </div>
                            <a class="col ps-4 text-dark text-decoration-none" href="${URL}/profile?u=${article.username}">
                                <p class='py-0 my-0'>${ht(article.display_name)}</p>
                                <small class="text-muted">@${ht(article.username)}</small>
                            </a>
                        </div>

                        <small class="text-muted mb-4 d-block">Published ${article.created_at}</small>
                        <p class="text-muted" style='font-size: 17px'>${ht(article.tagline, 300)}</p>
                        <p class='article-content'>${ht(article.content, 1000)}</p>
                        
                        ${article.view_count} <i class="fas fa-eye"></i>
  
                        <button class="btn btn-dark float-end toggle-bookmark" data-article-id="${article.article_id}">
                            <i class="${article.is_bookmarked ? "fas" : "far"} fa-bookmark" style='pointer-events: none;'></i>
                        </button>
                    </div>
                    <hr class='my-4'>
                </div>
            </div>
            <br>
            `;
        }
    );
  });