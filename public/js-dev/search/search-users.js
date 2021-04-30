import {ht, isJson, URL, LiveLoader, loginMdl, newTokenData} from "../script.js";


const bodyLoader = new LiveLoader();
bodyLoader.addIdSelector("[name='last_id']");
bodyLoader.addBtn("#show-more");
bodyLoader.addParams({
    "query": document.querySelector("[name='search_query']").value
})
bodyLoader.addEndPoint("ajax/explore/load-user-results");

bodyLoader.addListener((response) => {    
  Object.entries(response.users).forEach(
      ([key, value]) => {
        const profile = value;
        let btn = '';

        if(profile.show_btn && profile.show_btn) {
            btn =
            `<button class="btn mb-1 follow-btn btn-outline-primary float-end rounded ${profile.is_following?"active":""}" 
            data-uniq="${profile.uniq_id}">
                ${profile.is_following ? "Following" : "Follow"}
            </button>`;
        }

        document.querySelector(".list-container").innerHTML += `
            <div class="p-2 row mb-0 pb-1">
            <div class="col-2 text-center">
                <div class="follow-list-img" style='background-image: url(${URL}/uploads/${profile.profile_img});'></div>
            </div>
            <div class="col-10">
                <h6 class='d-inline-block'>
                    <a href="${URL}/profile?u=${profile.username}" class='text-dark text-decoration-none'>${ht(profile.display_name)}</a>
                </h6>
                ${btn}
                <p class="text-muted">@${ht(profile.username)}</p>
            </div>
        </div>
        `;
      }
  );
});

document.addEventListener("click", function(e) {
    const target = e.target;
    if(target.classList.contains("follow-btn")) {
        const form = newTokenData({"profile_uniq": target.getAttribute("data-uniq")})
		fetch(`${URL}/ajax/profile/toggle-follow`, {method: "POST", body: form})
		.then(r => r.text())
		.then(res => {
			if(isJson(res)) {
				let obj = JSON.parse(res);
				if(obj.status === 200) {
					target.classList.toggle("active");
					if(target.innerHTML.trim() == "Follow") {
						target.innerHTML = "Following";
					} else {
						target.innerHTML = "Follow";
					}
				}
			} else {
				loginMdl();
			}
		})
    }
})