import { URL, loginMdl, newTokenData,  isJson, LiveLoader, ht } from "./script.js";

const userId = document.querySelector("[name='user_uniq_id']").value;

const bodyLoader = new LiveLoader();
bodyLoader.addIdSelector("[name='last_article_id']");
bodyLoader.addBtn("#load-more-articles");
bodyLoader.addParams({
    "profile_uniq": userId
});
bodyLoader.addEndPoint("ajax/profile/load-profile-articles");

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

if(document.querySelector("#follow-btn")) {
	document.querySelector("#follow-btn").addEventListener("click", function() {
		const form = newTokenData({"profile_uniq": userId})
		fetch(`${URL}/ajax/profile/toggle-follow`, {method: "POST", body: form})
		.then(r => r.text())
		.then(res => {
			if(isJson(res)) {
				let obj = JSON.parse(res);
				if(obj.status === 200) {
					this.classList.toggle("active");
					if(this.innerHTML.trim() == "Follow") {
						this.innerHTML = "Following";
					} else {
						this.innerHTML = "Follow";
					}
				}
			} else {
				loginMdl();
			}
		})
	})
}

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