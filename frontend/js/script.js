
const API = "http://127.0.0.1:8000/api/";
const token = localStorage.getItem("auth_token");

if (!token) window.location.href = "login.html";

let user = null;
let modal;

document.addEventListener("DOMContentLoaded", () => {
    modal = new bootstrap.Modal(document.getElementById("editProfileModal"));
});
async function fetchUser() {
    try {
        const res = await axios.get(API + "user", { headers: { Authorization: `Bearer ${token}` } });
        user = res.data.data;
        if (typeof page !== "undefined") {
            console.log('test');
            document.getElementById("userName").textContent = user.name;
            document.getElementById("userEmail").textContent = user.email;
            document.getElementById("userRole").textContent = user.role.toUpperCase();
            document.getElementById("navUserName").textContent = user.name;
            document.getElementById("userMenu").style.display = "flex";

            if (user.role === "admin") {
                document.getElementById("adminNav").classList.remove("d-none");
                document.getElementById("userRole").classList.replace("bg-secondary", "bg-danger");
            }

        }

    } catch {
        logout();
    }
}

fetchUser();
function openEditModal() {
    document.getElementById("editName").value = user.name;
    document.getElementById("editEmail").value = user.email;
    document.getElementById("editPassword").value = "";
    document.getElementById("editPasswordConfirm").value = "";
    modal.show();
}

async function updateProfile() {
    const name = editName.value;
    const password = editPassword.value;
    const confirm = editPasswordConfirm.value;

    if (password && password !== confirm) {
        showEdit("Passwords do not match", "danger");
        return;
    }

    const payload = { name };
    if (password) payload.password = password, payload.password_confirmation = confirm;

    try {
        await axios.put(API + "user/profile", payload, { headers: { Authorization: `Bearer ${token}` } });
        showEdit("Updated Successfully ", "success");
        setTimeout(() => { modal.hide(); fetchUser(); }, 1000);
    } catch (err) {
        showEdit("Error updating profile", "danger");
    }
}

function showEdit(msg, type) { editAlert.innerHTML = `<div class="alert alert-${type}">${msg}</div>`; }

async function logout() {
    try { await axios.post(API + "logout", {}, { headers: { Authorization: `Bearer ${token}` } }); } catch { }
    localStorage.removeItem("auth_token");
    window.location.href = "login.html";
}


