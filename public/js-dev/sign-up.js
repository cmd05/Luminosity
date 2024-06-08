import {
	newTokenData,
	URL,
	isJson,
	addFormErrors,
	removeFormErrors,
	constructFormNameObj
} from './script.js';
// Inputs

const passwordInput = document.querySelector("#pwd-input");
const section1 = document.querySelector("#section_1");
const section2 = document.querySelector("#section_2");
const next1Btn = document.querySelector("#next_1");
const backBtn = document.querySelector("#back_1");
const completeBtn = document.querySelector("#complete_btn");

// Section 1
const passwordToggle = document.querySelector("#pwd-toggle");

passwordToggle.addEventListener("click", function () {
	if (passwordInput.getAttribute("type") == 'password') {
		passwordInput.setAttribute("type", 'text');
		passwordToggle.querySelector('i').className = 'fas fa-eye-slash';
	} else {
		passwordInput.setAttribute("type", 'password');
		passwordToggle.querySelector('i').className = 'fas fa-eye';
	}
})


next1Btn.addEventListener("click", function () {
	const form = constructFormNameObj(["email", "password", "confirm_password", "gender"]);
	const body = newTokenData(form);

	fetch(`${URL}/ajax/user/sign-up-check-1`, {
			method: "POST",
			body: body
		})
		.then(response => response.text())
		.then(result => {
			if (isJson(result)) {
				let obj = JSON.parse(result);
				if (obj.status === 200) {
					removeFormErrors(obj);
					section1.style.display = 'none';
					section2.style.display = 'block';
				} else {
					addFormErrors(obj);
				}
			}
		})
})

completeBtn.addEventListener("click", function () {
	const form = constructFormNameObj(
			['email', 'gender', 'password', 'confirm_password', 'display_name', 'username', 'about']
	);

	form["profile_img"] = document.querySelector(`[name='profile_img']`).files[0];
	const body = newTokenData(form);

	completeBtn.style.opacity = '0.6';
	completeBtn.querySelector('span').innerHTML = 'Complete <i class="fas fa-circle-notch fa-spin"></i>';
	completeBtn.disabled = true;

	fetch(`${URL}/ajax/user/complete-sign-up`, {
			method: "POST",
			body: body
		})
		.then(response => response.text())
		.then(result => {
			completeBtn.disabled = false;
			completeBtn.style.opacity = '1';
			completeBtn.querySelector('span').innerHTML = 'Complete';

			if (isJson(result)) {
				let obj = JSON.parse(result);
				if (obj.status === 200) {
					removeFormErrors(obj);
					location.replace(`${URL}/user/login`);
				} else {
					addFormErrors(obj);
				}
			}
		})
})


// Back Btn
backBtn.addEventListener("click", function () {
	section2.style.display = 'none';
	section1.style.display = 'block';
})