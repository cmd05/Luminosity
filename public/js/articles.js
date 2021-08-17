import { URL, clipboardCopy, newTokenData,  isJson, LiveLoader, ht } from "./script.js";

const toast = document.getElementById('articles-toast');
const toastBody = toast.querySelector('.toast-body');

document.addEventListener("click", (e) => {

    if(e.target.classList.contains("copy-link")) {
    	clipboardCopy(e.target.getAttribute("data-link"));

		toastBody.innerHTML = `Copied Link`;
		let bsAlert = new bootstrap.Toast(toast, { delay: 2000 });
		bsAlert.show();

    } else if(e.target.classList.contains('delete-article')) {
		
		let name = prompt("Enter username to delete article: ")??" ";
		const data = newTokenData({
			"username": name, 
			'article_id': e.target.getAttribute("data-delete-id")
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
						toastBody.innerHTML = `Article Deleted`;
						let bsAlert = new bootstrap.Toast(toast, { delay: 2000 });
						bsAlert.show();
						e.target.closest('.col').remove();
					} else {
						delete obj.status;
						toastBody.innerHTML = `${obj[Object.keys(obj)[0]]}`;
					}
				}

				setTimeout(function() {
					bsAlert.hide();
				}, 3000)
			})
    }
    
})

function isBlank(str) {
    return (!str || /^\s*$/.test(str));
}

const bodyLoader = new LiveLoader();
bodyLoader.addIdSelector("[name='last_article_id']");
bodyLoader.addBtn("#articles-more-btn");
bodyLoader.addEndPoint("ajax/write/load-articles");

bodyLoader.addListener((response) => {
  const articles = response.articles;
    
  Object.entries(articles).forEach(
      ([key, value]) => {
        const article = value;
        const edit_link = `${URL}/write/edit-article/${article.article_id}`;
        const view_link = `${URL}/write/edit-article/${article.article_id}`;
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
                <div class="py-2">
                    <small class="text-muted"><span style='font-weight: 500; font-size: 14px;'>Created: </span> ${article.created_at} </small>
                    <br>
                    <small class="text-muted"><span style='font-weight: 500; font-size: 14px;'>Last Edit: </span> ${article.last_updated} </small>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-2">
                    <a type="button" class="btn btn-sm btn-light border d-block w-100 mx-auto" href="${edit_link}">&nbsp;Edit&nbsp;</a>
                </div>
                <br>

                    <div class="btn-group mx-auto">
                    <button type="button" class="btn btn-sm btn-outline-danger delete-article" data-delete-id="${article.article_id}">&nbsp;Delete&nbsp;</button>
                    <button type="button" class="btn btn-sm btn-outline-primary copy-link" data-link="${view_link}">Copy Link</button>
                    </div>
                </div>
            </div>
            </div>
        `;
      }
  );
});