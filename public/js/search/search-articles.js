import { URL, loginMdl, newTokenData,  isJson, LiveLoader, ht } from "../script.js";

const bodyLoader = new LiveLoader();
bodyLoader.addIdSelector("[name='last_article_id']");
bodyLoader.addBtn("#load-more-articles");
bodyLoader.addParams({
    "query": document.querySelector("[name='query']").value
});
bodyLoader.addEndPoint("ajax/explore/load-article-results");


function isBlank(str) {
  return (!str || /^\s*$/.test(str));
}

bodyLoader.addListener((response) => {
  Object.entries(response.articles).forEach(
      ([key, value]) => {
          const article = value;
          const link = `${URL}/article?a=${article.article_id}`;
          const img = !isBlank(article.preview_img) ? `<img src="${article.preview_img}" class="preview-img pb-3" alt="...">` : "";
          const id = article.article_id;

          document.querySelector(".articles-container").innerHTML += `
          <div class="p-1">
              <div class="card-body row">
                  <div class="col-12 ms-2">
                      ${img}
                      <br>
                      <a class='mb-3 d-block h3 text-decoration-none text-dark' href="${URL}/article?a=${id}">${ht(article.title, 100)}</a>
                      <small class="text-muted mb-4 d-block">Published ${article.created_at}</small>
                      <p class="text-muted" style='font-size: 17px'>${ht(article.tagline, 300)}</p>
                      <p class='article-content'>${ht(article.content, 1000)}</p>
                      
                      ${article.view_count} <i class="fas fa-eye"></i>

                      <span class='d-inline-block float-end me-2'>
                        ${article.comments_count} <i class="fas fa-comment"></i>
                      </span>
                  </div>
                  <hr class='my-4'>
              </div>
          </div>
          <br>
          `;
      }
  );
});