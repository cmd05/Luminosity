import { URL, clipboardCopy, newTokenData,  isJson, LiveLoader, ht } from "./script.js";

const toast = document.getElementById('drafts-toast');
const toastBody = toast.querySelector('.toast-body');

document.addEventListener("click", (e) => {
    if(e.target.classList.contains("copy-link")) {
    	clipboardCopy(e.target.getAttribute("data-link"));

		toastBody.innerHTML = `Copied Link`;
		let bsAlert = new bootstrap.Toast(toast, { delay: 2000 });
		bsAlert.show();
		
    } else if(e.target.classList.contains('delete-draft')) {
		
		let name = prompt("Enter username to delete draft: ")??" ";
		const data = newTokenData({
			"username": name, 
			'draft_id': e.target.getAttribute("data-delete-id")
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
						toastBody.innerHTML = `Draft Deleted`;
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
bodyLoader.addIdSelector("[name='last_draft_id']");
bodyLoader.addBtn("#drafts-more-btn");
bodyLoader.addEndPoint("ajax/write/load-drafts");

bodyLoader.addListener((response) => {
  const drafts = response.drafts;
    
  Object.entries(drafts).forEach(
      ([key, value]) => {
          const draft = value;

          const link = `${URL}/write/draft/${draft["draft_id"]}`;
          const title = isBlank(draft['title']) ? "<i class='fs-6'>Title</i> " : ht(draft['title'], 40);
          const tagline = isBlank(draft['tagline']) ? "<i class='fs-6'>Title</i> " : ht(draft['tagline'], 100)
          document.querySelector("#drafts-container").innerHTML += `
          <div class="col mb-3">
            <div class="card shadow-sm">
              <div class="card-body">
                <h4 class='draft-name'><a href="${link}" class='text-dark text-decoration-none'>${ht(draft['draft_name'], 40)}</a></h4>
                <div class="p-2"></div>
                <h5>
                    <span style='font-weight: 400'>${title}</span>
                </h5>
                <p class="card-text tagline">
                  ${tagline}
                </p>
                <p class="card-text">${ht(draft['content'], 200)}</p>
                <div class="py-2">
                  <small class="text-muted"><span style='font-weight: 500; font-size: 14px;'>Created: </span> ${ht(draft['created_at'])}</small>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-2">
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-danger delete-draft" data-delete-id="${draft['draft_id']}">&nbsp;Delete&nbsp;</button>
                    <button type="button" class="btn btn-sm btn-outline-primary copy-link" data-link="${link}">Copy Link</button>
                  </div>
                  <small class="text-muted"><span style='font-weight: 500; font-size: 14px;'>Last Edit: </span> ${draft['last_edited']}</small>
                </div>
              </div>
            </div>
          </div>`;
      }
  );
});