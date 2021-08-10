const URL = document.querySelector("[name='app_url']").value;
function newTokenData(e={}){const t=document.querySelector("#ajax_csrf").value,n=new FormData;n.append("csrf_token",t);for(let[t,r]of Object.entries(e))n.append(t,r);return n}function isJson(e){var t=e;try{t=JSON.parse(e)}catch(e){return!1}return"object"==typeof t}function addFormErrors(e){for(let t in e){const n=e[t],r=document.querySelector(`[name='${t}']`);r&&(r.innerText=n);const o=document.querySelector(`[data-error='${t}']`);o&&(""!=n?o.classList.add("is-invalid"):o.classList.remove("is-invalid"))}}function removeFormErrors(e){for(let t in e){e[t];const n=document.querySelector(`[name='${t}']`);n&&(n.innerText="");const r=document.querySelector(`[data-error='${t}']`);r&&r.classList.remove("is-invalid")}}function dataURLtoFile(e,t){for(var n=e.split(","),r=n[0].match(/:(.*?);/)[1],o=atob(n[1]),a=o.length,c=new Uint8Array(a);a--;)c[a]=o.charCodeAt(a);return new File([c],t,{type:r})}function syncFileReader(e){let t=!1,n="";const r=function(e){return new Promise(t=>setTimeout(t,e))};this.readAsDataURL=async function(){for(;!1===t;)await r(100);return n};const o=new FileReader;o.onloadend=function(e){n=e.target.result,t=!0},o.readAsDataURL(e)}function constructFormNameObj(e){let t={};return e.forEach(e=>t[e]=document.querySelector(`[name='${e}']`).value),t}function clipboardCopy(e){var t=document.createElement("input");t.style="position: absolute; left: -1000px; top: -1000px",t.value=e,document.body.appendChild(t),t.select(),document.execCommand("copy"),document.body.removeChild(t)}function ht(e,t=null){if(null!=t){const n=e.length;e=e.substring(0,t),n.len>t&&(e+="...")}var n=document.createTextNode(e),r=document.createElement("p");return r.appendChild(n),r.innerHTML}

// function showLoginModal
  
class LiveLoader {
    constructor() {
        this.params = {};
    }

    addIdSelector = (idSelector) => {
        this.idSelector = document.querySelector(`${idSelector}`);
        if(!document.querySelector(idSelector)) 
            console.warn(`Element ${idSelector} not found for IDSelector @LiveLoader`);

    }

    addBtn = (btnSelector, loadingText = null) => {
        this.loadingText = loadingText == null 
            ? 'Loading <i class="ms-1 fas fa-circle-notch fa-spin"></i>' : loadingText;
        this.moreBtn = document.querySelector(`${btnSelector}`);

        if(!document.querySelector(btnSelector)) 
            console.warn(`Button ${btnSelector} not found for BtnSelector @LiveLoader`);
    }

    addEndPoint = (url) => {
        this.endpoint = url;
    }

    addParams = (obj) => {
        this.params = obj;
    }

    addListener = (fn) => {
        this.callbackFn = fn;

        const defaultText = this.moreBtn.innerHTML;
        
        const fetchUrl = async () => {
            const form = newTokenData({'last_id': this.idSelector, ...this.params});
            const response = await fetch(`${URL}/${this.endpoint}/${this.idSelector.value}`, {
                method: "POST",
                body: form
            })

            const json = await response.text();
	        return json;
        }

        const updateContents = (data) => {
            setTimeout(() => {
                if(isJson(data)) {
                    let obj = JSON.parse(data);
                    if(obj.status === 200) {
                        this.idSelector.value = obj.last_id;
                        this.callbackFn(obj);  
                        this.moreBtn.innerHTML = defaultText;
                        this.moreBtn.disabled = false;
                    } else {
                        this.moreBtn.remove();
                    }
                } else {
                    this.moreBtn.remove();
                }
            }, 500);
        }

        this.moreBtn.addEventListener("click", () => {
            // Add loading animation
            this.moreBtn.innerHTML = this.loadingText;
            this.moreBtn.disabled = true;

            const fetchAndCall = async () => {
                const data = await fetchUrl();
                updateContents(data);
            }
            
            fetchAndCall();
        })
    }
}

function loginMdl() {document.querySelector("#bs-login-mdl-btn").click();}


export { newTokenData, URL, isJson, addFormErrors, removeFormErrors, 
         dataURLtoFile, syncFileReader, constructFormNameObj, clipboardCopy,
         LiveLoader, ht, loginMdl
       };