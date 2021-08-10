import {isJson, newTokenData, URL, addFormErrors, removeFormErrors} from './script.js';

const saveBtn = document.querySelector("#save");

saveBtn.addEventListener("click", () => {
    const username = document.querySelector("[name='username']").value;
    const displayName = document.querySelector("[name='display_name']").value;
    const about = document.querySelector("[name='about']").value;

    const form = newTokenData({
        "username": username,
        "display_name": displayName,
        "about": about
    });

    const imgInput = document.querySelector("[name='image']");
    if(imgInput.value) form.append("image", imgInput.files[0]);
    
    fetch(`${URL}/ajax/settings/update-profile`, {method: "POST", body: form})
    .then(res => res.text())
    .then(result => {
        if (isJson(result)) {
            let obj = JSON.parse(result);
            if (obj.status === 200) {
                removeFormErrors(obj);
                location.replace(`${URL}/home`);
            } else {
                addFormErrors(obj);
            }
        }
    })
});

const uploader = document.querySelector("#image");
uploader.addEventListener("change", () => {
    if (uploader.files && uploader.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('#preview-img').style.backgroundImage = `url(${e.target.result})`;
        };
        reader.readAsDataURL(uploader.files[0]);
    }
})
